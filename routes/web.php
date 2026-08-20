<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PickupAppointmentController; // 👈 Importamos el controlador
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PdfReportController;
use App\Models\PickupAppointment;

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// 📅 RUTAS DE FAMILYCENTRAL (Protegidas por autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
    // 1. Métricas para los cuadros superiores
    $totalPickups = PickupAppointment::count();
    $pendingPickups = PickupAppointment::where('status', 'pending')->count();
    $completedPickups = PickupAppointment::where('status', 'completed')->count();

    // 2. Historial reciente (Últimos 5 envíos/recolecciones)
    $recentPickups = PickupAppointment::latest()->take(5)->get();

    return view('dashboard', compact('totalPickups', 'pendingPickups', 'completedPickups', 'recentPickups'));
})->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('/agenda', [PickupAppointmentController::class, 'index'])->name('agenda.index');
    Route::post('/agenda', [PickupAppointmentController::class, 'store'])->name('agenda.store');
    Route::patch('/agenda/{appointment}/tracking', [PickupAppointmentController::class, 'updateTracking'])->name('agenda.updateTracking');
    Route::post('/agenda/{appointment}/invoice', [PickupAppointmentController::class, 'uploadInvoice'])->name('agenda.uploadInvoice');
    Route::get('/historial', [HistoryController::class, 'index'])->name('history.index');
    Route::delete('/historial/{appointment}', [HistoryController::class, 'destroy'])->name('history.destroy');
    Route::get('/agenda/{appointment}/pdf', [PdfReportController::class, 'generateAppointmentPdf'])->name('agenda.pdf');

Route::get('/monitoreo', function () {
    return view('tracking.map');
})->middleware(['auth'])->name('tracking.map');

    });

    require __DIR__.'/auth.php';