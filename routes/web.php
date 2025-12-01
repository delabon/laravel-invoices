<?php

declare(strict_types=1);

use App\Http\Controllers\Register;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Welcome')
    ->name('home');

Route::prefix('register')->name('register')->group(function () {
    Route::get('/', [Register::class, 'create']);
    Route::post('/', [Register::class, 'store'])
        ->middleware(['guest', 'throttle:5,1'])
        ->name('.store');
});

Route::get('login', function () {})
    ->name('login');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
