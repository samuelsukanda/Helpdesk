{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Notifikasi')
@section('header', 'Notifikasi')

@section('content')
    <div class="bg-white rounded-xl border shadow-sm">
        <div class="p-5 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-700">Semua Notifikasi</h3>
            <form method="POST" action="{{ route('notifications.read.all') }}">
                @csrf
                <button type="submit" class="btn-secondary text-sm">
                    <i class="fas fa-check-double mr-1"></i> Tandai Semua Dibaca
                </button>
            </form>
        </div>

        <div class="divide-y">
            @forelse($notifications as $notif)
                @php
                    $isRead = $notif->read_at !== null;
                    $data = $notif->data;
                @endphp
                <div class="flex items-start gap-4 p-5 {{ !$isRead ? 'bg-blue-50' : 'hover:bg-gray-50' }} transition">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ !$isRead ? 'bg-blue-500' : 'bg-gray-200' }}">
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
                            onsubmit="return confirm('Hapus notifikasi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-300 hover:text-red-500 text-xs" title="Hapus">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-16 text-center text-gray-400">
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
