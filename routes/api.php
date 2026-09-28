<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GPSController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 🚛 Ruta para la telemetría GPS de la camioneta
Route::get('/tracking/truck', function () {
    return response()->json([
        'truck_id' => 'CAMION-01',
        'driver'   => 'Carlos Rodríguez',
        'lat'      => 25.6866 + (rand(-50, 50) / 100000),
        'lng'      => -100.3161 + (rand(-50, 50) / 100000),
        'speed'    => rand(20, 45) . ' km/h',
        'updated_at' => now()->format('H:i:s')
    ]);

    // Endpoint receptivo para Traccar (Público, securizado internamente por token)
Route::match(['get', 'post'], '/api/v1/gps/update', [GPSController::class, 'updateLocation'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Consulta AJAX interna para el mapa (Protegida por autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/gps/location', [GPSController::class, 'getLocation'])->name('gps.location');
});

});
