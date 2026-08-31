<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight flex items-center gap-2" style="color: #1d3085;">
            🔐 {{ __('Configuración de Autenticación 2FA') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-gray-100 max-w-2xl mx-auto">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Autenticación de Dos Factores (2FA)') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Añade una capa extra de seguridad escaneando este código QR con Google Authenticator, Authy o tu aplicación preferida.') }}
                        </p>
                    </header>

                    {{-- Estado actual / Mensaje de éxito --}}
                    @if (session('status'))
                        <div class="mt-4 p-4 text-sm text-emerald-800 bg-emerald-50 rounded-lg border border-emerald-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($enabled)
                        {{-- VISTA SI YA ESTÁ ACTIVADO --}}
                        <div class="mt-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-800 text-sm">
                            ✔ La autenticación de dos factores está <strong>activada</strong> en tu cuenta.
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <a href="{{ route('profile') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                                {{ __('Volver al Perfil') }}
                            </a>

                            <form action="{{ route('2fa.disable') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="background-color: #f6721d;"
                                        onmouseover="this.style.backgroundColor='#d85a10'"
                                        onmouseout="this.style.backgroundColor='#f6721d'"
                                        onclick="return confirm('¿Estás seguro de que deseas desactivar la autenticación de 2 factores?')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 shadow-sm">
                                    {{ __('Desactivar 2FA') }}
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- VISTA PARA ESCANEAR Y ACTIVAR --}}
                        <div class="mt-6 flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl">
                            <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                                {!! $qrImage !!}
                            </div>

                            @if ($secret)
                                <div class="mt-4 text-center">
                                    <span class="text-xs text-gray-500 block uppercase tracking-wider font-semibold">{{ __('¿No puedes escanear el QR?') }}</span>
                                    <p class="text-sm text-gray-700 font-mono bg-white px-3 py-1 rounded border border-gray-200 mt-1 inline-block select-all">
                                        {{ $secret }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Formulario para enviar el código del Authenticator --}}
                        <form action="{{ route('2fa.enable') }}" method="POST" class="mt-6 space-y-6">
                            @csrf

                            <div>
                                <label for="code" class="block font-medium text-sm text-gray-700">
                                    {{ __('Código de Verificación (6 dígitos)') }}
                                </label>
                                <input id="code" 
                                       name="code" 
                                       type="text" 
                                       maxlength="6" 
                                       inputmode="numeric" 
                                       style="outline-color: #1d3085;"
                                       class="mt-1 block w-full bg-white border border-gray-300 text-gray-900 rounded-md shadow-sm text-sm px-3 py-2 text-center text-lg tracking-widest font-mono focus:border-indigo-500 focus:ring-indigo-500" 
                                       required 
                                       placeholder="123456" 
                                       autocomplete="off" 
                                       autofocus />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                <a href="{{ route('profile') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                                    {{ __('Volver al Perfil') }}
                                </a>

                                <button type="submit" 
                                        style="background-color: #1d3085;"
                                        onmouseover="this.style.backgroundColor='#000232'"
                                        onmouseout="this.style.backgroundColor='#1d3085'"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 shadow-sm">
                                    {{ __('Confirmar y Activar') }}
                                </button>
                            </div>
                        </form>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>