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

        $month = $request->get('month', $today->format('Y-m'));
        $monthDate = Carbon::parse($month . '-01');
        $monthStart = $monthDate->copy()->startOfMonth();
        $monthEnd = $monthDate->copy()->endOfMonth();

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
            ->whereBetween('next_due_at', [$monthStart, $monthEnd])
            ->selectRaw('DATE(next_due_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $completed = TodoHistory::whereBetween('completed_at', [$monthStart, $monthEnd])
            ->selectRaw('DATE(completed_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $skipped = TodoHistory::whereBetween('skipped_at', [$monthStart, $monthEnd])
            ->selectRaw('DATE(skipped_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $overdue = Todo::where('status', 'active')
            ->where('next_due_at', '<', $today->startOfDay())
            ->where(function ($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('next_due_at', [$monthStart, $monthEnd]);
            })
            ->selectRaw('DATE(next_due_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $calendarData = [];
        foreach (range(1, $monthEnd->day) as $day) {
            $date = $monthDate->copy()->day($day)->format('Y-m-d');
            $calendarData[$day] = [
                'planned' => $planned[$date]->total ?? 0,
                'completed' => $completed[$date]->total ?? 0,
                'skipped' => $skipped[$date]->total ?? 0,
                'overdue' => $overdue[$date]->total ?? 0,
            ];
        }

        $selectedDay = $request->get('day', $today->day);
        $selectedDate = $monthDate->copy()->day(min($selectedDay, $monthEnd->day));

        $dayTodos = Todo::where('status', 'active')
            ->whereDate('next_due_at', $selectedDate)
            ->get();

        $dayHistories = TodoHistory::whereDate('completed_at', $selectedDate)
            ->orWhereDate('skipped_at', $selectedDate)
            ->with('todo')
            ->get();

        return view('dashboard', compact(
            'pinnedNotes', 'todayTodos', 'currentDate',
            'monthDate', 'monthStart', 'monthEnd', 'calendarData',
            'selectedDate', 'dayTodos', 'dayHistories'
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
