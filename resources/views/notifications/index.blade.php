{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Notifikasi')
@section('header', 'Notifikasi')

@section('content')
    <div class="bg-white rounded-xl border shadow-sm">
        <div class="p-5 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-700">
                Semua Notifikasi
                @if ($notifications->total() > 0)
                    <span class="text-xs text-gray-400 font-normal ml-1">({{ $notifications->total() }})</span>
                @endif
            </h3>
            <div class="flex items-center gap-2">
                {{-- Tandai Semua Dibaca --}}
                <form method="POST" action="{{ route('notifications.read.all') }}">
                    @csrf
                    <button type="submit" class="btn-secondary text-sm">
                        <i class="fas fa-check-double mr-1"></i> Tandai Semua Dibaca
                    </button>
                </form>

                {{-- Hapus Semua --}}
                @if ($notifications->total() > 0)
                    <button type="button" id="deleteAllBtn"
                        class="btn-secondary text-sm text-red-500 border-red-200 hover:bg-red-50">
                        <i class="fas fa-trash mr-1"></i> Hapus Semua
                    </button>
                @endif
            </div>
        </div>

        <div class="divide-y" id="notificationList">
            @forelse($notifications as $notif)
                @php
                    $isRead = $notif->read_at !== null;
                    $data = $notif->data;
                @endphp
                <div class="flex items-start gap-4 p-5 {{ !$isRead ? 'bg-blue-50' : 'hover:bg-gray-50' }} transition"
                    id="notif-row-{{ $notif->id }}">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                        {{ !$isRead ? 'bg-blue-500' : 'bg-gray-200' }}">
                        <i class="fas fa-bell text-sm {{ !$isRead ? 'text-white' : 'text-gray-400' }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800">
                            {{ $data['message'] ?? 'Notifikasi baru' }}
                        </p>
                        @if (isset($data['ticket_number']))
                            <p class="text-xs text-gray-500 mt-0.5">
                                Tiket: <span class="font-mono text-blue-600">{{ $data['ticket_number'] }}</span>
                                @if (isset($data['title']))
                                    — {{ Str::limit($data['title'], 50) }}
                                @endif
                            </p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if (isset($data['ticket_id']))
                            <a href="{{ route('tickets.show', $data['ticket_id']) }}"
                                class="text-blue-600 hover:underline text-xs">
                                Buka Tiket
                            </a>
                        @endif
                        @if (!$isRead)
                            <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-green-500 text-xs"
                                    title="Tandai dibaca">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}"
                            class="delete-notification-form" data-id="{{ $notif->id }}"
                            data-message="{{ $data['message'] ?? 'Notifikasi baru' }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-300 hover:text-red-500 text-xs" title="Hapus">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-16 text-center text-gray-400" id="emptyState">
                    <i class="fas fa-bell-slash text-5xl mb-3 block"></i>
                    <p class="font-medium">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>

        @if ($notifications->hasPages())
            <div class="p-4 border-t">{{ $notifications->links() }}</div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // Hapus satu notifikasi
            $('.delete-notification-form').on('submit', function(e) {
                e.preventDefault();

                const form = this;
                const message = $(form).data('message');

                Swal.fire({
                    title: 'Hapus Notifikasi?',
                    html: `
                        <div class="text-center">
                            <p class="text-gray-600 mb-3">Notifikasi berikut akan dihapus:</p>
                            <div class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700 break-words">${message}</div>
                            <p class="text-red-500 text-xs mt-4">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Tindakan ini tidak dapat dibatalkan
                            </p>
                        </div>`,
                    icon: 'warning',
                    iconColor: '#f59e0b',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                    focusCancel: true,
                    showCloseButton: true,
                    customClass: {
                        popup: 'rounded-xl'
                    },
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });

            // Hapus semua notifikasi
            $('#deleteAllBtn').on('click', function() {
                Swal.fire({
                    title: 'Hapus Semua Notifikasi?',
                    html: `
                        <div class="text-center">
                            <p class="text-gray-600 mb-3">Semua notifikasi akan dihapus sekaligus.</p>
                            <p class="text-red-500 text-xs">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Tindakan ini tidak dapat dibatalkan
                            </p>
                        </div>`,
                    icon: 'warning',
                    iconColor: '#ef4444',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus Semua',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                    focusCancel: true,
                    showCloseButton: true,
                    customClass: {
                        popup: 'rounded-xl'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#deleteAllForm').submit();
                    }
                });
            });
        });
    </script>

    <form id="deleteAllForm" method="POST" action="{{ route('notifications.destroy.all') }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endpush
