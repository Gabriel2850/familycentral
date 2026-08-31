<x-guest-layout>
    <div class="mb-4 text-sm text-white">
        {{ __('Esta es una área segura de la aplicación. Por favor, confirma el acceso ingresando el código de autenticación de 6 dígitos proporcionado por tu aplicación Authenticator.') }}
    </div>

    <form method="POST" action="{{ route('2fa.challenge.verify') }}">
        @csrf

        <div>
            <x-input-label for="code" class="text-white" :value="__('Código de Verificación')" />
            <x-text-input id="code" 
                          class="block mt-1 w-full text-center text-lg tracking-widest font-mono text-white bg-transparent border-gray-600 focus:border-indigo-500" 
                          type="text" 
                          name="code" 
                          maxlength="6"
                          inputmode="numeric"
                          required 
                          autofocus 
                          autocomplete="one-time-code" 
                          placeholder="123456" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" class="text-sm text-white hover:text-gray-300 underline">
                {{ __('Volver al Login') }}
            </a>

            <button type="submit" 
                    style="background-color: #1d3085;"
                    onmouseover="this.style.backgroundColor='#000232'"
                    onmouseout="this.style.backgroundColor='#1d3085'"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 shadow-sm">
                {{ __('Confirmar') }}
            </button>
        </div>
    </form>
</x-guest-layout>