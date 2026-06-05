<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('todos.index') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('register', fn () => redirect()->route('login'))->name('register');
    Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::get('pokemon', function () {
    return view('pokemon');
})->name('pokemon');

Route::get('pokemon/{id}', function ($id) {
    return view('pokemon-show', ['id' => $id]);
})->name('pokemon.show');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::patch('todos/{todo}/checklist', [TodoController::class, 'checklist'])->name('todos.checklist');
    Route::resource('todos', TodoController::class);
});
