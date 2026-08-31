<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight flex items-center gap-2" style="color: #1d3085;">
            👤 {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Formulario 1: Información Personal --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            {{-- Formulario 2: Actualizar Contraseña --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            {{-- Formulario 3: Preguntas de Seguridad --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Preguntas de Seguridad') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Establece tus preguntas de seguridad para poder recuperar el acceso a tu cuenta si olvidas tu contraseña de forma gratuita y sin correo.') }}
                            </p>
                        </header>

                        <form action="{{ route('profile.security_questions') }}" method="POST" class="mt-6 space-y-6">
                            @csrf

                            {{-- Mensaje de éxito --}}
                            @if (session('status'))
                                <div class="p-4 mb-4 text-sm text-emerald-800 bg-emerald-50 rounded-lg border border-emerald-200">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <!-- Pregunta 1 -->
                            <div>
                                <label for="security_question_1" class="block font-medium text-sm text-gray-700">
                                    {{ __('Pregunta 1') }}
                                </label>
                                <select name="security_question_1" id="security_question_1" class="mt-1 block w-full bg-white border border-gray-300 text-gray-900 rounded-md shadow-sm text-sm" required>
                                    <option value="" class="bg-white text-gray-900">-- Selecciona una pregunta --</option>
                                    <option value="¿Nombre de tu primera mascota?" @selected(auth()->user()->security_question_1 == '¿Nombre de tu primera mascota?') class="bg-white text-gray-900">¿Nombre de tu primera mascota?</option>
                                    <option value="¿Ciudad donde naciste?" @selected(auth()->user()->security_question_1 == '¿Ciudad donde naciste?') class="bg-white text-gray-900">¿Ciudad donde naciste?</option>
                                    <option value="¿Nombre de tu escuela primaria?" @selected(auth()->user()->security_question_1 == '¿Nombre de tu escuela primaria?') class="bg-white text-gray-900">¿Nombre de tu escuela primaria?</option>
                                </select>
                                <x-input-error :messages="$errors->get('security_question_1')" class="mt-2" />
                            </div>

                            <div>
                                <label for="security_answer_1" class="block font-medium text-sm text-gray-700">
                                    {{ __('Respuesta 1') }}
                                </label>
                                <input id="security_answer_1" name="security_answer_1" type="text" class="mt-1 block w-full bg-white border border-gray-300 text-gray-900 rounded-md shadow-sm text-sm px-3 py-2 placeholder-gray-400" required placeholder="Tu respuesta (se guardará encriptada)" autocomplete="off" />
                                <x-input-error :messages="$errors->get('security_answer_1')" class="mt-2" />
                            </div>

                            <!-- Pregunta 2 -->
                            <div>
                                <label for="security_question_2" class="block font-medium text-sm text-gray-700">
                                    {{ __('Pregunta 2') }}
                                </label>
                                <select name="security_question_2" id="security_question_2" class="mt-1 block w-full bg-white border border-gray-300 text-gray-900 rounded-md shadow-sm text-sm" required>
                                    <option value="" class="bg-white text-gray-900">-- Selecciona una pregunta --</option>
                                    <option value="¿Marca de tu primer vehículo?" @selected(auth()->user()->security_question_2 == '¿Marca de tu primer vehículo?') class="bg-white text-gray-900">¿Marca de tu primer vehículo?</option>
                                    <option value="¿Nombre de tu mejor amigo de la infancia?" @selected(auth()->user()->security_question_2 == '¿Nombre de tu mejor amigo de la infancia?') class="bg-white text-gray-900">¿Nombre de tu mejor amigo de la infancia?</option>
                                    <option value="¿Comida favorita de niño?" @selected(auth()->user()->security_question_2 == '¿Comida favorita de niño?') class="bg-white text-gray-900">¿Comida favorita de niño?</option>
                                </select>
                                <x-input-error :messages="$errors->get('security_question_2')" class="mt-2" />
                            </div>

                            <div>
                                <label for="security_answer_2" class="block font-medium text-sm text-gray-700">
                                    {{ __('Respuesta 2') }}
                                </label>
                                <input id="security_answer_2" name="security_answer_2" type="text" class="mt-1 block w-full bg-white border border-gray-300 text-gray-900 rounded-md shadow-sm text-sm px-3 py-2 placeholder-gray-400" required placeholder="Tu respuesta (se guardará encriptada)" autocomplete="off" />
                                <x-input-error :messages="$errors->get('security_answer_2')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Guardar Preguntas') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            {{-- Formulario 4: Autenticación 2FA --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Autenticación de Dos Factores (2FA)') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Añade una capa extra de seguridad a tu cuenta utilizando una aplicación de autenticación (Google Authenticator, Authy, etc.).') }}
                            </p>
                        </header>

                        <div class="mt-6">
                            @if (session('status'))
                                <div class="p-4 mb-4 text-sm text-emerald-800 bg-emerald-50 rounded-lg border border-emerald-200">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if (auth()->user()->two_factor_enabled)
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-4">
                                    ● Estado: Activado
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('2fa.setup') }}" 
                                       style="background-color: #1d3085;"
                                       onmouseover="this.style.backgroundColor='#000232'"
                                       onmouseout="this.style.backgroundColor='#1d3085'"
                                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                                        {{ __('Ver Configuración QR') }}
                                    </a>

                                    <form action="{{ route('2fa.disable') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="background-color: #f6721d;"
                                                onmouseover="this.style.backgroundColor='#d85a10'"
                                                onmouseout="this.style.backgroundColor='#f6721d'"
                                                onclick="return confirm('¿Estás seguro de que deseas desactivar la autenticación de 2 factores?')"
                                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                                            {{ __('Desactivar 2FA') }}
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 mb-4">
                                    ● Estado: Desactivado
                                </div>
                                <div>
                                    <a href="{{ route('2fa.setup') }}" 
                                       style="background-color: #1d3085;"
                                       onmouseover="this.style.backgroundColor='#000232'"
                                       onmouseout="this.style.backgroundColor='#1d3085'"
                                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                                        {{ __('Activar 2FA') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            {{-- Formulario 5: Eliminar Cuenta --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>