<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/js/app.js'])

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        {{-- ESTILOS GLOBALES PERSONALIZADOS (Diseño Azul Acero) --}}
        <style>
            /* --- FONDO GLOBAL --- */
            .main-content-wrapper {
                background-color: #f8f5f5ff; /* El Azul Acero Suave que elegiste */
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* --- ESTILOS DE TARJETAS (Disponibles para todas las vistas) --- */
            /* Efecto de elevación suave */
            .hover-lift {
                transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                background-color: #ffffff !important;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
                border: none !important; /* Quitamos bordes predeterminados */
            }
            .hover-lift:hover {
                transform: translateY(-6px);
                box-shadow: 0 12px 25px rgba(39, 59, 89, 0.15) !important;
            }
            
            /* Caja para iconos con fondo suave */
            .icon-box {
                width: 52px;
                height: 52px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 14px;
            }
            
            /* Banner degradado */
            .bg-gradient-supportive {
                background: linear-gradient(to right, #2c7be5, #5e93da);
            }

            /* Utilidad para redondear bordes más suavemente (opcional pero recomendada) */
            .rounded-4 { border-radius: 1rem !important; }
        </style>
    </head>
    <body> 

        {{-- Usamos nuestra clase .main-content-wrapper en lugar de bg-body-tertiary --}}
        <div class="main-content-wrapper">
            
            @include('layouts.navigation')

            @if (isset($header))
                {{-- Header con fondo blanco para que contraste con el azul --}}
                <header class="bg-white shadow-sm">
                    <div class="container-lg py-4 px-4">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{-- Agregamos py-5 aquí para dar espacio vertical en TODAS las páginas automáticamente --}}
            <main class="py-5">
                {{ $slot }} 
            </main>
        </div>

        {{-- Scripts de Bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        {{-- Stack de scripts preservado --}}
        @stack('scripts')

    </body>
</html>