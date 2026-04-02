<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingCompletion extends Model
{
    protected $fillable = [
        'user_id', 'onboarding_item_id', 'file_path',
        'original_name', 'notes', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function item() { return $this->belongsTo(OnboardingItem::class, 'onboarding_item_id'); }
}
