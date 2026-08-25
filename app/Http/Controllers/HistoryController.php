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
        // Carga ansiosa de relaciones para evitar N+1
        $query = PickupAppointment::with(['customer', 'zone']);

        // 🔍 Buscador Multicampo (Cliente, Teléfono, Tracking, Dirección o Caja)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($c) use ($search) {
                    $c->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhere('tracking_number', 'like', "%{$search}%")
                ->orWhere('pickup_address', 'like', "%{$search}%")
                ->orWhere('box_type', 'like', "%{$search}%");
            });
        }

        // 📍 Filtro por Zona de EUA
        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->input('zone_id'));
        }

        // 🏷️ Filtro por Estatus
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
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
                $query->whereHas('customer')->join('customers', 'pickup_appointments.customer_id', '=', 'customers.id')
                      ->orderBy('customers.name', 'asc')
                      ->select('pickup_appointments.*');
                break;
            case 'alpha_desc':
                $query->whereHas('customer')->join('customers', 'pickup_appointments.customer_id', '=', 'customers.id')
                      ->orderBy('customers.name', 'desc')
                      ->select('pickup_appointments.*');
                break;
            case 'newest':
            default:
                $query->orderBy('scheduled_date', 'desc');
                break;
        }

        // Paginación de 15 registros conservando los parámetros de la URL
        $appointments = $query->paginate(15)->withQueryString();
        $zones = Zone::all();

        return view('history.index', compact('appointments', 'zones'));
    }

   // 📄 Descargar/Ver Factura con Validación de Existencia en Storage
    public function downloadInvoice(PickupAppointment $appointment)
    {
        if (!$appointment->invoice_path) {
            return redirect()->back()->with('error', 'Este registro no tiene ninguna factura vinculada.');
        }

        if (!Storage::disk('public')->exists($appointment->invoice_path)) {
            return redirect()->back()->with('error', 'El archivo de la factura no se encuentra en el servidor.');
        }

        // Obtener la ruta absoluta del archivo en el sistema de archivos
        $fullPath = Storage::disk('public')->path($appointment->invoice_path);

        // Retornar la descarga segura usando la respuesta nativa de Laravel
        return response()->download($fullPath);
    }

    // 🗑️ Eliminar Registro y Limpiar Archivos del Storage
    public function destroy(PickupAppointment $appointment)
    {
        // Solo el Admin puede eliminar registros del historial
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // Limpieza de la factura física si existe en el disco
        if ($appointment->invoice_path && Storage::disk('public')->exists($appointment->invoice_path)) {
            Storage::disk('public')->delete($appointment->invoice_path);
        }

        $appointment->delete();

        return redirect()->back()->with('success', 'Registro de envío y sus archivos adjuntos eliminados correctamente.');
    }
}