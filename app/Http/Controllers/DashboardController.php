<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Todo;
use App\Models\TodoHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $today = simulated_today();
        $currentDate = $today->format('Y-m-d');

        $weekOffset = (int) $request->get('offset', 0);
        $weekStart = $today->copy()->startOfWeek()->addWeeks($weekOffset);
        $periodEnd = $weekStart->copy()->addDays(13);

        $pinnedNotes = Note::where('is_pinned', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $todayTodos = Todo::where('status', 'active')
            ->where(function ($q) use ($today) {
                $q->whereDate('next_due_at', $today)
                    ->orWhere(function ($q2) use ($today) {
                        $q2->whereNull('next_due_at')
                           ->where('completed_count', 0);
                    });
            })
            ->orderBy('is_urgent', 'desc')
            ->orderBy('is_important', 'desc')
            ->orderBy('next_due_at')
            ->get();

        $planned = Todo::where('status', 'active')
            ->whereBetween('next_due_at', [$weekStart, $periodEnd])
            ->selectRaw('DATE(next_due_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $completed = TodoHistory::whereBetween('completed_at', [$weekStart, $periodEnd])
            ->selectRaw('DATE(completed_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $skipped = TodoHistory::whereBetween('skipped_at', [$weekStart, $periodEnd])
            ->selectRaw('DATE(skipped_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $overdue = Todo::where('status', 'active')
            ->where('next_due_at', '<', $today->startOfDay())
            ->where('next_due_at', '>=', $weekStart)
            ->selectRaw('DATE(next_due_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $rangeStart = $weekStart->copy();
        $calendarData = [];
        foreach (range(0, 13) as $offset) {
            $date = $rangeStart->copy()->addDays($offset);
            $dateStr = $date->format('Y-m-d');
            $calendarData[] = [
                'date' => $date,
                'dateStr' => $dateStr,
                'day' => $date->day,
                'dayOfWeek' => $date->dayOfWeek,
                'isToday' => $dateStr === $currentDate,
                'planned' => $planned[$dateStr]->total ?? 0,
                'completed' => $completed[$dateStr]->total ?? 0,
                'skipped' => $skipped[$dateStr]->total ?? 0,
                'overdue' => $overdue[$dateStr]->total ?? 0,
            ];
        }

        $selectedDateStr = $request->get('date', $currentDate);
        $selectedDate = Carbon::parse($selectedDateStr);

        $dayTodos = Todo::where('status', 'active')
            ->whereDate('next_due_at', $selectedDate)
            ->get();

        $dayHistories = TodoHistory::where(function ($q) use ($selectedDate) {
                $q->whereDate('completed_at', $selectedDate)
                  ->orWhereDate('skipped_at', $selectedDate);
            })
            ->with('todo')
            ->get();

        $overdueTodos = Todo::where('status', 'active')
            ->where('next_due_at', '<', $today->startOfDay())
            ->whereDate('next_due_at', $selectedDate)
            ->get();

        $weekLabel = $weekStart->format('M d') . ' — ' . $periodEnd->format('M d, Y');

        return view('dashboard', compact(
            'pinnedNotes', 'todayTodos', 'currentDate',
            'weekOffset', 'weekStart', 'calendarData', 'weekLabel',
            'selectedDate', 'selectedDateStr', 'dayTodos', 'dayHistories', 'overdueTodos'
        ));
    }

    public function setToday(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        session(['simulated_today' => $request->date]);

        return redirect()->route('dashboard');
    }

    public function resetToday(): RedirectResponse
    {
        session()->forget('simulated_today');

        return redirect()->route('dashboard');
    }
}
