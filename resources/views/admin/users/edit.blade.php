{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('header', 'Edit Pengguna')

@section('content')
    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PATCH')
            <div class="bg-white rounded-xl border shadow-sm p-6 space-y-5">
                <div class="flex items-center gap-4 pb-4 border-b">
                    <img src="{{ $user->avatar_url }}" class="w-14 h-14 rounded-full">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                        <p class="text-sm text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input w-full"
                            required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input w-full"
                            required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Password Baru <span class="text-gray-400 text-xs">(kosongkan jika tidak
                                diubah)</span></label>
                        <input type="password" name="password" class="input w-full">
                    </div>
                    <div>
                        <label class="label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="input w-full">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Role <span class="text-red-500">*</span></label>
                        <select name="role" class="input w-full" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Departemen</label>
                        <select name="department_id" class="input w-full">
                            <option value="">— Tidak ada —</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}"
                                    {{ $user->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">ID Karyawan</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
                            class="input w-full">
                    </div>
                    <div>
                        <label class="label">Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input w-full">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded"
                        {{ $user->is_active ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm text-gray-700">Akun Aktif</label>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary flex-1 text-center">Batal</a>
                </div>
            </div>
        </form>
    </div>
@endsection
