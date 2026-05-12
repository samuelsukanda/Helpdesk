@extends('layouts.app')
@section('header', 'Daftar Tiket')

@vite(['resources/css/views/ticket.css', 'resources/js/views/ticket.js'])

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
        {{-- Filter Bar --}}
        <div class="filter-bar p-4">
            <form method="GET" class="filter-form">
                {{-- Pencarian --}}
                <div class="filter-search">
                    <label class="filter-label">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nomor tiket atau judul..." class="filter-input">
                </div>

                {{-- Status --}}
                <div class="filter-select">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-input">
                        <option value="">Semua Status</option>
                        @foreach (['open' => 'Open', 'in_progress' => 'In Progress', 'on_hold' => 'On Hold', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $val => $label)
                            <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Prioritas --}}
                <div class="filter-select">
                    <label class="filter-label">Prioritas</label>
                    <select name="priority" class="filter-input">
                        <option value="">Semua Prioritas</option>
                        @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'] as $val => $label)
                            <option value="{{ $val }}" {{ request('priority') == $val ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kategori --}}
                <div class="filter-select">
                    <label class="filter-label">Kategori</label>
                    <select name="category" class="filter-input">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="filter-buttons">
                    <button type="submit" class="btn-filter btn-filter-primary">
                        <i class="fas fa-search"></i>
                        <span class="hidden sm:inline">Filter</span>
                    </button>
                    <a href="{{ route('tickets.index') }}" class="btn-filter btn-filter-secondary">
                        <i class="fas fa-undo"></i>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                </div>
            </form>
        </div>

        {{-- DataTable --}}
        <div class="p-0">
            <table id="ticketsIndexTable" class="w-full" style="width: 100% !important;">
                <thead>
                    <tr>
                        <th class="text-left">No. Tiket</th>
                        <th class="text-left">Judul</th>
                        <th class="text-left">Kategori</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Prioritas</th>
                        <th class="text-left">Agent</th>
                        <th class="text-left">Due Date</th>
                        <th class="text-left">Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="ticket-row {{ $ticket->isOverdue() ? 'overdue' : '' }}"
                            data-href="{{ route('tickets.show', $ticket) }}">
                            <td>
                                <div class="flex items-center gap-1">
                                    <span class="font-mono text-blue-600 font-semibold px-2 py-1 rounded text-sm">
                                        {{ $ticket->ticket_number }}
                                    </span>
                                    @if ($ticket->is_escalated)
                                        <span class="escalated-badge" title="Tiket di-escalate">
                                            <i class="fas fa-arrow-up"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="max-w-xs">
                                    <p class="text-gray-700 truncate" title="{{ $ticket->title }}">
                                        {{ $ticket->title }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                @if ($ticket->category)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">
                                        <i class="fas fa-tag text-xs"></i>
                                        {{ $ticket->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match ($ticket->status) {
                                        'open' => 'badge-open',
                                        'in_progress' => 'badge-in_progress',
                                        'on_hold' => 'badge-on_hold',
                                        'resolved' => 'badge-resolved',
                                        'closed' => 'badge-closed',
                                        default => 'badge-open',
                                    };
                                    $statusIcon = match ($ticket->status) {
                                        'open' => 'fa-folder-open',
                                        'in_progress' => 'fa-spinner fa-spin',
                                        'on_hold' => 'fa-pause',
                                        'resolved' => 'fa-check',
                                        'closed' => 'fa-lock',
                                        default => 'fa-folder-open',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }} text-xs"></i>
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $priorityClass = match ($ticket->priority) {
                                        'low' => 'badge-priority-low',
                                        'medium' => 'badge-priority-medium',
                                        'high' => 'badge-priority-high',
                                        'critical' => 'badge-priority-critical',
                                        default => 'badge-priority-medium',
                                    };
                                @endphp
                                <span class="badge {{ $priorityClass }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                @if ($ticket->agent)
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-600 text-sm">{{ $ticket->agent->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm italic flex items-center gap-1">
                                        <i class="fas fa-user-slash text-xs"></i>
                                        Unassigned
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($ticket->due_at)
                                    @php
                                        $now = now();
                                        $due = $ticket->due_at;
                                        $isOverdue = $ticket->isOverdue();
                                        $isWarning = !$isOverdue && $now->diffInHours($due, false) <= 24;
                                        $dueClass = $isOverdue
                                            ? 'due-date-overdue'
                                            : ($isWarning
                                                ? 'due-date-warning'
                                                : 'due-date-normal');
                                        $dueIcon = $isOverdue
                                            ? 'fa-exclamation-circle'
                                            : ($isWarning
                                                ? 'fa-clock'
                                                : 'fa-calendar');
                                    @endphp
                                    <span class="due-date {{ $dueClass }}">
                                        {{ $due->format('d M y H:i') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>
                            <td data-order="{{ $ticket->created_at->timestamp }}">
                                <div class="flex items-center gap-1.5 text-gray-400 text-xs">
                                    <i class="far fa-clock"></i>
                                    <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn-action btn-action-view"
                                    title="Lihat detail tiket" onclick="event.stopPropagation();">
                                    <i class="fas fa-eye text-xs"></i>
                                    <span class="hidden lg:inline">Buka</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                    </div>
                                    <h4 class="text-gray-600 font-medium mb-1">Belum ada tiket</h4>
                                    <p class="text-gray-400 text-sm">Tiket akan muncul di sini setelah dibuat</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
