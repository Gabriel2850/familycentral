<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 {{ __('Panel Principal y Métricas') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- 📈 1. TARJETAS DE ESTADÍSTICAS (KPIs) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-600">
                <span class="text-xs font-bold text-gray-500 uppercase">Total Recolecciones</span>
                <p class="text-3xl font-extrabold text-blue-900 mt-2">{{ $totalPickups }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                <span class="text-xs font-bold text-gray-500 uppercase">Pendientes en Ruta</span>
                <p class="text-3xl font-extrabold text-yellow-600 mt-2">{{ $pendingPickups }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-600">
                <span class="text-xs font-bold text-gray-500 uppercase">Entregadas / Completadas</span>
                <p class="text-3xl font-extrabold text-green-700 mt-2">{{ $completedPickups }}</p>
            </div>
        </div>

        <!-- 📋 2. HISTORIAL DE ENVÍOS RECIENTES (Cabecera Detallada) -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">📦 Útimos Envíos Registrados</h3>
                <a href="{{ route('history.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">
                    Ver historial completo ➡️
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <th class="p-3 border-b">Cliente</th>
                            <th class="p-3 border-b">Tipo de Caja / Paquete</th>
                            <th class="p-3 border-b">Fecha de Agenda</th>
                            <th class="p-3 border-b">Estatus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-sm">
                        @forelse($recentPickups as $pickup)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-bold text-gray-800">{{ $pickup->client_name ?? 'Cliente General' }}</td>
                                <td class="p-3 text-gray-600">{{ $pickup->box_type ?? 'Caja Estándar' }}</td>
                                <td class="p-3 text-gray-600">{{ \Carbon\Carbon::parse($pickup->scheduled_date)->format('d/m/Y') }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold 
                                        {{ $pickup->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($pickup->status ?? 'Pendiente') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    No hay envíos registrados recientemente.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>