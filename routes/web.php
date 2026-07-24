<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('single-user')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('dashboard/set-today', [DashboardController::class, 'setToday'])->name('dashboard.set-today');
    Route::post('dashboard/reset-today', [DashboardController::class, 'resetToday'])->name('dashboard.reset-today');

    Route::resource('notes', NoteController::class);
    Route::post('notes/{note}/toggle-pin', [NoteController::class, 'togglePin'])->name('notes.toggle-pin');

    Route::resource('todos', TodoController::class);
    Route::post('todos/{todo}/complete', [TodoController::class, 'complete'])->name('todos.complete');
    Route::post('todos/{todo}/skip', [TodoController::class, 'skip'])->name('todos.skip');

    Route::get('history', [HistoryController::class, 'index'])->name('history.index');
    Route::delete('history/{history}', [HistoryController::class, 'destroy'])->name('history.destroy');
});
