{{-- resources/views/reports/pdf.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Tiket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .meta {
            color: #777;
            font-size: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #1e40af;
            color: white;
            text-align: left;
            padding: 7px 10px;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            padding: 6px 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        .badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }

        .open {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .in_progress {
            background: #fef3c7;
            color: #92400e;
        }

        .resolved {
            background: #d1fae5;
            color: #065f46;
        }

        .closed {
            background: #374151;
            color: white;
        }

        .low {
            background: #d1fae5;
            color: #065f46;
        }

        .medium {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .high {
            background: #ffedd5;
            color: #9a3412;
        }

        .critical {
            background: #fee2e2;
            color: #991b1b;
        }

        .sla-breach {
            color: #dc2626;
            font-weight: bold;
        }

        footer {
            margin-top: 30px;
            color: #9ca3af;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Laporan Tiket IT Helpdesk</h1>
    <p class="meta">Dicetak pada: {{ now()->format('d M Y H:i') }} &nbsp;|&nbsp; Total: {{ $tickets->count() }}
        tiket</p>

    <table>
        <thead>
            <tr>
                <th>No. Tiket</th>
                <th>Judul</th>
                <th>Status</th>
                <th>Prioritas</th>
                <th>Kategori</th>
                <th>Requester</th>
                <th>Agent</th>
                <th>SLA</th>
                <th>Dibuat</th>
                <th>Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td style="font-family: monospace; font-size: 10px;">{{ $ticket->ticket_number }}</td>
                    <td>{{ Str::limit($ticket->title, 40) }}</td>
                    <td><span
                            class="badge {{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                    </td>
                    <td><span class="badge {{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span></td>
                    <td>{{ $ticket->category?->name ?? '—' }}</td>
                    <td>{{ $ticket->requester->name }}</td>
                    <td>{{ $ticket->agent?->name ?? '—' }}</td>
                    <td class="{{ $ticket->sla_breached ? 'sla-breach' : '' }}">
                        {{ $ticket->sla_breached ? 'Breach' : 'OK' }}</td>
                    <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                    <td>{{ $ticket->resolved_at?->format('d/m/Y') ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>IT Helpdesk Ticketing System &mdash; {{ config('app.name') }}</footer>
</body>

</html>
