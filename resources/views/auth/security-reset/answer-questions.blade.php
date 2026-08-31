<x-guest-layout>
    <div class="mb-4 text-sm text-white">
        {{ __('Responde correctamente a tus preguntas de seguridad para restablecer tu contraseña.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.security.answers') }}">
        @csrf

        <div class="space-y-4">
            <!-- Pregunta 1 -->
            <div>
                <x-input-label class="text-white font-semibold" :value="$user->security_question_1" />
                <x-text-input id="answer_1" 
                              class="block mt-1 w-full text-white bg-transparent border-gray-600 focus:border-indigo-500" 
                              type="text" 
                              name="answer_1" 
                              required 
                              autofocus />
            </div>

            <!-- Pregunta 2 -->
            <div>
                <x-input-label class="text-white font-semibold" :value="$user->security_question_2" />
                <x-text-input id="answer_2" 
                              class="block mt-1 w-full text-white bg-transparent border-gray-600 focus:border-indigo-500" 
                              type="text" 
                              name="answer_2" 
                              required />
            </div>

            <x-input-error :messages="$errors->get('answers')" class="mt-2 text-red-400" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" class="text-sm text-white hover:text-gray-300 underline">
                {{ __('Cancelar') }}
            </a>

            <button type="submit" 
                    style="background-color: #1d3085;"
                    onmouseover="this.style.backgroundColor='#000232'"
                    onmouseout="this.style.backgroundColor='#1d3085'"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 shadow-sm ms-3">
                {{ __('Validar Respuestas') }}
            </button>
        </div>
    </form>
</x-guest-layout>