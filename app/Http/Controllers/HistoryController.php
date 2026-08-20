<?php

namespace App\Http\Controllers;

use App\Models\PickupAppointment;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // 🔴 Se quitó 'user' de with() para evitar el error de relación no definida
        $query = PickupAppointment::with(['customer', 'zone']);

        // Buscador por Cliente (Nombre o Teléfono) o por Tracking
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($c) use ($search) {
                    $c->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhere('tracking_number', 'like', "%{$search}%");
            });
        }

        // Filtro por Zona de EUA
        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->input('zone_id'));
        }

        // Filtro por Estado
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filtro por Rango de Fechas
        if ($request->filled('from_date')) {
            $query->whereDate('scheduled_date', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('scheduled_date', '<=', $request->input('to_date'));
        }

        // Ordenar por fecha descendente y paginar
        $appointments = $query->orderBy('scheduled_date', 'desc')->paginate(15);
        $zones = Zone::all();

        return view('history.index', compact('appointments', 'zones'));
    }

    public function destroy(PickupAppointment $appointment)
    {
        // Solo el Admin puede eliminar registros del historial
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $appointment->delete();

        return redirect()->back()->with('success', 'Registro de envío eliminado correctamente.');
    }
}