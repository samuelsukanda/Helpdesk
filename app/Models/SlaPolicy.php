<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaPolicy extends Model
{
    protected $fillable = [
        'name',
        'priority',
        'response_time_hours',
        'resolution_time_hours',
        'is_active',
    ];

    protected $casts = [
        'is_active'              => 'boolean',
        'response_time_hours'    => 'integer',
        'resolution_time_hours'  => 'integer',
    ];

    // Relationships
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForPriority($query, string $priority)
    {
        return $query->where('priority', $priority)->where('is_active', true);
    }

    // Helpers
    public function getResponseTimeLabelAttribute(): string
    {
        return $this->response_time_hours >= 24
            ? ($this->response_time_hours / 24) . ' hari'
            : $this->response_time_hours . ' jam';
    }

    public function getResolutionTimeLabelAttribute(): string
    {
        return $this->resolution_time_hours >= 24
            ? ($this->resolution_time_hours / 24) . ' hari'
            : $this->resolution_time_hours . ' jam';
    }
}
