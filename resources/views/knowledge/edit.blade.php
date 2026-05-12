{{-- resources/views/knowledge/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Artikel')
@section('header', 'Edit Artikel')

@section('content')
    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('knowledge.update', $knowledge) }}">
            @csrf
            @method('PATCH')
            <div class="bg-white rounded-xl border shadow-sm p-6 space-y-5">

                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg text-sm text-gray-500">
                    <i class="fas fa-eye text-blue-500"></i>
                    <span>{{ number_format($knowledge->views) }} views</span>
                    <span class="mx-2">·</span>
                    <i class="fas fa-user"></i>
                    <span>{{ $knowledge->author->name }}</span>
                    <span class="mx-2">·</span>
                    <span>Terakhir diubah {{ $knowledge->updated_at->format('d M Y H:i') }}</span>
                </div>

                <div>
                    <label class="label">Judul Artikel <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $knowledge->title) }}"
                        class="input w-full text-lg" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Kategori</label>
                        <select name="category_id" class="input w-full">
                            <option value="">— Tanpa Kategori —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $knowledge->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Status</label>
                        <select name="status" class="input w-full">
                            <option value="draft" {{ old('status', $knowledge->status) == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>
                            <option value="published"
                                {{ old('status', $knowledge->status) == 'published' ? 'selected' : '' }}>
                                Published
                            </option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="label">Konten <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="16" class="input w-full font-mono text-sm" required>{{ old('content', $knowledge->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('knowledge.show', $knowledge) }}" class="btn-secondary flex-1 text-center">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
