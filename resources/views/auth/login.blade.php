<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — MME Digital Client Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex items-center justify-center">

<div class="w-full max-w-sm px-4">

    {{-- Logo --}}
    <div class="flex flex-col items-center mb-8">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-sm font-bold mb-3"
             style="background-color:#1B2A5E;color:#F5C518;">
            MME
        </div>
        <h1 class="text-xl font-semibold text-gray-900">Client portal</h1>
        <p class="text-sm text-gray-400 mt-1">mmedigital.ca</p>
    </div>

    {{-- Card --}}
    <div class="card">

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label class="form-label" for="email">Email address</label>
                <input id="email" name="email" type="email" autocomplete="email"
                       class="form-input" value="{{ old('email') }}" required autofocus>
            </div>

            <div>
                <label class="form-label" for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password"
                       class="form-input" required>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn-primary w-full mt-2">
                Sign in
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">
        Having trouble? Contact
        <a href="mailto:creative@mmedigital.ca" class="underline">creative@mmedigital.ca</a>
    </p>
</div>

</body>
</html>
