@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="mb-4 lg:mb-6">
    <div>
        <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-1 block">Deep Work Session</span>
        <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Focus</h2>
    </div>
</section>

@if($pinnedNotes->count() > 0)
<section class="mb-4 lg:mb-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-sm">push_pin</span>
            <h3 class="font-label-sm text-label-sm text-secondary uppercase tracking-widest">Pinned Notes</h3>
        </div>
        <a href="{{ route('notes.index') }}" class="text-primary font-label-sm text-label-sm hover:underline">View all</a>
    </div>
    <div class="flex gap-gutter overflow-x-auto hide-scrollbar pb-4 snap-x">
        @foreach($pinnedNotes as $note)
        <div class="min-w-[280px] md:min-w-[340px] snap-start shrink-0">
            <x-note-card :note="$note" />
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- Today's Actions --}}
<section class="mb-4 lg:mb-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-sm">task_alt</span>
            <h3 class="font-label-sm text-label-sm text-secondary uppercase tracking-widest">Today's Actions</h3>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-primary-fixed text-primary px-3 py-1 rounded-full font-label-sm text-[10px] font-bold">{{ $todayTodos->count() }} TASKS</span>
            <a href="{{ route('todos.index') }}" class="text-primary font-label-sm text-label-sm hover:underline">Manage</a>
        </div>
    </div>
    <div class="space-y-2.5 max-w-[720px]">
        @forelse($todayTodos as $todo)
        <div class="bg-white p-4 border border-outline-variant rounded-lg shadow-sm hover:shadow-md transition-shadow group border-l-quadrant-{{ \App\View\Components\Quadrant::get($todo->quadrant)['class'] }}">
            <div class="flex justify-between items-start mb-1.5">
                <div class="flex gap-2">
                    <x-quadrant :quadrant="$todo->quadrant" />
                </div>
                @if($todo->repeat_type !== 'none')
                <div class="flex items-center gap-1 text-on-surface-variant group-hover:text-primary shrink-0">
                    <span class="material-symbols-outlined text-[14px]">refresh</span>
                    <span class="font-label-sm text-[9px] capitalize">{{ $todo->repeat_type }} {{ $todo->quadrant }}</span>
                </div>
                @endif
            </div>

            <h3 class="font-headline-md text-[15px] font-semibold text-on-surface mb-1">{{ $todo->title }}</h3>

            <div class="flex items-center gap-3 text-on-surface-variant font-label-sm text-[11px]">
                @if($todo->next_due_at)
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">calendar_today</span>
                    <span>{{ $todo->next_due_at->format('M d, g:i A') }}</span>
                </div>
                @endif
                <div class="flex items-center gap-1 ml-auto">
                    <button data-skip data-action="{{ route('todos.skip', $todo) }}" data-title="{{ $todo->title }}" class="material-symbols-outlined text-secondary text-[16px] p-0.5 hover:bg-surface-container-low rounded-full" title="Skip">skip_next</button>
                    <button data-complete data-action="{{ route('todos.complete', $todo) }}" data-title="{{ $todo->title }}" class="material-symbols-outlined text-secondary text-[16px] p-0.5 hover:bg-primary-fixed rounded-full hover:text-primary" title="Complete">check_circle</button>
                </div>
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

