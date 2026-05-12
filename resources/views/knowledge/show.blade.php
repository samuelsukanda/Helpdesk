{{-- resources/views/knowledge/show.blade.php --}}
@extends('layouts.app')
@section('title', $knowledge->title)
@section('header', 'Knowledge Base')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl border shadow-sm p-8">
                <div class="mb-6">
                    <div class="flex items-center gap-2 text-sm text-gray-400 mb-3">
                        <a href="{{ route('knowledge.index') }}" class="hover:text-blue-600">Knowledge Base</a>
                        <i class="fas fa-chevron-right text-xs"></i>
                        <span>{{ $knowledge->category?->name ?? 'Umum' }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-3">{{ $knowledge->title }}</h1>
                    <div class="flex items-center gap-4 text-sm text-gray-400">
                        <span><i class="fas fa-user mr-1"></i> {{ $knowledge->author->name }}</span>
                        <span><i class="fas fa-calendar mr-1"></i> {{ $knowledge->updated_at->format('d M Y') }}</span>
                        <span><i class="fas fa-eye mr-1"></i> {{ number_format($knowledge->views) }} views</span>
                    </div>
                </div>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($knowledge->content)) !!}
                </div>

                @hasanyrole('admin|agent')
                    <div class="mt-8 pt-5 border-t flex gap-3">
                        <a href="{{ route('knowledge.edit', $knowledge) }}" class="btn-secondary text-sm">
                            <i class="fas fa-edit mr-1"></i> Edit Artikel
                        </a>
                        <form method="POST" action="{{ route('knowledge.destroy', $knowledge) }}"
                            onsubmit="return confirm('Hapus artikel ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger text-sm">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                @endhasanyrole
            </div>
        </div>

        <div>
            <div class="bg-white rounded-xl border shadow-sm p-5 sticky top-4">
                <h3 class="font-semibold text-gray-700 mb-4">Artikel Terkait</h3>
                @forelse($related as $rel)
                    <a href="{{ route('knowledge.show', $rel) }}"
                        class="block py-2.5 border-b last:border-0 hover:text-blue-600 text-sm text-gray-600 transition">
                        {{ $rel->title }}
                    </a>
                @empty
                    <p class="text-sm text-gray-400">Tidak ada artikel terkait</p>
                @endforelse

                <div class="mt-5 pt-4 border-t">
                    <p class="text-sm text-gray-500 mb-3">Tidak menemukan solusi?</p>
                    <a href="{{ route('tickets.create') }}" class="btn-primary w-full text-center text-sm">
                        <i class="fas fa-ticket-alt mr-1"></i> Buat Tiket
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
