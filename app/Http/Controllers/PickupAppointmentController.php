<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PickupAppointment;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PickupAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $zones = Zone::where('is_active', true)->get();
        
        // 📅 Fecha seleccionada y cálculo de inicio de semana
        $selectedDate = $request->filled('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $startOfWeek = $selectedDate->copy()->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->addDays(5)->endOfDay(); // De Lunes a Sábado

        // 🛑 Excluimos los registros con estado 'cancelado' para limpiar el itinerario semanal
        $query = PickupAppointment::with(['customer', 'zone'])
            ->where('status', '!=', 'cancelado');

        // 🔍 Buscador
        if ($request->filled('search')) {
            $search = trim(strip_tags($request->input('search')));
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhere('tracking_number', 'like', "%{$search}%");
            });
        }

        // 📍 Filtro por Zona
        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->input('zone_id'));
        }

        // 🗓️ Rango de fecha por la semana o fecha específica
        if ($request->filled('date')) {
            $query->whereDate('scheduled_date', $request->input('date'));
        } else {
            $query->whereBetween('scheduled_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')]);
        }

        // Cargar colección y agrupar por fecha en formato Y-m-d
        $appointments = $query->orderBy('scheduled_date', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->scheduled_date)->format('Y-m-d');
            });

        return view('appointments.agenda', compact('appointments', 'zones', 'selectedDate', 'startOfWeek'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:300',
            'zone_id' => 'required|exists:zones,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'box_quantity' => 'required|integer|min:1|max:99',
            'box_dimensions' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($validated) {
        // Registrar o reutilizar cliente por teléfono (Sanitizado)
        $customer = Customer::firstOrCreate(
            ['phone' => trim(strip_tags($validated['phone']))],
            [
                'name' => trim(strip_tags($validated['name'])),
                    'address' => trim(strip_tags($validated['address'])),   
            ]
        );

        PickupAppointment::create([
                'customer_id' => $customer->id,
                'zone_id' => $validated['zone_id'],
                'user_id' => Auth::id(),
                'scheduled_date' => $validated['scheduled_date'],
                'box_quantity' => $validated['box_quantity'],
                'box_dimensions' => trim(strip_tags($validated['box_dimensions'])),
                'status' => 'programado',
                'notes' => isset($validated['notes']) ? trim(strip_tags($validated['notes'])) : null,
            ]);
        });

        return redirect()->back()->with('success', 'Cita agendada exitosamente.');
    }

    public function update(Request $request, PickupAppointment $appointment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'zone_id' => 'required|exists:zones,id',
            'scheduled_date' => 'required|date',
            'box_quantity' => 'required|integer|min:1|max:999',
            'box_dimensions' => 'required|string|max:255',
            'status' => 'required|in:programado,recolectado,reprogramado,cancelado',
            'notes' => 'nullable|string|max:1000',
            'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        DB::transaction(function () use ($request, $appointment, $validated) {
            if ($appointment->customer) {
                $appointment->customer->update([
                    'name' => trim(strip_tags($validated['name'])),
                    'phone' => trim(strip_tags($validated['phone'])),
                    'address' => trim(strip_tags($validated['address'])),
                ]);
            }

            $newStatus = $validated['status'];
            $originalDate = $appointment->scheduled_date ? Carbon::parse($appointment->scheduled_date)->format('Y-m-d') : null;
            $postedDate = Carbon::parse($validated['scheduled_date'])->format('Y-m-d');

            if ($originalDate !== $postedDate && $newStatus === 'programado') {
                $newStatus = 'reprogramado';
            }

            if ($request->hasFile('invoice')) {
                // Almacenamiento seguro en disco 'local' (privado)
                if ($appointment->invoice_path && Storage::disk('local')->exists($appointment->invoice_path)) {
                    Storage::disk('local')->delete($appointment->invoice_path);
                }
                
                $path = $request->file('invoice')->store('invoices', 'local');
                $appointment->invoice_path = $path;
            }

            $appointment->update([
                'zone_id' => $validated['zone_id'],
                'scheduled_date' => $validated['scheduled_date'],
                'box_quantity' => $validated['box_quantity'],
                'box_dimensions' => trim(strip_tags($validated['box_dimensions'])),
                'status' => $newStatus,
                'notes' => isset($validated['notes']) ? trim(strip_tags($validated['notes'])) : null,
            ]);
        });

        return redirect()->back()->with('success', 'Cita de recolección actualizada correctamente.');
    }

    public function updateTracking(Request $request, PickupAppointment $appointment)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:255|regex:/^[A-Za-z0-9\-]+$/',
        ]);

        $appointment->update([
            'tracking_number' => trim(strip_tags($validated['tracking_number'])),
            'status' => 'recolectado',
        ]);

        return redirect()->back()->with('success', 'Número de tracking asignado correctamente.');
    }

    public function uploadInvoice(Request $request, PickupAppointment $appointment)
    {
        $request->validate([
            'invoice' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        if ($appointment->invoice_path && Storage::disk('local')->exists($appointment->invoice_path)) {
            Storage::disk('local')->delete($appointment->invoice_path);
        }

        $path = $request->file('invoice')->store('invoices', 'local');
        $appointment->invoice_path = $path;
        $appointment->save();

        return redirect()->back()->with('success', 'Factura subida exitosamente.');
    }

    // 🚫 En lugar de borrar la fila de la BD, marcamos como 'cancelado'
    public function destroy(PickupAppointment $appointment)
    {
        $appointment->update([
            'status' => 'cancelado'
        ]);

        return redirect()->back()->with('success', 'El envío ha sido cancelado y archivado en el historial.');
    }
}