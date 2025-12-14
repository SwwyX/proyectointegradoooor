<x-app-layout>
    <div class="container px-4 py-5">

        {{-- 1. Banner de Bienvenida --}}
        <div class="p-5 mb-5 text-white rounded-4 shadow position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #6f0df0ff 0%, #0aa2c0 100%);">
            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-8">
                    <h3 class="fs-2 fw-bold mb-3">¡Hola, {{ Auth::user()->name }}!</h3>
                    <p class="fs-5 text-white-50 mb-0" style="max-width: 650px;">
                        Bienvenido/a a tu panel principal para la gestión inicial y seguimiento de casos.
                    </p>
                </div>
            </div>
            <svg class="position-absolute bottom-0 end-0 text-white opacity-10" style="transform: rotateY(180deg); width: 300px;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path fill="currentColor" d="M42.7,-62.9C54.3,-53.4,61.8,-38.2,66.3,-22.7C70.8,-7.2,72.3,8.6,67.1,22.3C61.9,36,50,47.5,36.4,56.7C22.7,65.9,7.4,72.7,-7.7,72.1C-22.7,71.5,-37.4,63.5,-49.3,53C-61.2,42.5,-70.2,29.5,-74.6,15C-79,0.5,-78.8,-15.5,-72.4,-29.1C-66,-42.7,-53.4,-53.8,-39.7,-62C-26.1,-70.1,-13,-75.4,1.6,-77.6C16.3,-79.8,31.1,-72.4,42.7,-62.9Z" transform="translate(100 100)" />
            </svg>
        </div>

        {{-- 2. TARJETAS DE ACCIÓN (Ahora en cuadrícula 2x2) --}}
        <div class="row g-4 mb-5">
            
            {{-- TARJETA 1: Crear Caso --}}
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 rounded-4 hover-lift border-0 shadow-sm">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-file-earmark-plus-fill fs-4"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold fs-5 text-dark">Registrar Caso</h4>
                        <p class="text-muted small mb-4 flex-grow-1">Iniciar el proceso (Anamnesis) para un estudiante.</p>
                        <a href="{{ route('encargada.casos.create') }}" class="btn btn-primary w-100 rounded-pill fw-medium stretched-link py-2">
                            Crear Caso <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TARJETA 2: Ver Casos en Proceso --}}
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 rounded-4 hover-lift border-0 shadow-sm" style="border-bottom: 4px solid #ffc107 !important;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="icon-box bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-folder-fill fs-4"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold fs-5 text-dark">En Proceso</h4>
                        <p class="text-muted small mb-4 flex-grow-1">Consultar estado de casos pendientes.</p>
                        <a href="{{ route('encargada.casos.index') }}" class="btn btn-outline-warning text-dark w-100 rounded-pill fw-medium stretched-link py-2 border-2">
                            Ver Listado <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TARJETA 3: Historial Finalizados (NUEVA) --}}
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 rounded-4 hover-lift border-0 shadow-sm" style="border-bottom: 4px solid #198754 !important;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="icon-box bg-success bg-opacity-10 text-success">
                                <i class="bi bi-archive-fill fs-4"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold fs-5 text-dark">Historial Final</h4>
                        <p class="text-muted small mb-4 flex-grow-1">Acceder a informes y casos cerrados.</p>
                        {{-- Ruta Nueva que crearemos --}}
                        <a href="{{ route('encargada.casos.finalizados') }}" class="btn btn-outline-success text-dark w-100 rounded-pill fw-medium stretched-link py-2 border-2">
                            Ver Historial <i class="bi bi-clock-history ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TARJETA 4: AGENDA --}}
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 rounded-4 hover-lift border-0 shadow-sm" style="border-bottom: 4px solid #20c997 !important;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-box bg-teal-subtle text-teal" style="background-color: #e6f2f2; color: #008080;">
                                <i class="bi bi-calendar-week fs-4"></i>
                            </div>
                            @if($citasPendientes > 0)
                                <span class="badge bg-danger rounded-pill shadow-sm animate__animated animate__pulse animate__infinite">
                                    {{ $citasPendientes }}
                                </span>
                            @endif
                        </div>
                        <h4 class="fw-bold fs-5 text-dark">Agenda</h4>
                        <p class="text-muted small mb-4 flex-grow-1">
                            @if($citasPendientes > 0)
                                <span class="text-danger fw-bold">{{ $citasPendientes }} pendientes.</span>
                            @else
                                {{ $citasHoy }} citas hoy.
                            @endif
                        </p>
                        <a href="{{ route('encargada.citas.index') }}" class="btn btn-outline-success text-dark w-100 rounded-pill fw-medium stretched-link py-2 border-2">
                            Gestionar <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- 3. MI GESTIÓN PERSONAL --}}
        <div class="row g-4 mb-5">
            <div class="col-12">
                <h5 class="fw-bold text-secondary ps-2 border-start border-4 border-primary mb-3">Mi Gestión Personal</h5>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-3 d-flex flex-row align-items-center">
                    <div class="p-3 bg-light rounded-circle me-3 text-primary"><i class="bi bi-person-lines-fill fs-4"></i></div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $misMetricas['mis_ingresados'] ?? 0 }}</h3>
                        <span class="text-muted small">Total Ingresados por Mí</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-3 d-flex flex-row align-items-center">
                    <div class="p-3 bg-light rounded-circle me-3 text-warning"><i class="bi bi-clock-history fs-4"></i></div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $misMetricas['mis_en_proceso'] ?? 0 }}</h3>
                        <span class="text-muted small">Mis Casos en Trámite</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. ANÁLISIS GLOBAL --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold m-0 text-primary">Panorama General de la Institución</h6>
                <span class="badge bg-light text-muted border">Datos Globales</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <canvas id="chartCarreras" height="150"></canvas>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <h2 class="display-3 fw-bold text-dark">{{ $stats['kpis']['total'] }}</h2>
                            <p class="text-muted">Total Alumnos Inclusivos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPT GRÁFICO --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('chartCarreras'), {
            type: 'bar',
            data: {
                labels: @json($stats['graficos']['carreras']['labels']),
                datasets: [{
                    label: 'Alumnos',
                    data: @json($stats['graficos']['carreras']['data']),
                    backgroundColor: '#6f0df0',
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y', // Barra Horizontal
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-app-layout>