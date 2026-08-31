<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Global de Envíos y Recolecciones</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #111827; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { color: #1e3a8a; margin: 0; font-size: 18px; }
        .header p { margin: 3px 0 0; color: #4b5563; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #1e3a8a; color: #ffffff; text-align: left; padding: 6px; font-size: 9px; text-transform: uppercase; }
        td { border-bottom: 1px solid #e5e7eb; padding: 6px; font-size: 9.5px; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .badge { display: inline-block; padding: 2px 5px; border-radius: 3px; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        
        /* Compatibilidad de estados tanto en inglés como en español */
        .recolectado, .completado, .completed { background: #ecfdf5; color: #047857; }
        .programado, .pendiente, .pending     { background: #fef3c7; color: #b45309; }
        .cancelado, .cancelled                { background: #fef2f2; color: #dc2626; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE GLOBAL DE ENVÍOS Y RECOLECCIONES</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i A') }} | Rango: {{ $fromDate ?? 'Inicio' }} al {{ $toDate ?? 'Actualidad' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Contacto</th>
                <th>Zona</th>
                <th>Estatus</th>
                <th>Cajas</th>
                <th>Tracking</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $item)
                <tr>
                    <td>{{ $item->scheduled_date ? \Carbon\Carbon::parse($item->scheduled_date)->format('d/m/Y') : ($item->created_at ? $item->created_at->format('d/m/Y') : 'N/A') }}</td>
                    <td><strong>{{ $item->customer->name ?? 'Sin Cliente' }}</strong></td>
                    <td>{{ $item->customer->phone ?? 'N/A' }}</td>
                    <td>{{ $item->zone->name ?? 'Sin Zona' }}</td>
                    <td><span class="badge {{ strtolower($item->status) }}">{{ $item->status }}</span></td>
                    <td>{{ $item->box_quantity ?? 0 }} caja(s)</td>
                    <td>{{ $item->tracking_number ?? 'Pendiente' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #9ca3af; padding: 15px;">No hay registros disponibles para el criterio seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>