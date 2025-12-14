<x-app-layout>
    <div class="container px-4 py-5">

        {{-- 1. Banner de Bienvenida --}}
        <div class="p-5 mb-5 text-white rounded-4 shadow position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #0b5555ff 0%, #20c997 100%);">
            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-8">
                    <h3 class="fs-2 fw-bold mb-3">Panel de Supervisión Global</h3>
                    <p class="fs-5 text-white-50 mb-0" style="max-width: 650px;">
                        Monitoreo completo del flujo de inclusión y análisis de datos.
                    </p>
                </div>
            </div>
            <svg class="position-absolute bottom-0 end-0 text-white opacity-10" style="transform: rotateY(180deg); width: 300px;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path fill="currentColor" d="M42.7,-62.9C54.3,-53.4,61.8,-38.2,66.3,-22.7C70.8,-7.2,72.3,8.6,67.1,22.3C61.9,36,50,47.5,36.4,56.7C22.7,65.9,7.4,72.7,-7.7,72.1C-22.7,71.5,-37.4,63.5,-49.3,53C-61.2,42.5,-70.2,29.5,-74.6,15C-79,0.5,-78.8,-15.5,-72.4,-29.1C-66,-42.7,-53.4,-53.8,-39.7,-62C-26.1,-70.1,-13,-75.4,1.6,-77.6C16.3,-79.8,31.1,-72.4,42.7,-62.9Z" transform="translate(100 100)" />
            </svg>
        </div>

        {{-- 2. Alerta de Supervisión (Casos Antiguos) --}}
        @if(isset($casosAntiguos) && $casosAntiguos->isNotEmpty())
            <div class="mb-5 alert alert-danger shadow-sm border-0 rounded-4 d-flex align-items-center p-4 bg-danger bg-opacity-10 text-danger" role="alert">
                <div class="d-flex align-items-center flex-grow-1">
                    <i class="bi bi-exclamation-octagon-fill fs-1 me-4"></i>
                    <div>
                        <h4 class="alert-heading fw-bold mb-1">Alerta de Retraso</h4>
                        <p class="mb-0 text-dark">Hay <strong>{{ $casosAntiguos->count() }}</strong> casos que llevan más de 7 días sin movimiento en etapa inicial.</p>
                    </div>
                </div>
                <a href="{{ route('casos.index') }}" class="btn btn-danger rounded-pill px-4 shadow-sm fw-bold">
                    Revisar Atrasos <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        @endif

        {{-- 3. Tarjeta de Acción Principal (NOMBRE CAMBIADO) --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 col-lg-6">
                <div class="card h-100 rounded-4 hover-lift border-0 shadow-sm">
                    <div class="card-body p-5 d-flex flex-column text-center align-items-center">
                        <div class="icon-box bg-dark bg-opacity-10 text-dark mb-4" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 20px;">
                            <i class="bi bi-archive-fill fs-1"></i>
                        </div>
                        
                        {{-- AQUÍ ESTÁ EL CAMBIO DE NOMBRE --}}
                        <h4 class="fw-bold fs-3 text-dark mb-3">Historial General de Casos</h4>
                        
                        <p class="text-muted mb-4 px-3">
                            Acceda al listado completo para buscar, filtrar y gestionar cualquier caso histórico o activo del sistema.
                        </p>
                        
                        <a href="{{ route('casos.index') }}" class="btn btn-dark rounded-pill px-5 py-3 fw-bold stretched-link shadow-sm">
                            Ver Historial Completo <i class="bi bi-arrow-right-circle ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. DASHBOARD DE ESTADÍSTICAS --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-secondary ps-2 border-start border-4 border-primary mb-0">Métricas Institucionales</h5>
            <span class="badge bg-light text-muted border">Actualizado: {{ now()->format('d/m/Y') }}</span>
        </div>

        <div class="row g-4 mb-5">
            {{-- KPI: Total --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 rounded-4 bg-light">
                    <div class="card-body text-center p-4">
                        <h2 class="display-5 fw-bold text-secondary mb-0">{{ $stats['kpis']['total'] }}</h2>
                        <span class="text-muted small fw-bold text-uppercase">Total Casos</span>
                    </div>
                </div>
            </div>
            {{-- KPI: En Revisión --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 rounded-4 bg-warning bg-opacity-10">
                    <div class="card-body text-center p-4">
                        <h2 class="display-5 fw-bold text-warning mb-0">{{ $stats['kpis']['en_revision'] }}</h2>
                        <span class="text-muted small fw-bold text-uppercase">En Proceso</span>
                    </div>
                </div>
            </div>
            {{-- KPI: Finalizados --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 rounded-4 bg-success bg-opacity-10">
                    <div class="card-body text-center p-4">
                        <h2 class="display-5 fw-bold text-success mb-0">{{ $stats['kpis']['finalizados'] }}</h2>
                        <span class="text-muted small fw-bold text-uppercase">Finalizados</span>
                    </div>
                </div>
            </div>
            {{-- KPI: Tasa Éxito --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 rounded-4 bg-primary bg-opacity-10">
                    <div class="card-body text-center p-4">
                        <h2 class="display-5 fw-bold text-primary mb-0">{{ $stats['kpis']['tasa_resolucion'] }}%</h2>
                        <span class="text-muted small fw-bold text-uppercase">Resolución</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. GRÁFICOS --}}
        <div class="row g-4">
            {{-- Gráfico: Discapacidades --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-bold m-0 text-primary">Prevalencia por Tipo de Discapacidad</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartDiscapacidades"></canvas>
                    </div>
                </div>
            </div>
            {{-- Gráfico: Evolución Mensual --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-bold m-0 text-primary">Ingreso de Casos por Mes</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartEvolucion"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico Discapacidades
        new Chart(document.getElementById('chartDiscapacidades'), {
            type: 'bar',
            data: {
                labels: @json($stats['graficos']['discapacidades']['labels']),
                datasets: [{
                    label: 'Estudiantes',
                    data: @json($stats['graficos']['discapacidades']['data']),
                    backgroundColor: '#20c997',
                    borderRadius: 5
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });

        // Gráfico Evolución
        new Chart(document.getElementById('chartEvolucion'), {
            type: 'line',
            data: {
                labels: @json($stats['graficos']['evolucion']['labels']),
                datasets: [{
                    label: 'Casos',
                    data: @json($stats['graficos']['evolucion']['data']),
                    borderColor: '#0b5555',
                    backgroundColor: 'rgba(32, 201, 151, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive: true }
        });
    </script>
</x-app-layout>