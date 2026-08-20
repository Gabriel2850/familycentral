<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PickupAppointmentController; // 👈 Importamos el controlador
use App\Http\Controllers\HistoryController;

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
    Route::post('/agenda/{appointment}/invoice', [PickupAppointmentController::class, 'uploadInvoice'])->name('agenda.uploadInvoice');
    Route::get('/historial', [HistoryController::class, 'index'])->name('history.index');
    Route::delete('/historial/{appointment}', [HistoryController::class, 'destroy'])->name('history.destroy');
});

require __DIR__.'/auth.php';