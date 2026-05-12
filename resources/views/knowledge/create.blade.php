{{-- resources/views/knowledge/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tulis Artikel')
@section('header', 'Tulis Artikel')

@section('content')
    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('knowledge.store') }}">
            @csrf
            <div class="bg-white rounded-xl border shadow-sm p-6 space-y-5">
                <div>
                    <label class="label">Judul Artikel <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="input w-full text-lg"
                        placeholder="Judul artikel..." required>
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
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Status</label>
                        <select name="status" class="input w-full">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published
                            </option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="label">Konten <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="14" class="input w-full font-mono text-sm"
                        placeholder="Tulis isi artikel di sini..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1"><i class="fas fa-save mr-2"></i> Simpan
                        Artikel</button>
                    <a href="{{ route('knowledge.index') }}" class="btn-secondary flex-1 text-center">Batal</a>
                </div>
            </div>
        </form>
    </div>
@endsection
