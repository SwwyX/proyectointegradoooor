<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">
                    
                    {{-- Botón Volver --}}
                    <div class="mb-4">
                        <a href="{{ route('docente.dashboard') }}" class="text-decoration-none text-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Volver al Portal
                        </a>
                    </div>

                    {{-- Mensajes de éxito --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card shadow border-0 rounded-0">
                        
                        {{-- Encabezado Oficial --}}
                        <div class="card-header bg-primary text-white p-4 rounded-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 text-uppercase fw-bold" style="letter-spacing: 1px;">Informe de Ajustes Razonables</h5>
                                    <small class="opacity-75">Unidad de Inclusión y Apoyo Pedagógico</small>
                                </div>
                                
                                @if($yaConfirmado)
                                    <span class="badge bg-white text-success fw-bold px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i> LECTURA CONFIRMADA
                                    </span>
                                @else
                                    <span class="badge bg-danger text-white fw-bold px-3 py-2 animate__animated animate__pulse animate__infinite">
                                        PENDIENTE DE LECTURA
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-5">
                            
                            {{-- 1. Datos del Estudiante --}}
                            <div class="row border-bottom pb-4 mb-4">
                                <div class="col-md-8">
                                    <h6 class="text-muted text-uppercase small fw-bold">Estudiante</h6>
                                    <h3 class="fw-bold text-dark mb-1">{{ $caso->nombre_estudiante }}</h3>
                                    <span class="text-secondary">{{ $caso->carrera }}</span>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <h6 class="text-muted text-uppercase small fw-bold">Folio</h6>
                                    <div class="fs-5 text-dark">#{{ str_pad($caso->id, 6, '0', STR_PAD_LEFT) }}</div>
                                    <div class="text-muted small">Fecha Validación: {{ $caso->updated_at->format('d/m/Y') }}</div>
                                </div>
                            </div>

                            {{-- 2. Contexto --}}
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold text-uppercase small mb-3">
                                    <i class="bi bi-info-circle-fill me-2"></i>Contexto para el Docente
                                </h6>
                                <div class="p-3 bg-light border rounded">
                                    <strong>Tipo de Discapacidad / Condición Declarada:</strong>
                                    <div class="mt-2">
                                        @if(is_array($caso->tipo_discapacidad) && count($caso->tipo_discapacidad) > 0)
                                            @foreach($caso->tipo_discapacidad as $tipo)
                                                <span class="badge bg-secondary me-1">{{ $tipo }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted fst-italic">Información confidencial.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- 3. AJUSTES OFICIALES --}}
                            <div class="mb-5">
                                <h6 class="text-primary fw-bold text-uppercase small mb-3">
                                    <i class="bi bi-check-square-fill me-2"></i>Estrategias a Implementar
                                </h6>
                                <div class="card border-primary border-opacity-25 bg-primary-subtle">
                                    <div class="card-body">
                                        <p class="text-primary-emphasis small mb-3">
                                            A continuación se detallan los ajustes razonables validados técnicamente y aprobados por la Dirección de Carrera:
                                        </p>

                                        @php $hayAjustesValidados = false; @endphp

                                        <ul class="list-group list-group-flush rounded shadow-sm">
                                            @if(is_array($caso->ajustes_ctp) && is_array($caso->evaluacion_director))
                                                @foreach($caso->ajustes_ctp as $index => $ajuste)
                                                    @if(isset($caso->evaluacion_director[$index]) && $caso->evaluacion_director[$index]['decision'] === 'Aceptado')
                                                        @php $hayAjustesValidados = true; @endphp
                                                        <li class="list-group-item bg-white p-3 d-flex align-items-start">
                                                            <i class="bi bi-check-lg text-success me-3 mt-1 fs-5"></i>
                                                            <div class="w-100">
                                                                <span class="fs-5 text-dark fw-medium">{{ $ajuste }}</span>
                                                                @if(!empty($caso->evaluacion_director[$index]['comentario']))
                                                                    <div class="mt-2 p-2 bg-warning-subtle border border-warning rounded small text-dark">
                                                                        <i class="bi bi-chat-left-dots-fill me-2 text-warning-emphasis"></i>
                                                                        <strong>Instrucción:</strong> {{ $caso->evaluacion_director[$index]['comentario'] }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>

                                        @if(!$hayAjustesValidados)
                                            <div class="alert alert-info mb-0">Sin ajustes específicos adicionales.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- 4. Comentarios Dirección --}}
                            @if($caso->motivo_decision)
                                <div class="mb-5">
                                    <h6 class="text-secondary fw-bold text-uppercase small mb-2">Observaciones Generales de Dirección</h6>
                                    <div class="p-3 border rounded bg-light text-secondary fst-italic">
                                        "{{ $caso->motivo_decision }}"
                                    </div>
                                </div>
                            @endif

                            <hr class="my-5">

                            {{-- 5. BITÁCORA DE SEGUIMIENTO --}}
                            <div class="mb-4" id="bitacora">
                                <h4 class="fw-bold text-dark mb-3">
                                    <i class="bi bi-journal-text me-2"></i>Bitácora de Seguimiento Docente
                                </h4>
                                <p class="text-muted mb-4">Espacio para registrar observaciones sobre la implementación de los ajustes y la evolución del estudiante.</p>

                                {{-- Formulario Nuevo Comentario --}}
                                <div class="card bg-light border-0 mb-4">
                                    <div class="card-body">
                                        {{-- AQUÍ ESTABA EL DETALLE: Ruta Corregida con 'docente.' --}}
                                        <form action="{{ route('docente.ajustes.comentario', $caso->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-uppercase text-muted">Nuevo Registro</label>
                                                <textarea class="form-control" name="comentario" rows="3" placeholder="Escriba aquí sus observaciones (Ej: El estudiante utilizó el tiempo extra...)" required></textarea>
                                            </div>
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-dark btn-sm px-4">
                                                    <i class="bi bi-send-fill me-1"></i> Guardar Observación
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Historial de Comentarios --}}
                                <div class="vstack gap-3">
                                    @forelse($caso->seguimientos as $seguimiento)
                                        <div class="card border-start border-4 border-secondary shadow-sm">
                                            <div class="card-body py-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="fw-bold text-dark">{{ $seguimiento->docente->name ?? 'Docente' }}</span>
                                                    <small class="text-muted">{{ $seguimiento->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                                <p class="mb-0 text-secondary">{{ $seguimiento->comentario }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center text-muted py-3 border rounded border-dashed">
                                            <i class="bi bi-chat-square-text display-6 mb-2 d-block opacity-25"></i>
                                            Aún no hay registros de seguimiento para este caso.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>

                        {{-- Pie de página: Confirmación --}}
                        <div class="card-footer bg-white p-4 text-center border-top">
                            @if(!$yaConfirmado)
                                <form action="{{ route('docente.ajustes.confirmar', $caso->id) }}" method="POST">
                                    @csrf
                                    <div class="alert alert-warning d-inline-block text-start mb-3">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <strong>Acción Requerida:</strong> Al hacer clic en el botón inferior, usted confirma que ha leído y entendido los ajustes razonables necesarios.
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill">
                                        <i class="bi bi-check2-circle me-2"></i> Confirmar Lectura
                                    </button>
                                </form>
                            @else
                                <div class="text-success py-2">
                                    <i class="bi bi-check-circle-fill fs-1 d-block mb-2"></i>
                                    <h5 class="fw-bold">Lectura Confirmada</h5>
                                    <p class="text-muted mb-0 small">Usted ya ha tomado conocimiento de este caso.</p>
                                    <small class="text-muted">Fecha de confirmación: {{ now()->format('d/m/Y') }}</small>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>