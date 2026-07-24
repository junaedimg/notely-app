<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = simulated_today();

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

        $currentDate = $today->format('Y-m-d');

        return view('dashboard', compact('pinnedNotes', 'todayTodos', 'currentDate'));
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
