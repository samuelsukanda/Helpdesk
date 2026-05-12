<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketNumberService
{
    public static function generate(): string
    {
        $year = now()->year;

        return DB::transaction(function () use ($year) {

            // Lock semua baris tahun ini agar proses lain menunggu giliran
            Ticket::withTrashed()
                ->whereYear('created_at', $year)
                ->lockForUpdate()
                ->get(['id']);

            // Ambil semua nomor urut yang sudah terpakai (aktif + deleted)
            $usedNumbers = Ticket::withTrashed()
                ->whereYear('created_at', $year)
                ->pluck('ticket_number')
                ->map(function ($number) {
                    $parts = explode('-', $number);
                    return (int) end($parts);
                })
                ->sort()
                ->values()
                ->toArray();

            // Cari nomor terkecil yang belum dipakai (isi kekosongan)
            $next = 1;
            foreach ($usedNumbers as $used) {
                if ($used === $next) {
                    $next++;
                } elseif ($used > $next) {
                    break; // ada kekosongan, pakai $next
                }
            }

            return 'TKT-' . $year . '-' . str_pad($next, 3, '0', STR_PAD_LEFT);
        });
    }
}
