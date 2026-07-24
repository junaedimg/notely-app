<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrashController extends Controller
{
    public function index(): View
    {
        $notes = Note::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        $todos = Todo::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        return view('trash.index', compact('notes', 'todos'));
    }

    public function restoreNote(int $id): RedirectResponse
    {
        $note = Note::onlyTrashed()->findOrFail($id);
        $note->restore();

        return redirect()->route('trash.index')->with('success', 'Note restored.');
    }

    public function restoreTodo(int $id): RedirectResponse
    {
        $todo = Todo::onlyTrashed()->findOrFail($id);
        $todo->restore();

        return redirect()->route('trash.index')->with('success', 'Todo restored.');
    }

    public function forceDeleteNote(int $id): RedirectResponse
    {
        $note = Note::onlyTrashed()->findOrFail($id);
        $note->forceDelete();

        return redirect()->route('trash.index')->with('success', 'Note permanently deleted.');
    }

    public function forceDeleteTodo(int $id): RedirectResponse
    {
        $todo = Todo::onlyTrashed()->findOrFail($id);
        $todo->forceDelete();

        return redirect()->route('trash.index')->with('success', 'Todo permanently deleted.');
    }
}
