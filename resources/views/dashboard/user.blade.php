{{-- resources/views/dashboard/user.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard')
@section('header', 'Dashboard')

@vite(['resources/css/dashboard/dashboard-user.css', 'resources/js/dashboard/dashboard-user.js'])

@section('content')
    {{-- Stat Cards dengan animasi --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Total Tiket Saya</span>
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
                <span class="text-sm text-gray-500 font-medium">Open / In Progress</span>
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-amber-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['open'] }}</p>
            <div class="flex items-center gap-1 mt-2">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-xs text-gray-400">Menunggu penanganan</span>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-200">
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

    {{-- CTA Card - Buat Tiket Baru --}}
    <div
        class="cta-card rounded-xl shadow-lg p-6 mb-6 animate-fade-in delay-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <i class="fas fa-headset text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-lg">Ada masalah IT?</h3>
                <p class="text-blue-100 text-sm">Buat tiket baru dan tim IT kami siap membantu Anda.</p>
            </div>
        </div>
        <a href="{{ route('tickets.create') }}" class="btn-cta">
            <i class="fas fa-plus"></i>
            Buat Tiket Baru
        </a>
    </div>

    {{-- Tiket Saya - DataTables --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm animate-fade-in delay-300 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-semibold text-gray-800 text-lg flex items-center gap-2">
                    <i class="fas fa-list-ul text-blue-500"></i>
                    Tiket Saya
                </h3>
                <p class="text-sm text-gray-400 mt-1">Riwayat tiket support Anda</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('tickets.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm hover:shadow-md">
                    <i class="fas fa-plus text-xs"></i>
                    Tiket Baru
                </a>
            </div>
        </div>

        <div class="p-0">
            <table id="userTicketsTable" class="w-full" style="width: 100% !important;">
                <thead>
                    <tr>
                        <th class="text-left">No. Tiket</th>
                        <th class="text-left">Judul</th>
                        <th class="text-left">Kategori</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Prioritas</th>
                        <th class="text-left">Agent</th>
                        <th class="text-left">Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myTickets as $ticket)
                        <tr class="ticket-row" data-href="{{ route('tickets.show', $ticket) }}">
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
                                @if ($ticket->category)
                                    <span class="category-badge">
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
                                    <span class="agent-waiting">
                                        <i class="fas fa-user-clock text-xs"></i>
                                        Menunggu assign
                                    </span>
                                @endif
                            </td>
                            <td data-order="{{ $ticket->created_at->timestamp }}">
                                <div class="flex items-center gap-1.5 text-gray-400 text-xs">
                                    <i class="far fa-clock"></i>
                                    <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                    </div>
                                    <h4 class="text-gray-600 font-medium mb-1">Belum ada tiket</h4>
                                    <p class="text-gray-400 text-sm mb-4">Anda belum membuat tiket support apapun</p>
                                    <a href="{{ route('tickets.create') }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                        <i class="fas fa-plus text-xs"></i>
                                        Buat tiket pertama Anda
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
