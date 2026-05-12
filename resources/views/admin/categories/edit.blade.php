{{-- resources/views/admin/categories/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('header', 'Edit Kategori')

@section('content')
    <div class="max-w-2xl mx-auto space-y-5">

        {{-- Edit Kategori Utama --}}
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf
            @method('PATCH')
            <div class="bg-white rounded-xl border shadow-sm p-6 space-y-5">
                <h3 class="font-semibold text-gray-700 border-b pb-3">Informasi Kategori</h3>

                <div>
                    <label class="label">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="input w-full"
                        required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Icon (FontAwesome class)</label>
                        <div class="flex gap-2 items-center">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                id="iconPreview" style="background-color: {{ $category->color }}22">
                                <i id="iconEl" class="fas {{ $category->icon }}"
                                    style="color: {{ $category->color }}"></i>
                            </div>
                            <input type="text" name="icon" id="iconInput" value="{{ old('icon', $category->icon) }}"
                                class="input flex-1" placeholder="fa-tag">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Contoh: fa-desktop, fa-code, fa-network-wired</p>
                    </div>
                    <div>
                        <label class="label">Warna</label>
                        <div class="flex gap-2">
                            <input type="color" name="color" id="colorPicker"
                                value="{{ old('color', $category->color) }}"
                                class="h-10 w-12 rounded border cursor-pointer p-0.5">
                            <input type="text" id="colorText" value="{{ old('color', $category->color) }}"
                                class="input flex-1" placeholder="#3B82F6"
                                onchange="document.getElementById('colorPicker').value = this.value">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded"
                        {{ $category->is_active ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm text-gray-700">Kategori Aktif</label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary flex-1 text-center">
                        Batal
                    </a>
                </div>
            </div>
        </form>

        {{-- Manajemen Sub-Kategori --}}
        <div class="bg-white rounded-xl border shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 border-b pb-3 mb-4">
                Sub-Kategori
                <span class="text-sm text-gray-400 font-normal ml-2">({{ $category->subCategories->count() }}
                    sub-kategori)</span>
            </h3>

            {{-- Existing sub-categories --}}
            <div id="subList" class="space-y-2 mb-4">
                @forelse($category->subCategories as $sub)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg" id="sub-{{ $sub->id }}">
                        <i class="fas fa-grip-vertical text-gray-300"></i>
                        <span class="flex-1 text-sm text-gray-700">{{ $sub->name }}</span>
                        <span
                            class="text-xs px-2 py-0.5 rounded-full {{ $sub->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                            {{ $sub->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <button type="button" onclick="deleteSub({{ $sub->id }})"
                            class="text-gray-300 hover:text-red-500 transition text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 py-3 text-center" id="emptyMsg">Belum ada sub-kategori</p>
                @endforelse
            </div>

            {{-- Add new sub --}}
            <div class="flex gap-2">
                <input type="text" id="newSubName" class="input flex-1" placeholder="Nama sub-kategori baru..."
                    maxlength="255">
                <button type="button" onclick="addSub()" class="btn-primary">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </button>
            </div>
            <p id="subMsg" class="text-xs mt-2 hidden"></p>
        </div>
    </div>

    @push('scripts')
        <script>
            // Icon & color preview
            document.getElementById('iconInput').addEventListener('input', function() {
                document.getElementById('iconEl').className = 'fas ' + this.value;
            });

            document.getElementById('colorPicker').addEventListener('input', function() {
                const color = this.value;

                document.getElementById('colorText').value = color;
                document.getElementById('iconEl').style.color = color;
                document.getElementById('iconPreview').style.backgroundColor = color + '22';
            });

            // Add Sub Category
            async function addSub() {
                const input = document.getElementById('newSubName');
                const name = input.value.trim();

                if (!name) {
                    showMsg('Nama sub-kategori tidak boleh kosong.', 'red');
                    return;
                }

                try {
                    const resp = await fetch('{{ route('admin.categories.sub.store', $category) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            name
                        })
                    });

                    const data = await resp.json();

                    if (!resp.ok) {
                        showMsg(data.message ?? 'Gagal menambahkan sub-kategori.', 'red');
                        return;
                    }

                    const emptyMsg = document.getElementById('emptyMsg');
                    if (emptyMsg) emptyMsg.remove();

                    const div = document.createElement('div');

                    div.id = 'sub-' + data.id;
                    div.className =
                        'flex items-center gap-3 p-3 bg-gray-50 rounded-lg';

                    div.innerHTML = `
                <i class="fas fa-grip-vertical text-gray-300"></i>

                <span class="flex-1 text-sm text-gray-700">
                    ${data.name}
                </span>

                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                    Aktif
                </span>

                <button
                    type="button"
                    onclick="deleteSub(${data.id}, '${data.name}')"
                    class="text-gray-300 hover:text-red-500 transition text-sm">

                    <i class="fas fa-trash"></i>
                </button>
            `;

                    document.getElementById('subList').appendChild(div);

                    input.value = '';

                    showMsg('Sub-kategori berhasil ditambahkan.', 'green');

                } catch (e) {
                    showMsg('Gagal menambahkan sub-kategori.', 'red');
                }
            }

            // Delete Sub Category + SweetAlert
            async function deleteSub(id, name) {

                const result = await Swal.fire({
                    title: "Hapus Sub-Kategori?",
                    html: `
                <div class="text-center">

                    <p class="text-gray-600 mb-2">
                        Anda akan menghapus sub-kategori:
                    </p>

                    <p class="font-bold text-gray-800 text-lg mb-1">
                        ${name}
                    </p>

                    <p class="text-red-500 text-xs mt-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Tindakan ini tidak dapat dibatalkan!
                    </p>

                </div>
            `,
                    icon: "warning",
                    iconColor: "#f59e0b",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Hapus",
                    cancelButtonText: "Batal",
                    confirmButtonColor: "#dc2626",
                    cancelButtonColor: "#6b7280",
                    reverseButtons: true,
                    focusCancel: true,
                    showCloseButton: true,
                    closeButtonHtml: '<i class="fas fa-times"></i>',

                    customClass: {
                        confirmButton: "swal2-confirm",
                        cancelButton: "swal2-cancel",
                        popup: "rounded-xl",
                    },

                    showClass: {
                        popup: "animate__animated animate__fadeInUp animate__faster",
                    },

                    hideClass: {
                        popup: "animate__animated animate__fadeOutDown animate__faster",
                    }
                });

                if (!result.isConfirmed) return;

                try {

                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const resp = await fetch(
                        '{{ url('admin/sub-categories') }}/' + id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                        });

                    const data = await resp.json();

                    if (data.success) {

                        document.getElementById('sub-' + id).remove();

                        const list = document.getElementById('subList');

                        if (list.querySelectorAll('[id^="sub-"]').length === 0) {
                            list.innerHTML = `
                        <p class="text-sm text-gray-400 py-3 text-center" id="emptyMsg">
                            Belum ada sub-kategori
                        </p>
                    `;
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Sub-kategori berhasil dihapus.',
                            timer: 1800,
                            showConfirmButton: false,
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message ??
                                'Gagal menghapus sub-kategori.',
                        });
                    }

                } catch (e) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menghapus.',
                    });
                }
            }

            function showMsg(text, color) {
                const msg = document.getElementById('subMsg');

                msg.textContent = text;
                msg.className = `text-xs mt-2 text-${color}-600`;

                msg.classList.remove('hidden');

                setTimeout(() => {
                    msg.classList.add('hidden');
                }, 3000);
            }

            // Enter key
            document.getElementById('newSubName').addEventListener('keypress', function(e) {

                if (e.key === 'Enter') {
                    e.preventDefault();
                    addSub();
                }
            });
        </script>
    @endpush
@endsection
