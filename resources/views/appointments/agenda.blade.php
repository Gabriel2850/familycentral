<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-family-blue leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-family-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Agenda Semanal de Recolecciones - FamilyCentral
            </h2>
            <span class="text-sm font-medium text-gray-500">
                Semana del {{ $startOfWeek->format('d/m/Y') }} al {{ $endOfWeek->format('d/m/Y') }}
            </span>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">¡Operación Exitosa!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border-t-4 border-family-blue">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-family-orange rounded-full"></span>
                Agendar Nueva Cita de Recolección
            </h3>

            <form action="{{ route('agenda.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Nombre del Cliente</label>
                    <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-family-blue focus:ring-family-blue text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Teléfono (Búsqueda Recurrente)</label>
                    <input type="text" name="phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-family-blue focus:ring-family-blue text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Email (Opcional)</label>
                    <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-family-blue focus:ring-family-blue text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Fecha Programada</label>
                    <input type="date" name="scheduled_date" required value="{{ $selectedDate->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-family-blue focus:ring-family-blue text-sm">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Dirección de Recolección (EUA)</label>
                    <input type="text" name="address" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-family-blue focus:ring-family-blue text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Cantidad de Cajas</label>
                    <input type="number" name="box_quantity" min="1" value="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-family-blue focus:ring-family-blue text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Medidas (Pulgadas)</label>
                    <input type="text" name="box_dimensions" placeholder="Ej: 18x18x24 in" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-family-blue focus:ring-family-blue text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase">Zona / Sector EUA</label>
                    <select name="zone_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-family-blue focus:ring-family-blue text-sm">
                        <option value="">Seleccionar Zona</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3 lg:col-span-3 flex items-end">
                    <button type="submit" class="w-full bg-family-orange hover:bg-family-orange-dark text-white font-bold py-2 px-4 rounded shadow transition duration-200">
                        + Registrar Cita en Agenda
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3">
            @for ($i = 0; $i < 6; $i++)
                @php
                    $currentDay = $startOfWeek->copy()->addDays($i);
                    $formattedKey = $currentDay->format('Y-m-d');
                    $dayAppointments = $appointments->get($formattedKey, collect());
                @endphp

                <div class="bg-slate-50 rounded-lg shadow border border-gray-200 flex flex-col h-full">
                    <div class="p-3 bg-family-blue text-white rounded-t-lg text-center">
                        <p class="text-xs uppercase font-semibold tracking-wider">{{ $currentDay->translatedFormat('l') }}</p>
                        <p class="text-lg font-extrabold">{{ $currentDay->format('d/m') }}</p>
                    </div>

                    <div class="p-2 flex-1 space-y-3 overflow-y-auto max-h-[600px]">
                        @forelse($dayAppointments as $item)
                            <div class="bg-white p-3 rounded border-l-4 shadow-sm text-xs space-y-2 {{ $item->status === 'recolectado' ? 'border-green-500' : 'border-family-orange' }}">
                                <div class="flex justify-between items-start">
                                    <span class="font-bold text-gray-800 text-sm">{{ $item->customer->name }}</span>
                                    
                                    @if($item->customer->is_recurrent)
                                        <span class="bg-blue-100 text-family-blue text-[10px] font-bold px-1.5 py-0.5 rounded">Recurrente</span>
                                    @else
                                        <span class="bg-orange-100 text-family-orange text-[10px] font-bold px-1.5 py-0.5 rounded">Nuevo</span>
                                    @endif
                                </div>

                                <p class="text-gray-600"><strong>📍 Dir:</strong> {{ $item->customer->address }}</p>
                                <p class="text-gray-600"><strong>📞 Tel:</strong> {{ $item->customer->phone }}</p>
                                <p class="text-gray-600"><strong>📦 Cajas:</strong> {{ $item->box_quantity }} ({{ $item->box_dimensions }})</p>
                                
                                @if($item->zone)
                                    <span class="inline-block bg-gray-100 text-gray-600 text-[10px] px-1 rounded">📍 {{ $item->zone->name }}</span>
                                @endif

                                <div class="pt-2 border-t border-gray-100">
                                    @if($item->tracking_number)
                                        <p class="text-green-700 font-semibold flex items-center gap-1">
                                            ✅ Tracking: {{ $item->tracking_number }}
                                        </p>
                                    @else
                                        <form action="{{ route('agenda.updateTracking', $item->id) }}" method="POST" class="mt-1 flex gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="tracking_number" placeholder="# Tracking" required class="w-full text-[10px] p-1 border rounded focus:ring-family-blue">
                                            <button type="submit" class="bg-family-blue text-white px-2 rounded text-[10px] font-bold hover:bg-family-blue-dark">
                                                Guardar
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- 📄 Factura / Comprobante e Impresión de Ficha PDF -->
                                <div class="pt-2 border-t border-gray-100 mt-2 space-y-2">
                                    <a href="{{ route('agenda.pdf', $item->id) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1 text-[10px] bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold px-2 py-1 rounded transition">
                                        🖨️ Imprimir Ficha PDF
                                    </a>

                                    @if($item->invoice_path)
                                        <div class="flex items-center justify-between">
                                            <a href="{{ asset('storage/' . $item->invoice_path) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-family-blue hover:text-family-orange transition">
                                                📄 Ver Factura
                                            </a>

                                            @if(auth()->user()->role === 'admin')
                                                <a href="{{ asset('storage/' . $item->invoice_path) }}" download class="text-[10px] bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-2 py-0.5 rounded">
                                                    ⬇️ Descargar
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <form action="{{ route('agenda.uploadInvoice', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-1">
                                            @csrf
                                            <label class="block text-[10px] text-gray-500 font-semibold">Adjuntar Factura (PDF/Imagen):</label>
                                            <div class="flex gap-1 items-center">
                                                <input type="file" name="invoice" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-[10px] text-gray-500 file:mr-1 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-gray-100 file:text-family-blue">
                                                <button type="submit" class="bg-family-orange text-white px-2 py-0.5 rounded text-[10px] font-bold hover:bg-family-orange-dark">
                                                    Subir
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <p class="text-center text-xs text-gray-400 italic py-4">Sin recolecciones</p>
                        @endforelse
                    </div>
                </div>
            @endfor
        </div>

    </div>
</x-app-layout>