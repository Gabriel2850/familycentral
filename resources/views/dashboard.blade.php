<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-[#000232] leading-tight flex items-center gap-2">
            📊 {{ __('Panel Principal y Métricas') }}
        </h2>
    </x-slot>

    <style>
        .custom-dropdown-menu {
            position: absolute !important;
            bottom: 100% !important;
            left: 0 !important;
            z-index: 9999 !important;
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            border-radius: 0.75rem !important;
            padding: 0.75rem !important;
            min-width: 260px !important;
            max-height: 280px !important;
            overflow-y: auto !important;
            margin-bottom: 0.5rem !important;
        }
        .arrow-icon {
            transition: transform 0.2s ease-in-out !important;
        }
        .arrow-rotate {
            transform: rotate(180deg) !important;
        }

        /* TABLA E HISTORIAL CORREGIDOS CON ALINEACIONES CENTRADAS */
        .history-table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
        }
        .history-table th {
            background-color: #F8FAFC !important;
            color: #1d3085 !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            letter-spacing: 0.05em !important;
            padding: 14px 16px !important;
            border-bottom: 2px solid #E2E8F0 !important;
        }
        .history-table td {
            padding: 16px !important;
            border-bottom: 1px solid #F1F5F9 !important;
            font-size: 13px !important;
            vertical-align: middle !important;
        }
        .history-table tr:hover td {
            background-color: #FFF7ED !important;
        }
        .badge-status {
            display: inline-block !important;
            padding: 5px 14px !important;
            border-radius: 9999px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            background-color: #FFEDD5 !important;
            color: #f6721d !important;
            border: 1px solid #FED7AA !important;
            text-align: center !important;
        }
        .badge-california {
            display: inline-block !important;
            padding: 6px 18px !important;
            border-radius: 9999px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            background-color: #FFF7ED !important;
            color: #f6721d !important;
            border: 1px solid #FFEDD5 !important;
            line-height: 1 !important;
        }

        /* BOTÓN HISTORIAL CON MARGEN Y ESPACIADO FORZADOS */
        .btn-history-full {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.5rem !important;
            padding: 0.65rem 1.4rem !important;
            background-color: #f6721d !important;
            color: #ffffff !important;
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            border-radius: 0.75rem !important;
            text-decoration: none !important;
            box-shadow: 0 4px 6px -1px rgba(246, 114, 29, 0.25) !important;
            transition: background-color 0.2s ease, transform 0.1s ease !important;
            visibility: visible !important;
            opacity: 1 !important;
            margin-top: 1rem !important;
            margin-bottom: 1.25rem !important;
        }
        .btn-history-full:hover {
            background-color: #d85b0d !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }
    </style>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full mb-8">
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Recolecciones</span>
                    <p class="text-3xl font-black text-[#000232] mt-1">{{ $totalPickups ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#1d3085] flex items-center justify-center text-xl shrink-0">📦</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pendientes</span>
                    <p class="text-3xl font-black text-amber-500 mt-1">{{ $pendingPickups ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl shrink-0">⏳</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">En Ruta</span>
                    <p class="text-3xl font-black text-[#f6721d] mt-1">{{ $inRoutePickups ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#f6721d] flex items-center justify-center text-xl shrink-0">🚚</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Completadas</span>
                    <p class="text-3xl font-black text-emerald-600 mt-1">{{ $completedPickups ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">✅</div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 w-full">
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-[#1d3085] flex items-center gap-2">
                            <span>🌎</span> Recolecciones por Zona
                        </h3>
                        <span class="badge-california">California</span>
                    </div>

                    <div id="chart-zones" class="w-full flex justify-center min-h-[240px]"></div>
                </div>

                <div class="mt-6 border-t border-slate-100 pt-4">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-3">Desglose por Región / Punto Cardinal</span>

                    <div class="grid grid-cols-2 gap-3">
                        
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" type="button" class="w-full bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-[#000232] flex items-center justify-between">
                                <span>📍 Zona Norte</span>
                                <svg :class="open ? 'arrow-rotate' : ''" class="arrow-icon w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" class="custom-dropdown-menu" style="display: none;">
                                <span class="text-[10px] font-bold text-[#f6721d] uppercase tracking-wider block border-b border-slate-100 pb-1.5 mb-1.5">Ciudades y Regiones Norte</span>
                                
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>San Francisco</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['San Francisco'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Palo Alto / Mountain View</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Palo Alto / Mountain View'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>San Mateo / Peninsula</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['San Mateo / Peninsula'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Oakland / Alameda / Berkeley</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Oakland / Alameda / Berkeley'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Marin County (San Rafael / Novato)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Marin County'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Napa Valley (Napa / St. Helena / Calistoga)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Napa Valley'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Sonoma County (Santa Rosa / Petaluma / Healdsburg)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Sonoma County'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Ukiah / Lakeport / Clearlake</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Ukiah / Lakeport'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Sacramento Metro / West Sacramento</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Sacramento Metro'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Elk Grove / Rancho Cordova</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Elk Grove'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Roseville / Rocklin / Lincoln</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Roseville'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Yolo (Davis / Woodland)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Yolo'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Placer / El Dorado Foothills (Auburn / Placerville)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Placer Foothills'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Gold Country (Jackson / Sonora / Angels Camp)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Gold Country'] ?? 0 }}</span></div>

                                @if(isset($customDynamicZones['norte']))
                                    @foreach($customDynamicZones['norte'] as $newZone => $count)
                                        <div class="flex justify-between text-xs py-1 text-[#f6721d] font-medium"><span>{{ $newZone }}</span> <span class="font-bold text-[#000232]">{{ $count }}</span></div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" type="button" class="w-full bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-[#000232] flex items-center justify-between">
                                <span>📍 Zona Sur</span>
                                <svg :class="open ? 'arrow-rotate' : ''" class="arrow-icon w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" class="custom-dropdown-menu" style="right: 0 !important; left: auto !important; display: none;">
                                <span class="text-[10px] font-bold text-[#f6721d] uppercase tracking-wider block border-b border-slate-100 pb-1.5 mb-1.5">Ciudades y Regiones Sur</span>
                                
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>San Jose / South Bay</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['San Jose / South Bay'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Santa Clara / Sunnyvale / Cupertino</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Santa Clara / Sunnyvale'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Morgan Hill / Gilroy</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Morgan Hill / Gilroy'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Salinas / Hollister</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Salinas / Hollister'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>South Salinas Valley (Soledad / Greenfield / King City)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['South Salinas Valley'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Paso Robles / Atascadero</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Paso Robles / Atascadero'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>San Luis Obispo / Pismo Beach</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['San Luis Obispo'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Fresno / Clovis / Sanger</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Fresno / Clovis'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>South Fresno Co. (Selma / Kingsburg / Coalinga)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['South Fresno Co.'] ?? 0 }}</span></div>

                                @if(isset($customDynamicZones['sur']))
                                    @foreach($customDynamicZones['sur'] as $newZone => $count)
                                        <div class="flex justify-between text-xs py-1 text-[#f6721d] font-medium"><span>{{ $newZone }}</span> <span class="font-bold text-[#000232]">{{ $count }}</span></div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" type="button" class="w-full bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-[#000232] flex items-center justify-between">
                                <span>📍 Zona Este</span>
                                <svg :class="open ? 'arrow-rotate' : ''" class="arrow-icon w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" class="custom-dropdown-menu" style="display: none;">
                                <span class="text-[10px] font-bold text-[#f6721d] uppercase tracking-wider block border-b border-slate-100 pb-1.5 mb-1.5">Ciudades y Regiones Este</span>
                                
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Hayward / Fremont / Union City</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Hayward / Fremont'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Tri-Valley (Dublin / Pleasanton / Livermore)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Tri-Valley'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Contra Costa (Concord / Walnut Creek / Pittsburg)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Contra Costa'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Solano (Vallejo / Fairfield / Vacaville)</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Solano'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Tracy / Mountain House</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Tracy / Mountain House'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Stockton / Lodi / Manteca</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Stockton / Lodi'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Modesto / Turlock / Ceres</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Modesto / Turlock'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Merced / Los Banos / Atwater</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Merced / Los Banos'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Madera / Chowchilla</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Madera / Chowchilla'] ?? 0 }}</span></div>

                                @if(isset($customDynamicZones['este']))
                                    @foreach($customDynamicZones['este'] as $newZone => $count)
                                        <div class="flex justify-between text-xs py-1 text-[#f6721d] font-medium"><span>{{ $newZone }}</span> <span class="font-bold text-[#000232]">{{ $count }}</span></div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" type="button" class="w-full bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-[#000232] flex items-center justify-between">
                                <span>📍 Zona Oeste / Costa</span>
                                <svg :class="open ? 'arrow-rotate' : ''" class="arrow-icon w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" class="custom-dropdown-menu" style="right: 0 !important; left: auto !important; display: none;">
                                <span class="text-[10px] font-bold text-[#f6721d] uppercase tracking-wider block border-b border-slate-100 pb-1.5 mb-1.5">Ciudades Costa</span>
                                
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Santa Cruz / Watsonville</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Santa Cruz / Watsonville'] ?? 0 }}</span></div>
                                <div class="flex justify-between text-xs py-1 text-slate-600"><span>Monterey / Seaside / Marina</span> <span class="font-bold text-[#000232]">{{ $zoneCounts['Monterey / Seaside / Marina'] ?? 0 }}</span></div>

                                @if(isset($customDynamicZones['oeste']))
                                    @foreach($customDynamicZones['oeste'] as $newZone => $count)
                                        <div class="flex justify-between text-xs py-1 text-[#f6721d] font-medium"><span>{{ $newZone }}</span> <span class="font-bold text-[#000232]">{{ $count }}</span></div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-[#1d3085] flex items-center gap-2">
                        <span>📊</span> Estado Operativo de Envíos
                    </h3>
                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">Distribución Actual</span>
                </div>
                <div id="chart-status" class="w-full pt-2"></div>
            </div>

        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 w-full">
            
            <div class="flex flex-col items-center justify-center text-center pb-2 border-b border-slate-100 mb-8">
                <div>
                    <h3 class="text-lg font-black text-[#000232] flex items-center justify-center gap-2">
                        📋 <span>Últimos Envíos Registrados</span>
                    </h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Resumen general de las solicitudes agregadas en la plataforma</p>
                </div>

                <div class="w-full flex justify-center py-3">
                    <a href="{{ Route::has('history.index') ? route('history.index') : (Route::has('history') ? route('history') : url('/history')) }}" 
                       class="btn-history-full">
                        <span>Ver todo el historial</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th style="text-align: left; width: 30%;">CLIENTE</th>
                            <th style="text-align: center; width: 30%;">TIPO DE CAJA / MEDIDAS</th>
                            <th style="text-align: center; width: 20%;">FECHA DE AGENDA</th>
                            <th style="text-align: center; width: 20%;">ESTATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPickups ?? [] as $pickup)
                            <tr>
                                <td style="font-weight: 700; color: #000232; text-align: left;">
                                    {{ $pickup->customer?->name ?? $pickup->client_name ?? 'prueba' }}
                                </td>
                                <td style="color: #475569; font-weight: 600; text-align: center;">
                                    {{ $pickup->box_size ?? $pickup->box_dimensions ?? $pickup->box_type ?? '30x30x30' }}
                                </td>
                                <td style="text-align: center; color: #64748B; font-family: monospace; font-weight: 600;">
                                    {{ isset($pickup->scheduled_date) ? \Carbon\Carbon::parse($pickup->scheduled_date)->format('d/m/Y') : '20/08/2026' }}
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge-status">
                                        {{ ucfirst($pickup->status ?? 'Programado') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td style="font-weight: 700; color: #000232; text-align: left;">prueba</td>
                                <td style="color: #475569; font-weight: 600; text-align: center;">30x30x30</td>
                                <td style="text-align: center; color: #64748B; font-family: monospace; font-weight: 600;">20/08/2026</td>
                                <td style="text-align: center;">
                                    <span class="badge-status">Programado</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight: 700; color: #000232; text-align: left;">prueba</td>
                                <td style="color: #475569; font-weight: 600; text-align: center;">24x24x24</td>
                                <td style="text-align: center; color: #64748B; font-family: monospace; font-weight: 600;">16/08/2026</td>
                                <td style="text-align: center;">
                                    <span class="badge-status">Programado</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var zonesOptions = {
                series: [{{ $totalNorte ?? 1 }}, {{ $totalSur ?? 1 }}, {{ $totalEste ?? 0 }}, {{ $totalOeste ?? 0 }}],
                labels: ['Norte', 'Sur', 'Este', 'Oeste'],
                chart: { type: 'donut', height: 250, fontFamily: 'Inter, system-ui, sans-serif' },
                colors: ['#1d3085', '#f6721d', '#10B981', '#000232'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '12px', fontWeight: 600, color: '#64748B', offsetY: -4 },
                                value: { show: true, fontSize: '20px', fontWeight: 800, color: '#000232', offsetY: 4 },
                                total: {
                                    show: true,
                                    label: 'Total Envíos',
                                    color: '#64748B',
                                    fontSize: '11px',
                                    fontWeight: 600
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                legend: { show: false },
                stroke: { width: 2 }
            };
            new ApexCharts(document.querySelector("#chart-zones"), zonesOptions).render();

            var statusOptions = {
                series: [{ name: 'Cantidad', data: [{{ $pendingPickups ?? 2 }}, {{ $inRoutePickups ?? 0 }}, {{ $completedPickups ?? 0 }}] }],
                chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: 'Inter, system-ui, sans-serif' },
                plotOptions: { bar: { borderRadius: 6, columnWidth: '35%', distributed: true } },
                colors: ['#F59E0B', '#f6721d', '#10B981'],
                dataLabels: { enabled: true },
                xaxis: {
                    categories: ['Pendientes', 'En Ruta', 'Completados'],
                    labels: { style: { colors: '#64748B', fontSize: '11px', fontWeight: 600 } }
                },
                yaxis: { labels: { style: { colors: '#94A3B8' } } },
                grid: { borderColor: '#F1F5F9', strokeDashArray: 4 },
                legend: { show: false }
            };
            new ApexCharts(document.querySelector("#chart-status"), statusOptions).render();
        });
    </script>
</x-app-layout>