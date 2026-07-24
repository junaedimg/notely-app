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
@endsection
