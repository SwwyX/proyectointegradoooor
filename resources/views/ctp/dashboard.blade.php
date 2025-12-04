<x-app-layout>
    <div class="container px-4">



        {{-- 2. Banner de Bienvenida (Estilo CTP - Morado) --}}
        <div class="p-5 mb-5 text-white rounded-4 shadow position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #1c7432b7 0%, #38a5ff94 100%);">
            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-8">
                    <h3 class="fs-2 fw-bold mb-3">¡Bienvenido/a, {{ Auth::user()->name }}!</h3>
                    <p class="fs-5 text-white-50 mb-0" style="max-width: 650px;">
                        Panel principal para la redacción de apoyos metodológicos y estrategias pedagógicas.
                    </p>
                </div>
            </div>
            {{-- Decoración de fondo --}}
            <svg class="position-absolute bottom-0 end-0 text-white opacity-10" style="transform: rotateY(180deg); width: 300px;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path fill="currentColor" d="M42.7,-62.9C54.3,-53.4,61.8,-38.2,66.3,-22.7C70.8,-7.2,72.3,8.6,67.1,22.3C61.9,36,50,47.5,36.4,56.7C22.7,65.9,7.4,72.7,-7.7,72.1C-22.7,71.5,-37.4,63.5,-49.3,53C-61.2,42.5,-70.2,29.5,-74.6,15C-79,0.5,-78.8,-15.5,-72.4,-29.1C-66,-42.7,-53.4,-53.8,-39.7,-62C-26.1,-70.1,-13,-75.4,1.6,-77.6C16.3,-79.8,31.1,-72.4,42.7,-62.9Z" transform="translate(100 100)" />
            </svg>
        </div>

        {{-- 3. Tarjetas de Acción --}}
        <div class="row justify-content-center mb-5">
            
            {{-- Tarjeta: Bandeja de Casos --}}
            <div class="col-md-8 col-lg-6">
                <div class="card h-100 rounded-4 hover-lift border-0">
                    <div class="card-body p-5 d-flex flex-column text-center align-items-center">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary mb-4" style="width: 70px; height: 70px;">
                            <i class="bi bi-pencil-square fs-2"></i>
                        </div>
                        
                        <h4 class="fw-bold fs-4 text-dark mb-3">Bandeja de Casos</h4>
                        <p class="text-muted mb-4">
                            Acceda al listado completo de casos para redactar ajustes razonables o corregir observaciones pendientes.
                        </p>
                        
                        <a href="{{ route('ctp.casos.index') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-medium stretched-link shadow-sm">
                            Ir a la Bandeja <i class="bi bi-arrow-right-circle ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- 4. Sección de Estadísticas (Resumen Rápido) --}}
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
                    <div class="card-header bg-white p-4 border-bottom-0 text-center">
                        <h5 class="fw-bold mb-1">Resumen de Tareas</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            
                            {{-- Estadística: Nuevos Casos --}}
                            <div class="col-md-6 border-end border-light">
                                <div class="p-5 text-center bg-info bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-file-earmark-plus text-info mb-3" style="font-size: 3rem;"></i>
                                    <h2 class="display-4 fw-bold text-dark mb-1">{{ $stats['nuevos'] ?? 0 }}</h2>
                                    <p class="text-muted fs-6 fw-bold mb-0">Nuevos Casos (Por Redactar)</p>
                                </div>
                            </div>

                            {{-- Estadística: Total Gestionados --}}
                            <div class="col-md-6">
                                 <div class="p-5 text-center bg-primary bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-send-check text-primary mb-3" style="font-size: 3rem;"></i>
                                    <h2 class="display-4 fw-bold text-dark mb-1">{{ $stats['gestionados'] ?? 0 }}</h2>
                                    <p class="text-muted fs-6 fw-bold mb-0">Total Enviados a Dirección</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>