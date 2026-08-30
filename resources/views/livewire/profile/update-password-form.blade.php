<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900">
            {{ __('Actualizar Contraseña') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantener la seguridad.') }}
        </p>
    </header>

   <form wire:submit="updatePassword" class="mt-6 space-y-6">
        <div>
            <label for="update_password_current_password" class="block text-sm font-bold text-gray-800">Contraseña Actual</label>
            <input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full rounded-md border-gray-300 bg-white text-gray-900 shadow-sm focus:border-[#1d3085] focus:ring-[#1d3085]" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-bold text-gray-800">Nueva Contraseña</label>
            <input wire:model="password" id="update_password_password" name="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 bg-white text-gray-900 shadow-sm focus:border-[#1d3085] focus:ring-[#1d3085]" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-bold text-gray-800">Confirmar Contraseña</label>
            <input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-md border-gray-300 bg-white text-gray-900 shadow-sm focus:border-[#1d3085] focus:ring-[#1d3085]" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

     <!-- Botón de guardado de contraseña -->
        <div class="pt-4 flex items-center gap-4 border-t border-gray-100">
            <button 
                type="submit" 
                style="background-color: #f6721d !important; color: #ffffff !important; padding: 10px 20px !important;"
                class="inline-block text-white font-bold text-sm rounded-lg shadow-sm hover:opacity-90 transition duration-150 ease-in-out cursor-pointer border-0"
            >
                Guardar Contraseña
            </button>

            <x-action-message class="text-sm font-bold text-emerald-600" on="password-updated">
            <span style="color: #059669 !important; font-weight: 700 !important;">✓ Guardado correctamente.</span>
            </x-action-message>
        </div>
    </form>
</section>