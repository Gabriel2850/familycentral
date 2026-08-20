<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Recolección #{{ $appointment->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; margin: 0; padding: 20px; font-size: 12px; }
        .header { border-bottom: 2px solid #0D3B9F; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-text { font-size: 24px; font-weight: bold; color: #0D3B9F; }
        .logo-orange { color: #F26522; }
        .title { text-align: right; font-size: 16px; font-weight: bold; color: #333; }
        .section-title { background: #0D3B9F; color: #fff; padding: 5px 10px; font-weight: bold; font-size: 11px; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        td, th { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
        .badge { background-color: #F26522; color: white; padding: 3px 8px; border-radius: 3px; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Encabezado con Marca -->
    <table style="border: none; margin-bottom: 10px;">
        <tr style="border: none;">
            <td style="border: none;" width="60%">
                <span class="logo-text">Family<span class="logo-orange">Central</span></span>
                <p style="margin: 3px 0 0 0; color: #555; font-size: 10px;">Servicio Profesional de Envíos y Recolecciones</p>
            </td>
            <td style="border: none;" width="40%" class="title">
                FICHA DE RECOLECCIÓN<br>
                <span style="font-size: 12px; color: #0D3B9F;">#{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}</span>
            </td>
        </tr>
    </table>

    <!-- Información del Cliente -->
    <div class="section-title">1. DATOS DEL CLIENTE</div>
    <table>
        <tr>
            <th width="25%">Nombre Completo:</th>
            <td>{{ $appointment->customer->name }}</td>
            <th width="20%">Tipo Cliente:</th>
            <td>{{ $appointment->customer->is_recurrent ? 'Recurrente' : 'Nuevo' }}</td>
        </tr>
        <tr>
            <th>Teléfono:</th>
            <td>{{ $appointment->customer->phone }}</td>
            <th>Email:</th>
            <td>{{ $appointment->customer->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Dirección de Recolección:</th>
            <td colspan="3">{{ $appointment->customer->address }}</td>
        </tr>
    </table>

    <!-- Detalles del Envío -->
    <div class="section-title">2. DETALLES DE LA RECOLECCIÓN</div>
    <table>
        <tr>
            <th width="25%">Fecha Programada:</th>
            <td>{{ \Carbon\Carbon::parse($appointment->scheduled_date)->format('d/m/Y') }}</td>
            <th width="25%">Zona asignada:</th>
            <td>{{ $appointment->zone->name ?? 'Sin Zona' }}</td>
        </tr>
        <tr>
            <th>Cantidad de Cajas:</th>
            <td><span class="badge">{{ $appointment->box_quantity }} Caja(s)</span></td>
            <th>Medidas (Pulgadas):</th>
            <td>{{ $appointment->box_dimensions }}</td>
        </tr>
        <tr>
            <th>Número de Tracking:</th>
            <td colspan="3" style="font-size: 14px; font-weight: bold; color: #0D3B9F;">
                {{ $appointment->tracking_number ? '#'.$appointment->tracking_number : 'Pendiente de Asignación' }}
            </td>
        </tr>
    </table>

    <!-- Notas Adicionales -->
    @if($appointment->notes)
        <div class="section-title">3. NOTAS Y OBSERVACIONES</div>
        <p style="padding: 8px; border: 1px solid #ddd; background: #fff;">{{ $appointment->notes }}</p>
    @endif

    <!-- Firma de Conformidad -->
    <table style="margin-top: 50px; border: none;">
        <tr style="border: none;">
            <td style="border: none; text-align: center;" width="50%">
                ___________________________________<br>
                <strong>Firma del Cliente</strong>
            </td>
            <td style="border: none; text-align: center;" width="50%">
                ___________________________________<br>
                <strong>Operador de Recolección</strong>
            </td>
        </tr>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema <strong>FamilyCentral</strong> el {{ date('d/m/Y H:i') }}.
    </div>

</body>
</html>