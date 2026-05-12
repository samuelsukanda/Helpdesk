{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('header', 'Manajemen Pengguna')

@vite(['resources/css/views/admin/users.css', 'resources/js/views/admin/users.js'])

@section('content')
    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Total Pengguna</span>
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] ?? $users->total() }}</p>
            <div class="flex items-center gap-1 mt-2">
                <i class="fas fa-chart-line text-blue-500 text-xs"></i>
                <span class="text-xs text-gray-400">Semua waktu</span>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-100">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Aktif</span>
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-check text-emerald-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['active_users'] ?? 0 }}</p>
            <div class="flex items-center gap-1 mt-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs text-gray-400">Sedang aktif</span>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Admin</span>
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-shield text-purple-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['admin_count'] ?? 0 }}</p>
            <div class="flex items-center gap-1 mt-2">
                <i class="fas fa-crown text-purple-500 text-xs"></i>
                <span class="text-xs text-gray-400">Super admin</span>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 animate-fade-in delay-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Agent</span>
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-headset text-amber-600 text-lg"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['agent_count'] ?? 0 }}</p>
            <div class="flex items-center gap-1 mt-2">
                <i class="fas fa-headset text-amber-500 text-xs"></i>
                <span class="text-xs text-gray-400">Support team</span>
            </div>
        </div>
    </div>

    {{-- Users Table Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm animate-fade-in delay-300 overflow-hidden">
        {{-- Header --}}
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            {{-- Title --}}
            <div>
                <h3 class="font-semibold text-gray-800 text-lg flex items-center gap-2">
                    <i class="fas fa-users text-blue-500"></i>
                    Daftar Pengguna
                </h3>

                <p class="text-sm text-gray-400 mt-1">
                    Kelola dan pantau semua pengguna sistem
                </p>
            </div>

            {{-- Button Tambah User --}}
            <div class="sm:w-auto sm:ml-auto flex justify-end">
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 w-full sm:w-auto">

                    <i class="fas fa-plus text-xs"></i>
                    Tambah Pengguna
                </a>
            </div>

        </div>

        {{-- Filter Bar --}}
        <div class="filter-bar p-4">
            <form method="GET" class="filter-form">

                {{-- Pencarian --}}
                <div class="filter-search">
                    <label class="filter-label">Pencarian</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, email..." class="filter-input">
                    </div>
                </div>

                {{-- Role --}}
                <div class="filter-select">
                    <label class="filter-label">Role</label>

                    <select name="role" class="filter-input">
                        <option value="">Semua Role</option>

                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Departemen --}}
                <div class="filter-select">
                    <label class="filter-label">Departemen</label>

                    <select name="department" class="filter-input">
                        <option value="">Semua Departemen</option>

                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="filter-select">
                    <label class="filter-label">Status</label>

                    <select name="status" class="filter-input">
                        <option value="">Semua Status</option>

                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="filter-buttons">
                    <button type="submit" class="btn-filter btn-filter-primary">
                        <i class="fas fa-filter"></i>
                        <span class="hidden sm:inline">Filter</span>
                    </button>

                    <a href="{{ route('admin.users.index') }}" class="btn-filter btn-filter-secondary">
                        <i class="fas fa-undo"></i>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                </div>

            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Pengguna</th>
                        <th class="px-5 py-3 text-left font-semibold">ID Karyawan</th>
                        <th class="px-5 py-3 text-left font-semibold">Role</th>
                        <th class="px-5 py-3 text-left font-semibold">Departemen</th>
                        <th class="px-5 py-3 text-left font-semibold">Telepon</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="user-row hover:bg-gray-50 transition-colors cursor-pointer"
                            data-href="{{ route('admin.users.edit', $user) }}">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span
                                    class="text-gray-500 font-mono text-xs bg-gray-50 px-2 py-1 rounded">{{ $user->employee_id ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($user->getRoleNames() as $role)
                                        @php
                                            $roleConfig = match ($role) {
                                                'admin' => [
                                                    'bg' => 'bg-purple-100',
                                                    'text' => 'text-purple-700',
                                                    'icon' => 'fa-user-shield',
                                                ],
                                                'agent' => [
                                                    'bg' => 'bg-amber-100',
                                                    'text' => 'text-yellow-700',
                                                    'icon' => 'fa-headset',
                                                ],
                                                'user' => [
                                                    'bg' => 'bg-blue-100',
                                                    'text' => 'text-blue-700',
                                                    'icon' => 'fa-user',
                                                ],
                                                default => [
                                                    'bg' => 'bg-gray-100',
                                                    'text' => 'text-gray-700',
                                                    'icon' => 'fa-user',
                                                ],
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center gap-1 {{ $roleConfig['bg'] }} {{ $roleConfig['text'] }} text-xs px-2.5 py-1 rounded-full font-medium">
                                            <i class="fas {{ $roleConfig['icon'] }} text-[10px]"></i>
                                            {{ ucfirst($role) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-gray-600 text-sm">{{ $user->department?->name ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-gray-500 text-sm">{{ $user->phone ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline"
                                    onclick="event.stopPropagation();">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="name" value="{{ $user->name }}">
                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                    <input type="hidden" name="role" value="{{ $user->getRoleNames()->first() }}">
                                    <input type="hidden" name="is_active" value="{{ $user->is_active ? 0 : 1 }}">
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-medium transition-all {{ $user->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}"></span>
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                        title="Edit pengguna" onclick="event.stopPropagation();">
                                        <i class="fas fa-pen text-sm"></i>
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <button type="button"
                                            class="btn-delete w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition-colors"
                                            title="Hapus pengguna" data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-delete-url="{{ route('admin.users.destroy', $user) }}"
                                            onclick="event.stopPropagation();">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-users-slash text-3xl text-gray-300"></i>
                                    </div>
                                    <h4 class="text-gray-600 font-medium mb-1">Tidak ada pengguna</h4>
                                    <p class="text-gray-400 text-sm">Pengguna baru akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection