<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-11">

                    {{-- 1. ENCABEZADO Y ESTADO --}}
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                        <div>
                            <h2 class="fw-semibold fs-2 text-body-emphasis mb-0">
                                Detalle del Caso #{{ $caso->id }}
                            </h2>
                            <div class="text-muted small mt-1">
                                <span class="fw-bold text-uppercase">Vía de Ingreso:</span> {{ $caso->via_ingreso ?? 'No registrada' }}
                            </div>
                        </div>
                        
                        {{-- Badge de Estado --}}
                        @php 
                            $estadoLimpio = strtolower(trim($caso->estado)); 
                            $claseBadge = 'bg-light text-dark border';
                            $icono = '';
                            $textoEstado = $caso->estado;

                            if(in_array($estadoLimpio, ['en gestion ctp', 'sin revision'])) {
                                $claseBadge = 'bg-info-subtle text-info-emphasis border border-info-subtle';
                                $icono = '📝';
                                $textoEstado = 'En Gestión CTP';
                            } elseif(in_array($estadoLimpio, ['pendiente de validacion', 'en revision'])) {
                                $claseBadge = 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';
                                $icono = '⏳';
                                $textoEstado = 'Pendiente Validación';
                            } elseif($estadoLimpio == 'reevaluacion') {
                                $claseBadge = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle';
                                $icono = '↩️';
                                $textoEstado = 'En Corrección';
                            } elseif(in_array($estadoLimpio, ['finalizado', 'aceptado'])) {
                                $claseBadge = 'bg-success-subtle text-success-emphasis border border-success-subtle';
                                $icono = '✅';
                                $textoEstado = 'Finalizado';
                            }
                        @endphp
                        <span class="badge rounded-pill fs-5 px-4 py-2 {{ $claseBadge }}">
                            {{ $icono }} {{ $textoEstado }}
                        </span>
                    </div>

                    {{-- 2. TARJETA DE ANTECEDENTES GENERALES --}}
                    <div class="card shadow-sm rounded-4 mb-4 border-0">
                        <div class="card-header bg-body-tertiary p-4 border-bottom">
                            <h5 class="fs-5 fw-bold mb-0 text-secondary"><i class="bi bi-person-lines-fill me-2"></i>Ficha del Estudiante</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                {{-- Datos Estudiante --}}
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            @if($caso->estudiante && $caso->estudiante->foto_perfil)
                                                <img src="{{ asset('storage/' . $caso->estudiante->foto_perfil) }}" class="rounded-circle shadow-sm border" width="60" height="60" style="object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm border" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                                    {{ substr($caso->nombre_estudiante, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-5">{{ $caso->nombre_estudiante }}</div>
                                            <div class="text-muted small">{{ $caso->rut_estudiante }}</div>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled small mb-0 text-secondary">
                                        <li class="mb-1"><i class="bi bi-envelope me-2"></i>{{ $caso->correo_estudiante }}</li>
                                        <li class="mb-1"><i class="bi bi-telephone me-2"></i>{{ $caso->estudiante->telefono ?? 'N/A' }}</li>
                                    </ul>
                                </div>

                                {{-- Datos Académicos y Responsables --}}
                                <div class="col-md-6 border-start-md ps-md-4">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label class="small fw-bold text-muted d-block">CARRERA</label>
                                            <span class="fw-medium text-dark">{{ $caso->carrera }}</span>
                                        </div>
                                        <div class="col-6">
                                            <label class="small fw-bold text-muted d-block">SEDE / JORNADA</label>
                                            <span>{{ $caso->estudiante->sede ?? 'Central' }} / {{ $caso->estudiante->jornada ?? 'Diurna' }}</span>
                                        </div>
                                        <div class="col-12 mt-3 pt-3 border-top">
                                            <label class="small fw-bold text-muted d-block mb-1">EQUIPO RESPONSABLE</label>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <span class="badge bg-light text-dark border" title="Encargada Inclusión"><i class="bi bi-person-check me-1"></i> {{ $caso->asesor->name ?? 'N/A' }}</span>
                                                <span class="badge bg-light text-dark border" title="Coord. Técnico Pedagógico"><i class="bi bi-gear me-1"></i> {{ $caso->ctp->name ?? 'Pendiente' }}</span>
                                                @if($caso->director)
                                                    <span class="badge bg-light text-dark border" title="Director Carrera"><i class="bi bi-building me-1"></i> {{ $caso->director->name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. ACORDEÓN DE DETALLES (LA PARTE RICA EN DATOS) --}}
                    <div class="accordion shadow-sm rounded-4 mb-4 border-0 overflow-hidden" id="accordionDetalles">
                        
                        {{-- SECCIÓN: Salud y Discapacidad --}}
                        <div class="accordion-item border-0 border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSalud">
                                    <i class="bi bi-clipboard-pulse me-2"></i> 1. Antecedentes de Salud y Discapacidad
                                </button>
                            </h2>
                            <div id="collapseSalud" class="accordion-collapse collapse show" data-bs-parent="#accordionDetalles">
                                <div class="accordion-body bg-light">
                                    
                                    {{-- Diagnósticos y Origen --}}
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-8">
                                            <label class="small fw-bold text-muted text-uppercase d-block mb-1">Diagnósticos Declarados</label>
                                            @if(is_array($caso->tipo_discapacidad))
                                                @foreach($caso->tipo_discapacidad as $tipo) 
                                                    <span class="badge bg-primary fs-6 me-1 mb-1">{{ $tipo }}</span> 
                                                @endforeach
                                            @else 
                                                <span class="text-muted fst-italic">No registra selección</span> 
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small fw-bold text-muted text-uppercase d-block mb-1">Origen</label>
                                            <div class="fw-medium">{{ $caso->origen_discapacidad ?? 'No indicado' }}</div>
                                        </div>
                                    </div>

                                    {{-- Documentación (Badges) --}}
                                    <div class="p-3 bg-white rounded border mb-4">
                                        <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Documentación de Respaldo</label>
                                        <div class="d-flex gap-3 flex-wrap">
                                            <span class="badge {{ $caso->credencial_rnd ? 'bg-success' : 'bg-secondary bg-opacity-25 text-secondary' }} rounded-pill px-3">
                                                <i class="bi {{ $caso->credencial_rnd ? 'bi-check-circle-fill' : 'bi-x-circle' }}"></i> Credencial RND
                                            </span>
                                            <span class="badge {{ $caso->pension_invalidez ? 'bg-success' : 'bg-secondary bg-opacity-25 text-secondary' }} rounded-pill px-3">
                                                <i class="bi {{ $caso->pension_invalidez ? 'bi-check-circle-fill' : 'bi-x-circle' }}"></i> Pensión Invalidez
                                            </span>
                                            <span class="badge {{ $caso->certificado_medico ? 'bg-success' : 'bg-secondary bg-opacity-25 text-secondary' }} rounded-pill px-3">
                                                <i class="bi {{ $caso->certificado_medico ? 'bi-check-circle-fill' : 'bi-x-circle' }}"></i> Certificado Médico
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Tratamientos y Apoyos --}}
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <label class="small fw-bold text-muted mb-1">TRATAMIENTO FARMACOLÓGICO</label>
                                                    <p class="small mb-0">{{ $caso->tratamiento_farmacologico ?? 'No indica' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <label class="small fw-bold text-muted mb-1">ESPECIALISTAS</label>
                                                    <p class="small mb-0">{{ $caso->acompanamiento_especialista ?? 'No indica' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <label class="small fw-bold text-muted mb-1">REDES DE APOYO</label>
                                                    <p class="small mb-0">{{ $caso->redes_apoyo ?? 'No indica' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN: Familia --}}
                        <div class="accordion-item border-0 border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFamilia">
                                    <i class="bi bi-people me-2"></i> 2. Grupo Familiar
                                </button>
                            </h2>
                            <div id="collapseFamilia" class="accordion-collapse collapse" data-bs-parent="#accordionDetalles">
                                <div class="accordion-body p-0">
                                    @if(is_array($caso->informacion_familiar) && count($caso->informacion_familiar) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0 small">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="ps-4">Nombre</th>
                                                        <th>Parentesco</th>
                                                        <th>Edad</th>
                                                        <th>Ocupación</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($caso->informacion_familiar as $familiar)
                                                        @if(!empty($familiar['nombre']))
                                                            <tr>
                                                                <td class="ps-4 fw-medium">{{ $familiar['nombre'] }}</td>
                                                                <td>{{ $familiar['parentesco'] ?? '-' }}</td>
                                                                <td>{{ $familiar['edad'] ?? '-' }}</td>
                                                                <td>{{ $familiar['ocupacion'] ?? '-' }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="p-4 text-muted fst-italic bg-light">No se registró información familiar.</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN: Historial Académico y Laboral --}}
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAcademico">
                                    <i class="bi bi-mortarboard me-2"></i> 3. Historial Académico y Laboral
                                </button>
                            </h2>
                            <div id="collapseAcademico" class="accordion-collapse collapse" data-bs-parent="#accordionDetalles">
                                <div class="accordion-body bg-light">
                                    
                                    {{-- Enseñanza Media --}}
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Enseñanza Media</h6>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label class="small text-muted fw-bold">MODALIDAD</label>
                                            <div>{{ $caso->enseñanza_media_modalidad ?? 'No indica' }}</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted fw-bold">APOYOS PIE</label>
                                            <div>
                                                @if($caso->recibio_apoyos_pie)
                                                    <span class="text-success fw-bold"><i class="bi bi-check-lg"></i> Sí</span>
                                                    <div class="small text-muted">{{ $caso->detalle_apoyos_pie ?? '' }}</div>
                                                @else
                                                    <span class="text-secondary">No</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted fw-bold">REPITENCIA</label>
                                            <div>
                                                @if($caso->repitio_curso)
                                                    <span class="text-danger fw-bold">Sí</span>
                                                    <div class="small text-muted">{{ $caso->motivo_repeticion ?? '' }}</div>
                                                @else
                                                    <span class="text-secondary">No</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Educación Superior --}}
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Educación Superior Previa</h6>
                                    <div class="mb-4">
                                        @if($caso->estudio_previo_superior)
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="small text-muted fw-bold">INSTITUCIÓN / TIPO</label>
                                                    <div>{{ $caso->nombre_institucion_anterior ?? '-' }} <span class="badge bg-light text-dark border ms-1">{{ $caso->tipo_institucion_anterior ?? '' }}</span></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted fw-bold">CARRERA</label>
                                                    <div>{{ $caso->carrera_anterior ?? '-' }}</div>
                                                </div>
                                                <div class="col-12">
                                                    <label class="small text-muted fw-bold">MOTIVO ABANDONO</label>
                                                    <div class="small fst-italic text-secondary">{{ $caso->motivo_no_termino ?? 'No detallado' }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">No registra estudios superiores previos.</span>
                                        @endif
                                    </div>

                                    {{-- Laboral --}}
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Situación Laboral</h6>
                                    <div>
                                        @if($caso->trabaja)
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="small text-muted fw-bold">EMPRESA</label>
                                                    <div>{{ $caso->empresa ?? 'No indica' }}</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small text-muted fw-bold">CARGO</label>
                                                    <div>{{ $caso->cargo ?? 'No indica' }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">No trabaja actualmente.</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. INTERESES Y SOLICITUDES --}}
                    <div class="card shadow-sm rounded-4 mb-4 border-0 border-start border-4 border-danger">
                        <div class="card-body p-4">
                            
                            {{-- Características e Intereses --}}
                            <div class="mb-4">
                                <h5 class="text-danger fw-bold fs-6 text-uppercase mb-2"><i class="bi bi-person-heart me-2"></i>Características e Intereses</h5>
                                <div class="bg-light p-3 rounded text-secondary small">
                                    {{ $caso->caracteristicas_intereses ?? 'No se registraron intereses o características específicas.' }}
                                </div>
                            </div>

                            {{-- Solicitudes --}}
                            <h5 class="text-danger fw-bold mb-3 fs-6 text-uppercase"><i class="bi bi-chat-quote-fill me-2"></i>Solicitudes Específicas (Anamnesis)</h5>
                            <div class="bg-danger-subtle p-3 rounded-3">
                                @if(is_array($caso->ajustes_propuestos) && count($caso->ajustes_propuestos) > 0)
                                    <ul class="mb-0 ps-3">
                                        @foreach($caso->ajustes_propuestos as $solicitud)
                                            @if(!empty($solicitud)) <li class="mb-2 text-dark">{{ $solicitud }}</li> @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted fst-italic mb-0">El estudiante no realizó solicitudes específicas o indicó que no requiere ajustes.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 5. RESOLUCIÓN TÉCNICA (CON AJUSTES CTP VISIBLES) --}}
                    @if($caso->ajustes_ctp || $caso->motivo_decision)
                        <div class="card shadow-lg border-0 rounded-4 border-top border-4 border-primary mb-5">
                            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold text-primary mb-0"><i class="bi bi-clipboard-check me-2"></i>Resolución Técnica</h5>
                                    <span class="text-muted small">Propuesta técnica y validación académica</span>
                                </div>
                                @if($estadoLimpio == 'finalizado')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        Firmado por: {{ $caso->director->name ?? 'Dirección' }}
                                    </span>
                                @endif
                            </div>

                            <div class="card-body p-0">
                                
                                {{-- ESCENARIO A: YA HAY EVALUACIÓN DEL DIRECTOR (FINALIZADO O RECHAZADO) --}}
                                @if(is_array($caso->evaluacion_director) && count($caso->evaluacion_director) > 0)
                                    <div class="table-responsive">
                                        <table class="table mb-0 align-middle">
                                            <thead class="bg-light text-secondary small text-uppercase">
                                                <tr>
                                                    <th class="px-4 py-3">Recurso / Ajuste Propuesto (CTP)</th>
                                                    <th class="px-4 py-3" style="width: 15%;">Decisión</th>
                                                    <th class="px-4 py-3" style="width: 35%;">Observación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($caso->evaluacion_director as $index => $eval)
                                                    <tr class="{{ $loop->even ? 'bg-body-tertiary' : '' }}">
                                                        <td class="px-4 py-3">
                                                            <div class="fw-medium text-dark">
                                                                {{ $caso->ajustes_ctp[$index] ?? 'Ajuste #' . ($index + 1) }}
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            @if($eval['decision'] == 'Aceptado') 
                                                                <span class="badge bg-success rounded-pill px-3"><i class="bi bi-check-lg me-1"></i> Aceptado</span>
                                                            @elseif($eval['decision'] == 'Rechazado') 
                                                                <span class="badge bg-danger rounded-pill px-3"><i class="bi bi-x-lg me-1"></i> Rechazado</span>
                                                            @else 
                                                                <span class="badge bg-secondary">{{ $eval['decision'] }}</span> 
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3 small text-muted">
                                                            {{ $eval['comentario'] ?? '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                {{-- ESCENARIO B: PENDIENTE DE VALIDACIÓN (SOLO PROPUESTA CTP) --}}
                                @elseif($caso->ajustes_ctp && count($caso->ajustes_ctp) > 0)
                                    <div class="p-4">
                                        <div class="alert alert-info border-info mb-3 d-flex align-items-center">
                                            <i class="bi bi-hourglass-split fs-4 me-3"></i>
                                            <div>
                                                <strong>Esperando Validación del Director</strong>
                                                <div class="small">A continuación se muestran los ajustes técnicos propuestos por el CTP que están pendientes de aprobación.</div>
                                            </div>
                                        </div>

                                        <h6 class="fw-bold text-dark mb-3 ps-2 border-start border-4 border-info">Propuesta Técnica del CTP:</h6>
                                        <ul class="list-group list-group-flush border rounded shadow-sm">
                                            @foreach($caso->ajustes_ctp as $ajuste)
                                                <li class="list-group-item bg-light py-3">
                                                    <div class="d-flex text-start">
                                                        <i class="bi bi-check2-square text-primary me-3 fs-5"></i>
                                                        <span class="fw-medium text-secondary">{{ $ajuste }}</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                {{-- ESCENARIO C: SIN DATOS AÚN --}}
                                @else
                                    <div class="p-5 text-center text-muted">
                                        <i class="bi bi-hourglass-split fs-1 mb-2 opacity-50"></i>
                                        <p>La evaluación técnica aún no ha sido cargada por el CTP.</p>
                                    </div>
                                @endif

                                {{-- COMENTARIO GLOBAL DEL DIRECTOR --}}
                                @if($caso->motivo_decision)
                                    <div class="p-4 border-top bg-light">
                                        <h6 class="fw-bold text-secondary small text-uppercase mb-2">Comentario Final de Dirección</h6>
                                        <div class="p-3 bg-white border rounded text-dark">
                                            {{ $caso->motivo_decision }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- 6. BOTONERA FINAL (FOOTER) --}}
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-5 p-3 bg-white rounded-4 shadow-sm border">
                        <a href="{{ route('casos.index') }}" class="btn btn-outline-secondary px-4 fw-bold">
                            <i class="bi bi-arrow-left me-2"></i> Volver al Listado
                        </a>

                        <div class="d-flex gap-2">
                            {{-- Botón Editar: Solo si NO está finalizado --}}
                            @if($estadoLimpio != 'finalizado')
                                <a href="{{ route('casos.edit', $caso->id) }}" class="btn btn-primary px-4 fw-bold">
                                    <i class="bi bi-pencil-square me-2"></i> Editar Caso
                                </a>
                            @endif

                            {{-- Botón PDF: Solo si ESTÁ finalizado --}}
                            @if($estadoLimpio == 'finalizado')
                                <a href="{{ route('reportes.caso.pdf', $caso->id) }}" class="btn btn-danger px-4 fw-bold shadow-sm" target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Descargar Informe PDF
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>