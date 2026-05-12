<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'phone',
        'employee_id',
        'avatar',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function ticketsAsRequester()
    {
        return $this->hasMany(Ticket::class, 'requester_id');
    }

    public function ticketsAsAgent()
    {
        return $this->hasMany(Ticket::class, 'agent_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    // Helpers
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isAgent(): bool
    {
        return $this->hasRole('agent');
    }

    public function isUser(): bool
    {
        return $this->hasRole('user');
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3B82F6&color=fff';
    }
}
