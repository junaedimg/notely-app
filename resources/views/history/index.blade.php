@extends('layouts.app')

@section('title', 'History')

@section('content')
<div class="mb-10">
    <h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Records</h1>
    <p class="text-on-surface-variant font-body-md">A chronological overview of your completed thoughts and actions.</p>
</div>

<section class="mb-10 flex gap-3 overflow-x-auto pb-2 hide-scrollbar">
    <a href="{{ route('history.index', ['filter' => 'all']) }}" class="px-5 py-2 rounded-full font-label-sm whitespace-nowrap {{ $filter === 'all' ? 'bg-primary text-on-primary shadow-sm' : 'bg-secondary-container text-on-secondary-container border border-outline-variant hover:bg-surface-variant transition-colors' }}">All Activities</a>
    <a href="{{ route('history.index', ['filter' => 'completed']) }}" class="px-5 py-2 rounded-full font-label-sm whitespace-nowrap {{ $filter === 'completed' ? 'bg-primary text-on-primary shadow-sm' : 'bg-secondary-container text-on-secondary-container border border-outline-variant hover:bg-surface-variant transition-colors' }}">Completed</a>
    <a href="{{ route('history.index', ['filter' => 'skipped']) }}" class="px-5 py-2 rounded-full font-label-sm whitespace-nowrap {{ $filter === 'skipped' ? 'bg-primary text-on-primary shadow-sm' : 'bg-secondary-container text-on-secondary-container border border-outline-variant hover:bg-surface-variant transition-colors' }}">Skipped</a>
</section>

<div class="space-y-12">
    @forelse($histories as $date => $dayHistories)
    <section class="relative">
        <h2 class="font-label-sm text-outline uppercase tracking-widest mb-8">{{ \Carbon\Carbon::parse($date)->format('l — M d') }}</h2>
        <div class="space-y-0 relative">
            <div class="timeline-line"></div>
            @foreach($dayHistories as $history)
            <div class="relative flex gap-6 pb-12">
                <div class="relative z-10 flex-shrink-0">
                    @if($history->completed_at)
                    <div class="w-10 h-10 rounded-xl bg-primary text-on-primary flex items-center justify-center shadow-md">
                        <span class="material-symbols-outlined text-[20px] font-bold" style="font-variation-settings: 'FILL' 1;">task_alt</span>
                    </div>
                    @else
                    <div class="w-10 h-10 rounded-xl bg-secondary-container text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </div>
                    @endif
                </div>
                <div class="flex-grow pt-1">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-headline-md text-on-surface leading-tight @if($history->skipped_at) line-through opacity-70 text-secondary @endif">
                            @if($history->todo)
                                {{ $history->todo->title }}
                                @if($history->todo->trashed())
                                <span class="text-[10px] text-error uppercase font-semibold ml-2">· Trashed</span>
                                @endif
                            @else
                                Deleted Todo
                            @endif
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-outline font-label-sm bg-surface-container-low px-2 py-0.5 rounded">{{ $history->created_at->format('H:i') }}</span>
                            <form method="POST" action="{{ route('history.destroy', $history) }}">
                                @csrf @method('DELETE')
                                <button data-delete data-type="history" class="material-symbols-outlined text-outline text-[16px] p-1 hover:bg-error-container hover:text-error rounded-full">delete</button>
                            </form>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-on-surface-variant font-label-sm mb-4">
                        @if($history->due_at)
                        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">calendar_today</span> Due: {{ $history->due_at->format('M d') }}</span>
                        @endif
                        @if($history->completed_at)
                        <span class="flex items-center gap-1.5 text-secondary font-medium"><span class="material-symbols-outlined text-[16px]">done_all</span> Completed {{ $history->completed_at->format('M d, g:i A') }}</span>
                        @endif
                        @if($history->skipped_at)
                        <span class="flex items-center gap-1.5 text-error font-medium"><span class="material-symbols-outlined text-[16px]">block</span> Skipped {{ $history->skipped_at->format('M d, g:i A') }}</span>
                        @endif
                    </div>
                    @if($history->completion_note)
                    <div class="bg-surface-container-low p-4 border border-outline-variant rounded-xl shadow-sm">
                        <p class="text-on-surface-variant italic font-body-md">{{ $history->completion_note }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @empty
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-8 text-center">
        <span class="material-symbols-outlined text-outline text-4xl mb-3">history</span>
        <p class="text-on-surface-variant font-body-md">No history yet. Complete some todos to see them here.</p>
    </div>
    @endforelse
</div>
@endsection
