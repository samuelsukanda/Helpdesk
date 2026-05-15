<?php

namespace App\Http\Controllers;

use App\Models\{Ticket, Category, Department, User, SlaPolicy, TicketAttachment};
use App\Notifications\TicketCreatedNotification;
use App\Notifications\TicketStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Ticket::with(['requester', 'agent', 'category']);

        if ($user->isUser()) {
            $query->where('requester_id', $user->id);
        } elseif ($user->isAgent()) {
            $query->where('agent_id', $user->id);
        }

        // Filters
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('priority')) $query->where('priority', $request->priority);
        if ($request->filled('category')) $query->where('category_id', $request->category);
        if ($request->filled('search'))   $query->where(function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('ticket_number', 'like', '%' . $request->search . '%');
        });

        $tickets    = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::active()->get();

        return view('tickets.index', compact('tickets', 'categories'));
    }

    public function create()
    {
        $categories  = Category::where('is_active', true)->with('subCategories')->get();
        $departments = Department::where('is_active', true)->get();
        return view('tickets.create', compact('categories', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'priority'        => 'required|in:low,medium,high,critical',
            'category_id'     => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'attachments.*'   => 'nullable|file|max:10240',
        ]);

        $duplicate = Ticket::where('requester_id', auth()->id())
            ->where(function ($q) use ($request) {
                $q->where('title', $request->title)
                    ->orWhere('description', $request->description);
            })
            ->where('created_at', '>=', now()->subSeconds(30))
            ->withTrashed()
            ->first();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'duplicate' => 'Tiket dengan judul atau deskripsi yang sama baru saja dibuat ('
                        . $duplicate->ticket_number
                        . '). Mohon tunggu sebentar sebelum membuat tiket baru.',
                ]);
        }

        try {
            $ticket = DB::transaction(function () use ($request) {

                $sla = SlaPolicy::where('priority', $request->priority)
                    ->where('is_active', true)
                    ->first();

                $ticket = Ticket::create([
                    'title'           => $request->title,
                    'description'     => $request->description,
                    'priority'        => $request->priority,
                    'category_id'     => $request->category_id,
                    'sub_category_id' => $request->sub_category_id,
                    'requester_id'    => auth()->id(),
                    'department_id'   => auth()->user()->department_id,
                    'sla_policy_id'   => $sla?->id,
                    'due_at'          => $sla
                        ? now()->addHours($sla->resolution_time_hours)
                        : null,
                ]);

                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $path = $file->store(
                            'ticket-attachments/' . $ticket->id,
                            'public'
                        );
                        TicketAttachment::create([
                            'ticket_id'     => $ticket->id,
                            'user_id'       => auth()->id(),
                            'filename'      => basename($path),
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type'     => $file->getMimeType(),
                            'size'          => $file->getSize(),
                            'path'          => $path,
                        ]);
                    }
                }

                return $ticket;
            });

            $agents = User::role(['admin', 'agent'])->get();
            foreach ($agents as $agent) {
                $agent->notify(new TicketCreatedNotification($ticket));
            }

            return redirect()
                ->route('tickets.show', $ticket)
                ->with('success', 'Tiket berhasil dibuat: ' . $ticket->ticket_number);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'ticket' => 'Terjadi konflik nomor tiket karena ada permintaan bersamaan. Silakan coba kirim ulang.',
                    ]);
            }

            throw $e;
        }
    }

    public function show(Ticket $ticket)
    {
        $this->authorizeTicketAccess($ticket);

        $ticket->load(['requester', 'agent', 'category', 'subCategory', 'comments.user', 'attachments.user', 'slaPolicy']);
        $agents = User::role('agent')->get();

        return view('tickets.show', compact('ticket', 'agents'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status' => 'required|in:open,in_progress,on_hold,resolved,closed']);

        $oldStatus = $ticket->status;
        $updates   = ['status' => $request->status];

        if ($request->status === 'resolved') $updates['resolved_at'] = now();
        if ($request->status === 'closed')   $updates['closed_at']   = now();

        $ticket->update($updates);

        // Notify requester
        $ticket->requester->notify(new TicketStatusUpdatedNotification($ticket, $oldStatus));

        return back()->with('success', 'Status tiket diperbarui.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate(['agent_id' => 'required|exists:users,id']);

        $ticket->update([
            'agent_id'         => $request->agent_id,
            'status'           => 'in_progress',
            'first_response_at' => $ticket->first_response_at ?? now(),
        ]);

        return back()->with('success', 'Tiket berhasil di-assign.');
    }

    private function authorizeTicketAccess(Ticket $ticket): void
    {
        $user = auth()->user();
        if ($user->isUser() && $ticket->requester_id !== $user->id) {
            abort(403);
        }
    }

    public function destroy(Ticket $ticket)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        foreach ($ticket->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }

            $attachment->delete();
        }

        $ticket->delete();

        return back()->with('success', 'Tiket berhasil dihapus.');
    }
}
