<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Family Central</title>

    <!-- Favicon Family Central -->
    <link rel="icon" type="image/png" href="{{ asset('images/familyenviosazul2.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/familyenviosazul2.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #0b0f19; color: #ffffff; font-family: 'Figtree', sans-serif; min-height: 100vh; display: flex; flex-direction: column; justify-content: space-between; margin: 0;">

    <!-- Navbar -->
    <header style="width: 100%; max-width: 1200px; margin: 0 auto; padding: 24px; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box;">
        <div style="display: flex; align-items: center; gap: 12px;">
            {{-- Ícono con letra "C" y fondo naranja corporativo --}}
            <div style="width: 40px; height: 40px; border-radius: 10px; background-color: #f6721d; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px; color: #ffffff;">
                C
            </div>
            <span style="font-weight: 700; font-size: 18px; color: #ffffff;">Centro de operaciones</span>
        </div>

        <div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" 
                       style="background-color: #1d3085; color: #ffffff; padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; text-decoration: none; display: inline-block;"
                       onmouseover="this.style.backgroundColor='#000232'"
                       onmouseout="this.style.backgroundColor='#1d3085'">
                        Ir al Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       style="background-color: #1d3085; color: #ffffff; padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;"
                       onmouseover="this.style.backgroundColor='#000232'"
                       onmouseout="this.style.backgroundColor='#1d3085'">
                        <span>Ingresar</span>
                    </a>
                @endauth
            @endif
        </div>
    </header>

    <!-- Hero Section -->
    <main style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 48px 24px; text-align: center;">
        <div style="max-width: 700px; width: 100%;">
            
            {{-- Ficha PLATAFORMA DE GESTIÓN en fondo naranja con texto blanco suave --}}
            <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #fff3eb; background-color: #f6721d; border: 1px solid #f6721d; display: inline-block; margin-bottom: 20px;">
                PLATAFORMA DE GESTIÓN
            </span>

            {{-- 📸 LOGO DE LA EMPRESA --}}
            <div style="margin-bottom: 24px; display: flex; justify-content: center;">
                <img src="{{ asset('images/FamilyEnviologo.png') }}" alt="Family Envíos Logo" style="max-width: 260px; height: auto; object-fit: contain;">
            </div>
            
            <h1 style="font-size: 42px; font-weight: 800; color: #ffffff; margin: 0 0 16px 0; line-height: 1.2;">
                Bienvenido a <span style="color: #f6721d;">Family Central</span>
            </h1>

            <p style="font-size: 16px; color: #9ca3af; line-height: 1.6; margin: 0 0 32px 0;">
                Accede al panel administrativo para gestionar tu paqueteria, agendamientos y monitoreo en tiempo real de forma rápida y segura.
            </p>

            <div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           style="background-color: #1d3085; color: #ffffff; padding: 14px 32px; border-radius: 10px; font-weight: 600; font-size: 16px; text-decoration: none; display: inline-block;"
                           onmouseover="this.style.backgroundColor='#000232'"
                           onmouseout="this.style.backgroundColor='#1d3085'">
                            Entrar al Panel
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           style="background-color: #1d3085; color: #ffffff; padding: 14px 32px; border-radius: 10px; font-weight: 600; font-size: 16px; text-decoration: none; display: inline-block;"
                           onmouseover="this.style.backgroundColor='#000232'"
                           onmouseout="this.style.backgroundColor='#1d3085'">
                            Ingresar a la cuenta
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer style="width: 100%; max-width: 1200px; margin: 0 auto; padding: 24px; text-align: center; font-size: 12px; color: #6b7280; border-top: 1px solid #1f2937; box-sizing: border-box;">
        &copy; {{ date('Y') }} Family Envios. Todos los derechos reservados.
    </footer>

</body>
</html>