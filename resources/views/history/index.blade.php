<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-family-blue leading-tight flex items-center gap-2">
            📦 Historial General de Envíos y Recolecciones
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Panel de Filtros, Búsqueda y Ordenamiento -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <form method="GET" action="{{ route('history.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                
                <!-- Buscador principal -->
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Buscar (Cliente / Tel / Tracking / Dirección / Caja)</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ej: Juan Pérez, TRK-1234, Cajas..." class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-family-blue focus:ring-family-blue">
                </div>

                <!-- Zona EUA -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Zona EUA</label>
                    <select name="zone_id" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-family-blue focus:ring-family-blue">
                        <option value="">Todas las Zonas</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Estatus -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Estatus</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-family-blue focus:ring-family-blue">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                    </select>
                </div>

                <!-- Rango de Fechas -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Desde Fecha</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-family-blue focus:ring-family-blue">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Hasta Fecha</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-family-blue focus:ring-family-blue">
                </div>

                <!-- Widget de Ordenamiento (A la derecha) -->
                <div class="md:col-span-4 md:col-start-9">
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Ordenar por</label>
                    <select name="sort" onchange="this.form.submit()" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-family-blue focus:ring-family-blue font-semibold text-gray-700 bg-gray-50">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>📅 Más Reciente primero</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>⌛ Más Antiguo primero</option>
                        <option value="alpha_asc" {{ request('sort') == 'alpha_asc' ? 'selected' : '' }}>🔤 Cliente (A ➔ Z)</option>
                        <option value="alpha_desc" {{ request('sort') == 'alpha_desc' ? 'selected' : '' }}>🔤 Cliente (Z ➔ A)</option>
                    </select>
                </div>

                <!-- Botones de Acción -->
                <div class="md:col-span-12 flex justify-end gap-2 pt-2">
                    @if(request()->anyFilled(['search', 'zone_id', 'status', 'from_date', 'to_date', 'sort']))
                        <a href="{{ route('history.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-xs font-bold hover:bg-gray-300 transition flex items-center gap-1">
                            🧹 Limpiar Filtros
                        </a>
                    @endif
                    <button type="submit" class="bg-family-blue text-white px-6 py-2 rounded text-xs font-bold hover:bg-blue-900 transition flex items-center gap-1">
                        🔍 Filtrar Registros
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de Registros -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
                    <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Cliente</th>
                            <th class="px-4 py-3">Teléfono / Dirección</th>
                            <th class="px-4 py-3">Zona</th>
                            <th class="px-4 py-3">Cajas</th>
                            <th class="px-4 py-3">Tracking</th>
                            <th class="px-4 py-3">Factura / PDF</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-4 py-3 text-right">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($appointments as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($item->scheduled_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-800">{{ $item->customer->name ?? 'Sin Cliente' }}</div>
                                    @if(optional($item->customer)->is_recurrent)
                                        <span class="text-[10px] bg-blue-100 text-family-blue font-semibold px-1.5 py-0.5 rounded">Recurrente</span>
                                    @else
                                        <span class="text-[10px] bg-orange-100 text-family-orange font-semibold px-1.5 py-0.5 rounded">Nuevo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    <p>📞 {{ $item->customer->phone ?? 'N/A' }}</p>
                                    <p class="truncate max-w-xs">📍 {{ $item->customer->address ?? $item->pickup_address }}</p>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">
                                        {{ $item->zone->name ?? 'Sin Zona' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs">
                                    {{ $item->box_quantity }} caja(s)<br>
                                    <span class="text-gray-400">({{ $item->box_dimensions ?? $item->box_type }})</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($item->tracking_number)
                                        <span class="font-bold text-green-700">#{{ $item->tracking_number }}</span>
                                    @else
                                        <span class="text-gray-400 italic text-xs">Pendiente</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs space-y-1">
                                    @if($item->invoice_path)
                                        <div>
                                            <a href="{{ asset('storage/' . $item->invoice_path) }}" target="_blank" class="text-family-blue font-bold hover:underline">
                                                📄 Ver PDF Factura
                                            </a>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('agenda.pdf', $item->id) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold px-2 py-1 rounded transition">
                                            🖨️ Imprimir Ficha PDF
                                        </a>
                                    </div>
                                    @if(!$item->invoice_path)
                                        <span class="text-gray-400 italic block">Sin factura cargada</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role === 'admin')
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <form action="{{ route('history.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs bg-red-50 hover:bg-red-100 px-2 py-1 rounded transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="text-center py-8 text-gray-400 italic">
                                    No se encontraron envíos registrados con los criterios seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200">
                {{ $appointments->links() }}
            </div>
        </div>

    </div>
</x-app-layout>