@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="container mx-auto p-4">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold text-gray-800">📍Rastreo GPS en Vivo - Camioneta</h2>
    <div class="text-sm bg-white p-2 rounded border shadow-sm">
      <span>Estado: </span>
      <strong id="status-text" class="text-yellow-600">Conectando...</strong> |
      <span>Última señal: </span><span id="last-updated">Buscando...</span>
    </div>
  </div>

  <div id="map" class="w-full h-[550px] rounded-lg shadow-md border border-gray-300"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  let map, marker;

  function initMap() {
    const defaultLat = {{ config('services.gps.default_lat', 10.4806) }};
    const defaultLng = {{ config('services.gps.default_lng', -66.9036) }};

    map = L.map('map').setView([defaultLat, defaultLng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '© OpenStreetMap'
    }).addTo(map);

    marker = L.marker([defaultLat, defaultLng]).addTo(map)
      .bindPopup("<b>Camioneta Empresa</b><br>Esperando reporte de GPS...")
      .openPopup();

    fetchCurrentLocation();
    setInterval(fetchCurrentLocation, 10000); // Actualiza cada 10 segundos
  }

  function fetchCurrentLocation() {
    fetch("{{ route('gps.location') }}")
      .then(res => res.json())
      .then(data => {
        const statusElem = document.getElementById('status-text');
        const updatedElem = document.getElementById('last-updated');

        if (data.latitude && data.longitude) {
          const newPos = [data.latitude, data.longitude];
         
          marker.setLatLng(newPos);
          map.panTo(newPos);

          if (data.has_data) {
            statusElem.innerText = "Activo";
            statusElem.className = "text-green-600";
            marker.getPopup().setContent(`<b>Camioneta Empresa</b><br>Velocidad: ${data.speed} km/h`);
          } else {
            statusElem.innerText = "Sin datos en BD";
            statusElem.className = "text-yellow-600";
          }

          updatedElem.innerText = data.recorded_at;
        }
      })
      .catch(err => {
        console.error("Error obteniendo GPS:", err);
        document.getElementById('status-text').innerText = "Error de conexión";
        document.getElementById('status-text').className = "text-red-600";
      });
  }

  document.addEventListener("DOMContentLoaded", initMap);
</script>
@endsection