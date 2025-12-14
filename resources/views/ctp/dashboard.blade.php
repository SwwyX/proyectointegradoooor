<x-app-layout>
    <div class="container px-4 py-5">

        {{-- 1. Banner de Bienvenida --}}
        <div class="p-5 mb-5 text-white rounded-4 shadow position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #1c7432b7 0%, #38a5ff94 100%);">
            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-8">
                    <h3 class="fs-2 fw-bold mb-3">¡Bienvenido/a, {{ Auth::user()->name }}!</h3>
                    <p class="fs-5 text-white-50 mb-0" style="max-width: 650px;">
                        Panel principal para la redacción de apoyos metodológicos y análisis de datos.
                    </p>
                </div>
            </div>
            <svg class="position-absolute bottom-0 end-0 text-white opacity-10" style="transform: rotateY(180deg); width: 300px;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path fill="currentColor" d="M42.7,-62.9C54.3,-53.4,61.8,-38.2,66.3,-22.7C70.8,-7.2,72.3,8.6,67.1,22.3C61.9,36,50,47.5,36.4,56.7C22.7,65.9,7.4,72.7,-7.7,72.1C-22.7,71.5,-37.4,63.5,-49.3,53C-61.2,42.5,-70.2,29.5,-74.6,15C-79,0.5,-78.8,-15.5,-72.4,-29.1C-66,-42.7,-53.4,-53.8,-39.7,-62C-26.1,-70.1,-13,-75.4,1.6,-77.6C16.3,-79.8,31.1,-72.4,42.7,-62.9Z" transform="translate(100 100)" />
            </svg>
        </div>

        {{-- 2. TARJETAS DE ACCIÓN (2 COLUMNAS) --}}
        <div class="row g-4 mb-5">
            
            {{-- TARJETA 1: BANDEJA DE CASOS --}}
            <div class="col-md-6">
                <div class="card h-100 rounded-4 hover-lift border-0 shadow-sm">
                    <div class="card-body p-5 d-flex flex-column text-center align-items-center">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary mb-4" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 20px;">
                            <i class="bi bi-pencil-square fs-1"></i>
                        </div>
                        
                        <h4 class="fw-bold fs-4 text-dark mb-3">Bandeja de Casos</h4>
                        <p class="text-muted mb-4 px-lg-4 flex-grow-1">
                            Acceda al listado completo de casos para redactar ajustes razonables o corregir observaciones.
                        </p>
                        
                        <a href="{{ route('ctp.casos.index') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold stretched-link w-100">
                            Ir a la Bandeja <i class="bi bi-arrow-right-circle ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TARJETA 2: ANALÍTICAS BI --}}
            <div class="col-md-6">
                <div class="card h-100 rounded-4 hover-lift border-0 shadow-sm" style="border-bottom: 5px solid #0dcaf0 !important;">
                    <div class="card-body p-5 d-flex flex-column text-center align-items-center">
                        <div class="icon-box bg-info bg-opacity-10 text-info mb-4" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 20px;">
                            <i class="bi bi-bar-chart-line-fill fs-1"></i>
                        </div>
                        
                        <h4 class="fw-bold fs-4 text-dark mb-3">Analíticas de Gestión</h4>
                        <p class="text-muted mb-4 px-lg-4 flex-grow-1">
                            Visualice gráficos de rendimiento, distribución de discapacidades y métricas clave (KPIs).
                        </p>
                        
                        <a href="{{ route('ctp.analiticas') }}" class="btn btn-outline-info text-dark border-2 rounded-pill px-5 py-3 fw-bold stretched-link w-100">
                            Ver Dashboard BI <i class="bi bi-graph-up-arrow ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- 3. MI GESTIÓN PERSONAL --}}
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-5">
                    <div class="card-header bg-white p-4 border-bottom-0 text-center">
                        <h5 class="fw-bold mb-1 text-secondary text-uppercase small ls-1">Resumen de mi Actividad</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-6 border-end border-light">
                                <div class="p-5 text-center bg-info bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                    <div class="mb-3">
                                        <i class="bi bi-file-earmark-plus text-info opacity-75" style="font-size: 3.5rem;"></i>
                                    </div>
                                    <h2 class="display-4 fw-bold text-dark mb-1">{{ $miGestion['por_atender'] ?? 0 }}</h2>
                                    <p class="text-muted fw-bold text-uppercase small mb-0">Nuevos Casos (Por Redactar)</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                 <div class="p-5 text-center bg-primary bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                    <div class="mb-3">
                                        <i class="bi bi-send-check text-primary opacity-75" style="font-size: 3.5rem;"></i>
                                    </div>
                                    <h2 class="display-4 fw-bold text-dark mb-1">{{ $miGestion['gestionados'] ?? 0 }}</h2>
                                    <p class="text-muted fw-bold text-uppercase small mb-0">Total Enviados a Dirección</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. ESTADÍSTICAS RÁPIDAS (MINI DASHBOARD) --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-secondary ps-2 border-start border-4 border-primary mb-0">Panorama General</h5>
            <small class="text-muted">Vista previa de datos globales</small>
        </div>
        
        <div class="row g-4 mb-5">
            {{-- Gráfico 1: Ajustes Razonables (CORREGIDO) --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-bold m-0 text-dark small text-uppercase">Top Ajustes Razonables Solicitados</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartAjustes" height="200"></canvas>
                    </div>
                </div>
            </div>
            {{-- Gráfico 2: Estados --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-bold m-0 text-dark small text-uppercase">Estado del Flujo</h6>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center h-100">
                            <div class="col-6">
                                <canvas id="chartEstados" height="200"></canvas>
                            </div>
                            <div class="col-6">
                                <ul class="list-unstyled mb-0 small">
                                    <li class="mb-2"><i class="bi bi-circle-fill text-success me-2"></i> Finalizados: <strong>{{ $stats['kpis']['finalizados'] }}</strong></li>
                                    <li><i class="bi bi-circle-fill text-warning me-2"></i> En Revisión: <strong>{{ $stats['kpis']['en_revision'] }}</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Ranking de Ajustes (AHORA CON DATOS REALES)
        new Chart(document.getElementById('chartAjustes'), {
            type: 'bar',
            data: {
                labels: @json($rankingAjustes['labels']), // Nombres de los ajustes (Ej: Tiempo Adicional)
                datasets: [{
                    label: 'Frecuencia',
                    data: @json($rankingAjustes['data']), // Cantidad de veces asignado
                    backgroundColor: 'rgba(56, 165, 255, 0.7)', // Azul CTP
                    borderRadius: 6
                }]
            },
            options: { 
                indexAxis: 'y', 
                responsive: true, 
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false } }, y: { grid: { display: false } } }
            }
        });

        // Estado del Flujo
        new Chart(document.getElementById('chartEstados'), {
            type: 'doughnut',
            data: {
                labels: ['Finalizados', 'En Revisión'],
                datasets: [{
                    data: [{{ $stats['kpis']['finalizados'] }}, {{ $stats['kpis']['en_revision'] }}],
                    backgroundColor: ['#198754', '#ffc107'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '75%',
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-app-layout>