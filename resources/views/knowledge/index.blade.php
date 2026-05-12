{{-- resources/views/knowledge/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Knowledge Base')
@section('header', 'Knowledge Base')

@section('content')

    {{-- Search & Tombol Tulis --}}
    <div class="mb-5">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2">

            <form method="GET" class="contents">
                @if (request('tab') && request('tab') !== 'published')
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif

                <div class="flex gap-2 flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel..."
                        class="input flex-1 sm:w-72 sm:flex-none">
                    <select name="category" class="input flex-1 sm:flex-none">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 flex-shrink-0 order-last sm:order-none">
                    <button type="submit" class="btn-primary flex-1 sm:flex-none">
                        <i class="fas fa-search mr-1"></i> Cari
                    </button>
                    <a href="{{ route('knowledge.index') }}" class="btn-secondary flex-1 sm:flex-none text-center">
                        Reset
                    </a>
                </div>
            </form>

            @hasanyrole('admin|agent')
                <div class="w-full sm:w-auto sm:ml-auto">
                    <a href="{{ route('knowledge.create') }}"
                        class="btn-primary w-full sm:w-fit inline-flex items-center justify-center gap-2 px-4 py-2">
                        <i class="fas fa-plus"></i>
                        <span>Tulis Artikel</span>
                    </a>
                </div>
            @endhasanyrole

        </div>
    </div>

    {{-- Tab Navigation (hanya tampil untuk admin & agent) --}}
    @hasanyrole('admin|agent')
        <div class="flex gap-1 mb-5 bg-gray-100 p-1 rounded-xl w-fit">
            <a href="{{ route('knowledge.index', array_merge(request()->except('tab', 'page'), ['tab' => 'published'])) }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                {{ $tab === 'published' ? 'bg-white shadow text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="fas fa-globe mr-1"></i>
                Published
                <span
                    class="ml-1 text-xs px-1.5 py-0.5 rounded-full
                    {{ $tab === 'published' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-500' }}">
                    {{ $counts['published'] ?? 0 }}
                </span>
            </a>
            <a href="{{ route('knowledge.index', array_merge(request()->except('tab', 'page'), ['tab' => 'draft'])) }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                {{ $tab === 'draft' ? 'bg-white shadow text-yellow-700' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="fas fa-file-alt mr-1"></i>
                Draft
                @if (($counts['draft'] ?? 0) > 0)
                    <span
                        class="ml-1 text-xs px-1.5 py-0.5 rounded-full
                        {{ $tab === 'draft' ? 'bg-yellow-100 text-yellow-700' : 'bg-yellow-100 text-yellow-600' }}">
                        {{ $counts['draft'] }}
                    </span>
                @endif
            </a>
            <a href="{{ route('knowledge.index', array_merge(request()->except('tab', 'page'), ['tab' => 'mine'])) }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                {{ $tab === 'mine' ? 'bg-white shadow text-purple-700' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="fas fa-user mr-1"></i>
                Artikel Saya
                <span
                    class="ml-1 text-xs px-1.5 py-0.5 rounded-full
                    {{ $tab === 'mine' ? 'bg-purple-100 text-purple-700' : 'bg-gray-200 text-gray-500' }}">
                    {{ $counts['mine'] ?? 0 }}
                </span>
            </a>
        </div>
    @endhasanyrole

    {{-- Info banner draft --}}
    @if ($tab === 'draft')
        <div
            class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
            <i class="fas fa-info-circle"></i>
            Draft tidak terlihat oleh pengguna biasa. Klik <strong>Publish</strong> untuk mempublikasikan artikel.
        </div>
    @endif

    {{-- Artikel Grid --}}
    @if ($articles->isEmpty())
        <div class="bg-white rounded-xl border shadow-sm p-16 text-center text-gray-400">
            <i class="fas fa-book-open text-5xl mb-3 block"></i>
            @if ($tab === 'draft')
                <p class="font-medium">Tidak ada draft artikel</p>
                <p class="text-sm mt-1">Tulis artikel baru dan simpan sebagai draft</p>
            @elseif($tab === 'mine')
                <p class="font-medium">Anda belum menulis artikel</p>
                <p class="text-sm mt-1">Mulai tulis artikel pertama Anda</p>
            @else
                <p class="font-medium">Belum ada artikel</p>
                <p class="text-sm mt-1">Artikel solusi dan FAQ akan muncul di sini</p>
            @endif
            @hasanyrole('admin|agent')
                <a href="{{ route('knowledge.create') }}" class="btn-primary inline-flex mt-4">
                    <i class="fas fa-plus mr-2"></i> Tulis Artikel
                </a>
            @endhasanyrole
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($articles as $article)
                <div
                    class="bg-white rounded-xl border shadow-sm p-5 hover:shadow-md transition-all
                    {{ $article->status === 'draft' ? 'border-yellow-200 bg-yellow-50/30' : 'hover:border-blue-200' }}">

                    {{-- Header badge --}}
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">
                                {{ $article->category?->name ?? 'Umum' }}
                            </span>
                            @if ($article->status === 'draft')
                                <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full font-medium">
                                    <i class="fas fa-file-alt mr-0.5"></i> Draft
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400 flex items-center gap-1 flex-shrink-0">
                            <i class="fas fa-eye"></i> {{ number_format($article->views) }}
                        </span>
                    </div>

                    {{-- Title & excerpt --}}
                    <a href="{{ route('knowledge.show', $article) }}" class="block group">
                        <h3
                            class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            {{ $article->title }}
                        </h3>
                        <p class="text-sm text-gray-500 line-clamp-3">
                            {{ Str::limit(strip_tags($article->content), 120) }}
                        </p>
                    </a>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                        <div class="text-xs text-gray-400">
                            <i class="fas fa-user mr-1"></i>{{ $article->author->name }}
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400">
                                {{ $article->updated_at->format('d M Y') }}
                            </span>

                            @hasanyrole('admin|agent')
                                <div class="flex items-center gap-1">
                                    @if ($article->status === 'draft')
                                        <form method="POST" action="{{ route('knowledge.publish', $article) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" title="Publish artikel ini"
                                                class="text-xs text-green-600 hover:text-green-800 font-medium transition-colors">
                                                <i class="fas fa-upload mr-0.5"></i> Publish
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('knowledge.edit', $article) }}" title="Edit artikel"
                                        class="text-xs text-blue-500 hover:text-blue-700 transition-colors ml-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @role('admin')
                                        <form method="POST" action="{{ route('knowledge.destroy', $article) }}"
                                            onsubmit="return confirm('Hapus artikel ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus artikel"
                                                class="text-xs text-red-400 hover:text-red-600 transition-colors ml-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endrole
                                </div>
                            @endhasanyrole
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">{{ $articles->links() }}</div>
    @endif

@endsection
