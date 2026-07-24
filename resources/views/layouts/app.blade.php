<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — @yield('title', 'Home')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon_io/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>@media (max-width:767px){.sm-hide{display:none!important}}</style>
</head>
<body class="bg-background font-body-md text-on-surface selection:bg-primary-container selection:text-on-primary-container min-h-screen">
    <header class="fixed top-0 z-50 w-full bg-surface border-b border-outline-variant">
        <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop flex items-center justify-between h-16">
            <div class="flex items-center gap-4 min-w-0">
                <a href="{{ route('dashboard') }}" class="font-headline-md text-headline-md font-bold text-primary shrink-0">{{ config('app.name') }}</a>
            </div>
            <div class="flex items-center gap-1 md:gap-2 min-w-0">
                @if(request()->routeIs('dashboard'))
                <div class="flex items-center gap-0.5 md:gap-1 bg-surface-container-lowest border border-outline-variant rounded-lg px-1.5 md:px-2 py-1.5 overflow-hidden">
                    <span class="material-symbols-outlined text-primary text-[11px] md:text-xs shrink-0 sm-hide" style="font-size:12px;">calendar_today</span>
                    <form method="POST" action="{{ route('dashboard.set-today') }}" class="flex items-center gap-0.5 md:gap-1" id="date-form">
                        @csrf
                        <button type="button" onclick="adjustDate(-1)" class="material-symbols-outlined text-secondary hover:text-primary active:scale-90 transition-all p-0.5 text-[11px] md:text-xs shrink-0">chevron_left</button>
                        <input type="date" name="date" id="date-input" value="{{ session('simulated_today') ? \Carbon\Carbon::parse(session('simulated_today'))->format('Y-m-d') : now()->format('Y-m-d') }}"
                            class="font-body-md text-[10px] md:text-[11px] bg-transparent border-none outline-none text-on-surface w-0 min-w-[80px] md:min-w-[90px] text-center [&::-webkit-calendar-picker-indicator]:opacity-40 [&::-webkit-calendar-picker-indicator]:cursor-pointer">
                        <button type="button" onclick="adjustDate(1)" class="material-symbols-outlined text-secondary hover:text-primary active:scale-90 transition-all p-0.5 text-[11px] md:text-xs shrink-0">chevron_right</button>
                    </form>
                    <button type="submit" form="date-form" class="bg-primary text-on-primary px-1.5 md:px-2 py-0.5 rounded-lg font-label-sm text-[9px] md:text-[10px] hover:brightness-110 active:scale-95 transition-all shrink-0">Set</button>
                    <form method="POST" action="{{ route('dashboard.reset-today') }}" class="flex">
                        @csrf
                        <button type="submit" class="text-[9px] md:text-[10px] font-label-sm text-secondary hover:text-primary px-1 shrink-0">Reset</button>
                    </form>
                </div>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="material-symbols-outlined text-secondary hover:text-error active:scale-90 transition-all p-1.5 rounded-full hover:bg-error-container text-sm" title="Logout">logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="pt-24 pb-32 min-h-screen">
        <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop">
            @yield('content')
        </div>
    </main>

    <nav class="fixed bottom-0 w-full z-50 bg-surface border-t border-outline-variant">
        <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop flex justify-around items-center h-20">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-colors w-full h-full relative @if(request()->routeIs('dashboard')) text-primary @endif">
                @if(request()->routeIs('dashboard'))
                <div class="absolute top-1 left-1/2 -translate-x-1/2 w-5 h-1 bg-primary rounded-full"></div>
                @endif
                <span class="material-symbols-outlined mb-0.5 @if(request()->routeIs('dashboard')) font-bold @endif" @if(request()->routeIs('dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>dashboard</span>
                <span class="font-label-sm text-[10px] @if(request()->routeIs('dashboard')) font-bold @endif">Dashboard</span>
            </a>
            <a href="{{ route('notes.index') }}" class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-colors w-full h-full relative @if(request()->routeIs('notes.*')) text-primary @endif">
                @if(request()->routeIs('notes.*'))
                <div class="absolute top-1 left-1/2 -translate-x-1/2 w-5 h-1 bg-primary rounded-full"></div>
                @endif
                <span class="material-symbols-outlined mb-0.5 @if(request()->routeIs('notes.*')) font-bold @endif" @if(request()->routeIs('notes.*')) style="font-variation-settings: 'FILL' 1;" @endif>description</span>
                <span class="font-label-sm text-[10px] @if(request()->routeIs('notes.*')) font-bold @endif">Notes</span>
            </a>
            <a href="{{ route('todos.index') }}" class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-colors w-full h-full relative @if(request()->routeIs('todos.*')) text-primary @endif">
                @if(request()->routeIs('todos.*'))
                <div class="absolute top-1 left-1/2 -translate-x-1/2 w-5 h-1 bg-primary rounded-full"></div>
                @endif
                <span class="material-symbols-outlined mb-0.5 @if(request()->routeIs('todos.*')) font-bold @endif" @if(request()->routeIs('todos.*')) style="font-variation-settings: 'FILL' 1;" @endif>task_alt</span>
                <span class="font-label-sm text-[10px] @if(request()->routeIs('todos.*')) font-bold @endif">Todos</span>
            </a>
            <a href="{{ route('history.index') }}" class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-colors w-full h-full relative @if(request()->routeIs('history.*')) text-primary @endif">
                @if(request()->routeIs('history.*'))
                <div class="absolute top-1 left-1/2 -translate-x-1/2 w-5 h-1 bg-primary rounded-full"></div>
                @endif
                <span class="material-symbols-outlined mb-0.5 @if(request()->routeIs('history.*')) font-bold @endif" @if(request()->routeIs('history.*')) style="font-variation-settings: 'FILL' 1;" @endif>history</span>
                <span class="font-label-sm text-[10px] @if(request()->routeIs('history.*')) font-bold @endif">History</span>
            </a>
        </div>
    </nav>

    <script>
        const currentUrl = window.location.pathname + window.location.search;
        function adjustDate(days) {
            const input = document.getElementById('date-input');
            if (!input) return;
            const d = new Date(input.value + 'T12:00:00');
            d.setDate(d.getDate() + days);
            input.value = d.toISOString().split('T')[0];
            document.getElementById('date-form').submit();
        }
        document.querySelectorAll('[data-complete]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const note = prompt('Completion note (optional):');
                if (note === null) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.dataset.action;
                form.innerHTML = '@csrf' + (note ? '<input name="completion_note" value="' + note + '">' : '') + '<input name="_redirect" value="' + currentUrl + '">';
                document.body.appendChild(form);
                form.submit();
            });
        });
        document.querySelectorAll('[data-skip]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Skip this todo?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = this.dataset.action;
                    form.innerHTML = '@csrf' + '<input name="_redirect" value="' + currentUrl + '">';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
