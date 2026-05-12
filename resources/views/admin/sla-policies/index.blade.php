{{-- resources/views/admin/sla-policies/index.blade.php --}}
@extends('layouts.app')
@section('title', 'SLA Policy')
@section('header', 'SLA Policy')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach ($policies as $policy)
            @php
                $colors = ['low' => 'green', 'medium' => 'blue', 'high' => 'orange', 'critical' => 'red'];
                $color = $colors[$policy->priority] ?? 'gray';
            @endphp
            <div class="bg-white rounded-xl border shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $policy->name }}</h3>
                        <span
                            class="text-xs px-2 py-0.5 rounded-full bg-{{ $color }}-100 text-{{ $color }}-700 font-medium capitalize mt-1 inline-block">
                            {{ $policy->priority }}
                        </span>
                    </div>
                    <span
                        class="text-xs px-2 py-0.5 rounded-full {{ $policy->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                        {{ $policy->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-gray-800">{{ $policy->response_time_label }}</p>
                        <p class="text-xs text-gray-500 mt-1">Waktu Respons Pertama</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <p class="text-2xl font-bold text-gray-800">{{ $policy->resolution_time_label }}</p>
                        <p class="text-xs text-gray-500 mt-1">Waktu Penyelesaian</p>
                    </div>
                </div>
                <a href="{{ route('admin.sla.edit', $policy) }}" class="btn-secondary w-full text-center text-sm">
                    <i class="fas fa-edit mr-1"></i> Edit SLA
                </a>
            </div>
        @endforeach
    </div>
@endsection
