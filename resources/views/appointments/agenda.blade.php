<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-family-blue leading-tight flex items-center gap-2">
            📅 Agenda Semanal de Recolecciones
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

        <!-- TARJETA DEL FORMULARIO DE REGISTRO -->
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 border border-slate-200 mt-0 mb-8" style="margin-bottom: 2rem !important;">
            
            <form action="{{ route('agenda.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Fila 1: Datos Cliente -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nombre del Cliente</label>
                        <input type="text" name="name" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 p-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#000232] transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Teléfono (Recurrente)</label>
                        <input type="text" name="phone" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 p-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#000232] transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Email (Opcional)</label>
                        <input type="email" name="email" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 p-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#000232] transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Fecha Programada</label>
                        <input type="date" name="scheduled_date" required value="{{ $selectedDate->format('Y-m-d') }}" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 p-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#000232] transition-all outline-none">
                    </div>
                </div>

                <!-- Fila 2: Paquete y Dirección -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <div class="md:col-span-6">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Dirección de Recolección (EUA)</label>
                        <input type="text" name="address" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 p-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#000232] transition-all outline-none">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Cantidad Cajas</label>
                        <input type="number" name="box_quantity" min="1" value="1" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 p-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#000232] transition-all outline-none">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Medidas (Pulgadas)</label>
                        <input type="text" name="box_dimensions" placeholder="Ej: 18x18x24 in" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 p-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#000232] transition-all outline-none">
                    </div>
                </div>

                <!-- Fila 3: Zona -->
                <div class="pt-1">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Zona / Sector EUA</label>
                    <select name="zone_id" required class="w-full rounded-xl border border-slate-300 bg-slate-50/50 p-2.5 text-sm font-medium text-slate-800 focus:bg-white focus:border-[#000232] transition-all outline-none">
                        <option value="">Seleccionar Zona...</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- BOTÓN CENTRADO -->
                <div class="flex justify-center pt-6 pb-2">
                    <button type="submit" 
                            style="background-color: #f6721d !important; display: inline-flex !important; padding: 12px 32px !important; border-radius: 12px !important;" 
                            class="text-white font-bold text-sm uppercase tracking-wider shadow-md hover:brightness-110 active:scale-95 transition-all items-center justify-center gap-2 cursor-pointer">
                        <span class="text-base font-extrabold">+</span> AGENDAR RECOLECCIÓN
                    </button>
                </div>

            </form>
        </div>

        <!-- TARJETAS SEMANALES (FICHAS DIARIAS) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mt-6">
            @for ($i = 0; $i < 6; $i++)
                @php
                    $currentDay = $startOfWeek->copy()->addDays($i);
                    $formattedKey = $currentDay->format('Y-m-d');
                    $dayAppointments = $appointments->get($formattedKey, collect());
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col overflow-hidden min-h-[350px]">
                    <!-- CABECERA DEL DÍA -->
                    <div style="background-color: #000232 !important;" class="p-3 text-white text-center">
                        <p style="color: #f6721d !important;" class="text-[10px] font-extrabold uppercase tracking-widest">{{ $currentDay->translatedFormat('l') }}</p>
                        <p class="text-xl font-black text-white mt-0.5">{{ $currentDay->format('d/m') }}</p>
                    </div>

                    <!-- LISTA DE RECOLECCIONES -->
                    <div class="p-3 flex-1 space-y-3 bg-slate-50/60 overflow-y-auto">
                        @forelse($dayAppointments as $item)
                            <div x-data="{ openModal: false }" class="bg-white p-3 rounded-xl border-l-4 shadow-sm text-xs space-y-2 border-slate-200 hover:shadow-md transition-all {{ $item->status === 'recolectado' ? 'border-l-emerald-500' : ($item->status === 'cancelado' ? 'border-l-red-500' : 'border-l-[#f6721d]') }}">
                                
                                <div class="flex justify-between items-start gap-1">
                                    <span class="font-extrabold text-[#000232] text-xs leading-tight">{{ $item->customer->name ?? 'Sin nombre' }}</span>
                                    
                                    @if(optional($item->customer)->is_recurrent)
                                        <span class="bg-blue-100 text-[#000232] text-[9px] font-black px-1.5 py-0.5 rounded border border-blue-200 shrink-0">Recurrente</span>
                                    @else
                                        <span class="bg-orange-100 text-[#f6721d] text-[9px] font-black px-1.5 py-0.5 rounded border border-orange-200 shrink-0">Nuevo</span>
                                    @endif
                                </div>

                                <div class="space-y-1 text-slate-600 text-[11px]">
                                    <p class="leading-tight"><strong class="text-slate-800">📍 Dir:</strong> {{ $item->customer->address ?? 'N/A' }}</p>
                                    <p><strong class="text-slate-800">📞 Tel:</strong> {{ $item->customer->phone ?? 'N/A' }}</p>
                                    <p><strong class="text-slate-800">📦 Cajas:</strong> {{ $item->box_quantity }} ({{ $item->box_dimensions }})</p>
                                </div>
                                
                                @if($item->zone)
                                    <span class="inline-block bg-slate-100 text-slate-700 text-[9px] font-extrabold px-2 py-0.5 rounded border border-slate-200">
                                        📍 {{ $item->zone->name }}
                                    </span>
                                @endif

                                <div class="pt-2 border-t border-slate-100">
                                    @if($item->tracking_number)
                                        <div class="bg-emerald-50 border border-emerald-200 p-1.5 rounded-lg text-emerald-800 font-bold text-[10px] text-center">
                                            ✅ {{ $item->tracking_number }}
                                        </div>
                                    @else
                                        <form action="{{ route('agenda.updateTracking', $item->id) }}" method="POST" class="flex gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="tracking_number" placeholder="# Tracking" required class="w-full text-[10px] p-1.5 border border-slate-300 rounded-lg focus:ring-1 focus:ring-[#000232] outline-none">
                                            <button type="submit" style="background-color: #000232 !important;" class="text-white px-2 rounded-lg text-[10px] font-bold shrink-0">
                                                OK
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- FACTURA, PDF Y BOTÓN EDITAR -->
                                <div class="pt-2 border-t border-slate-100 space-y-1.5">
                                    <div class="grid grid-cols-2 gap-1">
                                        @if(\Illuminate\Support\Facades\Route::has('agenda.pdf'))
                                            <a href="{{ route('agenda.pdf', $item->id) }}" target="_blank" class="inline-flex items-center justify-center gap-1 text-[10px] bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-1 px-1.5 rounded-lg transition">
                                                🖨️ PDF
                                            </a>
                                        @endif

                                        <button type="button" @click="openModal = true" class="inline-flex items-center justify-center gap-1 text-[10px] bg-slate-800 hover:bg-black text-white font-bold py-1 px-1.5 rounded-lg transition">
                                            ✏️ Editar
                                        </button>
                                    </div>

                                    @if($item->invoice_path)
                                        <div class="flex items-center justify-between text-[10px] pt-1">
                                            <a href="{{ asset('storage/' . $item->invoice_path) }}" target="_blank" class="font-bold text-[#000232] underline hover:text-[#f6721d]">
                                                📄 Ver Factura
                                            </a>

                                            @if(optional(auth()->user())->role === 'admin')
                                                <a href="{{ asset('storage/' . $item->invoice_path) }}" download class="bg-slate-200 text-slate-800 font-bold px-1.5 py-0.5 rounded text-[9px]">
                                                    ⬇️
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <form action="{{ route('agenda.uploadInvoice', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-1">
                                            @csrf
                                            <div class="flex gap-1 items-center">
                                                <input type="file" name="invoice" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-[9px] text-slate-500 file:py-0.5 file:px-1 file:rounded file:border-0 file:text-[9px] file:bg-slate-200">
                                                <button type="submit" style="background-color: #f6721d !important;" class="text-white px-2 py-0.5 rounded text-[9px] font-extrabold shrink-0">
                                                    Subir
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>

                                <!-- MODAL DE EDICIÓN CON SCROLL INTERNO Y TECLA ESC/OVERLAY DISPATCH -->
                                <div x-show="openModal" 
                                     x-cloak 
                                     @keydown.escape.window="openModal = false"
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-6"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0">

                                    <div @click.away="openModal = false" 
                                         class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-2xl overflow-hidden flex flex-col"
                                         style="max-height: calc(100vh - 40px); margin: auto 0;">

                                        <!-- CABECERA FIJA -->
                                        <div style="background-color: #000232 !important;" class="px-6 sm:px-8 py-4 text-white flex justify-between items-center shrink-0 border-b border-slate-800">
                                            <h3 class="font-bold text-base text-white flex items-center gap-2 tracking-wide">
                                                ✏️ <span>Editar Recolección</span>
                                            </h3>
                                            <button type="button" @click="openModal = false" class="text-slate-300 hover:text-white font-black text-2xl leading-none w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 transition-colors">&times;</button>
                                        </div>

                                        <!-- FORMULARIO CON SCROLL INTERNO -->
                                        <form action="{{ route('agenda.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                                            @csrf
                                            @method('PUT')

                                            <!-- CUERPO CON SCROLL -->
                                            <div class="px-6 sm:px-8 py-6 space-y-4 overflow-y-auto flex-1">
                                                
                                                <!-- Nombre y Teléfono -->
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nombre Cliente</label>
                                                        <input type="text" name="name" value="{{ $item->customer->name ?? '' }}" required class="w-full rounded-lg border border-slate-300 text-xs py-2 px-3 text-slate-800 font-medium focus:ring-1 focus:ring-[#000232] focus:border-[#000232] outline-none">
                                                    </div>

                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Teléfono</label>
                                                        <input type="text" name="phone" value="{{ $item->customer->phone ?? '' }}" required class="w-full rounded-lg border border-slate-300 text-xs py-2 px-3 text-slate-800 font-medium focus:ring-1 focus:ring-[#000232] focus:border-[#000232] outline-none">
                                                    </div>
                                                </div>

                                                <!-- Dirección -->
                                                <div class="pt-1.5">
                                                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Dirección (EUA)</label>
                                                    <input type="text" name="address" value="{{ $item->customer->address ?? '' }}" required class="w-full rounded-lg border border-slate-300 text-xs py-2 px-3 text-slate-800 font-medium focus:ring-1 focus:ring-[#000232] focus:border-[#000232] outline-none">
                                                </div>

                                                <!-- Fecha y Estado -->
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1.5">
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Fecha Programada</label>
                                                        <input type="date" name="scheduled_date" value="{{ \Carbon\Carbon::parse($item->scheduled_date)->format('Y-m-d') }}" required class="w-full rounded-lg border border-slate-300 text-xs py-2 px-3 text-slate-800 font-medium focus:ring-1 focus:ring-[#000232] focus:border-[#000232] outline-none">
                                                    </div>

                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Estado</label>
                                                        <select name="status" required class="w-full rounded-lg border border-slate-300 text-xs py-2 px-3 text-slate-800 font-medium focus:ring-1 focus:ring-[#000232] focus:border-[#000232] outline-none">
                                                            <option value="programado" {{ $item->status === 'programado' ? 'selected' : '' }}>Programado</option>
                                                            <option value="recolectado" {{ $item->status === 'recolectado' ? 'selected' : '' }}>Recolectado</option>
                                                            <option value="reprogramado" {{ $item->status === 'reprogramado' ? 'selected' : '' }}>Reprogramado</option>
                                                            <option value="cancelado" {{ $item->status === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Zona y Cantidad Cajas -->
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1.5">
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Zona</label>
                                                        <select name="zone_id" required class="w-full rounded-lg border border-slate-300 text-xs py-2 px-3 text-slate-800 font-medium focus:ring-1 focus:ring-[#000232] focus:border-[#000232] outline-none">
                                                            @foreach($zones as $zone)
                                                                <option value="{{ $zone->id }}" {{ $item->zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Cantidad Cajas</label>
                                                        <input type="number" name="box_quantity" min="1" value="{{ $item->box_quantity }}" required class="w-full rounded-lg border border-slate-300 text-xs py-2 px-3 text-slate-800 font-medium focus:ring-1 focus:ring-[#000232] focus:border-[#000232] outline-none">
                                                    </div>
                                                </div>

                                                <!-- Medidas Cajas -->
                                                <div class="pt-1.5">
                                                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Medidas Cajas</label>
                                                    <input type="text" name="box_dimensions" value="{{ $item->box_dimensions }}" required class="w-full rounded-lg border border-slate-300 text-xs py-2 px-3 text-slate-800 font-medium focus:ring-1 focus:ring-[#000232] focus:border-[#000232] outline-none">
                                                </div>

                                                <!-- Notas -->
                                                <div class="pt-1.5">
                                                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Notas u Observaciones</label>
                                                    <textarea name="notes" rows="2" class="w-full rounded-lg border border-slate-300 text-xs py-2 px-3 text-slate-800 font-medium focus:ring-1 focus:ring-[#000232] focus:border-[#000232] outline-none leading-normal">{{ $item->notes }}</textarea>
                                                </div>

                                                <!-- Reemplazar Factura -->
                                                <div class="pt-1.5">
                                                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Reemplazar Factura (Opcional)</label>
                                                    <input type="file" name="invoice" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-[11px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                                                </div>

                                            </div>

                                            <!-- FOOTER FIJO -->
                                            <div class="px-6 sm:px-8 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between shrink-0">
                                                <button type="button" 
                                                        onclick="if(confirm('¿Estás seguro de eliminar esta recolección?')) { document.getElementById('delete-form-{{ $item->id }}').submit(); }" 
                                                        class="bg-red-50 hover:bg-red-100 text-red-600 font-bold px-4 py-2 rounded-xl text-xs border border-red-200 transition-colors">
                                                    🗑️ Eliminar
                                                </button>

                                                <div class="flex items-center gap-3">
                                                    <button type="button" @click="openModal = false" class="bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold px-4 py-2 rounded-xl text-xs transition-colors">
                                                        Cancelar
                                                    </button>

                                                    <button type="submit" style="background-color: #f6721d !important;" class="text-white font-bold px-4 py-2 rounded-xl text-xs transition shadow-sm hover:brightness-110">
                                                        Guardar Cambios
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                        <!-- FORMULARIO OCULTO PARA ELIMINAR -->
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('agenda.destroy', $item->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center py-12 text-slate-400">
                                <span class="text-2xl mb-1">📅</span>
                                <p class="text-[11px] font-medium text-center">Sin recolecciones agendadas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endfor
        </div>

    </div>

</x-app-layout>