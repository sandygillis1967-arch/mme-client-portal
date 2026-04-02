<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ $title ?? 'Dashboard' }} — MME Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

<div class="portal-shell">

    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-mark">MME</div>
            <div>
                <div class="text-sm font-medium text-gray-800 leading-tight">Admin panel</div>
                <div class="text-[11px] text-gray-400">mmedigital.ca</div>
            </div>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">Overview</span>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'grid'])
                Dashboard
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">Clients</span>
            <a href="{{ route('admin.clients') }}"
               class="nav-item {{ request()->routeIs('admin.clients*') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'users'])
                All clients
            </a>
            <a href="{{ route('admin.clients.create') }}"
               class="nav-item {{ request()->routeIs('admin.clients.create') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'plus-circle'])
                Add client
            </a>
            <a href="{{ route('admin.project-stages') }}"
               class="nav-item {{ request()->routeIs('admin.project-stages') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'bar-chart'])
                Project stages
            </a>
            <a href="{{ route('admin.onboarding-items') }}"
               class="nav-item {{ request()->routeIs('admin.onboarding-items') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'check-circle'])
                Onboarding items
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">Submissions</span>
            <a href="{{ route('admin.submissions') }}"
               class="nav-item {{ request()->routeIs('admin.submissions') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'inbox'])
                All submissions
            </a>
            <a href="{{ route('admin.tickets') }}"
               class="nav-item {{ request()->routeIs('admin.tickets') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'message-circle'])
                Support tickets
            </a>
        </div>

        <div class="nav-section">
            <span class="nav-section-label">Reviews</span>
            <a href="{{ route('admin.app-reviews') }}"
               class="nav-item {{ request()->routeIs('admin.app-reviews*') ? 'active' : '' }}">
                @include('components.icon', ['name' => 'code'])
                App reviews
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="user-row">
                <div class="user-avatar">AD</div>
                <div>
                    <div class="text-xs font-medium text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-gray-400">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button class="w-full text-left px-2 py-1 text-xs text-gray-400 hover:text-gray-600 rounded">Sign out</button>
            </form>
        </div>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div>
                <h1 class="text-sm font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
                <p class="text-xs text-gray-400">{{ $subtitle ?? '' }}</p>
            </div>
            <div class="flex items-center gap-3">
                {{ $actions ?? '' }}
            </div>
        </header>

        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif
        </div>

        <main class="page-content">
            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
