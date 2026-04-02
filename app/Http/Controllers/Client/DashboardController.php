<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('client.dashboard', [
            'user'              => $user,
            'services'          => $user->services()->latest()->get(),
            'activeCount'       => $user->activeServicesCount(),
            'openTickets'       => $user->openTicketsCount(),
            'onboardingPercent' => $user->onboardingPercentage(),
            'recentActivity'    => $this->recentActivity($user),
        ]);
    }

    private function recentActivity($user): array
    {
        $items = collect();

        $user->photoUploads()->latest()->take(3)->get()
            ->each(fn($u) => $items->push([
                'label' => 'Photo upload — ' . $u->original_name,
                'date'  => $u->created_at,
            ]));

        $user->pageRequests()->latest()->take(3)->get()
            ->each(fn($r) => $items->push([
                'label' => 'Page request — ' . $r->page_title,
                'date'  => $r->created_at,
            ]));

        $user->serviceRequests()->latest()->take(3)->get()
            ->each(fn($r) => $items->push([
                'label' => 'Service request — ' . $r->service_name,
                'date'  => $r->created_at,
            ]));

        $user->supportTickets()->latest()->take(3)->get()
            ->each(fn($t) => $items->push([
                'label' => 'Support ticket — ' . $t->subject,
                'date'  => $t->created_at,
            ]));

        return $items->sortByDesc('date')->take(5)->values()->all();
    }
}
