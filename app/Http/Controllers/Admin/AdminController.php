<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AppReview;
use App\Models\AppReviewFeedback;
use App\Models\SupportTicket;
use App\Models\PhotoUpload;
use App\Models\PageRequest;
use App\Models\ServiceRequest;
use App\Models\ProjectStage;
use App\Models\OnboardingItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard()
    {
        return view('admin.dashboard', [
            'clientCount'     => User::where('is_admin', false)->where('is_active', true)->count(),
            'newSubmissions'  => $this->newSubmissionsCount(),
            'openTickets'     => SupportTicket::where('status', '!=', 'closed')->count(),
            'activeReviews'   => AppReview::where('status', 'in_review')->count(),
            'recentClients'   => User::where('is_admin', false)->latest()->take(5)->get(),
        ]);
    }

    private function newSubmissionsCount(): int
    {
        return PhotoUpload::where('status', 'new')->count()
             + PageRequest::where('status', 'new')->count()
             + ServiceRequest::where('status', 'new')->count();
    }

    // ── Clients ──────────────────────────────────────────────────────────────

    public function clients()
    {
        return view('admin.clients.index', [
            'clients' => User::where('is_admin', false)->orderBy('name')->get(),
        ]);
    }

    public function clientCreate()
    {
        return view('admin.clients.create');
    }

    public function clientStore(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'company_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:50',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'company_name' => $request->company_name,
            'phone'        => $request->phone,
            'password'     => $request->password,
            'is_admin'     => false,
        ]);

        return redirect()->route('admin.clients')->with('success', 'Client account created.');
    }

    public function clientEdit(User $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function clientUpdate(Request $request, User $client)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $client->id,
            'company_name' => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:50',
        ]);

        $client->update($request->only('name', 'email', 'company_name', 'phone', 'is_active'));

        // Update feature flags
        $flags = [
            'feature_onboarding', 'feature_project_status', 'feature_website_review',
            'feature_app_review', 'feature_support_tickets', 'feature_document_vault',
            'feature_seo_reports', 'feature_ai_status', 'feature_hosting_details', 'feature_invoices',
        ];
        foreach ($flags as $flag) {
            $client->$flag = $request->boolean($flag);
        }
        $client->save();

        return redirect()->route('admin.clients')->with('success', 'Client updated.');
    }

    // ── Submissions ──────────────────────────────────────────────────────────

    public function submissions()
    {
        return view('admin.submissions.index', [
            'photoUploads'   => PhotoUpload::with('user')->latest()->get(),
            'pageRequests'   => PageRequest::with('user')->latest()->get(),
            'serviceRequests'=> ServiceRequest::with('user')->latest()->get(),
        ]);
    }

    public function updateSubmissionStatus(Request $request, string $type, int $id)
    {
        $request->validate(['status' => 'required|in:new,in_progress,complete']);

        $model = match($type) {
            'photo'   => PhotoUpload::findOrFail($id),
            'page'    => PageRequest::findOrFail($id),
            'service' => ServiceRequest::findOrFail($id),
            default   => abort(404),
        };

        $model->update(['status' => $request->status]);

        return back()->with('success', 'Status updated.');
    }

    // ── Support Tickets ──────────────────────────────────────────────────────

    public function tickets()
    {
        return view('admin.submissions.tickets', [
            'tickets' => SupportTicket::with('user')->orderByRaw("FIELD(status,'open','in_progress','closed')")->latest()->get(),
        ]);
    }

    public function respondTicket(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'admin_response' => 'required|string|max:3000',
            'status'         => 'required|in:open,in_progress,closed',
        ]);

        $ticket->update([
            'admin_response' => $request->admin_response,
            'status'         => $request->status,
            'responded_at'   => now(),
        ]);

        return back()->with('success', 'Ticket updated.');
    }

    // ── App Reviews ──────────────────────────────────────────────────────────

    public function appReviews()
    {
        return view('admin.reviews.index', [
            'reviews' => AppReview::with(['user', 'feedback'])->latest()->get(),
        ]);
    }

    public function appReviewCreate()
    {
        return view('admin.reviews.create', [
            'clients' => User::where('is_admin', false)->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function appReviewStore(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'app_name'    => 'required|string|max:255',
            'version'     => 'nullable|string|max:50',
            'staging_url' => 'required|url',
        ]);

        AppReview::create($request->only('user_id', 'app_name', 'version', 'staging_url'));

        return redirect()->route('admin.app-reviews')->with('success', 'Review created and client notified.');
    }

    public function updateFeedbackStatus(Request $request, AppReviewFeedback $feedback)
    {
        $request->validate([
            'status'      => 'required|in:open,in_progress,done',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $feedback->update($request->only('status', 'admin_notes'));

        return back()->with('success', 'Feedback item updated.');
    }

    // ── Project Stages ───────────────────────────────────────────────────────

    public function projectStages()
    {
        return view('admin.clients.project-stages', [
            'stages'  => ProjectStage::with('user')->get(),
            'clients' => User::where('is_admin', false)->orderBy('name')->get(),
        ]);
    }

    public function projectStageStore(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|exists:users,id',
            'project_name' => 'required|string|max:255',
            'current_stage'=> 'required|in:discovery,design,development,review,live',
            'stage_notes'  => 'nullable|string|max:1000',
        ]);

        ProjectStage::updateOrCreate(
            ['user_id' => $request->user_id, 'is_active' => true],
            $request->only('project_name', 'current_stage', 'stage_notes')
        );

        return back()->with('success', 'Project stage updated.');
    }

    // ── Onboarding Items ─────────────────────────────────────────────────────

    public function onboardingItems()
    {
        return view('admin.clients.onboarding-items', [
            'items' => OnboardingItem::orderBy('sort_order')->get(),
        ]);
    }

    public function onboardingItemStore(Request $request)
    {
        $request->validate([
            'label'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'requires_file' => 'boolean',
            'sort_order'    => 'integer',
        ]);

        OnboardingItem::create($request->all());

        return back()->with('success', 'Onboarding item added.');
    }

    public function onboardingItemDestroy(OnboardingItem $item)
    {
        $item->delete();
        return back()->with('success', 'Item removed.');
    }
}
