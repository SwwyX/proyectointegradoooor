<x-app-layout>
    {{-- Estilos personalizados (ORIGINAL MANTENIDO) --}}
    <style>
        /* --- NUEVO FONDO --- */
        .main-content-wrapper {
            background-color: #f8f5f5ff; 
            min-height: 90vh;
        }

        /* --- Mejoras en las Tarjetas --- */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background-color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
        }
        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(39, 59, 89, 0.15) !important;
        }
        
        .icon-box {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
        }
        
        /* Banner degradado original */
        .bg-gradient-supportive {
            background: linear-gradient(to right, #2c7be5, #5e93da);
        }
    </style>

    {{-- Aplicamos el nuevo fondo --}}
    <div class="py-5 main-content-wrapper">
        <div class="container px-4">

            {{-- Encabezado (ORIGINAL MANTENIDO) --}}
            <div class="mb-5">
                <h2 class="fw-bold text-dark mb-0">
                    {{ Auth::user()->rol->nombre_rol ?? 'Director' }}
                </h2>
            </div>

            {{-- Banner de Bienvenida (ORIGINAL MANTENIDO) --}}
            <div class="p-5 mb-5 text-white rounded-4 shadow bg-gradient-supportive position-relative overflow-hidden">
                <div class="row align-items-center position-relative z-1">
                    <div class="col-lg-8">
                        <h3 class="fs-2 fw-bold mb-3">¡Bienvenido/a, {{ Auth::user()->name }}!</h3>
                        <p class="fs-5 text-white-50 mb-0" style="max-width: 650px;">
                            Panel principal para la validación y gestión de casos académicos.
                        </p>
                    </div>
                </div>
                {{-- Decoración SVG --}}
                <svg class="position-absolute bottom-0 end-0 text-white opacity-10" style="transform: rotateY(180deg); width: 300px;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                  <path fill="currentColor" d="M42.7,-62.9C54.3,-53.4,61.8,-38.2,66.3,-22.7C70.8,-7.2,72.3,8.6,67.1,22.3C61.9,36,50,47.5,36.4,56.7C22.7,65.9,7.4,72.7,-7.7,72.1C-22.7,71.5,-37.4,63.5,-49.3,53C-61.2,42.5,-70.2,29.5,-74.6,15C-79,0.5,-78.8,-15.5,-72.4,-29.1C-66,-42.7,-53.4,-53.8,-39.7,-62C-26.1,-70.1,-13,-75.4,1.6,-77.6C16.3,-79.8,31.1,-72.4,42.7,-62.9Z" transform="translate(100 100)" />
                </svg>
            </div>

            {{-- Tarjetas de Acción (ORIGINAL MANTENIDO) --}}
            <div class="row g-4 mb-5">
                
                {{-- Tarjeta: CREAR CASO --}}
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 rounded-4 hover-lift border-0">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-plus-circle-fill fs-4"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold fs-5 text-dark">Iniciar Solicitud</h4>
                            <p class="text-muted small mb-4 flex-grow-1">Ingresar un caso nuevo manualmente.</p>
                            <a href="{{ route('director.casos.create') }}" class="btn btn-primary w-100 rounded-pill fw-medium stretched-link py-2">
                                Crear Caso
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta: PENDIENTES (Actualizado con variable $stats) --}}
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 rounded-4 hover-lift border-0" style="border-bottom: 4px solid #ffc107 !important;">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-hourglass-split fs-4"></i>
                                </div>
                                {{-- AQUI ACTUALIZAMOS EL NÚMERO --}}
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                    {{ $stats['kpis']['en_revision'] ?? 0 }}
                                </span>
                            </div>
                            <h4 class="fw-bold fs-5 text-dark">Pendientes</h4>
                            <p class="text-muted small mb-4 flex-grow-1">Casos esperando validación.</p>
                            <a href="{{ route('director.casos.pendientes') }}" class="btn btn-outline-warning w-100 rounded-pill fw-medium text-dark stretched-link py-2 border-2">
                                Revisar Pendientes
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta: FINALIZADOS (Actualizado con variable $stats) --}}
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 rounded-4 hover-lift border-0">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="icon-box bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-check-circle-fill fs-4"></i>
                                </div>
                                {{-- AQUI ACTUALIZAMOS EL NÚMERO --}}
                                <span class="badge bg-success text-white rounded-pill px-3 py-2">
                                    {{ $stats['kpis']['finalizados'] ?? 0 }}
                                </span>
                            </div>
                            <h4 class="fw-bold fs-5 text-dark">Finalizados</h4>
                            <p class="text-muted small mb-4 flex-grow-1">Casos cerrados y procesados.</p>
                            <a href="{{ route('director.casos.aceptados') }}" class="btn btn-outline-success w-100 rounded-pill fw-medium stretched-link py-2 border-2">
                                Ver Finalizados
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta: HISTORIAL TOTAL --}}
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 rounded-4 hover-lift border-0">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="icon-box bg-secondary bg-opacity-10 text-secondary">
                                    <i class="bi bi-list-task fs-4"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold fs-5 text-dark">Historial Total</h4>
                            <p class="text-muted small mb-4 flex-grow-1">Listado completo de casos.</p>
                            <a href="{{ route('director.casos.todos') }}" class="btn btn-outline-secondary w-100 rounded-pill fw-medium stretched-link py-2 border-2">
                                Ver Todo
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- SECCIÓN DE ANALÍTICAS (Mantenemos tu diseño pero con variables nuevas) --}}
            <div class="row justify-content-center mb-5" id="analytics-section">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-header bg-white p-4 border-bottom-0 text-center">
                            <h5 class="fw-bold mb-1">Estadísticas Rápidas</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="row g-0">
                                {{-- Estadística Pendientes --}}
                                <div class="col-md-6 border-end border-light">
                                    <div class="p-5 text-center bg-warning bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                        <i class="bi bi-hourglass-top text-warning mb-3" style="font-size: 3rem;"></i>
                                        <h2 class="display-4 fw-bold text-dark mb-1">{{ $stats['kpis']['en_revision'] ?? 0 }}</h2>
                                        <p class="text-muted fs-6 fw-bold mb-0">Pendientes</p>
                                    </div>
                                </div>
                                {{-- Estadística Finalizados --}}
                                <div class="col-md-6">
                                     <div class="p-5 text-center bg-success bg-opacity-10 h-100 d-flex flex-column justify-content-center">
                                        <i class="bi bi-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                                        <h2 class="display-4 fw-bold text-dark mb-1">{{ $stats['kpis']['finalizados'] ?? 0 }}</h2>
                                        <p class="text-muted fs-6 fw-bold mb-0">Finalizados (Cerrados)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 1. NUEVO: FILTRO POR CARRERA --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-funnel-fill text-primary me-2"></i>
                        <h5 class="mb-0 fw-bold text-secondary">Filtros de Gestión</h5>
                    </div>
                    <form action="{{ route('director.dashboard') }}" method="GET" class="d-flex gap-2">
                        <select name="carrera" class="form-select border-0 bg-light fw-bold" style="min-width: 250px;" onchange="this.form.submit()">
                            <option value="">Vista Global (Todas las Carreras)</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera }}" {{ ($filtros['carrera'] ?? '') == $carrera ? 'selected' : '' }}>
                                    {{ $carrera }}
                                </option>
                            @endforeach
                        </select>
                        {{-- Filtro de fecha opcional si quieres --}}
                    </form>
                </div>
            </div>

            {{-- NUEVO: GRÁFICOS ESTRATÉGICOS (Agregado al final) --}}
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h6 class="fw-bold m-0 text-dark">Distribución de Discapacidades (Toma de Decisiones)</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="chartDirDiscapacidades" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    {{-- SCRIPTS (NUEVO) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('chartDirDiscapacidades'), {
            type: 'bar',
            data: {
                labels: @json($stats['graficos']['discapacidades']['labels']),
                datasets: [{
                    label: 'Estudiantes',
                    data: @json($stats['graficos']['discapacidades']['data']),
                    backgroundColor: '#2c7be5',
                    borderRadius: 5
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    </script>
</x-app-layout>