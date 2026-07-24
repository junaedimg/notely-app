<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon_io/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
</head>
<body class="bg-background font-body-md text-on-surface min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <h1 class="font-headline-lg text-headline-lg text-primary font-bold">{{ config('app.name') }}</h1>
            <p class="text-on-surface-variant font-body-md mt-2">Sign in to your workspace</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm space-y-4">
            @csrf

            @if($setupRequired)
            <div class="bg-error-container text-on-error-container text-sm px-4 py-3 rounded-lg">
                Login belum dikonfigurasi. Isi <strong>AUTH_USERNAME</strong> dan <strong>AUTH_PASSWORD</strong> di file <code>.env</code>.
            </div>
            @else
            @if($errors->any())
            <div class="bg-error-container text-on-error-container text-sm px-4 py-3 rounded-lg">
                {{ $errors->first('error') }}
            </div>
            @endif

            <div>
                <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                    class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary transition-colors">
            </div>

            <div>
                <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Password</label>
                <input type="password" name="password" required
                    class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary transition-colors">
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary py-3 rounded-lg font-label-sm text-label-sm hover:brightness-110 active:scale-95 transition-all">Sign In</button>
            @endif
        </form>
    </div>
</body>
</html>