{{-- Schedule Overview --}}
<section class="mb-4 lg:mb-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-sm">calendar_month</span>
            <h3 class="font-label-sm text-label-sm text-secondary uppercase tracking-widest">Schedule Overview</h3>
        </div>
    </div>
    <div id="calendar-section" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 shadow-sm max-w-[720px]">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-headline-md text-sm font-bold text-on-surface">{{ $weekLabel }}</h3>
            <div class="flex gap-1">
                <a href="{{ route('dashboard', ['offset' => $weekOffset - 2, 'date' => request('date')]) }}#calendar-section" class="material-symbols-outlined p-0.5 hover:bg-surface-container-low rounded-full transition-colors text-secondary text-sm">chevron_left</a>
                <a href="{{ route('dashboard', ['offset' => $weekOffset + 2, 'date' => request('date')]) }}#calendar-section" class="material-symbols-outlined p-0.5 hover:bg-surface-container-low rounded-full transition-colors text-secondary text-sm">chevron_right</a>
            </div>
        </div>

        <div class="grid grid-cols-7 gap-0.5 mb-2">
            @foreach(['S','M','T','W','T','F','S'] as $dayHeader)
            <div class="text-center font-label-sm text-secondary text-[10px] py-1">{{ $dayHeader }}</div>
            @endforeach

            @foreach($calendarData as $index => $cell)
            @if($index === 7)
        </div>
        <div class="grid grid-cols-7 gap-0.5 mb-3">
            @endif
            @php
                $hasActivity = $cell['planned'] > 0 || $cell['completed'] > 0 || $cell['skipped'] > 0 || $cell['overdue'] > 0;
                $totalTasks = $cell['planned'] + $cell['completed'] + $cell['skipped'];
            @endphp
            <a href="{{ route('dashboard', ['offset' => $weekOffset, 'date' => $cell['dateStr']]) }}#calendar-section"
                class="h-8 flex flex-col items-center justify-center rounded-md border transition-all cursor-pointer relative group text-[11px]
                {{ $cell['isToday'] ? 'border-2 border-primary font-bold text-primary' : 'border-transparent hover:border-primary hover:bg-surface-container-low' }}
                {{ $cell['dateStr'] === $selectedDateStr && !$cell['isToday'] ? 'bg-primary-fixed border-primary font-semibold text-primary' : '' }}
                {{ !$cell['isToday'] && $cell['dateStr'] !== $selectedDateStr ? 'text-on-surface' : '' }}">
                <span>{{ $cell['day'] }}</span>
                @if($hasActivity)
                <div class="flex gap-0.5 absolute bottom-0.5">
                    @if($cell['planned'] > 0)<div class="w-1 h-1 rounded-full bg-primary"></div>@endif
                    @if($cell['completed'] > 0)<div class="w-1 h-1 rounded-full bg-green-500"></div>@endif
                    @if($cell['skipped'] > 0)<div class="w-1 h-1 rounded-full bg-orange-500"></div>@endif
                    @if($cell['overdue'] > 0)<div class="w-1 h-1 rounded-full bg-error"></div>@endif
                </div>
                @endif
                @if($totalTasks > 0)
                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-inverse-surface text-inverse-on-surface text-[9px] rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-20 shadow-lg">
                    {{ $totalTasks }} task{{ $totalTasks > 1 ? 's' : '' }} | {{ $cell['completed'] }} done
                </div>
                @endif
            </a>
            @endforeach
        </div>

        @if(strtotime($selectedDateStr) >= strtotime($calendarData[0]['dateStr']) && strtotime($selectedDateStr) <= strtotime($calendarData[13]['dateStr']))
        <div class="mt-2 p-3 bg-surface-container-low rounded-xl border border-outline-variant">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-xs font-bold text-on-surface">{{ $selectedDate->format('M d, Y') }}</h4>
                <span class="text-[9px] font-medium text-secondary uppercase tracking-wider">{{ $dayTodos->count() + $dayHistories->count() + $overdueTodos->count() }} items</span>
            </div>
            <div class="space-y-1.5">
                @if($dayTodos->isNotEmpty())
                @foreach($dayTodos as $todo)
                <div class="flex items-start gap-2.5 py-1.5 border-b border-outline-variant last:border-0">
                    <span class="flex items-center justify-center w-3.5 h-3.5 mt-0.5 shrink-0">
                        <span class="w-2 h-2 rounded-full bg-primary block"></span>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-on-surface">{{ $todo->title }}</p>
                        <x-quadrant :quadrant="$todo->quadrant" class="mt-0.5" />
                    </div>
                </div>
                @endforeach
                @endif
                @if($overdueTodos->isNotEmpty())
                @foreach($overdueTodos as $todo)
                <div class="flex items-start gap-2.5 py-1.5 border-b border-outline-variant last:border-0">
                    <span class="flex items-center justify-center w-3.5 h-3.5 mt-0.5 shrink-0">
                        <span class="material-symbols-outlined text-[14px] text-error leading-none">close</span>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-on-surface line-through opacity-60">{{ $todo->title }}</p>
                        <span class="text-[9px] text-error uppercase font-semibold">Overdue</span>
                    </div>
                </div>
                @endforeach
                @endif
                @if($dayHistories->isNotEmpty())
                @foreach($dayHistories as $history)
                <div class="flex items-start gap-2.5 py-1.5 border-b border-outline-variant last:border-0">
                    <span class="flex items-center justify-center w-3.5 h-3.5 mt-0.5 shrink-0">
                        @if($history->completed_at)
                        <span class="material-symbols-outlined text-[14px] text-green-500 leading-none">check</span>
                        @elseif($history->skipped_at)
                        <span class="material-symbols-outlined text-[14px] text-orange-500 leading-none">arrow_forward</span>
                        @endif
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-on-surface">{{ $history->todo?->title ?? 'Deleted todo' }}</p>
                        <span class="text-[9px] text-secondary uppercase font-semibold">
                            {{ $history->completed_at ? 'Completed' : 'Skipped' }}
                        </span>
                    </div>
                </div>
                @endforeach
                @endif
                @if($dayTodos->isEmpty() && $dayHistories->isEmpty())
                <p class="text-[11px] text-secondary text-center py-2">No tasks for this day</p>
                @endif
            </div>
        </div>
        @endif

        <div class="flex flex-wrap gap-3 pt-3 border-t border-outline-variant mt-3">
            <div class="flex items-center gap-1">
                <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                <span class="text-[9px] font-medium text-secondary uppercase tracking-wider">Planned</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[10px] text-green-500">check</span>
                <span class="text-[9px] font-medium text-secondary uppercase tracking-wider">Done</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[10px] text-orange-500">arrow_forward</span>
                <span class="text-[9px] font-medium text-secondary uppercase tracking-wider">Skipped</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[10px] text-error">close</span>
                <span class="text-[9px] font-medium text-secondary uppercase tracking-wider">Overdue</span>
            </div>
        </div>
    </div>
</section>
@endsection
