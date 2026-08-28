<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\PickupAppointmentController;
use App\Http\Controllers\HistoryController; 
use App\Http\Controllers\PdfReportController;

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// 📅 RUTAS DE FAMILYCENTRAL (Protegidas por autenticación)
Route::middleware(['auth'])->group(function () {
    
    // 📊 Dashboard (Reemplaza la función anónima previa)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    // 🗓️ Agenda de Recolecciones
    Route::get('/agenda', [PickupAppointmentController::class, 'index'])->name('agenda.index');
    Route::post('/agenda', [PickupAppointmentController::class, 'store'])->name('agenda.store');
    Route::patch('/agenda/{appointment}/tracking', [PickupAppointmentController::class, 'updateTracking'])->name('agenda.updateTracking');
    Route::post('/agenda/{appointment}/invoice', [PickupAppointmentController::class, 'uploadInvoice'])->name('agenda.uploadInvoice');
    
    // 📜 Historial y Eliminación
    Route::get('/historial', [HistoryController::class, 'index'])->name('history.index');
    Route::delete('/historial/{appointment}', [HistoryController::class, 'destroy'])->name('history.destroy');
    
    // 📄 Reportes PDF
    Route::get('/agenda/{appointment}/pdf', [PdfReportController::class, 'generateAppointmentPdf'])->name('agenda.pdf');

    // 📍 Monitoreo GPS
    Route::get('/monitoreo', function () {
        return view('tracking.map');
    })->name('tracking.map');

});

require __DIR__.'/auth.php';