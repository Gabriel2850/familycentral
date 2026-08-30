<?php

namespace App\Http\Controllers;

use App\Models\PickupAppointment;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Métricas Principales (Distribución por estados reales)
        $totalPickups     = PickupAppointment::count();
        $pendingPickups   = PickupAppointment::where('status', 'programado')->count();
        $inRoutePickups   = PickupAppointment::where('status', 'reprogramado')->count(); // Representa reprogramados
        $completedPickups = PickupAppointment::where('status', 'recolectado')->count();  // Representa completados/recolectados

        // 2. Conteo por zonas agrupado desde la relación 'zone'
        $zoneCounts = PickupAppointment::query()
            ->join('zones', 'pickup_appointments.zone_id', '=', 'zones.id')
            ->select('zones.name as zone_name', DB::raw('count(*) as total'))
            ->groupBy('zones.name')
            ->pluck('total', 'zone_name')
            ->toArray();

        // 3. Totales por Puntos Cardinales (Alineados con el ZoneSeeder)
        $totalNorte = PickupAppointment::whereHas('zone', function ($query) {
            $query->whereIn('name', [
                'San Francisco', 
                'Palo Alto / Mountain View', 
                'San Mateo / Peninsula',
                'Oakland / Alameda / Berkeley', 
                'Marin County (San Rafael / Novato)', 
                'Napa Valley (Napa / St. Helena / Calistoga)', 
                'Sonoma County (Santa Rosa / Petaluma / Healdsburg)', 
                'Ukiah / Lakeport / Clearlake', 
                'Sacramento Metro / West Sacramento', 
                'Elk Grove / Rancho Cordova', 
                'Roseville / Rocklin / Lincoln', 
                'Yolo (Davis / Woodland)', 
                'Placer / El Dorado Foothills (Auburn / Placerville)', 
                'Gold Country (Jackson / Sonora / Angels Camp)'
            ]);
        })->count();

        $totalSur = PickupAppointment::whereHas('zone', function ($query) {
            $query->whereIn('name', [
                'San Jose / South Bay', 
                'Santa Clara / Sunnyvale / Cupertino', 
                'Morgan Hill / Gilroy', 
                'Salinas / Hollister', 
                'South Salinas Valley (Soledad / Greenfield / King City)', 
                'Paso Robles / Atascadero', 
                'San Luis Obispo / Pismo Beach', 
                'Fresno / Clovis / Sanger', 
                'South Fresno Co. (Selma / Kingsburg / Coalinga)'
            ]);
        })->count();

        $totalEste = PickupAppointment::whereHas('zone', function ($query) {
            $query->whereIn('name', [
                'Hayward / Fremont / Union City', 
                'Tri-Valley (Dublin / Pleasanton / Livermore)', 
                'Contra Costa (Concord / Walnut Creek / Pittsburg)', 
                'Solano (Vallejo / Fairfield / Vacaville)', 
                'Tracy / Mountain House', 
                'Stockton / Lodi / Manteca', 
                'Modesto / Turlock / Ceres', 
                'Merced / Los Banos / Atwater', 
                'Madera / Chowchilla'
            ]);
        })->count();

        $totalOeste = PickupAppointment::whereHas('zone', function ($query) {
            $query->whereIn('name', [
                'Santa Cruz / Watsonville', 
                'Monterey / Seaside / Marina'
            ]);
        })->count();

        // 4. Citas recientes cargadas con sus relaciones
        $recentPickups = PickupAppointment::with(['customer', 'zone'])
            ->latest('scheduled_date')
            ->take(5)
            ->get();

        // 5. Contenedor de zonas dinámicas adicionales
        $customDynamicZones = [
            'norte' => [],
            'sur'   => [],
            'este'  => [],
            'oeste' => [],
        ];

        return view('dashboard', compact(
            'totalPickups',
            'pendingPickups',
            'inRoutePickups',
            'completedPickups',
            'totalNorte',
            'totalSur',
            'totalEste',
            'totalOeste',
            'zoneCounts',
            'customDynamicZones',
            'recentPickups'
        ));
    }
}