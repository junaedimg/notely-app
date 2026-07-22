<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Todo;
use App\Models\TodoHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TodoController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'active');

        $todos = Todo::when($tab === 'active', function ($q) {
                return $q->where('status', 'active');
            })
            ->when($tab === 'paused', function ($q) {
                return $q->where('status', 'paused');
            })
            ->when($tab === 'archived', function ($q) {
                return $q->where('status', 'archived');
            })
            ->orderBy('next_due_at')
            ->orderBy('is_urgent', 'desc')
            ->orderBy('is_important', 'desc')
            ->get();

        return view('todos.index', compact('todos', 'tab'));
    }

    public function create(): View
    {
        $notes = Note::orderBy('title')->get();
        return view('todos.create', compact('notes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'note_id' => 'nullable|exists:notes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_important' => 'boolean',
            'is_urgent' => 'boolean',
            'repeat_type' => 'string|in:none,daily,weekly,monthly,yearly,interval',
            'interval_value' => 'nullable|integer|min:1',
            'interval_unit' => 'nullable|string|in:day,week,month,year',
            'days_of_week' => 'nullable|array',
            'day_of_month' => 'nullable|integer|min:1|max:31',
            'month_of_year' => 'nullable|integer|min:1|max:12',
            'repeat_anchor' => 'string|in:schedule,completion',
            'end_type' => 'string|in:never,date,count',
            'end_date' => 'nullable|date',
            'end_count' => 'nullable|integer|min:1',
            'next_due_at' => 'nullable|date',
            'reminder_time' => 'nullable|string|max:5',
        ]);

        Todo::create($data);

        return redirect()->route('todos.index');
    }

    public function show(Todo $todo): View
    {
        return view('todos.show', compact('todo'));
    }

    public function edit(Todo $todo): View
    {
        $notes = Note::orderBy('title')->get();
        return view('todos.edit', compact('todo', 'notes'));
    }

    public function update(Request $request, Todo $todo): RedirectResponse
    {
        $data = $request->validate([
            'note_id' => 'nullable|exists:notes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'string|in:active,paused,archived',
            'is_important' => 'boolean',
            'is_urgent' => 'boolean',
            'repeat_type' => 'string|in:none,daily,weekly,monthly,yearly,interval',
            'interval_value' => 'nullable|integer|min:1',
            'interval_unit' => 'nullable|string|in:day,week,month,year',
            'days_of_week' => 'nullable|array',
            'day_of_month' => 'nullable|integer|min:1|max:31',
            'month_of_year' => 'nullable|integer|min:1|max:12',
            'repeat_anchor' => 'string|in:schedule,completion',
            'end_type' => 'string|in:never,date,count',
            'end_date' => 'nullable|date',
            'end_count' => 'nullable|integer|min:1',
            'next_due_at' => 'nullable|date',
            'reminder_time' => 'nullable|string|max:5',
        ]);

        $todo->update($data);

        return redirect()->route('todos.index');
    }

    public function destroy(Todo $todo): RedirectResponse
    {
        $todo->delete();

        return redirect()->route('todos.index');
    }

    public function complete(Request $request, Todo $todo): RedirectResponse
    {
        $request->validate([
            'completion_note' => 'nullable|string',
        ]);

        TodoHistory::create([
            'todo_id' => $todo->id,
            'due_at' => $todo->next_due_at,
            'completed_at' => now(),
            'completion_note' => $request->completion_note,
        ]);

        $todo->increment('completed_count');

        $todo->next_due_at = $this->calculateNextDue($todo);
        $todo->save();

        return redirect()->route('todos.index');
    }

    public function skip(Todo $todo): RedirectResponse
    {
        TodoHistory::create([
            'todo_id' => $todo->id,
            'due_at' => $todo->next_due_at,
            'skipped_at' => now(),
        ]);

        $todo->next_due_at = $this->calculateNextDue($todo);
        $todo->save();

        return redirect()->route('todos.index');
    }

    private function calculateNextDue(Todo $todo): ?string
    {
        if ($todo->repeat_type === 'none') {
            return null;
        }

        $anchor = $todo->repeat_anchor === 'completion'
            ? now()
            : ($todo->next_due_at ?? now());

        $next = match ($todo->repeat_type) {
            'daily' => $anchor->copy()->addDay(),
            'weekly' => $anchor->copy()->addWeek(),
            'monthly' => $anchor->copy()->addMonth(),
            'yearly' => $anchor->copy()->addYear(),
            'interval' => $anchor->copy()->add(
                $todo->interval_value,
                $todo->interval_unit ?? 'month'
            ),
            default => null,
        };

        if ($todo->end_type === 'date' && $next && $todo->end_date && $next > $todo->end_date) {
            return null;
        }

        if ($todo->end_type === 'count' && $todo->completed_count >= ($todo->end_count ?? 999)) {
            return null;
        }

        return $next;
    }
}
