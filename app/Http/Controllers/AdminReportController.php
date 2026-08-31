<?php

namespace App\Http\Controllers;

use App\Models\PickupAppointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportController extends Controller
{
    /**
     * Genera y descarga el reporte PDF consolidado con los envíos y recolecciones.
     */
    public function downloadGlobalPdf(Request $request)
    {
        /** @var User|null $currentUser */
        $currentUser = Auth::user();

        // Verificación flexible de Rol Admin (admite columna role o flag is_admin)
        if (!$currentUser || ($currentUser->role !== 'admin' && !$currentUser->is_admin)) {
            abort(403, 'Acceso no autorizado al panel administrativo.');
        }

        // Validación estricta de parámetros ingresados
        $validated = $request->validate([
            'from_date' => 'nullable|date',
            'to_date'   => 'nullable|date|after_or_equal:from_date',
            'status'    => 'nullable|string',
        ]);

        $query = PickupAppointment::query();

        // Filtro por rango de fechas (created_at)
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        // Filtro por estatus de la recolección/envío
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Carga eficiente de datos ordenados
        $appointments = $query->orderBy('created_at', 'desc')->get();

        // Carga de la vista PDF
        $pdf = Pdf::loadView('admin.reports.global_pdf', [
            'appointments' => $appointments,
            'fromDate'     => $request->input('from_date'),
            'toDate'       => $request->input('to_date'),
            'status'       => $request->input('status', 'all'),
            'generatedAt'  => now()->format('d/m/Y H:i A'),
            'generatedBy'  => $currentUser->name,
        ]);

        // Opciones sugeridas para DomPDF
        $pdf->setPaper('a4', 'portrait');

        // Usa ->stream() si abres en target="_blank" para ver en pantalla antes de guardar,
        // o ->download() si prefieres forzar la descarga directa del archivo.
        $fileName = 'reporte_global_historial_' . now()->format('d_m_Y_His') . '.pdf';

        return $pdf->stream($fileName);
    }
}