<x-guest-layout>
    <div class="mb-4 text-sm text-white">
        {{ __('Ingresa tu correo electrónico registrado para recuperar tu cuenta mediante tus preguntas de seguridad.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.security.verify') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" class="text-white" :value="__('Correo Electrónico')" />
            <x-text-input id="email" 
                          class="block mt-1 w-full text-white bg-transparent border-gray-600 focus:border-indigo-500" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" class="text-sm text-white hover:text-gray-300 underline">
                {{ __('Volver al Login') }}
            </a>

            <button type="submit" 
                    style="background-color: #1d3085;"
                    onmouseover="this.style.backgroundColor='#000232'"
                    onmouseout="this.style.backgroundColor='#1d3085'"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 shadow-sm ms-3">
                {{ __('Continuar') }}
            </button>
        </div>
    </form>
</x-guest-layout>