@extends('layouts.app')
@section('header', 'Buat Tiket Baru')

@section('content')
    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="bg-white rounded-xl shadow-sm border p-6 space-y-5">

                {{-- Error duplikat tiket --}}
                @if ($errors->has('duplicate'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-copy text-red-500 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-red-700 mb-1">Tiket Duplikat Terdeteksi</p>
                                <p class="text-sm text-red-600">{{ $errors->first('duplicate') }}</p>
                                <p class="text-xs text-red-500 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Jika ini masalah berbeda, ubah judul atau deskripsi tiket Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Error konflik nomor tiket --}}
                @if ($errors->has('ticket'))
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-exclamation-triangle text-yellow-500 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-yellow-700 mb-1">Gagal Membuat Tiket</p>
                                <p class="text-sm text-yellow-600">{{ $errors->first('ticket') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="label">Judul Masalah <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="input w-full"
                        placeholder="Contoh: Tidak bisa login ke sistem" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="label">Deskripsi Detail <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" class="input w-full" placeholder="Jelaskan masalah secara detail..."
                        required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" class="input w-full" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Sub-Kategori</label>
                        <select name="sub_category_id" id="sub_category_id" class="input w-full">
                            <option value="">-- Pilih Sub-Kategori --</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="label">Prioritas <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach (['low' => ['text' => 'Low', 'color' => 'green'], 'medium' => ['text' => 'Medium', 'color' => 'blue'], 'high' => ['text' => 'High', 'color' => 'orange'], 'critical' => ['text' => 'Critical', 'color' => 'red']] as $val => $opt)
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="{{ $val }}" class="sr-only peer"
                                    {{ old('priority') == $val ? 'checked' : '' }}>
                                <div
                                    class="border-2 rounded-lg p-3 text-center text-sm font-medium peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition">
                                    {{ $opt['text'] }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="label">Lampiran <span class="text-gray-400 font-normal">(opsional)</span></label>

                    <div id="drop-zone"
                        class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center cursor-pointer transition hover:border-blue-300 hover:bg-blue-50"
                        onclick="document.getElementById('file-input').click()"
                        ondragover="event.preventDefault(); this.classList.add('border-blue-400','bg-blue-50')"
                        ondragleave="this.classList.remove('border-blue-400','bg-blue-50')" ondrop="handleDrop(event)">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-3 block"></i>
                        <p class="text-sm font-medium text-gray-700 mb-1">Drag and drop file</p>
                        <p class="text-xs text-gray-400 mb-3">atau klik untuk memilih file</p>
                        <span
                            class="inline-block px-4 py-1.5 border border-gray-200 rounded-lg text-xs text-gray-500 bg-white">
                            <i class="fas fa-folder-open mr-1"></i> Pilih File
                        </span>
                        <p class="text-xs text-gray-300 mt-3">JPG, PNG, PDF, DOC, XLSX, ZIP · Maks 10MB per file</p>
                    </div>

                    <input type="file" id="file-input" name="attachments[]" multiple class="hidden"
                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xlsx,.zip" onchange="addFiles(this.files)">

                    <div id="file-list" class="mt-3 space-y-2"></div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Tiket
                    </button>
                    <a href="{{ route('tickets.index') }}" class="btn-secondary flex-1 text-center">Batal</a>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const categories = @json($categories->keyBy('id'));

            document.getElementById('category_id').addEventListener('change', function() {
                const catId = this.value;
                const subSelect = document.getElementById('sub_category_id');
                subSelect.innerHTML = '<option value="">-- Pilih Sub-Kategori --</option>';

                if (catId && categories[catId]) {
                    categories[catId].sub_categories.forEach(sub => {
                        subSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                    });
                }
            });

            const fileIcons = {
                pdf: 'fa-file-pdf text-red-500',
                doc: 'fa-file-word text-blue-500',
                docx: 'fa-file-word text-blue-500',
                xls: 'fa-file-excel text-green-600',
                xlsx: 'fa-file-excel text-green-600',
                jpg: 'fa-file-image text-yellow-500',
                jpeg: 'fa-file-image text-yellow-500',
                png: 'fa-file-image text-yellow-500',
                zip: 'fa-file-archive text-purple-500',
            };

            function formatSize(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(1) + ' MB';
            }

            function addFiles(files) {
                const list = document.getElementById('file-list');
                [...files].forEach(file => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const icon = fileIcons[ext] || 'fa-file text-gray-400';
                    const id = 'file-' + Date.now() + Math.random().toString(36).slice(2);

                    const item = document.createElement('div');
                    item.id = id;
                    item.className = 'flex items-center gap-3 px-3 py-2 border border-gray-100 rounded-lg bg-white';
                    item.innerHTML = `
            <i class="fas ${icon} text-xl flex-shrink-0"></i>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-700 truncate">${file.name}</p>
                <p class="text-xs text-gray-400">${formatSize(file.size)}</p>
            </div>
            <button type="button" onclick="document.getElementById('${id}').remove()"
                class="text-gray-300 hover:text-red-400 transition p-1">
                <i class="fas fa-times"></i>
            </button>
        `;
                    list.appendChild(item);
                });
            }

            function handleDrop(e) {
                e.preventDefault();
                e.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
                addFiles(e.dataTransfer.files);
            }
        </script>
    @endpush
@endsection
