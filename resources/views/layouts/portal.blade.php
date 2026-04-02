<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portal' }} — MME Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

<div class="portal-shell">

    {{-- ── Sidebar ──────────────────────────────────────────────────── --}}
    <aside class="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <div class="logo-mark">MME</div>
            <div>
                <div class="text-sm font-medium text-gray-800 leading-tight">Client portal</div>
                <div class="text-[11px] text-gray-400">mmedigital.ca</div>
            </div>
        </div>

        {{-- My account --}}
        <div class="nav-section">
            <span class="nav-section-label">My account</span>

            <a href="{{ route('client.dashboard') }}"
               class="nav-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'grid'])
                My services
            </a>

            @if(auth()->user()->feature_onboarding)
            <a href="{{ route('client.onboarding') }}"
               class="nav-item {{ request()->routeIs('client.onboarding') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'check-circle'])
                Onboarding checklist
                @php $pct = auth()->user()->onboardingPercentage(); @endphp
                @if($pct < 100)
                    <span class="nav-item-badge">{{ 100 - $pct }}%</span>
                @endif
            </a>
            @endif

            @if(auth()->user()->feature_project_status)
            <a href="{{ route('client.dashboard') }}#project"
               class="nav-item">
                @include('components.icon', ['name' => 'bar-chart'])
                Project status
            </a>
            @endif
        </div>

        {{-- Submit a request --}}
        <div class="nav-section">
            <span class="nav-section-label">Submit a request</span>

            <a href="{{ route('client.photos') }}"
               class="nav-item {{ request()->routeIs('client.photos') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'upload'])
                Upload photos
            </a>

            <a href="{{ route('client.pages') }}"
               class="nav-item {{ request()->routeIs('client.pages') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'file-text'])
                Add a page
            </a>

            <a href="{{ route('client.services') }}"
               class="nav-item {{ request()->routeIs('client.services') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'plus-circle'])
                Request a service
            </a>

            @if(auth()->user()->feature_support_tickets)
            <a href="{{ route('client.tickets') }}"
               class="nav-item {{ request()->routeIs('client.tickets') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'message-circle'])
                Support tickets
                @php $open = auth()->user()->openTicketsCount(); @endphp
                @if($open > 0)
                    <span class="nav-item-badge">{{ $open }}</span>
                @endif
            </a>
            @endif
        </div>

        {{-- Review & approve --}}
        <div class="nav-section">
            <span class="nav-section-label">Review &amp; approve</span>

            @if(auth()->user()->feature_website_review)
            <a href="#" class="nav-item">
                @include('components.icon', ['name' => 'monitor'])
                Website review
                <span class="nav-item-soon">v1.1</span>
            </a>
            @else
            <span class="nav-item opacity-50 cursor-default">
                @include('components.icon', ['name' => 'monitor'])
                Website review
                <span class="nav-item-soon">v1.1</span>
            </span>
            @endif

            @if(auth()->user()->feature_app_review)
            <a href="{{ route('client.app-review') }}"
               class="nav-item {{ request()->routeIs('client.app-review') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'code'])
                Web app review
                @php $active = auth()->user()->appReviews()->where('status','in_review')->count(); @endphp
                @if($active > 0)
                    <span class="nav-item-badge">{{ $active }}</span>
                @endif
            </a>
            @endif
        </div>

        {{-- Documents & reports --}}
        <div class="nav-section">
            <span class="nav-section-label">Documents</span>

            @if(auth()->user()->feature_document_vault)
            <a href="#" class="nav-item">
                @include('components.icon', ['name' => 'folder'])
                Document vault
            </a>
            @else
            <span class="nav-item opacity-40 cursor-default text-xs">
                @include('components.icon', ['name' => 'folder'])
                Document vault
                <span class="nav-item-soon">v1.5</span>
            </span>
            @endif

            @if(auth()->user()->feature_seo_reports)
            <a href="#" class="nav-item">
                @include('components.icon', ['name' => 'trending-up'])
                SEO reports
            </a>
            @else
            <span class="nav-item opacity-40 cursor-default text-xs">
                @include('components.icon', ['name' => 'trending-up'])
                SEO reports
                <span class="nav-item-soon">v1.5</span>
            </span>
            @endif
        </div>

        {{-- Billing --}}
        <div class="nav-section">
            <span class="nav-section-label">Billing</span>

            @if(auth()->user()->feature_invoices)
            <a href="#" class="nav-item">
                @include('components.icon', ['name' => 'credit-card'])
                Invoices &amp; payments
            </a>
            @else
            <span class="nav-item opacity-40 cursor-default text-xs">
                @include('components.icon', ['name' => 'credit-card'])
                Invoices &amp; payments
                <span class="nav-item-soon">v2.0</span>
            </span>
            @endif
        </div>

        {{-- Footer --}}
        <div class="sidebar-footer">
            <div class="user-row">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->name, strrpos(auth()->user()->name, ' ') + 1, 1)) }}
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-gray-400">{{ auth()->user()->company_name }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button class="w-full text-left px-2 py-1 text-xs text-gray-400 hover:text-gray-600 rounded">
                    Sign out
                </button>
            </form>
        </div>

    </aside>

    {{-- ── Main area ─────────────────────────────────────────────────── --}}
    <div class="main-content">

        {{-- Topbar --}}
        <header class="topbar">
            <div>
                <h1 class="text-sm font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
                <p class="text-xs text-gray-400">{{ $subtitle ?? '' }}</p>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert-info">{{ session('info') }}</div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="page-content">
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>
