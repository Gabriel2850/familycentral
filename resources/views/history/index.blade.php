<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-family-blue leading-tight flex items-center gap-2">
            📦 {{ __('Historial General de Envíos y Recolecciones') }}
        </h2>
    </x-slot>

    <!-- Estilos locales para asegurar interacción Hover sin depender de la purga de Tailwind -->
    <style>
        .btn-search-custom {
            background-color: #1e3a8a !important; /* Azul Corporativo */
            color: #ffffff !important;
            border: 1px solid #1e3a8a !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08) !important;
            transition: all 0.2s ease-in-out !important;
        }
        .btn-search-custom:hover {
            background-color: #ffffff !important;
            color: #111827 !important;
            border-color: #d1d5db !important;
        }
        .table-row-history {
            transition: background-color 0.15s ease-in-out !important;
        }
        .table-row-history:hover {
            background-color: #fff4ed !important; /* Naranja suave corporativo */
        }
    </style>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Formulario de Filtros -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
            <form method="GET" action="{{ route('history.index') }}" class="space-y-4">
                
                <div class="space-y-4">
                    <!-- Fila de Campos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        
                        <!-- Buscador Principal -->
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-sm">
                                🔍
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Buscar cliente, teléfono, tracking, dirección o caja..." 
                                class="w-full h-10 pl-9 pr-3 text-sm bg-white border border-gray-300 rounded-md focus:ring-1 focus:ring-family-blue focus:border-family-blue">
                        </div>

                        <!-- Select Zona -->
                        <div class="w-full">
                            <select name="zone_id" class="w-full h-10 px-3 text-sm bg-white border border-gray-300 rounded-md focus:ring-1 focus:ring-family-blue focus:border-family-blue">
                                <option value="">📍 Todas las Zonas</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Estatus -->
                        <div class="w-full">
                            <select name="status" class="w-full h-10 px-3 text-sm bg-white border border-gray-300 rounded-md focus:ring-1 focus:ring-family-blue focus:border-family-blue">
                                <option value="">📌 Todos los Estatus</option>
                                <option value="programado" {{ request('status') == 'programado' ? 'selected' : '' }}>Programado</option>
                                <option value="recolectado" {{ request('status') == 'recolectado' ? 'selected' : '' }}>Recolectado</option>
                                <option value="reprogramado" {{ request('status') == 'reprogramado' ? 'selected' : '' }}>Reprogramado</option>
                                <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>

                        <!-- Select Orden -->
                        <div class="w-full">
                            <select name="sort" onchange="this.form.submit()" class="w-full h-10 px-3 text-sm font-medium bg-white border border-gray-300 rounded-md focus:ring-1 focus:ring-family-blue focus:border-family-blue">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>📅 Más Reciente</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>⌛ Más Antiguo</option>
                                <option value="alpha_asc" {{ request('sort') == 'alpha_asc' ? 'selected' : '' }}>🔤 Cliente (A-Z)</option>
                                <option value="alpha_desc" {{ request('sort') == 'alpha_desc' ? 'selected' : '' }}>🔤 Cliente (Z-A)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botón Centrado -->
                    <div class="flex justify-center pt-1">
                        <button type="submit" class="btn-search-custom w-full sm:w-52 h-10 rounded-md text-sm font-bold flex items-center justify-center">
                            Buscar
                        </button>
                    </div>
                </div>

                <!-- Rangos de Fecha -->
                <div class="flex flex-wrap items-center justify-between gap-4 pt-3 border-t border-gray-200 text-xs">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-bold uppercase tracking-wider text-gray-500">RANGO:</span>
                        
                        <div class="flex items-center gap-2 bg-gray-50 border border-gray-300 px-3 py-1.5 rounded-md">
                            <span class="text-gray-500 font-semibold">Desde:</span>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="bg-transparent border-0 p-0 text-xs text-gray-700 focus:ring-0">
                        </div>

                        <div class="flex items-center gap-2 bg-gray-50 border border-gray-300 px-3 py-1.5 rounded-md">
                            <span class="text-gray-500 font-semibold">Hasta:</span>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="bg-transparent border-0 p-0 text-xs text-gray-700 focus:ring-0">
                        </div>
                    </div>

                    @if(request()->anyFilled(['search', 'zone_id', 'status', 'from_date', 'to_date', 'sort']))
                        <a href="{{ route('history.index') }}" class="text-xs text-red-600 hover:text-red-800 font-bold transition flex items-center gap-1 bg-red-50 px-3 py-1.5 rounded-md border border-red-200">
                            ✕ Limpiar Filtros
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <th class="px-4 py-3.5">Fecha</th>
                            <th class="px-4 py-3.5">Cliente</th>
                            <th class="px-4 py-3.5">Contacto / Dirección</th>
                            <th class="px-4 py-3.5 text-center">Zona</th>
                            <th class="px-4 py-3.5 text-center">Estatus</th>
                            <th class="px-4 py-3.5">Cajas</th>
                            <th class="px-4 py-3.5">Tracking</th>
                            <th class="px-4 py-3.5">Documentos</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-4 py-3.5 text-right">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white text-xs sm:text-sm">
                        @forelse($appointments as $item)
                            <tr class="table-row-history">
                                <td class="px-4 py-4 font-bold text-gray-800 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->scheduled_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-bold text-gray-900">{{ $item->customer->name ?? 'Sin Cliente' }}</div>
                                    <div class="mt-1">
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold {{ optional($item->customer)->is_recurrent ? 'bg-blue-50 text-family-blue border border-blue-200' : 'bg-orange-50 text-family-orange border border-orange-200' }}">
                                            {{ optional($item->customer)->is_recurrent ? 'Recurrente' : 'Nuevo' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-xs space-y-1">
                                    <div class="font-semibold text-gray-700 flex items-center gap-1">
                                        📞 {{ $item->customer->phone ?? 'N/A' }}
                                    </div>
                                    <div class="text-gray-500 leading-relaxed">
                                        📍 {{ $item->customer->address ?? $item->pickup_address ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ $item->zone->name ?? 'Sin Zona' }}
                                    </span>
                                </td>

                                <!-- Columna de Estatus -->
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'programado' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'recolectado' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'reprogramado' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'cancelado' => 'bg-red-50 text-red-700 border-red-200',
                                        ];
                                        $currentStatus = strtolower($item->status ?? 'programado');
                                        $class = $statusClasses[$currentStatus] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold border uppercase tracking-wider {{ $class }}">
                                        {{ $item->status ?? 'Programado' }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-xs">
                                    <div class="font-bold text-gray-800">{{ $item->box_quantity }} caja(s)</div>
                                    <div class="text-gray-400 font-medium text-[11px] mt-0.5">{{ $item->box_dimensions ?? $item->box_type }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    @if($item->tracking_number)
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-green-50 text-green-700 border border-green-200 font-mono">
                                            #{{ $item->tracking_number }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic text-xs">Pendiente</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-xs space-y-2">
                                    @if($item->invoice_path)
                                        <div>
                                            <a href="{{ asset('storage/' . $item->invoice_path) }}" target="_blank" class="text-family-blue font-bold hover:underline inline-flex items-center gap-1">
                                                📄 Ver PDF Factura
                                            </a>
                                        </div>
                                    @endif
                                    <div>
                                        @if(\Illuminate\Support\Facades\Route::has('agenda.pdf'))
                                            <a href="{{ route('agenda.pdf', $item->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-700 font-bold text-[11px] transition">
                                                🖨️ Imprimir Ficha
                                            </a>
                                        @else
                                            <span class="text-red-500 text-[10px] font-bold">Ruta PDF no definida</span>
                                        @endif
                                    </div>
                                </td>
                                @if(auth()->user()->role === 'admin')
                                    <td class="px-4 py-4 text-right">
                                        <form action="{{ route('history.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs bg-red-50 hover:bg-red-100 border border-red-200 px-2.5 py-1 rounded transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-10 text-gray-400 italic">
                                    No se encontraron envíos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $appointments->links() }}
            </div>
        </div>

    </div>
</x-app-layout>