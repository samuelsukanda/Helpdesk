{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Kategori')
@section('header', 'Manajemen Kategori')

@vite(['resources/css/views/admin/categories.css', 'resources/js/views/admin/categories.js'])

@section('content')

    {{-- Stat Card --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Total Kategori</span>

                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-layer-group text-blue-600"></i>
                </div>
            </div>

            <p class="text-3xl font-bold text-gray-800">
                {{ $categories->total() }}
            </p>

            <p class="text-xs text-gray-400 mt-2">
                Semua kategori sistem
            </p>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Kategori Aktif</span>

                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
            </div>

            <p class="text-3xl font-bold text-emerald-600">
                {{ $categories->where('is_active', true)->count() }}
            </p>

            <p class="text-xs text-gray-400 mt-2">
                Sedang digunakan
            </p>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Sub Kategori</span>

                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-sitemap text-purple-600"></i>
                </div>
            </div>

            <p class="text-3xl font-bold text-gray-800">
                {{ $categories->sum(fn($item) => $item->subCategories->count()) }}
            </p>

            <p class="text-xs text-gray-400 mt-2">
                Total sub kategori
            </p>
        </div>

        <div class="stat-card bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500 font-medium">Total Tiket</span>

                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-amber-600"></i>
                </div>
            </div>

            <p class="text-3xl font-bold text-gray-800">
                {{ $categories->sum('tickets_count') }}
            </p>

            <p class="text-xs text-gray-400 mt-2">
                Semua tiket kategori
            </p>
        </div>

    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <div>
                <h3 class="font-semibold text-gray-800 text-lg flex items-center gap-2">
                    <i class="fas fa-layer-group text-blue-500"></i>
                    Daftar Kategori
                </h3>

                <p class="text-sm text-gray-400 mt-1">
                    Kelola kategori dan sub kategori tiket
                </p>
            </div>

            <div class="sm:w-auto sm:ml-auto flex justify-end">
                <a href="{{ route('admin.categories.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 w-full sm:w-auto">

                    <i class="fas fa-plus text-xs"></i>
                    Tambah Kategori
                </a>
            </div>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Kategori</th>
                        <th class="px-5 py-3 text-left font-semibold">Sub Kategori</th>
                        <th class="px-5 py-3 text-left font-semibold">Total Tiket</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- Nama --}}
                            <td class="px-5 py-3">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                        style="background-color: {{ $category->color }}22">

                                        <i class="fas {{ $category->icon }}" style="color: {{ $category->color }}"></i>
                                    </div>

                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">
                                            {{ $category->name }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            {{ $category->slug }}
                                        </p>
                                    </div>

                                </div>

                            </td>

                            {{-- Sub Kategori --}}
                            <td class="px-5 py-3">

                                <span
                                    class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-xs px-2.5 py-1 rounded-full font-medium">

                                    <i class="fas fa-sitemap text-[10px]"></i>

                                    {{ $category->subCategories->count() }} Sub
                                </span>

                            </td>

                            {{-- Total Tiket --}}
                            <td class="px-5 py-3">

                                <span class="text-gray-600 text-sm font-medium">
                                    {{ $category->tickets_count }}
                                </span>

                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3">

                                <span
                                    class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-medium
                                    {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">

                                    <span
                                        class="w-1.5 h-1.5 rounded-full
                                        {{ $category->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}">
                                    </span>

                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}

                                </span>

                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3 text-center">

                                <div class="flex items-center justify-center gap-1">

                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"
                                        title="Edit" onclick="event.stopPropagation();">

                                        <i class="fas fa-pen text-sm"></i>
                                    </a>

                                    <button type="button"
                                        class="btn-delete w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors"
                                        title="Hapus" data-category-name="{{ $category->name }}"
                                        data-delete-url="{{ route('admin.categories.destroy', $category) }}"
                                        onclick="event.stopPropagation();">

                                        <i class="fas fa-trash text-sm"></i>
                                    </button>

                                </div>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center">

                                <div class="py-14">

                                    <div
                                        class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">

                                        <i class="fas fa-folder-open text-2xl text-gray-300"></i>
                                    </div>

                                    <h4 class="text-gray-600 font-medium mb-1">
                                        Belum ada kategori
                                    </h4>

                                    <p class="text-gray-400 text-sm">
                                        Kategori baru akan muncul di sini
                                    </p>

                                </div>

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if ($categories->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $categories->links() }}
            </div>
        @endif

    </div>

@endsection
