<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\OnboardingItem;
use App\Models\OnboardingCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OnboardingController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $items = OnboardingItem::where('is_active', true)->orderBy('sort_order')->get();
        $done  = $user->onboardingCompletions()->pluck('onboarding_item_id')->toArray();

        return view('client.tabs.onboarding', [
            'items'      => $items,
            'done'       => $done,
            'percentage' => $user->onboardingPercentage(),
        ]);
    }

    public function complete(Request $request, OnboardingItem $item)
    {
        $user = auth()->user();

        if ($user->onboardingCompletions()->where('onboarding_item_id', $item->id)->exists()) {
            return back()->with('info', 'Already marked complete.');
        }

        $request->validate([
            'file'  => 'nullable|file|max:20480',
            'notes' => 'nullable|string|max:1000',
        ]);

        $filePath     = null;
        $originalName = null;

        if ($request->hasFile('file')) {
            $file         = $request->file('file');
            $filePath     = $file->store('uploads/' . $user->id . '/onboarding', 'local');
            $originalName = $file->getClientOriginalName();
        }

        $user->onboardingCompletions()->create([
            'onboarding_item_id' => $item->id,
            'file_path'          => $filePath,
            'original_name'      => $originalName,
            'notes'              => $request->notes,
            'completed_at'       => now(),
        ]);

        $to   = config('mail.notification_email', env('NOTIFICATION_EMAIL', 'creative@mmedigital.ca'));
        $body = "Onboarding item completed by {$user->name} ({$user->company_name})\n\nItem: {$item->label}\nFile: " . ($originalName ?? 'None') . "\nNotes: " . ($request->notes ?? '—');

        try {
            Mail::raw($body, fn($m) => $m->to($to)->subject("[MME Portal] Onboarding — {$item->label} — {$user->company_name}"));
        } catch (\Exception $e) {
            \Log::error('Mail failed: ' . $e->getMessage());
        }

        return back()->with('success', "'{$item->label}' marked as complete.");
    }

    public function undo(OnboardingItem $item)
    {
        auth()->user()->onboardingCompletions()
            ->where('onboarding_item_id', $item->id)
            ->delete();

        return back()->with('success', "'{$item->label}' marked as incomplete.");
    }
}
