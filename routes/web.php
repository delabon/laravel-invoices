<?php

declare(strict_types=1);

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Welcome')
    ->name('home');

Route::prefix('register')->name('register')->group(function () {
    Route::get('/', [RegisterController::class, 'create'])
        ->middleware(['guest']);
    Route::post('/', [RegisterController::class, 'store'])
        ->middleware(['guest', 'throttle:5,1'])
        ->name('.store');
});

Route::inertia('login', 'auth/Login')
    ->name('login');
Route::post('login', function () {})
    ->name('login.store');
Route::get('logout', function () {})
    ->name('logout');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
