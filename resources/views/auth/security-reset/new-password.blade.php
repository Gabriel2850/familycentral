<x-guest-layout>
    <div class="mb-4 text-sm text-white">
        {{ __('Respuestas validadas con éxito. Ingresa tu nueva contraseña para actualizar tu cuenta.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.security.update') }}">
        @csrf

        <div class="space-y-4">
            <!-- Nueva Contraseña -->
            <div>
                <x-input-label for="password" class="text-white" :value="__('Nueva Contraseña')" />
                <x-text-input id="password" 
                              class="block mt-1 w-full text-white bg-transparent border-gray-600 focus:border-indigo-500" 
                              type="password" 
                              name="password" 
                              required 
                              autofocus 
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <x-input-label for="password_confirmation" class="text-white" :value="__('Confirmar Nueva Contraseña')" />
                <x-text-input id="password_confirmation" 
                              class="block mt-1 w-full text-white bg-transparent border-gray-600 focus:border-indigo-500" 
                              type="password" 
                              name="password_confirmation" 
                              required 
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <button type="submit" 
                    style="background-color: #1d3085;"
                    onmouseover="this.style.backgroundColor='#000232'"
                    onmouseout="this.style.backgroundColor='#1d3085'"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 shadow-sm">
                {{ __('Restablecer Contraseña') }}
            </button>
        </div>
    </form>
</x-guest-layout>