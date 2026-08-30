<?php

namespace App\Http\Controllers;

use App\Models\PickupAppointment;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Cargamos con trashed (activos y Soft Delete) y relaciones
        $query = PickupAppointment::withTrashed()->with(['customer', 'zone']);

        // 🔍 Buscador Multicampo (Cliente, Teléfono, Tracking, Dirección o Caja)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                })
                ->orWhere('tracking_number', 'like', "%{$search}%")
                ->orWhere('box_dimensions', 'like', "%{$search}%")
                ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // 📍 Filtro por Zona
        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->input('zone_id'));
        }

        // 🏷️ Filtro por Estatus
        if ($request->filled('status')) {
            if ($request->input('status') === 'eliminado') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->input('status'));
            }
        }

        // 📅 Filtro por Rango de Fechas
        if ($request->filled('from_date')) {
            $query->whereDate('scheduled_date', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('scheduled_date', '<=', $request->input('to_date'));
        }

        // 🔀 Widget de Ordenamiento Dinámico
        switch ($request->input('sort', 'newest')) {
            case 'oldest':
                $query->orderBy('scheduled_date', 'asc');
                break;
            case 'alpha_asc':
                $query->select('pickup_appointments.*')
                    ->join('customers', 'pickup_appointments.customer_id', '=', 'customers.id')
                    ->orderBy('customers.name', 'asc');
                break;
            case 'alpha_desc':
                $query->select('pickup_appointments.*')
                    ->join('customers', 'pickup_appointments.customer_id', '=', 'customers.id')
                    ->orderBy('customers.name', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('scheduled_date', 'desc');
                break;
        }

        $appointments = $query->paginate(15)->withQueryString();
        $zones = Zone::all();

        return view('history.index', compact('appointments', 'zones'));
    }

    // 📄 Descargar/Ver Factura
    public function downloadInvoice(PickupAppointment $appointment)
    {
        if (!$appointment->invoice_path) {
            return redirect()->back()->with('error', 'Este registro no tiene ninguna factura vinculada.');
        }

        if (!Storage::disk('public')->exists($appointment->invoice_path)) {
            return redirect()->back()->with('error', 'El archivo de la factura no se encuentra en el servidor.');
        }

        $fullPath = Storage::disk('public')->path($appointment->invoice_path);

        return response()->download($fullPath);
    }

  // 🚫 Cancelar Registro (Mantiene el historial y la ficha PDF)
    public function destroy(PickupAppointment $appointment)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // En lugar de borrar/ocultar el registro, actualizamos su estatus a cancelado
        $appointment->update([
            'status' => 'cancelado'
        ]);

        return redirect()->back()->with('success', 'El envío se ha Cancelado correctamente.');
    }
}