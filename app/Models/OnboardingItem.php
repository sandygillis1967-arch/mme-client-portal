<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingItem extends Model
{
    protected $fillable = ['label', 'description', 'requires_file', 'sort_order', 'is_active'];

    protected $casts = [
        'requires_file' => 'boolean',
        'is_active'     => 'boolean',
    ];

    public function completions() { return $this->hasMany(OnboardingCompletion::class); }
}
