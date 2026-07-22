<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Todo;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $pinnedNotes = Note::where('is_pinned', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $todayTodos = Todo::where('status', 'active')
            ->where(function ($q) {
                $q->whereDate('next_due_at', today())
                    ->orWhere(function ($q2) {
                        $q2->whereNull('next_due_at')
                           ->where('completed_count', 0);
                    });
            })
            ->orderBy('is_urgent', 'desc')
            ->orderBy('is_important', 'desc')
            ->orderBy('next_due_at')
            ->get();

        return view('dashboard', compact('pinnedNotes', 'todayTodos'));
    }
}
