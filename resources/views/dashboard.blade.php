@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="mb-10">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-1 block">Workspace</span>
            <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Focus</h2>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 w-full md:w-auto">
            <div class="flex flex-wrap items-center gap-2">
                <span class="material-symbols-outlined text-primary text-sm shrink-0">calendar_today</span>
                <form method="POST" action="{{ route('dashboard.set-today') }}" class="flex items-center gap-1 flex-1 md:flex-none min-w-0" id="date-form">
                    @csrf
                    <button type="button" onclick="adjustDate(-1)" class="material-symbols-outlined text-secondary hover:text-primary active:scale-90 transition-all p-1 text-sm shrink-0" title="Previous day">chevron_left</button>
                    <input type="date" name="date" id="date-input" value="{{ $currentDate }}"
                        class="font-body-md text-sm bg-transparent border-none outline-none text-on-surface w-0 min-w-[100px] flex-1 md:flex-none md:w-[130px] text-center [&::-webkit-calendar-picker-indicator]:opacity-50 [&::-webkit-calendar-picker-indicator]:cursor-pointer">
                    <button type="button" onclick="adjustDate(1)" class="material-symbols-outlined text-secondary hover:text-primary active:scale-90 transition-all p-1 text-sm shrink-0" title="Next day">chevron_right</button>
                </form>
                <script>
                    function adjustDate(days) {
                        const input = document.getElementById('date-input');
                        const d = new Date(input.value + 'T12:00:00');
                        d.setDate(d.getDate() + days);
                        input.value = d.toISOString().split('T')[0];
                        document.getElementById('date-form').submit();
                    }
                </script>
            </div>
            <div class="flex items-center gap-2 mt-2 md:mt-0 md:ml-auto">
                <button type="submit" form="date-form" class="bg-primary text-on-primary px-3 py-1 rounded-lg font-label-sm text-[11px] hover:brightness-110 active:scale-95 transition-all">Set</button>
                <form method="POST" action="{{ route('dashboard.reset-today') }}" class="flex">
                    @csrf
                    <button type="submit" class="text-secondary font-label-sm text-[11px] hover:text-primary active:scale-95 transition-all px-2 py-1" title="Reset to real today">Reset</button>
                </form>
            </div>
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
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-md text-headline-md text-on-surface">{{ $monthDate->format('F Y') }}</h3>
            <div class="flex gap-2">
                <a href="{{ route('dashboard', ['month' => $monthDate->copy()->subMonth()->format('Y-m'), 'day' => request('day')]) }}" class="material-symbols-outlined p-1 hover:bg-surface-container-low rounded-full transition-colors text-secondary">chevron_left</a>
                <a href="{{ route('dashboard', ['month' => $monthDate->copy()->addMonth()->format('Y-m'), 'day' => request('day')]) }}" class="material-symbols-outlined p-1 hover:bg-surface-container-low rounded-full transition-colors text-secondary">chevron_right</a>
            </div>
        </div>

        <div class="grid grid-cols-7 gap-1 mb-6">
            @foreach(['S','M','T','W','T','F','S'] as $dayHeader)
            <div class="text-center font-label-sm text-secondary py-2 text-[11px]">{{ $dayHeader }}</div>
            @endforeach

            @php
                $startDayOfWeek = $monthStart->dayOfWeek;
                $totalDays = $monthEnd->day;
                $todayDate = simulated_today()->format('Y-m-d');
                $selectedDayNum = (int) request('day', simulated_today()->day);
            @endphp

            @for($i = 0; $i < $startDayOfWeek; $i++)
            <div></div>
            @endfor

            @foreach(range(1, $totalDays) as $day)
                @php
                    $cellDate = $monthDate->copy()->day($day)->format('Y-m-d');
                    $data = $calendarData[$day] ?? ['planned' => 0, 'completed' => 0, 'skipped' => 0, 'overdue' => 0];
                    $isToday = $cellDate === $todayDate;
                    $isSelected = $day === $selectedDayNum && $monthDate->format('Y-m') === request('month', simulated_today()->format('Y-m'));
                    $hasActivity = $data['planned'] > 0 || $data['completed'] > 0 || $data['skipped'] > 0 || $data['overdue'] > 0;
                    $totalTasks = $data['planned'] + $data['completed'] + $data['skipped'];
                @endphp
                <a href="{{ route('dashboard', ['month' => $monthDate->format('Y-m'), 'day' => $day]) }}"
                    class="aspect-square flex flex-col items-center justify-center rounded-lg border transition-all cursor-pointer relative group
                    {{ $isToday ? 'border-2 border-primary' : 'border-transparent hover:border-primary hover:-translate-y-0.5' }}
                    {{ $isSelected && !$isToday ? 'bg-primary-fixed border-primary' : '' }}
                    {{ !$isToday && !$isSelected ? 'hover:bg-surface-container-low' : '' }}">
                    <span class="text-sm {{ $isToday ? 'font-bold text-primary' : ($isSelected ? 'text-primary' : 'text-on-surface') }}">{{ $day }}</span>
                    @if($hasActivity)
                    <div class="absolute bottom-1 right-1 flex gap-0.5">
                        @if($data['planned'] > 0)<div class="w-1 h-1 rounded-full bg-primary"></div>@endif
                        @if($data['completed'] > 0)<div class="w-1 h-1 rounded-full bg-green-500"></div>@endif
                        @if($data['skipped'] > 0)<div class="w-1 h-1 rounded-full bg-orange-500"></div>@endif
                        @if($data['overdue'] > 0)<div class="w-1 h-1 rounded-full bg-error"></div>@endif
                    </div>
                    @endif
                    @if($totalTasks > 0)
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-inverse-surface text-inverse-on-surface text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-20 shadow-lg">
                        {{ $totalTasks }} task{{ $totalTasks > 1 ? 's' : '' }} | {{ $data['completed'] }} done
                    </div>
                    @endif
                </a>
            @endforeach
        </div>

        @if($selectedDate->format('Y-m') === $monthDate->format('Y-m'))
        <div class="mt-4 p-4 bg-surface-container-low rounded-xl border border-outline-variant">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-headline-md text-sm font-bold text-on-surface">Tasks for {{ $selectedDate->format('M d, Y') }}</h4>
                <span class="text-[10px] font-medium text-secondary uppercase tracking-wider">{{ $dayTodos->count() + $dayHistories->count() }} items</span>
            </div>
            <div class="space-y-3">
                @forelse($dayTodos as $todo)
                <div class="flex items-center justify-between py-2 border-b border-outline-variant last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-primary"></div>
                        <div>
                            <p class="text-sm font-medium text-on-surface">{{ $todo->title }}</p>
                            <span class="font-label-sm text-[10px] {{ $todo->quadrant === 'do' ? 'text-error' : ($todo->quadrant === 'plan' ? 'text-primary' : 'text-secondary') }} uppercase font-semibold">
                                {{ $todo->quadrant === 'do' ? 'Urgent & Important' : ($todo->quadrant === 'plan' ? 'Important' : ($todo->quadrant === 'delegate' ? 'Urgent' : 'Eliminate')) }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('todos.edit', $todo) }}" class="material-symbols-outlined text-secondary text-sm hover:text-primary p-1 rounded-full hover:bg-surface-container-low transition-all">more_vert</a>
                </div>
                @empty
                @foreach($dayHistories as $history)
                <div class="flex items-center justify-between py-2 border-b border-outline-variant last:border-0">
                    <div class="flex items-center gap-3">
                        @if($history->completed_at)
                        <span class="material-symbols-outlined text-[14px] text-green-500">check</span>
                        @elseif($history->skipped_at)
                        <span class="material-symbols-outlined text-[14px] text-orange-500">arrow_forward</span>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-on-surface">{{ $history->todo?->title ?? 'Deleted todo' }}</p>
                            <span class="font-label-sm text-[10px] text-secondary uppercase font-semibold">
                                {{ $history->completed_at ? 'Completed' : 'Skipped' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
                @if($dayTodos->isEmpty() && $dayHistories->isEmpty())
                <p class="text-sm text-secondary text-center py-4">No tasks for this day</p>
                @endif
                @endforelse
            </div>
        </div>
        @endif

        <div class="flex flex-wrap gap-4 pt-4 border-t border-outline-variant mt-4">
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-primary"></div>
                <span class="text-[10px] font-medium text-secondary uppercase tracking-wider">Planned</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[12px] text-green-500">check</span>
                <span class="text-[10px] font-medium text-secondary uppercase tracking-wider">Done</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[12px] text-orange-500">arrow_forward</span>
                <span class="text-[10px] font-medium text-secondary uppercase tracking-wider">Skipped</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[12px] text-error">close</span>
                <span class="text-[10px] font-medium text-secondary uppercase tracking-wider">Overdue</span>
            </div>
        </div>
    </div>

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
