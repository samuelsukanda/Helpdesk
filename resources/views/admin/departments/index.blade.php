{{-- resources/views/admin/departments/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Departemen')
@section('header', 'Manajemen Departemen')

@vite(['resources/css/views/admin/departments.css', 'resources/js/views/admin/departments.js'])

@section('content')

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">

                <span class="text-sm text-gray-500 font-medium">
                    Total Departemen
                </span>

                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-blue-600"></i>
                </div>

            </div>

            <p class="text-3xl font-bold text-gray-800">
                {{ $departments->total() }}
            </p>

            <p class="text-xs text-gray-400 mt-2">
                Semua departemen sistem
            </p>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">

                <span class="text-sm text-gray-500 font-medium">
                    Departemen Aktif
                </span>

                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>

            </div>

            <p class="text-3xl font-bold text-emerald-600">
                {{ $departments->where('is_active', true)->count() }}
            </p>

            <p class="text-xs text-gray-400 mt-2">
                Sedang digunakan
            </p>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">

                <span class="text-sm text-gray-500 font-medium">
                    Total User
                </span>

                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-purple-600"></i>
                </div>

            </div>

            <p class="text-3xl font-bold text-gray-800">
                {{ $departments->sum('users_count') }}
            </p>

            <p class="text-xs text-gray-400 mt-2">
                Semua pengguna
            </p>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">

                <span class="text-sm text-gray-500 font-medium">
                    Nonaktif
                </span>

                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-ban text-red-500"></i>
                </div>

            </div>

            <p class="text-3xl font-bold text-gray-800">
                {{ $departments->where('is_active', false)->count() }}
            </p>

            <p class="text-xs text-gray-400 mt-2">
                Tidak aktif
            </p>
        </div>

    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <div>
                <h3 class="font-semibold text-gray-800 text-lg flex items-center gap-2">
                    <i class="fas fa-building text-blue-500"></i>
                    Daftar Departemen
                </h3>

                <p class="text-sm text-gray-400 mt-1">
                    Kelola departemen dan pengguna perusahaan
                </p>
            </div>

            <div class="sm:w-auto sm:ml-auto flex justify-end">
                <a href="{{ route('admin.departments.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 w-full sm:w-auto">

                    <i class="fas fa-plus text-xs"></i>
                    Tambah Departemen
                </a>
            </div>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Departemen</th>
                        <th class="px-5 py-3 text-left font-semibold">Kode</th>
                        <th class="px-5 py-3 text-left font-semibold">Deskripsi</th>
                        <th class="px-5 py-3 text-left font-semibold">Total User</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($departments as $dept)
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- Nama --}}
                            <td class="px-5 py-3">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">

                                        <i class="fas fa-building text-blue-600"></i>
                                    </div>

                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">
                                            {{ $dept->name }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            Department
                                        </p>
                                    </div>

                                </div>

                            </td>

                            {{-- Kode --}}
                            <td class="px-5 py-3">

                                <span class="text-gray-500 font-mono text-xs bg-gray-50 px-2 py-1 rounded">
                                    {{ $dept->code }}
                                </span>

                            </td>

                            {{-- Deskripsi --}}
                            <td class="px-5 py-3">

                                <p class="text-gray-500 text-sm max-w-xs truncate">
                                    {{ $dept->description ?? '—' }}
                                </p>

                            </td>

                            {{-- Total User --}}
                            <td class="px-5 py-3">

                                <span
                                    class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-xs px-2.5 py-1 rounded-full font-medium">

                                    <i class="fas fa-users text-[10px]"></i>

                                    {{ $dept->users_count }} User
                                </span>

                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3">

                                <span
                                    class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-medium
                                    {{ $dept->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">

                                    <span
                                        class="w-1.5 h-1.5 rounded-full
                                        {{ $dept->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}">
                                    </span>

                                    {{ $dept->is_active ? 'Aktif' : 'Nonaktif' }}

                                </span>

                            </td>

                            {{-- Action --}}
                            <td class="px-5 py-3 text-center">

                                <div class="flex items-center justify-center gap-1">

                                    <a href="{{ route('admin.departments.edit', $dept) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                        title="Edit" onclick="event.stopPropagation();">

                                        <i class="fas fa-pen text-sm"></i>
                                    </a>

                                    <button type="button"
                                        class="btn-delete w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors"
                                        title="Hapus" data-department-name="{{ $dept->name }}"
                                        data-department-code="{{ $dept->code }}"
                                        data-delete-url="{{ route('admin.departments.destroy', $dept) }}"
                                        onclick="event.stopPropagation();">

                                        <i class="fas fa-trash text-sm"></i>
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">

                                <div class="py-14">

                                    <div
                                        class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">

                                        <i class="fas fa-building text-2xl text-gray-300"></i>
                                    </div>

                                    <h4 class="text-gray-600 font-medium mb-1">
                                        Belum ada departemen
                                    </h4>

                                    <p class="text-gray-400 text-sm">
                                        Departemen baru akan muncul di sini
                                    </p>

                                </div>

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if ($departments->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $departments->links() }}
            </div>
        @endif

    </div>

@endsection
