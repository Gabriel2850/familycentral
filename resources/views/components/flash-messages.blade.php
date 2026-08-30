@if (session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg shadow-sm">
        <h3 class="text-emerald-800 font-bold text-base">¡Operación Exitosa!</h3>
        <p class="text-emerald-700 text-sm mt-0.5">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
        <h3 class="text-red-800 font-bold text-base">¡Atención!</h3>
        <p class="text-red-700 text-sm mt-0.5">{{ session('error') }}</p>
    </div>
@endif