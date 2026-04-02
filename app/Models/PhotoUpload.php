<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoUpload extends Model
{
    protected $fillable = [
        'user_id', 'file_path', 'original_name',
        'placement_description', 'text_to_add', 'status',
    ];

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
