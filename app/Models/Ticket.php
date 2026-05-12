<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Services\TicketNumberService;

class Ticket extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'status',
        'priority',
        'category_id',
        'sub_category_id',
        'requester_id',
        'agent_id',
        'department_id',
        'sla_policy_id',
        'first_response_at',
        'resolved_at',
        'closed_at',
        'due_at',
        'sla_breached',
        'is_escalated',
    ];

    protected $casts = [
        'first_response_at' => 'datetime',
        'resolved_at'       => 'datetime',
        'closed_at'         => 'datetime',
        'due_at'            => 'datetime',
        'sla_breached'      => 'boolean',
        'is_escalated'      => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    // Relationships
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function slaPolicy()
    {
        return $this->belongsTo(SlaPolicy::class);
    }
    public function comments()
    {
        return $this->hasMany(TicketComment::class)->latest();
    }
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    // Scopes
    public function scopeOpen($q)
    {
        return $q->where('status', 'open');
    }
    public function scopeInProgress($q)
    {
        return $q->where('status', 'in_progress');
    }
    public function scopeResolved($q)
    {
        return $q->where('status', 'resolved');
    }
    public function scopeByPriority($q, $priority)
    {
        return $q->where('priority', $priority);
    }

    // Helpers
    public function isOverdue(): bool
    {
        return $this->due_at && now()->gt($this->due_at)
            && !in_array($this->status, ['resolved', 'closed']);
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'open'        => 'badge-blue',
            'in_progress' => 'badge-yellow',
            'on_hold'     => 'badge-gray',
            'resolved'    => 'badge-green',
            'closed'      => 'badge-dark',
            default       => 'badge-gray',
        };
    }

    public function priorityBadgeClass(): string
    {
        return match ($this->priority) {
            'low'      => 'badge-green',
            'medium'   => 'badge-blue',
            'high'     => 'badge-orange',
            'critical' => 'badge-red',
            default    => 'badge-gray',
        };
    }

    // Auto generate ticket number
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Ticket $ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = static::generateUniqueNumber();
            }
        });
    }

    private static function generateUniqueNumber(int $maxAttempts = 5): string
    {
        $attempts = 0;

        do {
            $number = TicketNumberService::generate();
            $exists = static::withTrashed()->where('ticket_number', $number)->exists();
            $attempts++;

            if (!$exists) {
                return $number;
            }

            if ($attempts < $maxAttempts) {
                usleep(50000); // tunggu 50ms lalu coba lagi
            }
        } while ($attempts < $maxAttempts);

        // Fallback terakhir — timestamp based, dijamin unik
        return 'TKT-' . now()->year . '-' . now()->format('His') . rand(10, 99);
    }
}
