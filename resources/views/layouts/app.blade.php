<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Notely') — Notely</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
</head>
<body class="bg-background font-body-md text-on-surface selection:bg-primary-container selection:text-on-primary-container min-h-screen">
    <header class="flex justify-between items-center px-margin-mobile md:px-margin-desktop h-16 w-full fixed top-0 z-50 bg-surface border-b border-outline-variant">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="font-headline-md text-headline-md font-bold text-primary">Notely</a>
        </div>
        <div class="flex items-center gap-2">
            @if(request()->routeIs('dashboard'))
            <div class="flex items-center gap-1 bg-surface-container-lowest border border-outline-variant rounded-lg px-2 py-1.5">
                <span class="material-symbols-outlined text-primary text-xs shrink-0">calendar_today</span>
                <form method="POST" action="{{ route('dashboard.set-today') }}" class="flex items-center gap-1" id="date-form">
                    @csrf
                    <button type="button" onclick="adjustDate(-1)" class="material-symbols-outlined text-secondary hover:text-primary active:scale-90 transition-all p-0.5 text-xs shrink-0">chevron_left</button>
                    <input type="date" name="date" id="date-input" value="{{ session('simulated_today') ? \Carbon\Carbon::parse(session('simulated_today'))->format('Y-m-d') : now()->format('Y-m-d') }}"
                        class="font-body-md text-[11px] bg-transparent border-none outline-none text-on-surface w-0 min-w-[90px] text-center [&::-webkit-calendar-picker-indicator]:opacity-50 [&::-webkit-calendar-picker-indicator]:cursor-pointer">
                    <button type="button" onclick="adjustDate(1)" class="material-symbols-outlined text-secondary hover:text-primary active:scale-90 transition-all p-0.5 text-xs shrink-0">chevron_right</button>
                </form>
                <button type="submit" form="date-form" class="bg-primary text-on-primary px-2 py-0.5 rounded-lg font-label-sm text-[10px] hover:brightness-110 active:scale-95 transition-all">Set</button>
                <form method="POST" action="{{ route('dashboard.reset-today') }}" class="flex">
                    @csrf
                    <button type="submit" class="text-[10px] font-label-sm text-secondary hover:text-primary px-1">Reset</button>
                </form>
            </div>
            @endif
        </div>
    </header>

    <main class="pt-24 pb-32 max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop min-h-screen">
        @yield('content')
    </main>

    <nav class="fixed bottom-0 w-full z-50 flex justify-around items-center h-20 bg-surface px-2 border-t border-outline-variant">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-colors p-2 rounded-xl group @if(request()->routeIs('dashboard')) text-primary @endif">
            <span class="material-symbols-outlined mb-0.5 group-active:scale-90 transition-transform duration-150" @if(request()->routeIs('dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>dashboard</span>
            <span class="font-label-sm text-label-sm">Dashboard</span>
        </a>
        <a href="{{ route('notes.index') }}" class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-colors p-2 rounded-xl group @if(request()->routeIs('notes.*')) text-primary @endif">
            <span class="material-symbols-outlined mb-0.5 group-active:scale-90 transition-transform duration-150" @if(request()->routeIs('notes.*')) style="font-variation-settings: 'FILL' 1;" @endif>description</span>
            <span class="font-label-sm text-label-sm">Notes</span>
        </a>
        <a href="{{ route('todos.index') }}" class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-colors p-2 rounded-xl group @if(request()->routeIs('todos.*')) text-primary @endif">
            <span class="material-symbols-outlined mb-0.5 group-active:scale-90 transition-transform duration-150" @if(request()->routeIs('todos.*')) style="font-variation-settings: 'FILL' 1;" @endif>task_alt</span>
            <span class="font-label-sm text-label-sm">Todos</span>
        </a>
        <a href="{{ route('history.index') }}" class="flex flex-col items-center justify-center text-secondary hover:text-primary transition-colors p-2 rounded-xl group @if(request()->routeIs('history.*')) text-primary @endif">
            <span class="material-symbols-outlined mb-0.5 group-active:scale-90 transition-transform duration-150" @if(request()->routeIs('history.*')) style="font-variation-settings: 'FILL' 1;" @endif>history</span>
            <span class="font-label-sm text-label-sm">History</span>
        </a>
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
