{{-- resources/views/tickets/show.blade.php --}}
@extends('layouts.app')
@section('title', $ticket->ticket_number)
@section('header', 'Detail Tiket')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Detail & Comments --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Header Card --}}
            <div class="bg-white rounded-xl border shadow-sm p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <p class="font-mono text-sm text-blue-600 font-medium mb-1">{{ $ticket->ticket_number }}</p>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $ticket->title }}</h2>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        @include('components.status-badge', ['status' => $ticket->status])
                        @include('components.priority-badge', ['priority' => $ticket->priority])
                    </div>
                </div>

                @if ($ticket->isOverdue())
                    <div
                        class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm mb-4 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i> Tiket ini melewati batas SLA!
                    </div>
                @endif

                <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 rounded-lg p-4">
                    {!! nl2br(e($ticket->description)) !!}
                </div>

                {{-- Attachments --}}
                @if ($ticket->attachments->isNotEmpty())
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600 mb-2"><i class="fas fa-paperclip mr-1"></i> Lampiran</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($ticket->attachments as $att)
                                <a href="{{ asset('storage/' . $att->path) }}" target="_blank"
                                    class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 transition">
                                    <i class="fas fa-file text-blue-500"></i>
                                    <span class="truncate max-w-32">{{ $att->original_name }}</span>
                                    <span class="text-gray-400 text-xs">{{ round($att->size / 1024) }}KB</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Comments --}}
            <div class="bg-white rounded-xl border shadow-sm">
                <div class="p-5 border-b">
                    <h3 class="font-semibold text-gray-700">
                        <i class="fas fa-comments mr-2 text-blue-500"></i>
                        Percakapan ({{ $ticket->comments->count() }})
                    </h3>
                </div>

                <div class="divide-y">
                    @forelse($ticket->comments as $comment)
                        <div class="p-5 {{ $comment->is_internal ? 'bg-yellow-50 border-l-4 border-yellow-400' : '' }}">
                            <div class="flex items-start gap-3">
                                <img src="{{ $comment->user->avatar_url }}" class="w-9 h-9 rounded-full flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-medium text-sm text-gray-800">{{ $comment->user->name }}</span>
                                        <span
                                            class="text-xs text-gray-400">{{ $comment->created_at->format('d M Y H:i') }}</span>
                                        @if ($comment->is_internal)
                                            <span
                                                class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full font-medium">
                                                <i class="fas fa-lock mr-1"></i> Internal Note
                                            </span>
                                        @endif
                                        <span class="text-xs capitalize bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                                            {{ $comment->user->getRoleNames()->first() }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-700">{!! nl2br(e($comment->body)) !!}</div>
                                </div>
                                @if ($comment->user_id === auth()->id() || auth()->user()->isAdmin())
                                    <form method="POST"
                                        action="{{ route('tickets.comments.destroy', [$ticket, $comment]) }}"
                                        onsubmit="return confirm('Hapus komentar ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-300 hover:text-red-500 transition text-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-gray-400">
                            <i class="fas fa-comment-slash text-3xl mb-2 block"></i>
                            Belum ada percakapan
                        </div>
                    @endforelse
                </div>

                {{-- Reply Form --}}
                @if (!in_array($ticket->status, ['closed']))
                    <div class="p-5 border-t bg-gray-50">
                        <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}">
                            @csrf
                            <textarea name="body" rows="3" required placeholder="Tulis balasan..." class="input w-full mb-3 resize-none">{{ old('body') }}</textarea>

                            <div class="flex items-center justify-between">
                                @hasanyrole('admin|agent')
                                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                        <input type="checkbox" name="is_internal" value="1" class="rounded">
                                        <i class="fas fa-lock text-yellow-500"></i> Internal Note (hanya agent)
                                    </label>
                                @else
                                    <span></span>
                                @endhasanyrole
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Info Panel --}}
        <div class="space-y-5">

            {{-- Ticket Info --}}
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-4">Informasi Tiket</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Status</dt>
                        <dd>@include('components.status-badge', ['status' => $ticket->status])</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Prioritas</dt>
                        <dd>@include('components.priority-badge', ['priority' => $ticket->priority])</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Kategori</dt>
                        <dd class="text-gray-700">{{ $ticket->category?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Sub Kategori</dt>
                        <dd class="text-gray-700">{{ $ticket->subCategory?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">SLA Due</dt>
                        <dd class="text-gray-700 {{ $ticket->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                            {{ $ticket->due_at?->format('d M Y H:i') ?? '—' }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Dibuat</dt>
                        <dd class="text-gray-700">{{ $ticket->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    @if ($ticket->resolved_at)
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Diselesaikan</dt>
                            <dd class="text-gray-700">{{ $ticket->resolved_at->format('d M Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Requester --}}
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-4">Pemohon</h3>
                <div class="flex items-center gap-3">
                    <img src="{{ $ticket->requester->avatar_url }}" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium text-sm text-gray-800">{{ $ticket->requester->name }}</p>
                        <p class="text-xs text-gray-400">{{ $ticket->requester->email }}</p>
                        <p class="text-xs text-gray-400">{{ $ticket->requester->department?->name ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Agent & Actions (Admin/Agent only) --}}
            @hasanyrole('admin|agent')
                <div class="bg-white rounded-xl border shadow-sm p-5">
                    <h3 class="font-semibold text-gray-700 mb-4">Agent</h3>

                    @if ($ticket->agent)
                        <div class="flex items-center gap-3 mb-4">
                            <img src="{{ $ticket->agent->avatar_url }}" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-medium text-sm text-gray-800">{{ $ticket->agent->name }}</p>
                                <p class="text-xs text-gray-400">{{ $ticket->agent->email }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-400 mb-4">Belum ada agent yang di-assign</p>
                    @endif

                    @role('admin')
                        <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                            @csrf @method('PATCH')
                            <select name="agent_id" class="input w-full mb-2" required>
                                <option value="">-- Assign ke Agent --</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ $ticket->agent_id == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-primary w-full text-sm">
                                <i class="fas fa-user-check mr-1"></i> Assign Agent
                            </button>
                        </form>
                    @endrole
                </div>

                {{-- Update Status --}}
                <div class="bg-white rounded-xl border shadow-sm p-5">
                    <h3 class="font-semibold text-gray-700 mb-4">Update Status</h3>
                    <form method="POST" action="{{ route('tickets.status.update', $ticket) }}">
                        @csrf @method('PATCH')
                        <select name="status" class="input w-full mb-3">
                            @foreach (['open' => 'Open', 'in_progress' => 'In Progress', 'on_hold' => 'On Hold', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $val => $label)
                                <option value="{{ $val }}" {{ $ticket->status === $val ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-primary w-full text-sm">
                            <i class="fas fa-sync mr-1"></i> Update Status
                        </button>
                    </form>
                </div>
            @endhasanyrole

            {{-- Activity Log --}}
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-4">Aktivitas</h3>
                @php $activities = \Spatie\Activitylog\Models\Activity::where('subject_type', get_class($ticket))->where('subject_id', $ticket->id)->latest()->limit(8)->get(); @endphp
                @forelse($activities as $activity)
                    <div class="flex gap-2 text-xs text-gray-500 mb-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-blue-400 mt-1.5 flex-shrink-0"></div>
                        <div>
                            <span class="font-medium text-gray-700">{{ $activity->causer?->name ?? 'System' }}</span>
                            {{ $activity->description }}
                            <span class="text-gray-400 block">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
