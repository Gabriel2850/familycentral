<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        // Si el usuario tiene 2FA activo, LoginForm detiene el flujo y redirige al challenge.
        if (session()->has('2fa_user_id')) {
            return;
        }

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" class="text-white" :value="__('Correo Electrónico')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full text-white bg-transparent border-gray-600 focus:border-indigo-500" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" class="text-white" :value="__('Contraseña')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full text-white bg-transparent border-gray-600 focus:border-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-white">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            {{-- Enlace a Preguntas de Seguridad usando la ruta registrada --}}
            @if (Route::has('password.security.request'))
                <a class="underline text-sm text-white hover:text-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                   href="{{ route('password.security.request') }}" wire:navigate>
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @else
                <div></div>
            @endif

            {{-- Botón con azul corporativo --}}
            <button type="submit" 
                    style="background-color: #1d3085;"
                    onmouseover="this.style.backgroundColor='#000232'"
                    onmouseout="this.style.backgroundColor='#1d3085'"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150 shadow-sm ms-3">
                {{ __('Iniciar Sesión') }}
            </button>
        </div>
    </form>
</div>