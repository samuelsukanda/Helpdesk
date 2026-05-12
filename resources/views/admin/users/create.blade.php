{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('header', 'Tambah Pengguna')

@section('content')
    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="bg-white rounded-xl border shadow-sm p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input w-full" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input w-full" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="input w-full" required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" class="input w-full" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Role <span class="text-red-500">*</span></label>
                        <select name="role" class="input w-full" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Departemen</label>
                        <select name="department_id" class="input w-full">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}"
                                    {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">ID Karyawan</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id') }}" class="input w-full"
                            placeholder="EMP001">
                        @error('employee_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="input w-full"
                            placeholder="08xx">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary flex-1 text-center">Batal</a>
                </div>
            </div>
        </form>
    </div>
@endsection