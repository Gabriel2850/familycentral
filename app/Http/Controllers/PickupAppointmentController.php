<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PickupAppointment;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
            $search = $request->input('search');
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
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
            'zone_id' => 'required|exists:zones,id',
            'scheduled_date' => 'required|date',
            'box_quantity' => 'required|integer|min:1',
            'box_dimensions' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Registrar o reutilizar cliente por teléfono
        $customer = Customer::firstOrCreate(
            ['phone' => $request->input('phone')],
            [
                'name' => $request->input('name'),
                'address' => $request->input('address'),
            ]
        );

        PickupAppointment::create([
            'customer_id' => $customer->id,
            'zone_id' => $request->input('zone_id'),
            'user_id' => Auth::id(),
            'scheduled_date' => $request->input('scheduled_date'),
            'box_quantity' => $request->input('box_quantity'),
            'box_dimensions' => $request->input('box_dimensions'),
            'status' => 'programado',
            'notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'Cita agendada exitosamente.');
    }

    public function update(Request $request, PickupAppointment $appointment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
            'zone_id' => 'required|exists:zones,id',
            'scheduled_date' => 'required|date',
            'box_quantity' => 'required|integer|min:1',
            'box_dimensions' => 'required|string|max:255',
            'status' => 'required|in:programado,recolectado,reprogramado,cancelado',
            'notes' => 'nullable|string',
            'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        if ($appointment->customer) {
            $appointment->customer->update([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
            ]);
        }

        $newStatus = $request->input('status');
        $originalDate = $appointment->scheduled_date ? Carbon::parse($appointment->scheduled_date)->format('Y-m-d') : null;
        $postedDate = date('Y-m-d', strtotime($request->input('scheduled_date')));

        if ($originalDate !== $postedDate && $newStatus === 'programado') {
            $newStatus = 'reprogramado';
        }

        if ($request->hasFile('invoice')) {
            if ($appointment->invoice_path && Storage::disk('public')->exists($appointment->invoice_path)) {
                Storage::disk('public')->delete($appointment->invoice_path);
            }
            $path = $request->file('invoice')->store('invoices', 'public');
            $appointment->invoice_path = $path;
        }

        $appointment->update([
            'zone_id' => $request->input('zone_id'),
            'scheduled_date' => $request->input('scheduled_date'),
            'box_quantity' => $request->input('box_quantity'),
            'box_dimensions' => $request->input('box_dimensions'),
            'status' => $newStatus,
            'notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'Cita de recolección actualizada correctamente.');
    }

    public function updateTracking(Request $request, PickupAppointment $appointment)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $appointment->update([
            'tracking_number' => $request->input('tracking_number'),
            'status' => 'recolectado',
        ]);

        return redirect()->back()->with('success', 'Número de tracking asignado correctamente.');
    }

    public function uploadInvoice(Request $request, PickupAppointment $appointment)
    {
        $request->validate([
            'invoice' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        if ($appointment->invoice_path && Storage::disk('public')->exists($appointment->invoice_path)) {
            Storage::disk('public')->delete($appointment->invoice_path);
        }

        $path = $request->file('invoice')->store('invoices', 'public');
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