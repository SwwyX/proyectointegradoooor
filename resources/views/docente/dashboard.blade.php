<x-app-layout>
    <div class="container px-4">



        {{-- 2. Banner de Bienvenida (Estilo Moderno - Tono Verde/Teal) --}}
        <div class="p-5 mb-5 text-white rounded-4 shadow position-relative overflow-hidden" 
             style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">
            <div class="row align-items-center position-relative z-1">
                <div class="col-lg-8">
                    <h3 class="fs-2 fw-bold mb-3">¡Hola, {{ Auth::user()->name }}!</h3>
                    <p class="fs-5 text-white-50 mb-0" style="max-width: 650px;">
                        Gestión de Ajustes Razonables por Sección.
                    </p>
                </div>
            </div>
            {{-- Decoración de fondo --}}
            <svg class="position-absolute bottom-0 end-0 text-white opacity-10" style="transform: rotateY(180deg); width: 300px;" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path fill="currentColor" d="M42.7,-62.9C54.3,-53.4,61.8,-38.2,66.3,-22.7C70.8,-7.2,72.3,8.6,67.1,22.3C61.9,36,50,47.5,36.4,56.7C22.7,65.9,7.4,72.7,-7.7,72.1C-22.7,71.5,-37.4,63.5,-49.3,53C-61.2,42.5,-70.2,29.5,-74.6,15C-79,0.5,-78.8,-15.5,-72.4,-29.1C-66,-42.7,-53.4,-53.8,-39.7,-62C-26.1,-70.1,-13,-75.4,1.6,-77.6C16.3,-79.8,31.1,-72.4,42.7,-62.9Z" transform="translate(100 100)" />
            </svg>
        </div>

        {{-- 3. Resumen Rápido (Estilo Estadísticas) --}}
        <div class="row mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="p-4 d-flex align-items-center bg-success bg-opacity-10">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-check-fill fs-3"></i>
                        </div>
                        <div>
                            <h2 class="mb-0 fw-bold text-dark display-6">{{ $stats['total_alumnos_ajustes'] ?? 0 }}</h2>
                            <span class="text-muted small fw-bold text-uppercase">Mis Alumnos con Ajustes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Separador / Título de Sección --}}
        <div class="mb-4 border-bottom pb-2">
            <h4 class="fw-bold text-dark">
                <i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Mis Secciones y Estudiantes
            </h4>
        </div>
        
        {{-- LISTADO AGRUPADO POR SECCIONES --}}
        @forelse($seccionesConAlumnos as $seccion)
            
            {{-- Solo mostramos la sección si tiene estudiantes con casos --}}
            @if($seccion->estudiantes->count() > 0)
                <div class="mb-5">
                    
                    {{-- Título de la Sección (Tarjeta limpia) --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-light">
                        <div class="card-body py-3 px-4 d-flex align-items-center">
                            <span class="badge bg-primary rounded-pill me-3 px-3 py-2">Sección {{ $seccion->nombre_seccion }}</span>
                            <h5 class="mb-0 fw-bold text-dark">{{ $seccion->asignatura->nombre ?? 'Asignatura Sin Nombre' }}</h5>
                        </div>
                    </div>

                    {{-- Grid de Estudiantes --}}
                    <div class="row g-4">
                        @foreach($seccion->estudiantes as $estudiante)
                            @foreach($estudiante->casos as $caso) 
                                
                                {{-- Lógica original de lectura --}}
                                @php
                                    $leido = $caso->docentesQueConfirmaron->contains(Auth::id());
                                @endphp

                                <div class="col-lg-6">
                                    {{-- Tarjeta de Estudiante: Aplicamos hover-lift y redondeado. Mantenemos borde rojo si no está leído. --}}
                                    <div class="card h-100 shadow-sm border-0 rounded-4 hover-lift {{ !$leido ? 'border-start border-4 border-danger' : '' }}" style="overflow: hidden;">
                                        
                                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                {{-- Avatar --}}
                                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                                    <span class="fw-bold text-primary">{{ substr($estudiante->nombre_completo, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $estudiante->nombre_completo }}</h6>
                                                    <small class="text-muted" style="font-size: 0.8rem;">RUT: {{ $estudiante->rut }}</small>
                                                </div>
                                            </div>
                                            
                                            {{-- BADGE DE ESTADO DE LECTURA --}}
                                            @if(!$leido)
                                                <span class="badge bg-danger rounded-pill px-3 py-2 animate__animated animate__pulse animate__infinite">
                                                    <i class="bi bi-exclamation-circle-fill me-1"></i> Pendiente
                                                </span>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-all me-1"></i> Leído
                                                </span>
                                            @endif
                                        </div>

                                        <div class="card-body bg-light bg-opacity-25">
                                            <h6 class="text-secondary text-uppercase fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                Resumen de Estrategias:
                                            </h6>
                                            @if(is_array($caso->ajustes_propuestos))
                                                <ul class="mb-0 ps-3 small text-muted">
                                                    @foreach(array_slice($caso->ajustes_propuestos, 0, 2) as $ajuste)
                                                        <li class="mb-2">{{ \Illuminate\Support\Str::limit($ajuste, 60) }}</li>
                                                    @endforeach
                                                    
                                                    @if(count($caso->ajustes_propuestos) > 2)
                                                        <li class="fst-italic text-primary mt-2" style="font-size: 0.8rem;">
                                                            + {{ count($caso->ajustes_propuestos) - 2 }} estrategias más...
                                                        </li>
                                                    @endif
                                                </ul>
                                            @else
                                                <p class="text-muted small fst-italic mb-0">Detalles disponibles en la ficha completa.</p>
                                            @endif
                                        </div>

                                        <div class="card-footer bg-white border-top-0 p-3 text-end">
                                            <a href="{{ route('docente.ajustes.show', $caso->id) }}" class="btn btn-sm {{ !$leido ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }} rounded-pill px-4 fw-medium stretched-link">
                                                {{ !$leido ? 'Revisar Ajustes' : 'Ver Ficha' }} <i class="bi bi-chevron-right ms-1"></i>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endif

        @empty
            {{-- Empty State con diseño limpio --}}
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill fs-1 text-warning opacity-50"></i>
                </div>
                <h5 class="fw-bold text-dark">Sin carga académica</h5>
                <p class="text-muted mb-0">No se encontraron secciones asociadas a su cuenta de docente.</p>
            </div>
        @endforelse
        
        {{-- Mensaje Sin Novedades --}}
        @if($stats['total_alumnos_ajustes'] == 0 && $seccionesConAlumnos->isNotEmpty())
            <div class="py-5 text-center">
                <div class="mb-3">
                    <i class="bi bi-clipboard-check display-1 text-secondary opacity-25"></i>
                </div>
                <h4 class="fw-bold text-secondary">Sin novedades</h4>
                <p class="text-muted">Actualmente no tiene estudiantes con ajustes razonables activos en sus secciones.</p>
            </div>
        @endif

    </div>
</x-app-layout>