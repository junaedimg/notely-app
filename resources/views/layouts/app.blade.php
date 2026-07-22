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
            <div class="w-10 h-10 rounded-xl bg-surface-container-highest border border-outline-variant overflow-hidden flex items-center justify-center text-on-surface-variant font-headline-md">
                <span class="material-symbols-outlined text-primary">person</span>
            </div>
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
        document.querySelectorAll('[data-complete]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const note = prompt('Completion note (optional):');
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.dataset.action;
                form.innerHTML = '@csrf' + (note ? '<input name="completion_note" value="' + note + '">' : '');
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
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
