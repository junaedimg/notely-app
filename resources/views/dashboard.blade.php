@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="mb-10">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-1 block">Workspace</span>
            <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Focus</h2>
        </div>
    </div>
</section>

@if($pinnedNotes->count() > 0)
<section class="mb-12">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-headline-md text-headline-md text-on-surface">Pinned Notes</h3>
        <a href="{{ route('notes.index') }}" class="text-primary font-label-sm text-label-sm hover:underline">View all</a>
    </div>
    <div class="flex gap-gutter overflow-x-auto hide-scrollbar pb-4 snap-x">
        @foreach($pinnedNotes as $note)
        <a href="{{ route('notes.show', $note) }}" class="min-w-[280px] md:min-w-[340px] bg-surface-container-lowest border border-outline-variant p-6 rounded-xl snap-start hover:border-primary transition-colors cursor-pointer group shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">push_pin</span>
                <span class="font-label-sm text-label-sm text-secondary">{{ $note->updated_at->diffForHumans() }}</span>
            </div>
            <h4 class="font-headline-md text-headline-md mb-2 group-hover:text-primary transition-colors">{{ $note->title }}</h4>
            <p class="text-on-surface-variant font-body-md line-clamp-3 leading-relaxed">{{ Str::limit($note->content, 150) }}</p>
        </a>
        @endforeach
    </div>
</section>
@endif

<section class="max-w-[720px]">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <h3 class="font-headline-md text-headline-md text-on-surface">Today's Actions</h3>
            <span class="bg-primary-fixed text-primary px-3 py-1 rounded-full font-label-sm text-[10px] font-bold">{{ $todayTodos->count() }} TASKS</span>
        </div>
        <a href="{{ route('todos.index') }}" class="text-primary font-label-sm text-label-sm hover:underline">Manage</a>
    </div>
    <div class="space-y-3">
        @forelse($todayTodos as $todo)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex items-center justify-between hover:shadow-md transition-shadow group">
            <div class="flex items-center gap-4">
                <button data-complete data-action="{{ route('todos.complete', $todo) }}" class="w-6 h-6 rounded-lg border-2 border-outline-variant hover:border-primary flex items-center justify-center transition-all bg-transparent flex-shrink-0">
                    <span class="material-symbols-outlined text-xs text-on-primary hidden">check</span>
                </button>
                <div>
                    <p class="font-body-md font-medium text-on-surface group-hover:text-primary transition-colors">{{ $todo->title }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        @if($todo->quadrant === 'do')
                        <span class="font-label-sm text-[10px] text-error px-2 py-0.5 border border-error rounded-full font-semibold uppercase tracking-wide">Urgent & Important</span>
                        @elseif($todo->quadrant === 'plan')
                        <span class="font-label-sm text-[10px] text-primary px-2 py-0.5 border border-primary rounded-full font-semibold uppercase tracking-wide">Important</span>
                        @elseif($todo->quadrant === 'delegate')
                        <span class="font-label-sm text-[10px] text-secondary px-2 py-0.5 border border-secondary rounded-full font-semibold uppercase tracking-wide">Urgent</span>
                        @endif
                        @if($todo->next_due_at)
                        <span class="text-secondary font-label-sm text-[10px]">Due {{ $todo->next_due_at->format('g:i A') }}</span>
                        @endif
                        @if($todo->repeat_type !== 'none')
                        <span class="material-symbols-outlined text-secondary text-[14px]">refresh</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-1">
                @if($todo->repeat_type !== 'none')
                <button data-skip data-action="{{ route('todos.skip', $todo) }}" class="material-symbols-outlined text-secondary text-sm p-2 hover:bg-surface-container-low rounded-full" title="Skip">skip_next</button>
                @endif
                <a href="{{ route('todos.edit', $todo) }}" class="material-symbols-outlined text-secondary text-sm p-2 hover:bg-surface-container-low rounded-full">more_vert</a>
            </div>
        </div>
        @empty
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 text-center">
            <span class="material-symbols-outlined text-outline text-4xl mb-3">task_alt</span>
            <p class="text-on-surface-variant font-body-md">No todos for today. Time to plan something!</p>
            <a href="{{ route('todos.create') }}" class="inline-block mt-4 bg-primary text-on-primary px-6 py-2 rounded-lg font-label-sm text-label-sm hover:brightness-110 active:scale-95 transition-all">Create Todo</a>
        </div>
        @endforelse
    </div>
</section>
@endsection
