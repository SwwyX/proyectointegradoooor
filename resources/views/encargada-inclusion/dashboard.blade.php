<x-app-layout>
    <div class="container px-4">


        {{-- 2. Banner de Bienvenida --}}
        <div class="p-5 mb-5 text-white rounded-4 shadow position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #6f0df0ff 0%, #0aa2c0 100%);">
            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-8">
                    <h3 class="fs-2 fw-bold mb-3">¡Hola, {{ Auth::user()->name }}!</h3>
                    <p class="fs-5 text-white-50 mb-0" style="max-width: 650px;">
                        Bienvenido/a a tu panel principal para la gestión inicial y seguimiento de casos (Anamnesis).
                    </p>
                </div>
            </div>
            <svg class="position-absolute bottom-0 end-0 text-white opacity-10" style="transform: rotateY(180deg); width: 300px;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path fill="currentColor" d="M42.7,-62.9C54.3,-53.4,61.8,-38.2,66.3,-22.7C70.8,-7.2,72.3,8.6,67.1,22.3C61.9,36,50,47.5,36.4,56.7C22.7,65.9,7.4,72.7,-7.7,72.1C-22.7,71.5,-37.4,63.5,-49.3,53C-61.2,42.5,-70.2,29.5,-74.6,15C-79,0.5,-78.8,-15.5,-72.4,-29.1C-66,-42.7,-53.4,-53.8,-39.7,-62C-26.1,-70.1,-13,-75.4,1.6,-77.6C16.3,-79.8,31.1,-72.4,42.7,-62.9Z" transform="translate(100 100)" />
            </svg>
        </div>

        {{-- 3. Tarjetas de Acción --}}
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 rounded-4 hover-lift border-0">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-file-earmark-plus-fill fs-4"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold fs-5 text-dark">Registrar Nuevo Caso</h4>
                        <p class="text-muted small mb-4 flex-grow-1">Iniciar el proceso (Anamnesis) para un estudiante.</p>
                        <a href="{{ route('encargada.casos.create') }}" class="btn btn-primary w-100 rounded-pill fw-medium stretched-link py-2">
                            Crear Caso <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 rounded-4 hover-lift border-0" style="border-bottom: 4px solid #ffc107 !important;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="icon-box bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-folder-fill fs-4"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold fs-5 text-dark">Ver Casos en Proceso</h4>
                        <p class="text-muted small mb-4 flex-grow-1">Consultar estado de los casos pendientes de validación.</p>
                        <a href="{{ route('encargada.casos.index') }}" class="btn btn-outline-warning text-dark w-100 rounded-pill fw-medium stretched-link py-2 border-2">
                            Ver Listado <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Sección de Estadísticas (3 COLUMNAS) --}}
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
                    <div class="card-header bg-white p-4 border-bottom-0 text-center">
                        <h5 class="fw-bold mb-1">Resumen Rápido</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            
                            {{-- 1. Total Casos --}}
                            <div class="col-md-4 border-end border-light">
                                <div class="p-5 text-center bg-primary bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-people-fill text-primary mb-3" style="font-size: 3rem;"></i>
                                    <h2 class="display-4 fw-bold text-dark mb-1">{{ $stats['total'] ?? 0 }}</h2>
                                    <p class="text-muted fs-6 fw-bold mb-0">Total Registrados</p>
                                </div>
                            </div>

                            {{-- 2. En Gestión CTP (Color Indigo/Info) --}}
                            <div class="col-md-4 border-end border-light">
                                <div class="p-5 text-center bg-indigo-subtle h-100 d-flex flex-column justify-content-center" style="background-color: #f3e8ff;">
                                    <i class="bi bi-pencil-square text-purple mb-3" style="font-size: 3rem; color: #6f42c1;"></i>
                                    <h2 class="display-4 fw-bold text-dark mb-1">{{ $stats['gestion_ctp'] ?? 0 }}</h2>
                                    <p class="text-muted fs-6 fw-bold mb-0">En Gestión CTP</p>
                                    <small class="text-muted mt-2">(Redacción de Ajustes)</small>
                                </div>
                            </div>

                            {{-- 3. Pendientes de Validación (Color Warning) --}}
                            <div class="col-md-4">
                                 <div class="p-5 text-center bg-warning bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-hourglass-split text-warning mb-3" style="font-size: 3rem;"></i>
                                    <h2 class="display-4 fw-bold text-dark mb-1">{{ $stats['proceso'] ?? 0 }}</h2>
                                    <p class="text-muted fs-6 fw-bold mb-0">Pendientes Validación</p>
                                    <small class="text-muted mt-2">(Director / Corrección)</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>