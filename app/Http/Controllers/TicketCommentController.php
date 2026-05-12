<?php

namespace App\Http\Controllers;

use App\Models\{Ticket, TicketComment};
use App\Notifications\TicketCommentNotification;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate(['body' => 'required|string']);

        $isInternal = $request->boolean('is_internal') && !auth()->user()->isUser();

        $comment = $ticket->comments()->create([
            'user_id'     => auth()->id(),
            'body'        => $request->body,
            'is_internal' => $isInternal,
        ]);

        if (auth()->user()->isAgent() && !$ticket->first_response_at) {
            $ticket->update(['first_response_at' => now()]);
        }

        if (!$isInternal && $ticket->requester_id !== auth()->id()) {
            $ticket->requester->notify(new TicketCommentNotification($ticket, $comment));
        }

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function destroy(Ticket $ticket, TicketComment $comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        $comment->delete();
        return back()->with('success', 'Komentar dihapus.');
    }
}
