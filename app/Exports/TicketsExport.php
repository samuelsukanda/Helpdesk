<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping};

class TicketsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private array $filters = []) {}

    public function query()
    {
        return Ticket::with(['requester', 'agent', 'category'])->latest();
    }

    public function headings(): array
    {
        return ['No. Tiket', 'Judul', 'Status', 'Prioritas', 'Kategori', 'Requester', 'Agent', 'Dibuat', 'Diselesaikan'];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->title,
            $ticket->status,
            $ticket->priority,
            $ticket->category?->name,
            $ticket->requester->name,
            $ticket->agent?->name ?? 'Unassigned',
            $ticket->created_at->format('d/m/Y H:i'),
            $ticket->resolved_at?->format('d/m/Y H:i') ?? '-',
        ];
    }
}
