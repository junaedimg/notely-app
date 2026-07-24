@extends('layouts.app')

@section('title', $todo->title)

@section('content')
<div class="max-w-[720px] mx-auto">
    <div class="mb-6">
        <a href="{{ route('todos.index') }}" class="text-primary font-label-sm text-label-sm hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Todos
        </a>
    </div>

    <div class="bg-surface-container-low border border-outline-variant rounded-lg p-8 border-l-quadrant-{{ \App\View\Components\Quadrant::get($todo->quadrant)['class'] }}">
        <div class="flex justify-between items-start mb-4">
            <div>
                <div class="flex gap-2 mb-3">
                    <x-quadrant :quadrant="$todo->quadrant" />
                </div>
                <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">{{ $todo->title }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('todos.edit', $todo) }}" class="material-symbols-outlined text-secondary p-2 hover:bg-surface-container-low rounded-full">edit</a>
                <form method="POST" action="{{ route('todos.destroy', $todo) }}">
                    @csrf @method('DELETE')
                    <button data-delete data-type="soft" data-title="{{ $todo->title }}" class="material-symbols-outlined text-error p-2 hover:bg-error-container rounded-full">delete</button>
                </form>
            </div>
        </div>

        @if($todo->description)
        <p class="text-on-surface-variant font-body-md mb-6 whitespace-pre-line">{{ $todo->description }}</p>
        @endif

        <div class="grid grid-cols-2 gap-4 text-label-sm text-outline border-t border-outline-variant pt-6">
            <div><span class="text-on-surface-variant">Status:</span> {{ ucfirst($todo->status) }}</div>
            @if($todo->repeat_type !== 'none')
            <div><span class="text-on-surface-variant">Repeat:</span> {{ ucfirst($todo->repeat_type) }}</div>
            @endif
            @if($todo->next_due_at)
            <div><span class="text-on-surface-variant">Next due:</span> {{ $todo->next_due_at->format('M d, Y g:i A') }}</div>
            @endif
            <div><span class="text-on-surface-variant">Completed:</span> {{ $todo->completed_count }} times</div>
        </div>

        <div class="flex gap-3 mt-8">
            <button data-complete data-action="{{ route('todos.complete', $todo) }}" data-title="{{ $todo->title }}" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg font-label-sm text-label-sm hover:brightness-110 active:scale-95 transition-all">Complete</button>
            @if($todo->repeat_type !== 'none')
            <button data-skip data-action="{{ route('todos.skip', $todo) }}" data-title="{{ $todo->title }}" class="px-6 py-2.5 rounded-lg border border-outline-variant font-label-sm text-label-sm text-on-surface hover:bg-surface-container-low transition-all">Skip</button>
            @endif
        </div>
    </div>
</div>
@endsection
