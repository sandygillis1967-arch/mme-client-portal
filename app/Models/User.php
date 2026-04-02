<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'company_name', 'phone', 'password', 'is_admin', 'is_active',
        'feature_onboarding', 'feature_project_status', 'feature_website_review',
        'feature_app_review', 'feature_support_tickets', 'feature_document_vault',
        'feature_seo_reports', 'feature_ai_status', 'feature_hosting_details', 'feature_invoices',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'feature_onboarding' => 'boolean',
        'feature_project_status' => 'boolean',
        'feature_website_review' => 'boolean',
        'feature_app_review' => 'boolean',
        'feature_support_tickets' => 'boolean',
        'feature_document_vault' => 'boolean',
        'feature_seo_reports' => 'boolean',
        'feature_ai_status' => 'boolean',
        'feature_hosting_details' => 'boolean',
        'feature_invoices' => 'boolean',
    ];

    public function services() { return $this->hasMany(Service::class); }
    public function photoUploads() { return $this->hasMany(PhotoUpload::class); }
    public function pageRequests() { return $this->hasMany(PageRequest::class); }
    public function serviceRequests() { return $this->hasMany(ServiceRequest::class); }
    public function supportTickets() { return $this->hasMany(SupportTicket::class); }
    public function onboardingCompletions() { return $this->hasMany(OnboardingCompletion::class); }
    public function projectStages() { return $this->hasMany(ProjectStage::class); }
    public function appReviews() { return $this->hasMany(AppReview::class); }

    public function onboardingPercentage(): int
    {
        $total = OnboardingItem::where('is_active', true)->count();
        if ($total === 0) return 100;
        $done = $this->onboardingCompletions()->count();
        return (int) round(($done / $total) * 100);
    }

    public function openTicketsCount(): int
    {
        return $this->supportTickets()->whereIn('status', ['open', 'in_progress'])->count();
    }

    public function activeServicesCount(): int
    {
        return $this->services()->where('status', 'active')->count();
    }
}
