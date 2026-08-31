<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" style="background-color: #1d3085 !important; position: sticky; top: 0; z-index: 50;">
    <style>
        /* Forzar fondo azul oscuro en el contenedor principal de la cabecera */
        nav {
            background-color: #1d3085 !important;
            border-bottom: 1px solid #000232 !important;
        }

        /* Quitar fondos blancos/grises predeterminados que hereda de Breeze */
        nav div, nav header {
            background-color: transparent !important;
        }

        /* Forzar texto blanco y peso de fuente delgado (font-weight: 500) en el menú principal */
        nav > div a, nav > div button, nav > div button span {
            color: #ffffff !important;
            font-size: 0.95rem !important;
            font-weight: 500 !important;
        }

        /* Color al pasar el cursor (Hover) en la barra principal */
        nav > div a:hover, nav > div button:hover {
            color: #f6721d !important;
        }

        /* ESTADO ACTIVO: Pinta únicamente la pestaña activa de naranja */
        nav a[class*="border-indigo"] {
            color: #f6721d !important;
            border-color: #f6721d !important;
        }

        /* ESTADO INACTIVO: Mantiene el borde inferior transparente en las demás pestañas */
        nav a[class*="border-transparent"] {
            border-color: transparent !important;
        }

        /* 🎨 CORRECCIÓN DE ESTILOS DEL DROPDOWN DE USUARIO */
        .dropdown-menu-custom a, 
        .dropdown-menu-custom button {
            color: #1f2937 !important;
            background-color: #ffffff !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
        }

        .dropdown-menu-custom a:hover, 
        .dropdown-menu-custom button:hover {
            color: #f6721d !important;
            background-color: #f9fafb !important;
        }
    </style>

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo ajustado a un tamaño más prominente (h-12) -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-12 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate class="text-base font-medium">
                        {{ __('📊 Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('agenda.index')" :active="request()->routeIs('agenda.*')" wire:navigate class="text-base font-medium">
                        {{ __('🗓️ Agenda Semanal') }}
                    </x-nav-link>

                    <!-- Historial Completo -->
                    <x-nav-link :href="route('history.index')" :active="request()->routeIs('history.index')" class="text-base font-medium">
                        {{ __('📜 Historial') }}
                    </x-nav-link>

                    <x-nav-link :href="route('tracking.map')" :active="request()->routeIs('tracking.map')" class="text-base font-medium">
                        {{ __('📍 Monitoreo GPS') }}
                    </x-nav-link>

                    <!-- 🛡️ Panel de Administración (Visible solo para Admins) -->
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.*')" wire:navigate class="text-base font-medium text-amber-400 hover:text-amber-300">
                            {{ __('🛡️ Panel Administrador') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#000232] hover:text-orange-400 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="dropdown-menu-custom">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            <!-- Option rápida a Panel Admin en el dropdown si es admin -->
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <x-dropdown-link :href="route('admin.users.index')" wire:navigate class="text-amber-600 font-semibold">
                                    {{ __('Administración') }}
                                </x-dropdown-link>
                            @endif

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </button>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-orange-400 hover:bg-[#000232] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#000232]">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('📊 Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('agenda.index')" :active="request()->routeIs('agenda.*')" wire:navigate>
                {{ __('🗓️ Agenda Semanal') }}
            </x-responsive-nav-link>

            <!-- Historial Completo -->
            <x-responsive-nav-link :href="route('history.index')" :active="request()->routeIs('history.index')">
                {{ __('📜 Historial') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('tracking.map')" :active="request()->routeIs('tracking.map')">
                {{ __('📍 Monitoreo GPS') }}
            </x-responsive-nav-link>

            <!-- 🛡️ Panel Admin Responsive -->
            @if(auth()->check() && auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.*')" wire:navigate class="text-amber-400">
                    {{ __('🛡️ Panel Administrador') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-[#1d3085]">
            <div class="px-4">
                <div class="font-medium text-base text-white" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-300">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>