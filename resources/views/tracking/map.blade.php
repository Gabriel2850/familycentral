<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-family-blue leading-tight flex items-center gap-2">
            📍 Monitoreo de Camioneta de Recolección en Tiempo Real
        </h2>
    </x-slot>

    <!-- Hojas de estilo y Scripts de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <style>
        /* Forzar altura física para que el contenedor no colapse */
        #map {
            height: 500px !important;
            width: 100% !important;
            z-index: 1;
        }
    </style>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-family-blue">
            
            <!-- Tarjetas de Telemetría -->
            <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 p-4 rounded border">
                <div>
                    <span class="text-xs font-bold text-gray-500 uppercase">Unidad Activa:</span>
                    <p id="truck-name" class="text-sm font-bold text-family-blue">Cargando...</p>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-500 uppercase">Velocidad Actual:</span>
                    <p id="truck-speed" class="text-sm font-bold text-family-orange">-- km/h</p>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-500 uppercase">Última Señal GPS:</span>
                    <p id="truck-time" class="text-sm font-bold text-gray-700">--:--:--</p>
                </div>
            </div>

            <!-- Contenedor del Mapa con estilo inline de respaldo -->
            <div id="map" style="height: 500px; width: 100%;" class="rounded-lg shadow-inner border"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Coordenadas base
            const latBase = 25.6866;
            const lngBase = -100.3161;

            // 1. Inicializar Mapa
            let map = L.map('map').setView([latBase, lngBase], 15);

            // 2. Cargar Capa de OpenStreetMap
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // 3. Crear Ícono Personalizado
            let truckIcon = L.divIcon({
                className: 'custom-truck-marker',
                html: `<div style="background-color: #1e40af; color: white; padding: 6px; border-radius: 50%; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3); text-align: center; font-size: 18px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">🚚</div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18]
            });

            let truckMarker = null;

            // 4. Actualización de Coordenadas
            function updateTruckPosition() {
                fetch('/api/tracking/truck')
                    .then(response => response.json())
                    .then(data => {
                        let lat = data.lat;
                        let lng = data.lng;

                        document.getElementById('truck-name').innerText = data.truck_id + ' (' + data.driver + ')';
                        document.getElementById('truck-speed').innerText = data.speed;
                        document.getElementById('truck-time').innerText = data.updated_at;

                        if (!truckMarker) {
                            truckMarker = L.marker([lat, lng], { icon: truckIcon }).addTo(map)
                                .bindPopup("<b>" + data.truck_id + "</b><br>Recolecciones en ruta.")
                                .openPopup();
                        } else {
                            truckMarker.setLatLng([lat, lng]);
                        }

                        map.panTo([lat, lng]);
                    })
                    .catch(error => console.error("Error al obtener telemetría:", error));
            }

            // Ejecutar inmediatamente
            updateTruckPosition();
            
            // Refrescar cada 3 segundos
            setInterval(updateTruckPosition, 3000);

            // Invalidation para corregir posibles renders truncados en flexbox/grid
            setTimeout(() => {
                map.invalidateSize();
            }, 500);
        });
    </script>
</x-app-layout>