<?php

namespace App\Http\Controllers;

use App\Models\TodoHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->get('filter', 'all');

        $histories = TodoHistory::with(['todo' => function ($q) {
                $q->withTrashed();
            }])
            ->when($filter === 'completed', function ($q) {
                return $q->whereNotNull('completed_at');
            })
            ->when($filter === 'skipped', function ($q) {
                return $q->whereNotNull('skipped_at');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });

        return view('history.index', compact('histories', 'filter'));
    }

    public function destroy(TodoHistory $history): RedirectResponse
    {
        $history->delete();

        return redirect()->route('history.index');
    }
}
