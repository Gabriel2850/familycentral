<?php

namespace App\Http\Controllers;

use App\Models\PickupAppointment;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfReportController extends Controller
{
    /**
     * Genera y descarga/visualiza la Ficha Oficial de Recolección en PDF.
     */
    public function generateAppointmentPdf(PickupAppointment $appointment)
    {
        // Cargar las relaciones necesarias
        $appointment->load(['customer', 'zone']);

        // Vista Blade optimizada para DomPDF
        $pdf = Pdf::loadView('pdf.appointment-ticket', compact('appointment'))
                  ->setPaper('letter', 'portrait');

        // Retornar la vista en el navegador (stream) o descarga directa (download)
        return $pdf->stream("Guia-Recoleccion-{$appointment->id}.pdf");
    }
}