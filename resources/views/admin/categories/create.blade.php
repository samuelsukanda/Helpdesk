{{-- resources/views/admin/categories/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('header', 'Tambah Kategori')

@section('content')
    <div class="max-w-xl mx-auto">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="bg-white rounded-xl border shadow-sm p-6 space-y-5">
                <div>
                    <label class="label">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input w-full" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" value="{{ old('icon', 'fa-tag') }}" class="input w-full"
                            placeholder="fa-desktop">
                        <p class="text-xs text-gray-400 mt-1">Contoh: fa-desktop, fa-code, fa-network-wired</p>
                    </div>
                    <div>
                        <label class="label">Warna</label>
                        <div class="flex gap-2">
                            <input type="color" name="color" value="{{ old('color', '#3B82F6') }}"
                                class="h-10 w-12 rounded border cursor-pointer">
                            <input type="text" id="colorText" value="{{ old('color', '#3B82F6') }}" class="input flex-1"
                                placeholder="#3B82F6">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="label">Sub-Kategori</label>
                    <div id="sub-list" class="space-y-2 mb-2">
                        <div class="flex gap-2">
                            <input type="text" name="subs[]" class="input flex-1" placeholder="Nama sub-kategori">
                            <button type="button" onclick="addSub()" class="btn-secondary px-3"><i
                                    class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1"><i class="fas fa-save mr-2"></i> Simpan</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary flex-1 text-center">Batal</a>
                </div>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            function addSub() {
                const div = document.createElement('div');
                div.className = 'flex gap-2';
                div.innerHTML =
                    `<input type="text" name="subs[]" class="input flex-1" placeholder="Nama sub-kategori">
        <button type="button" onclick="this.parentElement.remove()" class="btn-secondary px-3 text-red-500"><i class="fas fa-trash"></i></button>`;
                document.getElementById('sub-list').appendChild(div);
            }
            document.querySelector('input[type=color]').addEventListener('input', function() {
                document.getElementById('colorText').value = this.value;
            });
        </script>
    @endpush
@endsection
