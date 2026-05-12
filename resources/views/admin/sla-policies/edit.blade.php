{{-- resources/views/admin/sla-policies/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit SLA Policy')
@section('header', 'Edit SLA Policy')

@section('content')
    <div class="max-w-lg mx-auto">
        <form method="POST" action="{{ route('admin.sla.update', $slaPolicy) }}">
            @csrf @method('PATCH')
            <div class="bg-white rounded-xl border shadow-sm p-6 space-y-5">
                <div>
                    <label class="label">Nama Policy</label>
                    <input type="text" name="name" value="{{ old('name', $slaPolicy->name) }}" class="input w-full"
                        required>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-600">
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                    Prioritas: <span class="font-semibold capitalize">{{ $slaPolicy->priority }}</span> (tidak dapat
                    diubah)
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Waktu Respons (jam) <span class="text-red-500">*</span></label>
                        <input type="number" name="response_time_hours" min="1"
                            value="{{ old('response_time_hours', $slaPolicy->response_time_hours) }}" class="input w-full"
                            required>
                        <p class="text-xs text-gray-400 mt-1">Batas waktu respons pertama</p>
                    </div>
                    <div>
                        <label class="label">Waktu Penyelesaian (jam) <span class="text-red-500">*</span></label>
                        <input type="number" name="resolution_time_hours" min="1"
                            value="{{ old('resolution_time_hours', $slaPolicy->resolution_time_hours) }}"
                            class="input w-full" required>
                        <p class="text-xs text-gray-400 mt-1">Batas waktu penyelesaian tiket</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded"
                        {{ $slaPolicy->is_active ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm text-gray-700">SLA Aktif</label>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1"><i class="fas fa-save mr-2"></i> Simpan</button>
                    <a href="{{ route('admin.sla.index') }}" class="btn-secondary flex-1 text-center">Batal</a>
                </div>
            </div>
        </form>
    </div>
@endsection
