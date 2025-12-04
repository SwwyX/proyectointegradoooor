<x-app-layout>
    <div class="container px-4">



        {{-- 2. Alerta de Supervisión (Modernizada) --}}
        @if(isset($casosAntiguos) && $casosAntiguos->isNotEmpty())
            <div class="mb-5 alert alert-danger shadow-sm border-0 rounded-4 d-flex align-items-center p-4 bg-danger bg-opacity-10 text-danger" role="alert">
                <i class="bi bi-exclamation-octagon-fill fs-1 me-4"></i>
                <div>
                    <h4 class="alert-heading fw-bold mb-1">Alerta de Retraso</h4>
                    <p class="mb-2 text-dark">Hay <strong>{{ $casosAntiguos->count() }}</strong> casos nuevos que llevan más de 7 días sin ser gestionados.</p>
                    <a href="{{ route('casos.index') }}" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm">
                        Ver Casos Atrasados <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        @endif

        {{-- 3. Banner de Bienvenida (Estilo Asesoría - Teal) --}}
        <div class="p-5 mb-5 text-white rounded-4 shadow position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #0b5555ff 0%, #20c997 100%);">
            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-8">
                    <h3 class="fs-2 fw-bold mb-3">Panel de Supervisión Global</h3>
                    <p class="fs-5 text-white-50 mb-0" style="max-width: 650px;">
                        Monitoreo completo del flujo de inclusión (Control Total).
                    </p>
                </div>
            </div>
            {{-- Decoración de fondo --}}
            <svg class="position-absolute bottom-0 end-0 text-white opacity-10" style="transform: rotateY(180deg); width: 300px;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path fill="currentColor" d="M42.7,-62.9C54.3,-53.4,61.8,-38.2,66.3,-22.7C70.8,-7.2,72.3,8.6,67.1,22.3C61.9,36,50,47.5,36.4,56.7C22.7,65.9,7.4,72.7,-7.7,72.1C-22.7,71.5,-37.4,63.5,-49.3,53C-61.2,42.5,-70.2,29.5,-74.6,15C-79,0.5,-78.8,-15.5,-72.4,-29.1C-66,-42.7,-53.4,-53.8,-39.7,-62C-26.1,-70.1,-13,-75.4,1.6,-77.6C16.3,-79.8,31.1,-72.4,42.7,-62.9Z" transform="translate(100 100)" />
            </svg>
        </div>

        {{-- 4. Tarjeta de Acción Principal (Centrada) --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 col-lg-6">
                <div class="card h-100 rounded-4 hover-lift border-0">
                    <div class="card-body p-5 d-flex flex-column text-center align-items-center">
                        <div class="icon-box bg-dark bg-opacity-10 text-dark mb-4" style="width: 70px; height: 70px;">
                            <i class="bi bi-database-gear fs-2"></i>
                        </div>
                        
                        <h4 class="fw-bold fs-4 text-dark mb-3">Gestión Maestra de Casos</h4>
                        <p class="text-muted mb-4">
                            Acceso total para crear, editar, eliminar o auditar cualquier caso del sistema.
                        </p>
                        
                        <a href="{{ route('casos.index') }}" class="btn btn-dark rounded-pill px-5 py-2 fw-medium stretched-link shadow-sm">
                            Ir al Listado Maestro <i class="bi bi-arrow-right-circle ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. Estadísticas del Sistema (Diseño Legible) --}}
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
                    <div class="card-header bg-white p-4 border-bottom-0 text-center">
                        <h5 class="fw-bold mb-1">Estado del Sistema</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            
                            {{-- 1. Total Histórico --}}
                            <div class="col-md-6 col-lg-3 border-end border-light">
                                <div class="p-5 text-center bg-light h-100 d-flex flex-column justify-content-center">
                                    <h2 class="display-4 fw-bold text-secondary mb-1">{{ $stats['total'] ?? 0 }}</h2>
                                    <p class="text-muted fs-6 fw-bold mb-0">Total Histórico</p>
                                </div>
                            </div>

                            {{-- 2. En Gestión CTP (ARREGLADO PARA QUE SE VEA BIEN) --}}
                            <div class="col-md-6 col-lg-3 border-end border-light">
                                <div class="p-5 text-center bg-info bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-pencil-square text-info mb-3 d-block" style="font-size: 2rem;"></i>
                                    <h2 class="display-4 fw-bold text-dark mb-1">{{ $stats['esperando_ctp'] ?? 0 }}</h2>
                                    <p class="text-muted fs-6 fw-bold mb-0">En Gestión CTP</p>
                                    <small class="text-muted" style="font-size: 0.75rem;">(Redacción/Corrección)</small>
                                </div>
                            </div>

                            {{-- 3. En Validación Director --}}
                            <div class="col-md-6 col-lg-3 border-end border-light">
                                <div class="p-5 text-center bg-warning bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-hourglass-split text-warning mb-3 d-block" style="font-size: 2rem;"></i>
                                    <h2 class="display-4 fw-bold text-dark mb-1">{{ $stats['esperando_director'] ?? 0 }}</h2>
                                    <p class="text-muted fs-6 fw-bold mb-0">En Validación</p>
                                    <small class="text-muted" style="font-size: 0.75rem;">(Director)</small>
                                </div>
                            </div>

                            {{-- 4. Finalizados --}}
                            <div class="col-md-6 col-lg-3">
                                 <div class="p-5 text-center bg-success bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                    <i class="bi bi-check-circle-fill text-success mb-3 d-block" style="font-size: 2rem;"></i>
                                    <h2 class="display-4 fw-bold text-success mb-1">{{ $stats['cerrados'] ?? 0 }}</h2>
                                    <p class="text-muted fs-6 fw-bold mb-0">Finalizados</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>