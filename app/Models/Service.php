<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'status'];

    public function user() { return $this->belongsTo(User::class); }

    public function statusLabel(): string
    {
        return match($this->status) {
            'active'   => 'Active',
            'pending'  => 'Pending',
            'complete' => 'Complete',
            default    => 'Unknown',
        };
    }

    public function statusClass(): string
    {
        return match($this->status) {
            'active'   => 'badge-active',
            'pending'  => 'badge-pending',
            'complete' => 'badge-complete',
            default    => 'badge-complete',
        };
    }
}
