<x-app-layout>
    <div class="container px-4">

        {{-- 1. Título --}}

        {{-- 2. Banner de Bienvenida (Estilo Moderno - Cian/Info) --}}
        <div class="p-5 mb-5 text-white rounded-4 shadow position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #6f0df0ff 0%, #0aa2c0 100%);">
            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-8">
                    <h3 class="fs-2 fw-bold mb-3">¡Hola, {{ Auth::user()->name }}!</h3>
                    <p class="fs-5 text-white-50 mb-0" style="max-width: 650px;">
                        Bienvenido/a a tu espacio para gestionar tus solicitudes de ajustes académicos.
                    </p>
                </div>
            </div>
            {{-- Decoración de fondo --}}
            <svg class="position-absolute bottom-0 end-0 text-white opacity-10" style="transform: rotateY(180deg); width: 300px;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path fill="currentColor" d="M42.7,-62.9C54.3,-53.4,61.8,-38.2,66.3,-22.7C70.8,-7.2,72.3,8.6,67.1,22.3C61.9,36,50,47.5,36.4,56.7C22.7,65.9,7.4,72.7,-7.7,72.1C-22.7,71.5,-37.4,63.5,-49.3,53C-61.2,42.5,-70.2,29.5,-74.6,15C-79,0.5,-78.8,-15.5,-72.4,-29.1C-66,-42.7,-53.4,-53.8,-39.7,-62C-26.1,-70.1,-13,-75.4,1.6,-77.6C16.3,-79.8,31.1,-72.4,42.7,-62.9Z" transform="translate(100 100)" />
            </svg>
        </div>

        @if(!$stats['tiene_perfil'])
            {{-- Alerta de Sin Perfil (Mejorada) --}}
            <div class="alert alert-warning shadow-sm border-0 rounded-4 d-flex align-items-center p-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-1 me-4 opacity-50"></i>
                <div>
                    <h4 class="alert-heading fw-bold mb-1">Perfil no encontrado</h4>
                    <p class="mb-0">No se encontró un perfil de estudiante asociado a tu cuenta. Por favor, contacta a la Encargada de Inclusión.</p>
                </div>
            </div>
        @else
            
            {{-- 3. Tarjeta de Acción Principal (Estilo Bandeja) --}}
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 col-lg-6">
                    <div class="card h-100 rounded-4 hover-lift border-0">
                        <div class="card-body p-5 d-flex flex-column text-center align-items-center">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary mb-4" style="width: 70px; height: 70px;">
                                <i class="bi bi-folder2-open fs-2"></i>
                            </div>
                            
                            <h4 class="fw-bold fs-4 text-dark mb-3">Mis Solicitudes</h4>
                            <p class="text-muted mb-4">
                                Revisa el estado de tus solicitudes y accede al detalle de tus ajustes académicos.
                            </p>
                            
                            <a href="{{ route('estudiante.casos.index') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-medium stretched-link shadow-sm">
                                Ver Mis Casos <i class="bi bi-arrow-right-circle ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Estadísticas (Estilo Resumen Rápido) --}}
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
                        <div class="card-header bg-white p-4 border-bottom-0 text-center">
                            <h5 class="fw-bold mb-1">Resumen de Actividad</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="row g-0">
                                
                                {{-- Total Solicitudes --}}
                                <div class="col-md-4 border-end border-light">
                                    <div class="p-5 text-center bg-primary bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                        <h2 class="display-4 fw-bold text-primary mb-1">{{ $stats['total'] }}</h2>
                                        <p class="text-muted fs-6 fw-bold mb-0">Total Solicitudes</p>
                                    </div>
                                </div>

                                {{-- En Proceso --}}
                                <div class="col-md-4 border-end border-light">
                                    <div class="p-5 text-center bg-warning bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                        <h2 class="display-4 fw-bold text-warning-emphasis mb-1">{{ $stats['activos'] }}</h2>
                                        <p class="text-muted fs-6 fw-bold mb-0">En Proceso</p>
                                        <small class="text-muted mt-2">(Siendo evaluadas)</small>
                                    </div>
                                </div>

                                {{-- Finalizadas --}}
                                <div class="col-md-4">
                                     <div class="p-5 text-center bg-success bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                        <h2 class="display-4 fw-bold text-success mb-1">{{ $stats['finalizados'] }}</h2>
                                        <p class="text-muted fs-6 fw-bold mb-0">Finalizadas</p>
                                        <small class="text-muted mt-2">(Con resolución)</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif

    </div>
</x-app-layout>