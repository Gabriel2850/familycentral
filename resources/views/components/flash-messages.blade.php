@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 flex items-center justify-between rounded-md bg-green-50 p-4 border-l-4 border-green-500 shadow-sm">
        <div class="flex items-center gap-3">
            <span class="text-green-600 text-lg">✅</span>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="text-green-500 hover:text-green-700 font-bold text-sm">✕</button>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 flex items-center justify-between rounded-md bg-red-50 p-4 border-l-4 border-red-500 shadow-sm">
        <div class="flex items-center gap-3">
            <span class="text-red-600 text-lg">⚠️</span>
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-red-500 hover:text-red-700 font-bold text-sm">✕</button>
    </div>
@endif

@if (session('info'))
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 flex items-center justify-between rounded-md bg-blue-50 p-4 border-l-4 border-family-blue shadow-sm">
        <div class="flex items-center gap-3">
            <span class="text-family-blue text-lg">ℹ️</span>
            <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
        </div>
        <button @click="show = false" class="text-blue-500 hover:text-blue-700 font-bold text-sm">✕</button>
    </div>
@endif