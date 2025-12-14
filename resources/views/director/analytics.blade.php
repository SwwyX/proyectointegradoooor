<x-app-layout>
    <style>
        .bg-analytics { background-color: #f8f9fa; min-height: 100vh; }
        .card-chart { border-radius: 16px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.04); transition: transform 0.2s; background: white; }
        .card-chart:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .chart-header { font-weight: 700; color: #344767; font-size: 1.1rem; }
    </style>

    <div class="py-5 bg-analytics">
        <div class="container-fluid px-4 px-lg-5">
            
            {{-- ENCABEZADO Y FILTROS --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1"><i class="bi bi-bar-chart-steps me-2 text-primary"></i>Analíticas BI</h2>
                    <p class="text-muted mb-0">Inteligencia de negocios para la gestión inclusiva.</p>
                </div>
                
                {{-- Formulario de Filtros --}}
                <form action="{{ request()->url() }}" method="GET" class="d-flex gap-2 bg-white p-2 rounded-pill shadow-sm">
                    <select name="carrera" class="form-select border-0 rounded-pill bg-light fw-bold" style="width: 250px;" onchange="this.form.submit()">
                        <option value="">Todas las Carreras</option>
                        @foreach($carreras as $carrera)
                            <option value="{{ $carrera }}" {{ ($filtros['carrera'] ?? '') == $carrera ? 'selected' : '' }}>
                                {{ $carrera }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Actualizar</button>
                </form>
            </div>

            {{-- FILA 1: AJUSTES CTP (OFICIALES) - EL DATO MÁS IMPORTANTE --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card card-chart">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="chart-header text-success">
                                    <i class="bi bi-file-earmark-check-fill me-2"></i>Ajustes Razonables Oficiales (CTP)
                                </h5>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success">Datos Vinculantes</span>
                            </div>
                            
                            <div style="height: 350px;">
                                <canvas id="chartAjustesOficiales"></canvas>
                            </div>
                            <p class="text-muted mt-3 small mb-0">
                                <i class="bi bi-info-circle me-1"></i> Muestra la frecuencia de ajustes definidos técnicamente por el CTP en casos finalizados. Utilizar para gestión de recursos (Tablets, Intérpretes, Mobiliario).
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILA 2: PERFIL Y ORIGEN --}}
            <div class="row g-4 mb-4">
                {{-- Perfil Socioeducativo (SIN TRABAJADORES, PIE CORRECTO) --}}
                <div class="col-lg-8">
                    <div class="card card-chart h-100">
                        <div class="card-body p-4">
                            <h5 class="chart-header mb-4">Perfil Socioeducativo</h5>
                            <div style="height: 250px;">
                                <canvas id="chartPerfil"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Vías de Ingreso --}}
                <div class="col-lg-4">
                    <div class="card card-chart h-100">
                        <div class="card-body p-4">
                            <h5 class="chart-header mb-4 text-center">Vías de Ingreso</h5>
                            <div style="height: 220px; display: flex; justify-content: center;">
                                <canvas id="chartIngreso"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILA 3: LABORAL, DISCAPACIDAD Y COBERTURA (NUEVOS) --}}
            <div class="row g-4">
                
                {{-- Situación Laboral (Gráfico Nuevo) --}}
                <div class="col-lg-4">
                    <div class="card card-chart h-100">
                        <div class="card-body p-4">
                            <h5 class="chart-header mb-4 text-center">Situación Laboral</h5>
                            <div style="height: 200px; display: flex; justify-content: center;">
                                <canvas id="chartLaboral"></canvas>
                            </div>
                            <div class="text-center mt-3 small text-muted">Estudiantes que trabajan vs Dedicación Exclusiva</div>
                        </div>
                    </div>
                </div>

                {{-- Discapacidades (Traído del Dashboard) --}}
                <div class="col-lg-4">
                    <div class="card card-chart h-100">
                        <div class="card-body p-4">
                            <h5 class="chart-header mb-4">Tipos de Discapacidad</h5>
                            <div style="height: 200px;">
                                <canvas id="chartDiscapacidades"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cobertura (Total vs Casos) --}}
                <div class="col-lg-4">
                    <div class="card card-chart h-100">
                        <div class="card-body p-4">
                            <h5 class="chart-header mb-4 text-center">Cobertura de Inclusión</h5>
                            <div style="height: 200px; display: flex; justify-content: center;">
                                <canvas id="chartCobertura"></canvas>
                            </div>
                            <div class="text-center mt-3 fw-bold text-primary">
                                {{ $analytics['total_analizados'] }} Casos Activos
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
        Chart.defaults.font.family = "'Nunito', sans-serif";
        Chart.defaults.color = '#6c757d';

        // 1. AJUSTES OFICIALES (CTP) - Horizontal
        new Chart(document.getElementById('chartAjustesOficiales'), {
            type: 'bar',
            data: {
                labels: @json($analytics['ajustes_ranking']['labels']),
                datasets: [{
                    label: 'Otorgados',
                    data: @json($analytics['ajustes_ranking']['data']),
                    backgroundColor: 'rgba(25, 135, 84, 0.7)', // Success Green
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });

        // 2. PERFIL SOCIOEDUCATIVO (Vertical - Datos limpios)
        new Chart(document.getElementById('chartPerfil'), {
            type: 'bar',
            data: {
                labels: @json($analytics['perfil_estudiante']['labels']),
                datasets: [{
                    label: 'Estudiantes',
                    data: @json($analytics['perfil_estudiante']['data']),
                    backgroundColor: '#4e73df',
                    borderRadius: 6,
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // 3. VÍAS DE INGRESO (Doughnut)
        new Chart(document.getElementById('chartIngreso'), {
            type: 'doughnut',
            data: {
                labels: @json($analytics['vias_ingreso']['labels']),
                datasets: [{
                    data: @json($analytics['vias_ingreso']['data']),
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                    borderWidth: 2
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } } }
        });

        // 4. SITUACIÓN LABORAL (Pie - NUEVO)
        new Chart(document.getElementById('chartLaboral'), {
            type: 'pie',
            data: {
                labels: @json($analytics['situacion_laboral']['labels']),
                datasets: [{
                    data: @json($analytics['situacion_laboral']['data']),
                    backgroundColor: ['#ff5252', '#e0e0e0'], // Rojo para trabajadores (alerta), Gris para resto
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // 5. DISCAPACIDADES (Bar - TRAÍDO DEL DASHBOARD)
        new Chart(document.getElementById('chartDiscapacidades'), {
            type: 'bar',
            data: {
                labels: @json($analytics['discapacidades']['labels']),
                datasets: [{
                    label: 'Casos',
                    data: @json($analytics['discapacidades']['data']),
                    backgroundColor: '#6f42c1', // Purple
                    borderRadius: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        // 6. COBERTURA (Doughnut - NUEVO)
        new Chart(document.getElementById('chartCobertura'), {
            type: 'doughnut',
            data: {
                labels: @json($analytics['cobertura']['labels']),
                datasets: [{
                    data: @json($analytics['cobertura']['data']),
                    backgroundColor: ['#36b9cc', '#f8f9fa'], // Cyan vs Empty
                    borderColor: '#e3e6f0',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '80%', plugins: { legend: { position: 'bottom' } } }
        });
    </script>
</x-app-layout>