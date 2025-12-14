<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">

                    {{-- Header de navegación --}}
                    <div class="mb-4">
                        <a href="{{ route('estudiante.casos.index') }}" class="text-decoration-none text-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Volver a mis casos
                        </a>
                    </div>

                    <h2 class="fw-bold mb-4">Detalle de Solicitud #{{ str_pad($caso->id, 5, '0', STR_PAD_LEFT) }}</h2>

                    {{-- MENSAJES FLASH --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row g-4">
                        
                        {{-- CONTENIDO PRINCIPAL --}}
                        <div class="col-12">
                            
                            {{-- 1. TARJETA DE ESTADO Y RESOLUCIÓN --}}
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="text-muted text-uppercase fw-bold small">Estado del Proceso</h6>
                                        
                                        {{-- BOTÓN DE DESCARGA PDF (Solo si finalizado) --}}
                                        @if(in_array($caso->estado, ['Finalizado', 'Aceptado', 'Rechazado']))
                                            <a href="{{ route('reportes.caso.pdf', $caso) }}" target="_blank" class="btn btn-danger btn-sm shadow-sm fw-bold px-3">
                                                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Descargar Informe PDF
                                            </a>
                                        @endif
                                    </div>
                                    
                                    {{-- CASO FINALIZADO / ACEPTADO --}}
                                    @if($caso->estado == 'Finalizado' || $caso->estado == 'Aceptado')
                                        <div class="d-flex align-items-center text-success mb-4">
                                            <i class="bi bi-check-circle-fill fs-2 me-3"></i>
                                            <div>
                                                <h4 class="fw-bold mb-0">Proceso Finalizado</h4>
                                                <p class="mb-0 small text-muted">La Dirección de Carrera ha emitido la resolución favorable de tu caso.</p>
                                            </div>
                                        </div>
                                        
                                        {{-- DETALLE DE LA RESOLUCIÓN --}}
                                        <div class="card border-success border-opacity-25 bg-success-subtle">
                                            <div class="card-header bg-transparent border-success border-opacity-25 fw-bold text-success">
                                                <i class="bi bi-list-check me-2"></i>Resolución de Ajustes Razonables
                                            </div>
                                            <ul class="list-group list-group-flush bg-transparent">
                                                @if(is_array($caso->ajustes_ctp) && is_array($caso->evaluacion_director))
                                                    @foreach($caso->ajustes_ctp as $index => $ajuste)
                                                        @php 
                                                            $eval = $caso->evaluacion_director[$index] ?? null; 
                                                            $decision = $eval['decision'] ?? 'Pendiente';
                                                        @endphp

                                                        <li class="list-group-item bg-transparent d-flex align-items-start py-3">
                                                            @if($decision == 'Aceptado')
                                                                <i class="bi bi-check-lg text-success fs-5 me-3 mt-1"></i>
                                                                <div>
                                                                    <span class="fw-bold text-dark">{{ $ajuste }}</span>
                                                                    <div class="mt-1">
                                                                        <span class="badge bg-success border border-success-subtle text-white">Aprobado</span>
                                                                    </div>
                                                                </div>
                                                            @elseif($decision == 'Rechazado')
                                                                <i class="bi bi-x-circle text-danger fs-5 me-3 mt-1"></i>
                                                                <div class="opacity-75">
                                                                    <span class="text-decoration-line-through text-secondary">{{ $ajuste }}</span>
                                                                    <div class="mt-1 d-flex align-items-center flex-wrap gap-2">
                                                                        <span class="badge bg-danger border border-danger-subtle text-white">No Aprobado</span>
                                                                        @if(!empty($eval['comentario']))
                                                                            <span class="small text-danger bg-danger-subtle px-2 py-1 rounded">
                                                                                <i class="bi bi-info-circle me-1"></i> {{ $eval['comentario'] }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <i class="bi bi-dash-circle text-muted fs-5 me-3 mt-1"></i>
                                                                <span class="text-muted">{{ $ajuste }} (Sin resolución)</span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <li class="list-group-item bg-transparent text-muted fst-italic">
                                                        No se encontraron detalles de la evaluación técnica. Por favor contacta a tu Encargada de Inclusión.
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>

                                        @if($caso->motivo_decision)
                                            <div class="mt-3 p-3 bg-light rounded border small">
                                                <strong class="text-secondary d-block mb-1">Comentario General de Dirección:</strong>
                                                "{{ $caso->motivo_decision }}"
                                            </div>
                                        @endif

                                    {{-- CASO RECHAZADO --}}
                                    @elseif($caso->estado == 'Rechazado')
                                        <div class="d-flex align-items-center text-danger">
                                            <i class="bi bi-x-circle-fill fs-3 me-3"></i>
                                            <div>
                                                <h4 class="fw-bold mb-0">Solicitud Rechazada</h4>
                                                <p class="mb-0 small text-muted">Tu caso no cumple con los criterios para ajustes razonables o ha sido desestimado.</p>
                                            </div>
                                        </div>
                                        @if($caso->motivo_decision)
                                            <div class="mt-3 p-3 bg-danger-subtle text-danger rounded small border border-danger-subtle">
                                                <strong>Motivo:</strong> {{ $caso->motivo_decision }}
                                            </div>
                                        @endif

                                    {{-- CASO EN TRÁMITE --}}
                                    @else
                                        <div class="d-flex align-items-center text-warning">
                                            <i class="bi bi-hourglass-split fs-3 me-3"></i>
                                            <div>
                                                <h4 class="fw-bold mb-0">En Evaluación</h4>
                                                <p class="mb-0 small text-muted">Tu solicitud está siendo revisada por el equipo académico.</p>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <div class="progress" style="height: 10px;">
                                                @php
                                                    $progreso = 20;
                                                    if($caso->estado == 'En Gestion CTP') $progreso = 50;
                                                    if($caso->estado == 'Pendiente de Validacion') $progreso = 80;
                                                @endphp
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: {{ $progreso }}%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1 small text-muted">
                                                <span>Ingreso</span>
                                                <span>Evaluación Técnica</span>
                                                <span>Validación</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- 2. INFORMACIÓN ORIGINAL (ANAMNESIS) --}}
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white py-3">
                                    <h5 class="fw-bold mb-0 text-secondary">Mi Información Entregada</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label class="small text-muted fw-bold">DISCAPACIDAD / DIAGNÓSTICO</label>
                                            <div>
                                                @if(is_array($caso->tipo_discapacidad))
                                                    @foreach($caso->tipo_discapacidad as $tipo)
                                                        <span class="badge bg-secondary">{{ $tipo }}</span>
                                                    @endforeach
                                                @else
                                                    {{ $caso->tipo_discapacidad ?? 'No especificado' }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="small text-muted fw-bold">MIS SOLICITUDES INICIALES</label>
                                            <div class="bg-light p-3 rounded mt-1">
                                                @if(is_array($caso->ajustes_propuestos))
                                                    <ul class="mb-0 ps-3 text-muted small">
                                                        @foreach($caso->ajustes_propuestos as $soli)
                                                            <li>{{ $soli }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="mb-0 text-muted fst-italic small">No registré solicitudes específicas.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. NUEVO: BITÁCORA DE SEGUIMIENTO DOCENTE --}}
                            @if($caso->seguimientos->count() > 0)
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="fw-bold mb-0 text-secondary">
                                            <i class="bi bi-journal-text me-2"></i>Bitácora de Seguimiento Docente
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <p class="text-muted small mb-3">Observaciones registradas por el cuerpo docente sobre la implementación de los ajustes:</p>
                                        <div class="vstack gap-3">
                                            @foreach($caso->seguimientos as $seguimiento)
                                                <div class="card border-0 bg-light shadow-sm">
                                                    <div class="card-body py-2 px-3">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            {{-- Mostramos "Docente" genérico o nombre según prefieras. Aquí va el nombre. --}}
                                                            <strong class="text-dark small">{{ $seguimiento->docente->name ?? 'Docente' }}</strong>
                                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $seguimiento->created_at->format('d/m/Y H:i') }}</small>
                                                        </div>
                                                        <p class="mb-0 small text-secondary">{{ $seguimiento->comentario }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>