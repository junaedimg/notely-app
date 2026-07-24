@extends('layouts.app')

@section('title', $note->title)

@section('content')
<div class="max-w-[720px] mx-auto">
    <div class="mb-6">
        <a href="{{ route('notes.index') }}" class="text-primary font-label-sm text-label-sm hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Notes
        </a>
    </div>

    <div class="bg-surface-container-low border border-outline-variant rounded-lg p-8">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center gap-3">
                <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">{{ $note->title }}</h2>
                @if($note->is_pinned)
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">push_pin</span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('notes.toggle-pin', $note) }}">
                    @csrf
                    <input type="hidden" name="_redirect" value="{{ request()->url() }}">
                    <button class="material-symbols-outlined p-2 hover:bg-surface-container-low rounded-full transition-all active:scale-90 {{ $note->is_pinned ? 'text-primary' : 'text-secondary' }}" style="{{ $note->is_pinned ? 'font-variation-settings: \'FILL\' 1;' : '' }}">push_pin</button>
                </form>
                <a href="{{ route('notes.edit', $note) }}" class="material-symbols-outlined text-secondary p-2 hover:bg-surface-container-low rounded-full">edit</a>
                <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('Delete this note?')">
                    @csrf @method('DELETE')
                    <button class="material-symbols-outlined text-error p-2 hover:bg-error-container rounded-full">delete</button>
                </form>
            </div>
        </div>

        @if($note->color)
        <div class="mb-4">
            <span class="bg-surface-container-highest px-3 py-1 rounded text-[10px] uppercase tracking-wider text-on-surface-variant border border-outline-variant" style="border-left: 4px solid {{ $note->color }};">{{ $note->color }}</span>
        </div>
        @endif

        <div class="prose prose-sm max-w-none text-on-surface font-body-md leading-relaxed">
            {!! nl2br(e($note->content)) !!}
        </div>

        <div class="mt-8 pt-6 border-t border-outline-variant text-label-sm text-outline flex items-center gap-4">
            <span>Created {{ $note->created_at->format('M d, Y g:i A') }}</span>
            <span>Updated {{ $note->updated_at->format('M d, Y g:i A') }}</span>
        </div>
    </div>
</div>
@endsection
