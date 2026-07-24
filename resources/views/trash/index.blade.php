@extends('layouts.app')

@section('title', 'Trash')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Trash</h2>
            <p class="text-on-surface-variant font-body-md mt-1">Deleted notes and todos can be restored or permanently deleted.</p>
        </div>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

@if($notes->isEmpty() && $todos->isEmpty())
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 text-center">
    <span class="material-symbols-outlined text-outline text-4xl mb-3">delete_sweep</span>
    <p class="text-on-surface-variant font-body-md">Trash is empty.</p>
</div>
@endif

@if($notes->isNotEmpty())
<section class="mb-8">
    <h3 class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-4">Deleted Notes</h3>
    <div class="space-y-3">
        @foreach($notes as $note)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-4 flex items-center justify-between">
            <div>
                <p class="font-body-md font-medium text-on-surface">{{ $note->title }}</p>
                <span class="text-label-sm text-outline">Deleted {{ $note->deleted_at->format('M d, Y g:i A') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('trash.restore-note', $note->id) }}">
                    @csrf
                    <button class="text-primary font-label-sm text-label-sm hover:underline">Restore</button>
                </form>
                <form method="POST" action="{{ route('trash.force-delete-note', $note->id) }}" onsubmit="return confirm('Permanently delete this note?')">
                    @csrf @method('DELETE')
                    <button class="text-error font-label-sm text-label-sm hover:underline">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

@if($todos->isNotEmpty())
<section class="mb-8">
    <h3 class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-4">Deleted Todos</h3>
    <div class="space-y-3">
        @foreach($todos as $todo)
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-4 flex items-center justify-between">
            <div>
                <p class="font-body-md font-medium text-on-surface">{{ $todo->title }}</p>
                <span class="text-label-sm text-outline">Deleted {{ $todo->deleted_at->format('M d, Y g:i A') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('trash.restore-todo', $todo->id) }}">
                    @csrf
                    <button class="text-primary font-label-sm text-label-sm hover:underline">Restore</button>
                </form>
                <form method="POST" action="{{ route('trash.force-delete-todo', $todo->id) }}" onsubmit="return confirm('Permanently delete this todo?')">
                    @csrf @method('DELETE')
                    <button class="text-error font-label-sm text-label-sm hover:underline">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
@endsection
