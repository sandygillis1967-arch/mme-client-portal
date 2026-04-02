<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStage extends Model
{
    protected $fillable = [
        'user_id', 'project_name', 'current_stage', 'stage_notes', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }

    public const STAGES = [
        'discovery'   => 'Discovery',
        'design'      => 'Design',
        'development' => 'Development',
        'review'      => 'Review',
        'live'        => 'Live',
    ];

    public function stageIndex(): int
    {
        return array_search($this->current_stage, array_keys(self::STAGES));
    }

    public function stageLabel(): string
    {
        return self::STAGES[$this->current_stage] ?? 'Discovery';
    }
}
