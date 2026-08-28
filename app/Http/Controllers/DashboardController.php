<?php

namespace App\Http\Controllers;

use App\Models\PickupAppointment;
use App\Models\Zone;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Métricas para los cuadros superiores (aceptando 'pending' y 'programado')
        $totalPickups = PickupAppointment::count();
        $pendingPickups = PickupAppointment::whereIn('status', ['pending', 'programado'])->count();
        $inRoutePickups = PickupAppointment::whereIn('status', ['in_route', 'recolectado'])->count();
        $completedPickups = PickupAppointment::whereIn('status', ['completed', 'entregado'])->count();

        // 2. Historial reciente
        $recentPickups = PickupAppointment::with(['customer', 'zone'])->latest()->take(5)->get();

        // 3. Datos para el Gráfico de Dona: Conteo por Zona
        $zonesData = Zone::withCount('pickupAppointments')->get();
        
        // Si no hay zonas o están en 0, garantizamos arreglos numéricos planos
        $zoneNames = $zonesData->pluck('name')->toArray();
        $zoneCounts = $zonesData->pluck('pickup_appointments_count')->map(fn($v) => (int)$v)->toArray();

        // 4. Datos para el Gráfico de Barras por Estatus
        $statusCounts = [(int)$pendingPickups, (int)$inRoutePickups, (int)$completedPickups];

        return view('dashboard', compact(
            'totalPickups',
            'pendingPickups',
            'inRoutePickups',
            'completedPickups',
            'recentPickups',
            'zoneNames',
            'zoneCounts',
            'statusCounts'
        ));
    }
}