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

<div class="flex flex-col gap-4">
    @forelse($notes as $note)
    <div class="bg-surface-container-low border border-outline-variant rounded-lg p-5 flex flex-col gap-2 hover:border-primary transition-colors group relative">
        <a href="{{ route('notes.show', $note) }}" class="absolute inset-0 z-0"></a>
        <div class="flex justify-between items-start relative z-10">
            <h3 class="font-headline-md text-primary font-bold">{{ $note->title }}</h3>
            <div class="flex items-center gap-1" onclick="event.stopPropagation()">
                <form method="POST" action="{{ route('notes.toggle-pin', $note) }}">
                    @csrf
                    <input type="hidden" name="_redirect" value="{{ request()->url() }}">
                    <button class="material-symbols-outlined text-secondary hover:text-primary active:scale-90 transition-all p-1 text-[18px] {{ $note->is_pinned ? 'text-primary' : '' }}" style="{{ $note->is_pinned ? 'font-variation-settings: \'FILL\' 1;' : '' }}">push_pin</button>
                </form>
            </div>
        </div>
        @if($note->content)
        <p class="text-on-surface-variant font-body-md line-clamp-2 leading-relaxed">{{ Str::limit(strip_tags($note->content), 200) }}</p>
        @endif
        <div class="mt-2 flex items-center gap-3 text-label-sm text-outline">
            <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">calendar_today</span> {{ $note->created_at->format('M d') }}
            </span>
            @if($note->color)
            <span class="bg-surface-container-highest px-2 py-0.5 rounded text-[10px] uppercase tracking-wider text-on-surface-variant border border-outline-variant" style="border-left: 3px solid {{ $note->color }};">{{ $note->color }}</span>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 text-center">
        <span class="material-symbols-outlined text-outline text-4xl mb-3">description</span>
        <p class="text-on-surface-variant font-body-md">No notes yet. Start writing your knowledge.</p>
        <a href="{{ route('notes.create') }}" class="inline-block mt-4 bg-primary text-on-primary px-6 py-2 rounded-lg font-label-sm text-label-sm hover:brightness-110 active:scale-95 transition-all">Create Note</a>
    </div>
    @endforelse
</div>
@endsection
