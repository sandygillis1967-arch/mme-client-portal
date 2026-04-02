<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageRequest extends Model
{
    protected $fillable = ['user_id', 'page_title', 'description', 'notes', 'status'];

    public function user() { return $this->belongsTo(User::class); }

    public function statusLabel(): string
    {
        return match($this->status) {
            'new'         => 'New',
            'in_progress' => 'In Progress',
            'complete'    => 'Complete',
            default       => 'New',
        };
    }
}
