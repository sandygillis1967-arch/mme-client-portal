<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AppReview;
use App\Models\AppReviewFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppReviewController extends Controller
{
    public function index()
    {
        $reviews = auth()->user()->appReviews()
            ->with('feedback')
            ->orderByRaw("FIELD(status, 'in_review', 'approved')")
            ->latest()
            ->get();

        return view('client.tabs.app-review', compact('reviews'));
    }

    public function storeFeedback(Request $request, AppReview $review)
    {
        abort_unless($review->user_id === auth()->id(), 403);
        abort_if($review->isApproved(), 403, 'This review has been approved.');

        $request->validate([
            'screen_section' => 'required|string|max:255',
            'issue_type'     => 'required|in:bug,change_request,design_issue,works_great',
            'description'    => 'required|string|max:3000',
        ]);

        $feedback = $review->feedback()->create([
            'user_id'        => auth()->id(),
            'screen_section' => $request->screen_section,
            'issue_type'     => $request->issue_type,
            'description'    => $request->description,
        ]);

        $user = auth()->user();
        $to   = config('mail.notification_email', env('NOTIFICATION_EMAIL', 'creative@mmedigital.ca'));
        $body = "New app review feedback from {$user->name} ({$user->company_name})\n\n"
              . "App: {$review->displayName()}\n"
              . "Screen/Section: {$feedback->screen_section}\n"
              . "Issue type: {$feedback->issueLabel()}\n"
              . "Description: {$feedback->description}";

        try {
            Mail::raw($body, fn($m) => $m->to($to)->subject("[MME Portal] App Feedback — {$review->app_name} — {$user->company_name}"));
        } catch (\Exception $e) {
            \Log::error('Mail failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Feedback submitted.');
    }

    public function approve(Request $request, AppReview $review)
    {
        abort_unless($review->user_id === auth()->id(), 403);
        abort_if($review->isApproved(), 403, 'Already approved.');

        $request->validate(['approval_notes' => 'nullable|string|max:2000']);

        $review->update([
            'status'         => 'approved',
            'approval_notes' => $request->approval_notes,
            'approved_at'    => now(),
        ]);

        $user = auth()->user();
        $to   = config('mail.notification_email', env('NOTIFICATION_EMAIL', 'creative@mmedigital.ca'));
        $body = "{$user->name} ({$user->company_name}) approved {$review->displayName()}\n\nNotes: " . ($request->approval_notes ?? '—');

        try {
            Mail::raw($body, fn($m) => $m->to($to)->subject("[MME Portal] App Approved — {$review->app_name} — {$user->company_name}"));
        } catch (\Exception $e) {
            \Log::error('Mail failed: ' . $e->getMessage());
        }

        return back()->with('success', $review->app_name . ' approved.');
    }
}
