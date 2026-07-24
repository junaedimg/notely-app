@extends('layouts.app')

@section('title', 'Notes')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary tracking-tight">Knowledge</h2>
            <p class="text-on-surface-variant font-body-md mt-1">Deep thinking repository.</p>
        </div>
        <a href="{{ route('notes.create') }}" class="bg-primary text-on-primary w-12 h-12 rounded-xl flex items-center justify-center shadow-lg hover:brightness-110 active:scale-95 transition-all">
            <span class="material-symbols-outlined text-2xl">add</span>
        </a>
    </div>
</div>

<nav class="flex gap-8 mb-8 border-b border-outline-variant">
    <a href="{{ route('notes.index', ['tab' => 'active']) }}" class="pb-3 font-label-sm text-label-sm uppercase tracking-widest transition-all flex items-center gap-2 {{ $tab === 'active' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }}">
        Notes
        <span class="text-[10px] {{ $tab === 'active' ? 'bg-primary-fixed text-primary' : 'bg-surface-container-low text-on-surface-variant' }} px-1.5 py-0.5 rounded-full font-bold">{{ $counts['active'] }}</span>
    </a>
    <a href="{{ route('notes.index', ['tab' => 'trash']) }}" class="pb-3 font-label-sm text-label-sm uppercase tracking-widest transition-all flex items-center gap-2 {{ $tab === 'trash' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }}">
        Trash
        <span class="text-[10px] {{ $tab === 'trash' ? 'bg-primary-fixed text-primary' : 'bg-surface-container-low text-on-surface-variant' }} px-1.5 py-0.5 rounded-full font-bold">{{ $counts['trash'] }}</span>
    </a>
</nav>

@if($tab === 'trash')
<div class="space-y-3">
    @forelse($notes as $note)
    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-4 flex items-center justify-between">
        <div>
            <p class="font-body-md font-medium text-on-surface">{{ $note->title }}</p>
            <span class="text-label-sm text-outline">Deleted {{ $note->deleted_at->format('M d, Y g:i A') }}</span>
        </div>
        <div class="flex items-center gap-1">
            <form method="POST" action="{{ route('notes.restore', $note->id) }}">
                @csrf
                <button class="material-symbols-outlined text-secondary hover:text-primary active:scale-90 transition-all p-1.5 rounded-full hover:bg-surface-container-low text-sm" title="Restore">restore_from_trash</button>
            </form>
            <form method="POST" action="{{ route('notes.force-delete', $note->id) }}">
                @csrf @method('DELETE')
                <button data-delete data-title="{{ $note->title }}" class="material-symbols-outlined text-secondary hover:text-error active:scale-90 transition-all p-1.5 rounded-full hover:bg-error-container text-sm" title="Delete permanently">delete_forever</button>
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
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($notes as $note)
    <x-note-card :note="$note" />
    @empty
    <div class="col-span-full bg-surface-container-lowest border border-outline-variant rounded-xl p-8 text-center">
        <span class="material-symbols-outlined text-outline text-4xl mb-3">description</span>
        <p class="text-on-surface-variant font-body-md">No notes yet. Start writing your knowledge.</p>
        <a href="{{ route('notes.create') }}" class="inline-block mt-4 bg-primary text-on-primary px-6 py-2 rounded-lg font-label-sm text-label-sm hover:brightness-110 active:scale-95 transition-all">Create Note</a>
    </div>
    @endforelse
</div>
@endif
@endsection
