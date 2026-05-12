{{-- resources/views/admin/departments/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Departemen')
@section('header', 'Tambah Departemen')

@section('content')
    <div class="max-w-lg mx-auto">
        <form method="POST" action="{{ route('admin.departments.store') }}">
            @csrf
            <div class="bg-white rounded-xl border shadow-sm p-6 space-y-5">

                <div>
                    <label class="label">Nama Departemen <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input w-full"
                        placeholder="Contoh: Information Technology" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" class="input w-full"
                        placeholder="Contoh: IT" maxlength="10" required>
                    <p class="text-xs text-gray-400 mt-1">Maks. 10 karakter, huruf kapital</p>
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Deskripsi</label>
                    <textarea name="description" rows="3" class="input w-full" placeholder="Deskripsi singkat departemen...">{{ old('description') }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Simpan
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
