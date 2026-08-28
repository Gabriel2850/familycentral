<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PickupAppointment;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PickupAppointmentController extends Controller
{
    /**
     * Muestra la agenda distribuida de Lunes a Sábado.
     */
    public function index(Request $request)
    {
        // 1. Obtener la fecha seleccionada o usar la fecha actual de la semana
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        
        // 2. Definir el rango de la semana (Lunes a Sábado)
        $startOfWeek = $selectedDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = $startOfWeek->copy()->addDays(5); // Sábado

        // 3. Cargar las citas de la semana agrupadas por fecha
        $appointments = PickupAppointment::with(['customer', 'zone'])
            ->whereBetween('scheduled_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->scheduled_date)->format('Y-m-d');
            });

        // 4. Obtener la lista de zonas activas para los selects
        $zones = Zone::where('is_active', true)->get();

        return view('appointments.agenda', compact('appointments', 'startOfWeek', 'endOfWeek', 'selectedDate', 'zones'));
    }

    /**
     * Guarda o programa una nueva cita de recolección.
     */
    public function store(Request $request)
    {
        // Validar campos estructurados (evita mezcla de texto)
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'email'          => 'nullable|email',
            'address'        => 'required|string',
            'zone_id'        => 'required|exists:zones,id',
            'scheduled_date' => 'required|date',
            'box_quantity'   => 'required|integer|min:1',
            'box_dimensions' => 'required|string|max:100', // Ejemplo: "18x18x24 in"
            'notes'          => 'nullable|string',
        ]);

        // A. Buscar o registrar cliente y determinar si es recurrente
        $customer = Customer::where('phone', $validated['phone'])->first();

        if ($customer) {
            if (!$customer->is_recurrent) {
                $customer->update(['is_recurrent' => true]);
            }
        } else {
            $customer = Customer::create([
                'name'             => $validated['name'],
                'phone'            => $validated['phone'],
                'email'            => $validated['email'] ?? null,
                'address'          => $validated['address'],
                'is_recurrent'     => false,
                'first_shipped_at' => now(),
            ]);
        }

        // B. Crear la cita de recolección utilizando Auth::id() de forma segura
        PickupAppointment::create([
            'customer_id'    => $customer->id,
            'zone_id'        => $validated['zone_id'],
            'user_id'        => Auth::id(),
            'scheduled_date' => $validated['scheduled_date'],
            'box_quantity'   => $validated['box_quantity'],
            'box_dimensions' => $validated['box_dimensions'],
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'programado',
        ]);

        return redirect()->back()->with('success', 'Cita agendada correctamente.');
    }

    /**
     * Actualiza el número de tracking cuando se realiza la recolección.
     */
    public function updateTracking(Request $request, PickupAppointment $appointment)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        $appointment->update([
            'tracking_number' => $validated['tracking_number'],
            'status'          => 'recolectado',
        ]);

        return redirect()->back()->with('success', 'Número de tracking actualizado.');
    }

    /**
     * Sube y almacena la factura / recibo de la cita de recolección.
     */
    public function uploadInvoice(Request $request, PickupAppointment $appointment)
    {
        $request->validate([
            'invoice' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Máx 5MB
        ]);

        if ($request->hasFile('invoice')) {
            // Guardar en storage/app/public/invoices
            $path = $request->file('invoice')->store('invoices', 'public');

            $appointment->update([
                'invoice_path' => $path,
            ]);
        }

        return redirect()->back()->with('success', 'Factura subida correctamente.');
    }
}