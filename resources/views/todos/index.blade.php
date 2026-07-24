@extends('layouts.app')

@section('title', 'Todos')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Actions</h2>
            <p class="text-on-surface-variant font-body-md">Manage your deep-work queue and high-priority tasks.</p>
        </div>
        <a href="{{ route('todos.create') }}" class="bg-primary text-on-primary w-12 h-12 rounded-xl flex items-center justify-center shadow-lg hover:brightness-110 active:scale-95 transition-all">
            <span class="material-symbols-outlined text-2xl">add</span>
        </a>
    </div>
</div>

<nav class="flex gap-6 mb-8 border-b border-outline-variant overflow-x-auto hide-scrollbar">
    <a href="{{ route('todos.index', ['tab' => 'active']) }}" class="pb-3 font-label-sm text-label-sm uppercase tracking-widest transition-all flex items-center gap-2 whitespace-nowrap {{ $tab === 'active' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }}">
        Active
        <span class="text-[10px] {{ $tab === 'active' ? 'bg-primary-fixed text-primary' : 'bg-surface-container-low text-on-surface-variant' }} px-1.5 py-0.5 rounded-full font-bold">{{ $counts['active'] }}</span>
    </a>
    <a href="{{ route('todos.index', ['tab' => 'paused']) }}" class="pb-3 font-label-sm text-label-sm uppercase tracking-widest transition-all flex items-center gap-2 whitespace-nowrap {{ $tab === 'paused' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }}">
        Paused
        <span class="text-[10px] {{ $tab === 'paused' ? 'bg-primary-fixed text-primary' : 'bg-surface-container-low text-on-surface-variant' }} px-1.5 py-0.5 rounded-full font-bold">{{ $counts['paused'] }}</span>
    </a>
    <a href="{{ route('todos.index', ['tab' => 'archived']) }}" class="pb-3 font-label-sm text-label-sm uppercase tracking-widest transition-all flex items-center gap-2 whitespace-nowrap {{ $tab === 'archived' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }}">
        Archived
        <span class="text-[10px] {{ $tab === 'archived' ? 'bg-primary-fixed text-primary' : 'bg-surface-container-low text-on-surface-variant' }} px-1.5 py-0.5 rounded-full font-bold">{{ $counts['archived'] }}</span>
    </a>
    <a href="{{ route('todos.index', ['tab' => 'trash']) }}" class="pb-3 font-label-sm text-label-sm uppercase tracking-widest transition-all flex items-center gap-2 whitespace-nowrap {{ $tab === 'trash' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }}">
        Trash
        <span class="text-[10px] {{ $tab === 'trash' ? 'bg-primary-fixed text-primary' : 'bg-surface-container-low text-on-surface-variant' }} px-1.5 py-0.5 rounded-full font-bold">{{ $counts['trash'] }}</span>
    </a>
</nav>

@if($tab === 'trash')
<div class="space-y-3">
    @forelse($todos as $todo)
    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-4 flex items-center justify-between">
        <div>
            <p class="font-body-md font-medium text-on-surface">{{ $todo->title }}</p>
            <span class="text-label-sm text-outline">Deleted {{ $todo->deleted_at->format('M d, Y g:i A') }}</span>
        </div>
        <div class="flex items-center gap-1">
            <form method="POST" action="{{ route('todos.restore', $todo->id) }}">
                @csrf
                <button class="material-symbols-outlined text-secondary hover:text-primary active:scale-90 transition-all p-1.5 rounded-full hover:bg-surface-container-low text-sm" title="Restore">restore_from_trash</button>
            </form>
            <form method="POST" action="{{ route('todos.force-delete', $todo->id) }}">
                @csrf @method('DELETE')
                <button data-delete data-title="{{ $todo->title }}" class="material-symbols-outlined text-secondary hover:text-error active:scale-90 transition-all p-1.5 rounded-full hover:bg-error-container text-sm" title="Delete permanently">delete_forever</button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 text-center">
        <span class="material-symbols-outlined text-outline text-4xl mb-3">delete_sweep</span>
        <p class="text-on-surface-variant font-body-md">Trash is empty.</p>
    </div>
    @endforelse
</div>
@else
<div class="space-y-4">
    @forelse($todos as $todo)
    <div class="bg-white p-5 border border-outline-variant rounded-lg shadow-sm hover:translate-x-1 transition-all group cursor-pointer"
         style="border-left: 4px solid {{ $todo->quadrant_color }};">
        <div class="flex justify-between items-start mb-2">
            <div class="flex gap-2">
                <x-quadrant :quadrant="$todo->quadrant" />
            </div>
            @if($todo->repeat_type !== 'none')
            <div class="flex items-center gap-1 text-on-surface-variant group-hover:text-primary shrink-0">
                <span class="material-symbols-outlined text-[16px]">refresh</span>
                <span class="font-label-sm text-[10px] capitalize">{{ $todo->repeat_type }}</span>
            </div>
            @endif
        </div>

        <h3 class="font-headline-md text-headline-md text-on-surface mb-1">{{ $todo->title }}</h3>
        @if($todo->description)
        <p class="text-on-surface-variant font-body-md mb-4 whitespace-pre-line">{{ Str::limit($todo->description, 120) }}</p>
        @endif

        <div class="flex items-center gap-4 text-on-surface-variant font-label-sm">
            @if($todo->next_due_at)
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                <span>{{ $todo->next_due_at->format('M d, g:i A') }}</span>
            </div>
            @endif
            <div class="flex items-center gap-2 ml-auto">
                @if($todo->repeat_type !== 'none')
                <button data-skip data-action="{{ route('todos.skip', $todo) }}" data-title="{{ $todo->title }}" class="material-symbols-outlined text-secondary text-[18px] p-1 hover:bg-surface-container-low rounded-full" title="Skip">skip_next</button>
                @endif
                <button data-complete data-action="{{ route('todos.complete', $todo) }}" data-title="{{ $todo->title }}" class="material-symbols-outlined text-secondary text-[18px] p-1 hover:bg-primary-fixed rounded-full hover:text-primary" title="Complete">check_circle</button>
                <a href="{{ route('todos.edit', $todo) }}" class="material-symbols-outlined text-secondary text-[18px] p-1 hover:bg-surface-container-low rounded-full">edit</a>
                <form method="POST" action="{{ route('todos.destroy', $todo) }}" class="inline">
                    @csrf @method('DELETE')
                    <button data-delete data-title="{{ $todo->title }}" class="material-symbols-outlined text-secondary text-[18px] p-1 hover:bg-error-container rounded-full hover:text-error">delete</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 text-center">
        <span class="material-symbols-outlined text-outline text-4xl mb-3">task_alt</span>
        <p class="text-on-surface-variant font-body-md">No {{ $tab }} todos.</p>
        <a href="{{ route('todos.create') }}" class="inline-block mt-4 bg-primary text-on-primary px-6 py-2 rounded-lg font-label-sm text-label-sm hover:brightness-110 active:scale-95 transition-all">Create Todo</a>
    </div>
    @endforelse
</div>
@endif
@endsection
