<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id', 'subject', 'description', 'priority',
        'status', 'admin_response', 'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function statusLabel(): string
    {
        return match($this->status) {
            'open'        => 'Open',
            'in_progress' => 'In Progress',
            'closed'      => 'Closed',
            default       => 'Open',
        };
    }

    public function statusClass(): string
    {
        return match($this->status) {
            'open'        => 'badge-pending',
            'in_progress' => 'badge-active',
            'closed'      => 'badge-complete',
            default       => 'badge-pending',
        };
    }

    public function priorityClass(): string
    {
        return match($this->priority) {
            'urgent' => 'text-red-600',
            'medium' => 'text-amber-600',
            'low'    => 'text-gray-500',
            default  => 'text-gray-500',
        };
    }
}
