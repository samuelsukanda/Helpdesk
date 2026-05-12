{{-- resources/views/admin/departments/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Departemen')
@section('header', 'Edit Departemen')

@section('content')
    <div class="max-w-lg mx-auto">
        <form method="POST" action="{{ route('admin.departments.update', $department) }}">
            @csrf
            @method('PATCH')
            <div class="bg-white rounded-xl border shadow-sm p-6 space-y-5">

                {{-- Info header --}}
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $department->name }}</p>
                        <p class="text-xs text-gray-400">Kode: {{ $department->code }}</p>
                    </div>
                </div>

                <div>
                    <label class="label">Nama Departemen <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $department->name) }}" class="input w-full"
                        required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $department->code) }}" class="input w-full"
                        maxlength="10" required>
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Deskripsi</label>
                    <textarea name="description" rows="3" class="input w-full">{{ old('description', $department->description) }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded"
                        {{ $department->is_active ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm text-gray-700">Departemen Aktif</label>
                </div>

                <div class="pt-2 bg-gray-50 -mx-6 -mb-6 px-6 py-4 rounded-b-xl flex gap-3 border-t">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.departments.index') }}" class="btn-secondary flex-1 text-center">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            document.querySelector('input[name=code]').addEventListener('input', function() {
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            });
        </script>
    @endpush
@endsection
