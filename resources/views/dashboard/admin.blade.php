{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('header', 'Dashboard')

@vite(['resources/css/dashboard/dashboard-admin.css', 'resources/js/dashboard/dashboard-admin.js'])

@section('content')
    {{-- Stat Cards dengan animasi --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Total Tiket</span>
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-blue-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            <div class="flex items-center gap-1 mt-2">
                <i class="fas fa-chart-line text-blue-500 text-xs"></i>
                <span class="text-xs text-gray-400">Semua waktu</span>
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
                <span class="text-xs text-gray-400">Belum ditangani</span>
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
                <span class="text-sm text-gray-500 font-medium">SLA Breach</span>
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-red-600">{{ $stats['overdue'] }}</p>
            <div class="flex items-center gap-1 mt-2">
                <i class="fas fa-exclamation-circle text-red-500 text-xs"></i>
                <span class="text-xs text-gray-400">Melewati deadline</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Chart Status dengan progress animasi --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-blue-500"></i>
                    Tiket per Status
                </h3>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">{{ $stats['total'] }} total</span>
            </div>
            <div class="space-y-4">
                @php
                    $statusConfig = [
                        'open' => [
                            'label' => 'Open',
                            'color' => 'bg-blue-500',
                            'text' => 'text-blue-600',
                            'bg' => 'bg-blue-50',
                        ],
                        'in_progress' => [
                            'label' => 'In Progress',
                            'color' => 'bg-amber-500',
                            'text' => 'text-amber-600',
                            'bg' => 'bg-amber-50',
                        ],
                        'on_hold' => [
                            'label' => 'On Hold',
                            'color' => 'bg-gray-400',
                            'text' => 'text-gray-600',
                            'bg' => 'bg-gray-50',
                        ],
                        'resolved' => [
                            'label' => 'Resolved',
                            'color' => 'bg-emerald-500',
                            'text' => 'text-emerald-600',
                            'bg' => 'bg-emerald-50',
                        ],
                        'closed' => [
                            'label' => 'Closed',
                            'color' => 'bg-gray-700',
                            'text' => 'text-gray-700',
                            'bg' => 'bg-gray-100',
                        ],
                    ];
                    $total = $stats['total'] ?: 1;
                @endphp
                @foreach ($statusConfig as $key => $cfg)
                    @php
                        $count = $ticketsByStatus[$key] ?? 0;
                        $percentage = round(($count / $total) * 100);
                    @endphp
                    <div class="group">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $cfg['color'] }}"></span>
                                <span class="text-sm text-gray-600 font-medium">{{ $cfg['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold {{ $cfg['text'] }}">{{ $count }}</span>
                                <span class="text-xs text-gray-400">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="progress-bar-fill {{ $cfg['color'] }} h-2 rounded-full" style="width: 0%"
                                data-width="{{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Chart Priority --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-100">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-layer-group text-purple-500"></i>
                    Tiket per Prioritas
                </h3>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">{{ $stats['total'] }} total</span>
            </div>
            <div class="space-y-4">
                @php
                    $priorityConfig = [
                        'low' => [
                            'label' => 'Low',
                            'color' => 'bg-emerald-500',
                            'text' => 'text-emerald-600',
                            'icon' => 'fa-arrow-down',
                        ],
                        'medium' => [
                            'label' => 'Medium',
                            'color' => 'bg-blue-500',
                            'text' => 'text-blue-600',
                            'icon' => 'fa-minus',
                        ],
                        'high' => [
                            'label' => 'High',
                            'color' => 'bg-orange-500',
                            'text' => 'text-orange-600',
                            'icon' => 'fa-arrow-up',
                        ],
                        'critical' => [
                            'label' => 'Critical',
                            'color' => 'bg-red-600',
                            'text' => 'text-red-600',
                            'icon' => 'fa-fire',
                        ],
                    ];
                @endphp
                @foreach ($priorityConfig as $key => $cfg)
                    @php
                        $count = $ticketsByPriority[$key] ?? 0;
                        $percentage = round(($count / $total) * 100);
                    @endphp
                    <div class="group">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $cfg['color'] }}"></span>
                                <span class="text-sm text-gray-600 font-medium">{{ $cfg['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold {{ $cfg['text'] }}">{{ $count }}</span>
                                <span class="text-xs text-gray-400">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="progress-bar-fill {{ $cfg['color'] }} h-2 rounded-full" style="width: 0%"
                                data-width="{{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-200">
            <h3 class="font-semibold text-gray-700 mb-5 flex items-center gap-2">
                <i class="fas fa-chart-bar text-emerald-500"></i>
                Ringkasan Sistem
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-500"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Pengguna</p>
                            <p class="text-xs text-gray-400">Aktif & non-aktif</p>
                        </div>
                    </div>
                    <span class="font-bold text-gray-800 text-lg">{{ $stats['total_users'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-headset text-emerald-500"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Agent</p>
                            <p class="text-xs text-gray-400">Support team</p>
                        </div>
                    </div>
                    <span class="font-bold text-gray-800 text-lg">{{ $stats['total_agents'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Resolved</p>
                            <p class="text-xs text-gray-400">Tiket selesai</p>
                        </div>
                    </div>
                    <span class="font-bold text-emerald-600 text-lg">{{ $stats['resolved'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Overdue</p>
                            <p class="text-xs text-gray-400">Melewati SLA</p>
                        </div>
                    </div>
                    <span class="font-bold text-red-600 text-lg">{{ $stats['overdue'] }}</span>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="mt-4 w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition-all hover:shadow-lg hover:shadow-blue-200 text-sm">
                <i class="fas fa-users"></i>
                Kelola Pengguna
            </a>
        </div>
    </div>

    {{-- Recent Tickets - DataTables --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm animate-fade-in delay-300 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-semibold text-gray-800 text-lg flex items-center gap-2">
                    <i class="fas fa-list-ul text-blue-500"></i>
                    Tiket Terbaru
                </h3>
                <p class="text-sm text-gray-400 mt-1">Kelola dan pantau semua tiket support</p>
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
            <table id="ticketsTable" class="w-full" style="width: 100% !important;">
                <thead>
                    <tr>
                        <th class="text-left">No. Tiket</th>
                        <th class="text-left">Judul</th>
                        <th class="text-left">Pemohon</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Prioritas</th>
                        <th class="text-left">Agent</th>
                        <th class="text-left">Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTickets as $ticket)
                        <tr class="ticket-row" data-href="{{ route('tickets.show', $ticket) }}">
                            <td>
                                <span class="ticket-number font-mono text-blue-600 font-semibold rounded">
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
                            <td data-order="{{ $ticket->created_at->timestamp }}">
                                <div class="flex items-center gap-1.5 text-gray-400 text-xs">
                                    <i class="far fa-clock"></i>
                                    <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('tickets.show', $ticket) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                        title="Lihat detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <button type="button"
                                        class="btn-delete w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition-colors"
                                        title="Hapus tiket" data-ticket-number="{{ $ticket->ticket_number }}"
                                        data-ticket-title="{{ $ticket->title }}"
                                        data-delete-url="{{ route('tickets.destroy', $ticket) }}"
                                        onclick="event.stopPropagation();">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                    </div>
                                    <h4 class="text-gray-600 font-medium mb-1">Belum ada tiket</h4>
                                    <p class="text-gray-400 text-sm">Tiket baru akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
