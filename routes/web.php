<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('pokemon', function () {
    return view('pokemon');
})->name('pokemon');

Route::get('pokemon/{id}', function ($id) {
    return view('pokemon-show', ['id' => $id]);
})->name('pokemon.show');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('todos', TodoController::class);
});
