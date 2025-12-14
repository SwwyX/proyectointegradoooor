<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12">

                    {{-- ENCABEZADO --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold text-body-emphasis mb-1">
                                Validación de Ajustes
                            </h2>
                            <p class="text-muted mb-0">Caso #{{ $caso->id }} - {{ $caso->nombre_estudiante }}</p>
                        </div>
                        <span class="badge rounded-pill fs-6 px-3 py-2 
                            {{ $caso->estado == 'Pendiente de Validacion' ? 'bg-warning text-dark' : 'bg-secondary text-white' }}">
                            {{ $caso->estado }}
                        </span>
                    </div>

                    {{-- BLOQUE DE ERRORES --}}
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 1. RADIOGRAFÍA COMPLETA DEL ESTUDIANTE (NUEVO DISEÑO DETALLADO) --}}
                    <div class="card shadow-sm rounded-4 mb-5 border-0">
                        <div class="card-header bg-body-tertiary p-4 border-bottom">
                            <h5 class="fw-bold text-secondary mb-0"><i class="bi bi-person-lines-fill me-2"></i>Ficha Integral del Estudiante</h5>
                        </div>
                        <div class="card-body p-4">
                            
                            {{-- A. IDENTIFICACIÓN --}}
                            <div class="row g-4 mb-4 border-bottom pb-4">
                                <div class="col-md-2 text-center">
                                    @if($caso->estudiante && $caso->estudiante->foto_perfil)
                                        <img src="{{ asset('storage/' . $caso->estudiante->foto_perfil) }}" class="rounded-circle shadow-sm border" style="width: 90px; height: 90px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto" style="width: 90px; height: 90px;">
                                            <i class="bi bi-person fs-1 text-secondary opacity-50"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="small text-muted fw-bold text-uppercase">Nombre</label>
                                            <div class="fw-medium text-dark">{{ $caso->nombre_estudiante }}</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted fw-bold text-uppercase">RUT</label>
                                            <div>{{ $caso->rut_estudiante }}</div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="small text-muted fw-bold text-uppercase">Carrera</label>
                                            <div>{{ $caso->carrera }}</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted fw-bold text-uppercase">Correo</label>
                                            <div>{{ $caso->correo_estudiante }}</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted fw-bold text-uppercase">Vía Ingreso</label>
                                            <div>{{ $caso->via_ingreso }}</div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="small text-muted fw-bold text-uppercase">Equipo</label>
                                            <div class="small">
                                                <span class="me-2"><i class="bi bi-person"></i> Ases: {{ $caso->asesor->name ?? 'N/A' }}</span>
                                                <span><i class="bi bi-gear"></i> CTP: {{ $caso->ctp->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- B. DETALLES ESPECÍFICOS (ACORDEÓN) --}}
                            <div class="accordion accordion-flush border rounded" id="accordionDirector">
                                
                                {{-- Salud --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSalud">
                                            <i class="bi bi-heart-pulse me-2"></i> 1. Salud y Discapacidad
                                        </button>
                                    </h2>
                                    <div id="flush-collapseSalud" class="accordion-collapse collapse" data-bs-parent="#accordionDirector">
                                        <div class="accordion-body bg-light">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="small fw-bold text-muted">DIAGNÓSTICOS:</label>
                                                    <div>
                                                        @if(is_array($caso->tipo_discapacidad))
                                                            @foreach($caso->tipo_discapacidad as $t) <span class="badge bg-primary me-1">{{ $t }}</span> @endforeach
                                                        @else <span class="text-muted fst-italic">No registra</span> @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6"><label class="small fw-bold text-muted">ORIGEN:</label> {{ $caso->origen_discapacidad ?? 'No indica' }}</div>
                                                <div class="col-md-6">
                                                    <label class="small fw-bold text-muted">DOCUMENTACIÓN:</label>
                                                    <div class="d-flex gap-2 small">
                                                        <span class="{{ $caso->credencial_rnd ? 'text-success fw-bold' : 'text-muted text-decoration-line-through' }}">RND</span> |
                                                        <span class="{{ $caso->pension_invalidez ? 'text-success fw-bold' : 'text-muted text-decoration-line-through' }}">Pensión</span> |
                                                        <span class="{{ $caso->certificado_medico ? 'text-success fw-bold' : 'text-muted text-decoration-line-through' }}">Cert. Médico</span>
                                                    </div>
                                                </div>
                                                <div class="col-12 border-top pt-2">
                                                    <p class="mb-1 small"><strong>Tratamiento:</strong> {{ $caso->tratamiento_farmacologico ?? 'No indica' }}</p>
                                                    <p class="mb-1 small"><strong>Especialistas:</strong> {{ $caso->acompanamiento_especialista ?? 'No indica' }}</p>
                                                    <p class="mb-0 small"><strong>Redes:</strong> {{ $caso->redes_apoyo ?? 'No indica' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Historial Académico / Laboral --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseHistorial">
                                            <i class="bi bi-book me-2"></i> 2. Historial Académico y Laboral
                                        </button>
                                    </h2>
                                    <div id="flush-collapseHistorial" class="accordion-collapse collapse" data-bs-parent="#accordionDirector">
                                        <div class="accordion-body bg-light">
                                            <div class="row g-3">
                                                <div class="col-md-6 border-end">
                                                    <h6 class="fw-bold text-dark border-bottom pb-1 small">Enseñanza Media</h6>
                                                    <ul class="list-unstyled small mb-0">
                                                        <li>Modalidad: {{ $caso->enseñanza_media_modalidad ?? '-' }}</li>
                                                        <li>PIE: {{ $caso->recibio_apoyos_pie ? 'Sí (' . $caso->detalle_apoyos_pie . ')' : 'No' }}</li>
                                                        <li>Repitencia: {{ $caso->repitio_curso ? 'Sí (' . $caso->motivo_repeticion . ')' : 'No' }}</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold text-dark border-bottom pb-1 small">Ed. Superior y Trabajo</h6>
                                                    <ul class="list-unstyled small mb-0">
                                                        <li>Previos: {{ $caso->estudio_previo_superior ? 'Sí (' . $caso->nombre_institucion_anterior . ')' : 'No' }}</li>
                                                        <li>Trabaja: {{ $caso->trabaja ? 'Sí (' . $caso->cargo . ')' : 'No' }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Familia --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFamilia">
                                            <i class="bi bi-people me-2"></i> 3. Grupo Familiar
                                        </button>
                                    </h2>
                                    <div id="flush-collapseFamilia" class="accordion-collapse collapse" data-bs-parent="#accordionDirector">
                                        <div class="accordion-body bg-light p-0">
                                            @if(is_array($caso->informacion_familiar) && count($caso->informacion_familiar) > 0)
                                                <table class="table table-sm table-striped mb-0 small">
                                                    <thead><tr><th class="ps-3">Nombre</th><th>Parentesco</th><th>Edad</th><th>Ocupación</th></tr></thead>
                                                    <tbody>
                                                        @foreach($caso->informacion_familiar as $fam)
                                                            @if(!empty($fam['nombre']))
                                                                <tr><td class="ps-3">{{ $fam['nombre'] }}</td><td>{{ $fam['parentesco'] }}</td><td>{{ $fam['edad'] }}</td><td>{{ $fam['ocupacion'] }}</td></tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <div class="p-3 small text-muted">No registra información familiar.</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- C. SOLICITUDES DEL ESTUDIANTE --}}
                            <div class="mt-4">
                                <h5 class="text-danger fw-bold fs-6 text-uppercase border-bottom border-danger pb-2 mb-3">
                                    <i class="bi bi-chat-quote-fill me-2"></i>Solicitudes del Estudiante (Anamnesis)
                                </h5>
                                <div class="bg-danger-subtle p-3 rounded border border-danger-subtle">
                                    @if(is_array($caso->ajustes_propuestos) && count($caso->ajustes_propuestos) > 0)
                                        <ul class="mb-0 ps-3">
                                            @foreach($caso->ajustes_propuestos as $solicitud)
                                                @if(!empty($solicitud)) <li class="mb-1 text-dark">{{ $solicitud }}</li> @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted fst-italic mb-0">No registra solicitudes específicas.</p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- 2. ZONA DE EVALUACIÓN (LÓGICA INTACTA) --}}
                    @php $estadoLimpio = strtolower(trim($caso->estado)); @endphp

                    <div class="card shadow-lg border-0 rounded-4 border-top border-4 border-primary">
                        <div class="card-header bg-white p-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="fs-4 fw-bold mb-0 text-primary">Evaluación de Ajustes</h3>
                                    <p class="text-muted mb-0 small">Revise y valide la propuesta técnica del CTP.</p>
                                </div>
                                <i class="bi bi-check2-circle fs-1 text-primary opacity-25"></i>
                            </div>
                        </div>

                        {{-- CASO A: PENDIENTE DE VALIDACIÓN (MOSTRAR FORMULARIO) --}}
                        @if(in_array($estadoLimpio, ['pendiente de validacion', 'en revision']))
                            
                            <form method="POST" action="{{ route('director.casos.validar', $caso) }}">
                                @csrf
                                <div class="card-body p-0">
                                    
                                    @if(is_array($caso->ajustes_ctp) && count($caso->ajustes_ctp) > 0)
                                        @foreach($caso->ajustes_ctp as $index => $ajusteTecnico)
                                            @if(empty(trim($ajusteTecnico))) @continue @endif

                                            <div class="p-4 border-bottom {{ $loop->even ? 'bg-body-tertiary' : '' }}">
                                                <div class="row g-4 align-items-center">
                                                    {{-- Propuesta CTP --}}
                                                    <div class="col-lg-7">
                                                        <h6 class="fw-bold text-secondary small text-uppercase mb-2">Recurso #{{ $index + 1 }}</h6>
                                                        <div class="p-3 bg-info-subtle rounded-3 border border-info-subtle">
                                                            <div class="fs-5 text-dark fw-medium">{{ $ajusteTecnico }}</div>
                                                        </div>
                                                    </div>
                                                    {{-- Decisión Director --}}
                                                    <div class="col-lg-5 border-start-lg ps-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold text-primary small">Decisión</label>
                                                            <select class="form-select form-select-sm border-primary" name="decisiones[{{ $index }}]" required>
                                                                <option value="" selected disabled>Seleccione...</option>
                                                                <option value="Aceptado">✅ Aceptar</option>
                                                                <option value="Rechazado">❌ Rechazar</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="form-label fw-bold text-muted small">Observación (Opcional)</label>
                                                            <textarea class="form-control form-control-sm" name="comentarios[{{ $index }}]" rows="1"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="p-5 text-center text-muted">
                                            <i class="bi bi-exclamation-circle fs-1 d-block mb-3 opacity-25"></i>
                                            El CTP no ha seleccionado ningún ajuste técnico para evaluar.
                                        </div>
                                        <input type="hidden" name="decisiones" value="">
                                    @endif

                                    {{-- Comentario Global --}}
                                    <div class="p-4 bg-light border-top">
                                        <label class="form-label fw-bold text-secondary mb-2">
                                            <i class="bi bi-chat-left-text me-2"></i>Observaciones Generales (Opcional)
                                        </label>
                                        <textarea class="form-control" name="comentario_global" rows="3" placeholder="Comentario general para el informe final..."></textarea>
                                    </div>
                                </div>

                                <div class="card-footer bg-white p-4 d-flex justify-content-end border-top">
                                    <a href="{{ route('director.casos.pendientes') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                                        <i class="bi bi-check-lg me-2"></i> Finalizar Validación
                                    </button>
                                </div>
                            </form>

                        {{-- CASO B: YA PROCESADO O EN OTRA ETAPA --}}
                        @else
                            <div class="card-body p-4">
                                @if(in_array($estadoLimpio, ['en gestion ctp', 'sin revision']))
                                    <div class="alert alert-info d-flex align-items-center border-info shadow-sm" role="alert">
                                        <i class="bi bi-hourglass-split fs-2 me-3"></i>
                                        <div>
                                            <strong>En Gestión Técnica:</strong> El CTP está definiendo la propuesta.
                                        </div>
                                    </div>
                                @else
                                    {{-- Resumen de Evaluación (Solo Lectura) --}}
                                    <div class="alert alert-secondary d-flex justify-content-between align-items-center mb-4">
                                        <span>Estado Final: <strong>{{ ucfirst($caso->estado) }}</strong></span>
                                        <a href="{{ route('reportes.caso.pdf', $caso) }}" class="btn btn-danger btn-sm fw-bold" target="_blank">
                                            <i class="bi bi-file-pdf-fill me-1"></i> Informe PDF
                                        </a>
                                    </div>

                                    @if(is_array($caso->evaluacion_director))
                                        <div class="table-responsive border rounded">
                                            <table class="table table-sm mb-0 align-middle">
                                                <thead class="table-light">
                                                    <tr><th>Recurso</th><th>Decisión</th><th>Observación</th></tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($caso->evaluacion_director as $index => $eval)
                                                        <tr>
                                                            <td class="fw-medium">{{ $caso->ajustes_ctp[$index] ?? '-' }}</td>
                                                            <td>
                                                                @if($eval['decision'] == 'Aceptado') <span class="badge bg-success">Aceptado</span>
                                                                @elseif($eval['decision'] == 'Rechazado') <span class="badge bg-danger">Rechazado</span>
                                                                @else <span class="badge bg-secondary">{{ $eval['decision'] }}</span> @endif
                                                            </td>
                                                            <td class="small text-muted">{{ $eval['comentario'] ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                    @if($caso->motivo_decision)
                                        <div class="p-3 bg-light rounded border mt-3">
                                            <small class="fw-bold text-muted">Comentario General:</small>
                                            <div>{{ $caso->motivo_decision }}</div>
                                        </div>
                                    @endif

                                    {{-- BITÁCORA DOCENTE (NUEVO) --}}
                                    <div class="mt-5 pt-4 border-top">
                                        <h5 class="text-secondary fw-bold fs-6 text-uppercase mb-3">
                                            <i class="bi bi-journal-text me-2"></i>Bitácora de Seguimiento Docente
                                        </h5>
                                        
                                        @if($caso->seguimientos->isNotEmpty())
                                            <div class="vstack gap-3">
                                                @foreach($caso->seguimientos as $seguimiento)
                                                    <div class="card border-0 bg-light shadow-sm">
                                                        <div class="card-body py-2 px-3">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <strong class="text-dark small">{{ $seguimiento->docente->name ?? 'Docente' }}</strong>
                                                                <small class="text-muted" style="font-size: 0.75rem;">{{ $seguimiento->created_at->format('d/m/Y H:i') }}</small>
                                                            </div>
                                                            <p class="mb-0 small text-secondary">{{ $seguimiento->comentario }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-light border text-center text-muted small">
                                                No hay registros de seguimiento docente para este caso.
                                            </div>
                                        @endif
                                    </div>

                                @endif
                            </div>
                            <div class="card-footer bg-white p-3 text-end">
                                <a href="{{ route('director.casos.pendientes') }}" class="btn btn-secondary">Volver</a>
                            </div>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>