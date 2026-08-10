<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PickupAppointmentController; // 👈 Importamos el controlador

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// 📅 RUTAS DE FAMILYCENTRAL (Protegidas por autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/agenda', [PickupAppointmentController::class, 'index'])->name('agenda.index');
    Route::post('/agenda', [PickupAppointmentController::class, 'store'])->name('agenda.store');
    Route::patch('/agenda/{appointment}/tracking', [PickupAppointmentController::class, 'updateTracking'])->name('agenda.updateTracking');
});

require __DIR__.'/auth.php';