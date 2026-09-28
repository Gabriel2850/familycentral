<?php

namespace App\Http\Controllers;

use App\Models\VehicleLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GPSController extends Controller
{
    /**
     * ENDPOINT RECEPTOR DE TRACCAR CLIENT
     * Recibe los pings enviadas por la app Traccar desde el celular.
     */
    public function updateLocation(Request $request)
    {
        // Validar token de seguridad definido en .env
        $incomingToken = $request->header('X-Device-Token') 
            ?? $request->input('token') 
            ?? $request->input('secret');

        $configuredToken = config('services.gps.device_token');

        if (!empty($configuredToken) && $incomingToken !== $configuredToken) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        // Mapeo de parámetros estándar enviados por Traccar Client (lat, lon, speed, timestamp)
        $latitude = $request->input('lat') ?? $request->input('latitude');
        $longitude = $request->input('lon') ?? $request->input('longitude');
        $speed = $request->input('speed');

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Coordenadas incompletas'], 422);
        }

        $validated = validator([
            'latitude'  => $latitude,
            'longitude' => $longitude,
            'speed'     => $speed,
        ], [
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'speed'     => ['nullable', 'numeric'],
        ])->validate();

        $location = VehicleLocation::create([
            'vehicle_id'  => 'camioneta_principal',
            'latitude'    => $validated['latitude'],
            'longitude'   => $validated['longitude'],
            'speed'       => $validated['speed'] ?? null,
            'recorded_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'id' => $location->id], 200);
    }

    /**
     * ENDPOINT AJAX PARA LA VISTA ADMIN
     * Devuelve la ubicación en tiempo real formateada para Leaflet.js
     */
    public function getLocation()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $latestLocation = VehicleLocation::where('vehicle_id', 'camioneta_principal')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestLocation) {
            return response()->json([
                'latitude'    => (float) config('services.gps.default_lat', 10.4806),
                'longitude'   => (float) config('services.gps.default_lng', -66.9036),
                'speed'       => 0,
                'recorded_at' => 'Sin datos registrados aún',
                'has_data'    => false,
            ]);
        }

        return response()->json([
            'latitude'    => (float) $latestLocation->latitude,
            'longitude'   => (float) $latestLocation->longitude,
            'speed'       => $latestLocation->speed ? round($latestLocation->speed, 1) : 0,
            'recorded_at' => $latestLocation->recorded_at ? $latestLocation->recorded_at->diffForHumans() : 'Recientemente',
            'has_data'    => true,
        ]);
    }
}