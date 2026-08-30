<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-family-blue leading-tight flex items-center gap-2">
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

            {{-- Formulario 3: Eliminar Cuenta --}}
            <div class="p-6 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>