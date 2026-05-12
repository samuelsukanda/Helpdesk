{{-- resources/views/reports/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Laporan')
@section('header', 'Laporan Tiket')

@vite(['resources/css/views/report.css', 'resources/js/views/report.js'])

@section('content')
    {{-- Filter + Export --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-5 animate-fade-in">
        <div class="filter-bar p-4">
            <form method="GET" class="filter-form">
                <div class="filter-date">
                    <label class="filter-label">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="filter-input">
                </div>
                <div class="filter-date">
                    <label class="filter-label">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="filter-input">
                </div>
                <div class="filter-buttons">
                    <button type="submit" class="btn-filter btn-filter-primary">
                        <i class="fas fa-filter"></i>
                        <span class="hidden sm:inline">Filter</span>
                    </button>
                    <a href="{{ route('reports.index') }}" class="btn-filter btn-filter-secondary">
                        <i class="fas fa-undo"></i>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                </div>
                <div class="export-buttons">
                    <a href="{{ route('reports.export.excel', request()->all()) }}" class="btn-export btn-export-excel">
                        <i class="fas fa-file-excel"></i>
                        <span class="hidden sm:inline">Excel</span>
                    </a>
                    <a href="{{ route('reports.export.pdf', request()->all()) }}" class="btn-export btn-export-pdf">
                        <i class="fas fa-file-pdf"></i>
                        <span class="hidden sm:inline">PDF</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $statusConfig = [
                'open' => ['label' => 'Open', 'color' => 'blue', 'icon' => 'fa-folder-open'],
                'in_progress' => ['label' => 'In Progress', 'color' => 'amber', 'icon' => 'fa-spinner'],
                'resolved' => ['label' => 'Resolved', 'color' => 'emerald', 'icon' => 'fa-check-circle'],
                'closed' => ['label' => 'Closed', 'color' => 'gray', 'icon' => 'fa-lock'],
            ];
        @endphp
        @foreach ($statusConfig as $key => $cfg)
            @php $count = $stats['by_status'][$key] ?? 0; @endphp
            <div
                class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-{{ $loop->index * 100 }}">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500 font-medium">{{ $cfg['label'] }}</span>
                    <div class="w-10 h-10 bg-{{ $cfg['color'] }}-50 rounded-xl flex items-center justify-center">
                        <i class="fas {{ $cfg['icon'] }} text-{{ $cfg['color'] }}-600 text-lg"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-{{ $cfg['color'] }}-600">{{ $count }}</p>
                <div class="flex items-center gap-1 mt-2">
                    <span class="text-xs text-gray-400">Tiket {{ strtolower($cfg['label']) }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
        {{-- By Priority --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-layer-group text-purple-500"></i>
                    Distribusi Prioritas
                </h3>
                <span
                    class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">{{ array_sum($stats['by_priority']->toArray()) }}
                    total</span>
            </div>
            @php
                $priorityConfig = [
                    'low' => ['label' => 'Low', 'color' => 'bg-emerald-500', 'text' => 'text-emerald-600'],
                    'medium' => ['label' => 'Medium', 'color' => 'bg-blue-500', 'text' => 'text-blue-600'],
                    'high' => ['label' => 'High', 'color' => 'bg-orange-500', 'text' => 'text-orange-600'],
                    'critical' => ['label' => 'Critical', 'color' => 'bg-red-600', 'text' => 'text-red-600'],
                ];
                $totalPriority = array_sum($stats['by_priority']->toArray()) ?: 1;
            @endphp
            <div class="space-y-4">
                @foreach ($priorityConfig as $key => $cfg)
                    @php
                        $count = $stats['by_priority'][$key] ?? 0;
                        $percentage = round(($count / $totalPriority) * 100);
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

        {{-- By Category --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-100">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-tags text-blue-500"></i>
                    Top Kategori
                </h3>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">Top 6</span>
            </div>
            <div class="space-y-3">
                @forelse($stats['by_category']->sortByDesc('total')->take(6) as $item)
                    @php
                        $maxCat = $stats['by_category']->max('total') ?: 1;
                        $catPercentage = round(($item->total / $maxCat) * 100);
                    @endphp
                    <div
                        class="flex items-center justify-between text-sm group p-2 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <span
                                class="w-6 h-6 bg-blue-50 text-blue-600 rounded-md flex items-center justify-center text-xs font-bold">
                                {{ $loop->iteration }}
                            </span>
                            <span class="text-gray-600 truncate">{{ $item->category?->name ?? 'Tanpa Kategori' }}</span>
                        </div>
                        <div class="flex items-center gap-2 ml-3">
                            <div class="w-20 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                <div class="progress-bar-fill bg-blue-500 h-1.5 rounded-full" style="width: 0%"
                                    data-width="{{ $catPercentage }}%"></div>
                            </div>
                            <span class="font-semibold text-sm w-6 text-right">{{ $item->total }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state py-8">
                        <div class="empty-state-icon w-12 h-12">
                            <i class="fas fa-chart-bar text-2xl text-gray-300"></i>
                        </div>
                        <p class="text-sm text-gray-400">Belum ada data kategori</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Ticket Table - DataTables --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm animate-fade-in delay-200 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-semibold text-gray-800 text-lg flex items-center gap-2">
                    <i class="fas fa-table text-blue-500"></i>
                    Detail Tiket
                </h3>
                <p class="text-sm text-gray-400 mt-1">{{ $tickets->count() }} tiket ditemukan</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1.5 rounded-lg">
                    <i class="fas fa-info-circle mr-1"></i>
                    Klik baris untuk detail
                </span>
            </div>
        </div>

        <div class="p-0">
            <table id="reportsTable" class="w-full" style="width: 100% !important;">
                <thead>
                    <tr>
                        <th class="text-left">No. Tiket</th>
                        <th class="text-left">Judul</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Prioritas</th>
                        <th class="text-left">Kategori</th>
                        <th class="text-left">Requester</th>
                        <th class="text-left">Agent</th>
                        <th class="text-left">SLA</th>
                        <th class="text-left">Dibuat</th>
                        <th class="text-left">Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="ticket-row">
                            <td>
                                <span class="font-mono text-blue-600 font-semibold bg-blue-50 px-2 py-1 rounded text-xs">
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
                                @if ($ticket->category)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">
                                        <i class="fas fa-tag text-xs"></i>
                                        {{ $ticket->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600 text-sm">{{ $ticket->requester->name }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($ticket->agent)
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-600 text-sm">{{ $ticket->agent->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs italic flex items-center gap-1">
                                        <i class="fas fa-user-slash text-xs"></i>
                                        Unassigned
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($ticket->sla_breached)
                                    <span class="badge badge-sla-breach">
                                        <i class="fas fa-times-circle text-xs"></i>
                                        Breach
                                    </span>
                                @else
                                    <span class="badge badge-sla-ok">
                                        <i class="fas fa-check-circle text-xs"></i>
                                        OK
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1.5 text-gray-500 text-xs">
                                    <i class="far fa-calendar"></i>
                                    <span>{{ $ticket->created_at->format('d/m/Y') }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($ticket->resolved_at)
                                    <div class="flex items-center gap-1.5 text-emerald-600 text-xs font-medium">
                                        <i class="fas fa-check"></i>
                                        <span>{{ $ticket->resolved_at->format('d/m/Y') }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                    </div>
                                    <h4 class="text-gray-600 font-medium mb-1">Tidak ada data tiket</h4>
                                    <p class="text-gray-400 text-sm">Sesuaikan filter tanggal untuk melihat data</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection