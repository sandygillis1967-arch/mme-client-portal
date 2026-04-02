<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppReview extends Model
{
    protected $fillable = [
        'user_id', 'app_name', 'version', 'staging_url',
        'status', 'approval_notes', 'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function feedback() { return $this->hasMany(AppReviewFeedback::class)->latest(); }

    public function isApproved(): bool { return $this->status === 'approved'; }

    public function statusLabel(): string
    {
        return match($this->status) {
            'in_review' => 'In review',
            'approved'  => 'Approved',
            default     => 'In review',
        };
    }

    public function displayName(): string
    {
        return $this->version
            ? $this->app_name . ' — ' . $this->version
            : $this->app_name;
    }
}
