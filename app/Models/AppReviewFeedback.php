<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppReviewFeedback extends Model
{
    protected $fillable = [
        'app_review_id', 'user_id', 'screen_section',
        'issue_type', 'description', 'status', 'admin_notes',
    ];

    public function review() { return $this->belongsTo(AppReview::class, 'app_review_id'); }
    public function user() { return $this->belongsTo(User::class); }

    public function issueLabel(): string
    {
        return match($this->issue_type) {
            'bug'            => 'Bug',
            'change_request' => 'Change request',
            'design_issue'   => 'Design issue',
            'works_great'    => 'Works great',
            default          => '',
        };
    }

    public function issueClass(): string
    {
        return match($this->issue_type) {
            'bug'            => 'badge-bug',
            'change_request' => 'badge-change',
            'design_issue'   => 'badge-design',
            'works_great'    => 'badge-good',
            default          => '',
        };
    }

    public function statusDotClass(): string
    {
        return match($this->status) {
            'open'        => 'dot-red',
            'in_progress' => 'dot-amber',
            'done'        => 'dot-green',
            default       => 'dot-red',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'open'        => 'Open — awaiting MME response',
            'in_progress' => 'In progress — MME reviewing',
            'done'        => 'Resolved',
            default       => 'Open',
        };
    }
}
