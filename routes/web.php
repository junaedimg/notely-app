<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('notes', NoteController::class);

Route::resource('todos', TodoController::class);
Route::post('todos/{todo}/complete', [TodoController::class, 'complete'])->name('todos.complete');
Route::post('todos/{todo}/skip', [TodoController::class, 'skip'])->name('todos.skip');

Route::get('history', [HistoryController::class, 'index'])->name('history.index');
Route::delete('history/{history}', [HistoryController::class, 'destroy'])->name('history.destroy');
