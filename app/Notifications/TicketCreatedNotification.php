<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketCreatedNotification extends Notification
{
    public function __construct(public Ticket $ticket) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[' . $this->ticket->ticket_number . '] Tiket Baru: ' . $this->ticket->title)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Tiket baru telah dibuat dan membutuhkan perhatian Anda.')
            ->line('**Nomor Tiket:** ' . $this->ticket->ticket_number)
            ->line('**Judul:** ' . $this->ticket->title)
            ->line('**Prioritas:** ' . strtoupper($this->ticket->priority))
            ->line('**Dari:** ' . $this->ticket->requester->name)
            ->action('Lihat Tiket', route('tickets.show', $this->ticket))
            ->line('Mohon segera ditangani. Terima kasih.');
    }

    public function toArray($notifiable): array
    {
        return [
            'ticket_id'     => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'title'         => $this->ticket->title,
            'type'          => 'ticket_created',
            'message'       => 'Tiket baru: ' . $this->ticket->title,
        ];
    }
}
