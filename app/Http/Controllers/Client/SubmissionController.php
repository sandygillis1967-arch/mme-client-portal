<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PhotoUpload;
use App\Models\PageRequest;
use App\Models\ServiceRequest;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    // ── Photos ──────────────────────────────────────────────────────────────

    public function photosIndex()
    {
        return view('client.tabs.photos', [
            'uploads' => auth()->user()->photoUploads()->latest()->get(),
        ]);
    }

    public function photosStore(Request $request)
    {
        $request->validate([
            'photo'                 => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'placement_description' => 'nullable|string|max:1000',
            'text_to_add'           => 'nullable|string|max:1000',
        ]);

        $file = $request->file('photo');
        $path = $file->store('uploads/' . auth()->id() . '/photos', 'local');

        $upload = auth()->user()->photoUploads()->create([
            'file_path'             => $path,
            'original_name'         => $file->getClientOriginalName(),
            'placement_description' => $request->placement_description,
            'text_to_add'           => $request->text_to_add,
        ]);

        $this->notify('New Photo Upload', auth()->user(), [
            'File'        => $upload->original_name,
            'Placement'   => $upload->placement_description ?? '—',
            'Text to add' => $upload->text_to_add ?? '—',
        ]);

        return back()->with('success', 'Photo uploaded successfully.');
    }

    // ── Page Requests ────────────────────────────────────────────────────────

    public function pagesIndex()
    {
        return view('client.tabs.pages', [
            'requests' => auth()->user()->pageRequests()->latest()->get(),
        ]);
    }

    public function pagesStore(Request $request)
    {
        $request->validate([
            'page_title'  => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'notes'       => 'nullable|string|max:2000',
        ]);

        $pr = auth()->user()->pageRequests()->create($request->only('page_title', 'description', 'notes'));

        $this->notify('New Page Request', auth()->user(), [
            'Page title'  => $pr->page_title,
            'Description' => $pr->description ?? '—',
            'Notes'       => $pr->notes ?? '—',
        ]);

        return back()->with('success', 'Page request submitted.');
    }

    // ── Service Requests ────────────────────────────────────────────────────

    public function servicesIndex()
    {
        return view('client.tabs.service-request', [
            'requests' => auth()->user()->serviceRequests()->latest()->get(),
        ]);
    }

    public function servicesStore(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'notes'        => 'nullable|string|max:2000',
        ]);

        $sr = auth()->user()->serviceRequests()->create($request->only('service_name', 'description', 'notes'));

        $this->notify('New Service Request', auth()->user(), [
            'Service'     => $sr->service_name,
            'Description' => $sr->description ?? '—',
            'Notes'       => $sr->notes ?? '—',
        ]);

        return back()->with('success', 'Service request submitted.');
    }

    // ── Support Tickets ─────────────────────────────────────────────────────

    public function ticketsIndex()
    {
        return view('client.tabs.tickets', [
            'tickets' => auth()->user()->supportTickets()->latest()->get(),
        ]);
    }

    public function ticketsStore(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string|max:3000',
            'priority'    => 'required|in:low,medium,urgent',
        ]);

        $ticket = auth()->user()->supportTickets()->create($request->only('subject', 'description', 'priority'));

        $this->notify('New Support Ticket', auth()->user(), [
            'Subject'     => $ticket->subject,
            'Priority'    => ucfirst($ticket->priority),
            'Description' => $ticket->description,
        ]);

        return back()->with('success', 'Support ticket submitted.');
    }

    // ── Shared notification helper ───────────────────────────────────────────

    private function notify(string $type, $user, array $fields): void
    {
        $to = config('mail.notification_email', env('NOTIFICATION_EMAIL', 'creative@mmedigital.ca'));

        $body = "New submission from {$user->name} ({$user->company_name})\n\n";
        foreach ($fields as $label => $value) {
            $body .= "{$label}: {$value}\n";
        }

        try {
            Mail::raw($body, function ($message) use ($to, $type, $user) {
                $message->to($to)
                    ->subject("[MME Portal] {$type} — {$user->company_name}");
            });
        } catch (\Exception $e) {
            \Log::error('Mail failed: ' . $e->getMessage());
        }
    }
}
