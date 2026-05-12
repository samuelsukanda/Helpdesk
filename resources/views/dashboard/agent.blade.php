{{-- resources/views/dashboard/agent.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Agent')
@section('header', 'Dashboard')

@vite(['resources/css/dashboard/dashboard-agent.css', 'resources/js/dashboard/dashboard-agent.js'])

@section('content')
    {{-- Stat Cards dengan animasi --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Di-assign ke Saya</span>
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tasks text-blue-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['assigned'] }}</p>
            <div class="flex items-center gap-1 mt-2">
                <i class="fas fa-user-check text-blue-500 text-xs"></i>
                <span class="text-xs text-gray-400">Tiket aktif</span>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Open</span>
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-folder-open text-amber-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['open'] }}</p>
            <div class="flex items-center gap-1 mt-2">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-xs text-gray-400">Menunggu ditangani</span>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">In Progress</span>
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-spinner text-purple-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['in_progress'] }}</p>
            <div class="flex items-center gap-1 mt-2">
                <i class="fas fa-clock text-purple-500 text-xs"></i>
                <span class="text-xs text-gray-400">Sedang dikerjakan</span>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Resolved</span>
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['resolved'] }}</p>
            <div class="flex items-center gap-1 mt-2">
                <i class="fas fa-check-double text-emerald-500 text-xs"></i>
                <span class="text-xs text-gray-400">Tiket selesai</span>
            </div>
        </div>
    </div>

    {{-- Tiket Saya yang Aktif - DataTables --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm animate-fade-in delay-300 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-semibold text-gray-800 text-lg flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-blue-500"></i>
                    Tiket Saya yang Aktif
                </h3>
                <p class="text-sm text-gray-400 mt-1">Kelola tiket yang di-assign ke Anda</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('tickets.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                    Lihat semua
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        <div class="p-0">
            <table id="agentTicketsTable" class="w-full" style="width: 100% !important;">
                <thead>
                    <tr>
                        <th class="text-left">No. Tiket</th>
                        <th class="text-left">Judul</th>
                        <th class="text-left">Pemohon</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Prioritas</th>
                        <th class="text-left">Due Date</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myTickets as $ticket)
                        <tr class="ticket-row {{ $ticket->isOverdue() ? 'overdue' : '' }}"
                            data-href="{{ route('tickets.show', $ticket) }}">
                            <td>
                                <span class="font-mono text-blue-600 font-semibold px-2 py-1 rounded text-sm">
                                    {{ $ticket->ticket_number }}
                                </span>
                            </td>
                            <td>
                                <div class="max-w-xs">
                                    <p class="text-gray-700 truncate" title="{{ $ticket->title }}">
                                        {{ $ticket->title }}
                                    </p>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-7 h-7 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($ticket->requester->name, 0, 1)) }}
                                    </div>
                                    <span class="text-gray-600 text-sm">{{ $ticket->requester->name }}</span>
                                </div>
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
                                    <div class="flex items-center gap-1.5">
                                        <span class="due-date {{ $dueClass }}">
                                            {{ $due->format('d M y H:i') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn-action btn-action-view"
                                    title="Lihat detail tiket" onclick="event.stopPropagation();">
                                    <i class="fas fa-eye text-xs"></i>
                                    Buka
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-clipboard-check text-3xl text-gray-300"></i>
                                    </div>
                                    <h4 class="text-gray-600 font-medium mb-1">Tidak ada tiket aktif</h4>
                                    <p class="text-gray-400 text-sm">Tiket yang di-assign akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
