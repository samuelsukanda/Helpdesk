<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Notifications\SlaBreachedNotification;
use App\Models\User;
use Illuminate\Console\Command;

class CheckSlaBreaches extends Command
{
    protected $signature   = 'sla:check';
    protected $description = 'Check SLA breaches and escalate overdue tickets';

    public function handle(): void
    {
        $overdueTickets = Ticket::whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->whereNotIn('status', ['resolved', 'closed'])
            ->where('sla_breached', false)
            ->get();

        foreach ($overdueTickets as $ticket) {
            $ticket->update([
                'sla_breached' => true,
                'is_escalated' => true,
            ]);

            // Notif admin
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new SlaBreachedNotification($ticket));
            }

            $this->info('SLA breached: ' . $ticket->ticket_number);
        }

        $this->info('SLA check selesai. ' . $overdueTickets->count() . ' tiket di-eskalasi.');
    }
}