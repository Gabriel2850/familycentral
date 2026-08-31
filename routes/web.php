<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\PickupAppointmentController;
use App\Http\Controllers\HistoryController; 
use App\Http\Controllers\PdfReportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminReportController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\SecurityQuestionResetController;
use App\Http\Controllers\TwoFactorController;


Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Vista para ingresar el código 2FA en el login
Route::get('/2fa/challenge', [TwoFactorController::class, 'showChallenge'])->name('2fa.challenge');

// Procesar la verificación del código
Route::post('/2fa/challenge', [TwoFactorController::class, 'verifyChallenge'])->name('2fa.challenge.verify');

// 📅 RUTAS PROTEGIDAS POR AUTENTICACIÓN (Usuarios Logueados)
Route::middleware(['auth'])->group(function () {

    // 🔐 Seguridad 2FA y Preguntas
    Route::post('/perfil/preguntas-seguridad', [SecurityQuestionResetController::class, 'storeUserQuestions'])->name('profile.security_questions');
    Route::get('/perfil/2fa', [TwoFactorController::class, 'showSetup'])->name('2fa.setup');
    Route::post('/perfil/2fa/activar', [TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::delete('/perfil/2fa/desactivar', [TwoFactorController::class, 'disable'])->name('2fa.disable');
    
    // 📊 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    // 🗓️ Agenda de Recolecciones
    Route::get('/agenda', [PickupAppointmentController::class, 'index'])->name('agenda.index');
    Route::post('/agenda', [PickupAppointmentController::class, 'store'])->name('agenda.store');
    Route::put('/agenda/{appointment}', [PickupAppointmentController::class, 'update'])->name('agenda.update');
    Route::delete('/agenda/{appointment}', [PickupAppointmentController::class, 'destroy'])->name('agenda.destroy');
    Route::patch('/agenda/{appointment}/tracking', [PickupAppointmentController::class, 'updateTracking'])->name('agenda.updateTracking');
    Route::post('/agenda/{appointment}/invoice', [PickupAppointmentController::class, 'uploadInvoice'])->name('agenda.uploadInvoice');
    
    // 📜 Historial y Cancelación de Envíos
    Route::get('/historial', [HistoryController::class, 'index'])->name('history.index');
    Route::patch('/historial/{appointment}/cancel', [HistoryController::class, 'cancel'])->name('history.cancel');
    
    // 📄 Reportes PDF Individuales
    Route::get('/agenda/{appointment}/pdf', [PdfReportController::class, 'generateAppointmentPdf'])->name('agenda.pdf');

    // 📍 Monitoreo GPS
    Route::get('/monitoreo', function () {
        return view('tracking.map');
    })->name('tracking.map');

    // 🛡️ PANEL DE ADMINISTRACIÓN (Exclusivo Admin)
    Route::middleware([EnsureUserIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::put('/users/{user}/password', [AdminUserController::class, 'updatePassword'])->name('users.password');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::post('/reports/pdf', [AdminReportController::class, 'downloadGlobalPdf'])->name('reports.pdf');
    });

});

// 🔑 RUTAS PÚBLICAS Y INICIO DE SESIÓN (Invitados / Guest)
Route::middleware('guest')->group(function () {
    Route::get('/recuperar-clave', [SecurityQuestionResetController::class, 'showFindAccountForm'])->name('password.security.request');
    Route::post('/recuperar-clave/buscar', [SecurityQuestionResetController::class, 'verifyAccount'])->name('password.security.verify');
    Route::post('/recuperar-clave/respuestas', [SecurityQuestionResetController::class, 'processAnswers'])->name('password.security.answers');
    Route::get('/recuperar-clave/nueva-contrasena', [SecurityQuestionResetController::class, 'showResetForm'])->name('password.security.reset_form');
    Route::post('/recuperar-clave/guardar', [SecurityQuestionResetController::class, 'updatePassword'])->name('password.security.update');

    // 🔐 Challenge de 2FA al Iniciar Sesión (Debe ser público)
    Route::get('/2fa/verificar', [TwoFactorController::class, 'showChallenge'])->name('2fa.challenge');
    Route::post('/2fa/verificar', [TwoFactorController::class, 'verifyChallenge'])->name('2fa.verify');
});

require __DIR__.'/auth.php';