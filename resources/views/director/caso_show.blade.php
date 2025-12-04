<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-11">

                    <h2 class="fw-semibold fs-2 text-body-emphasis mb-4">
                        Validación de Ajustes (Caso #{{ $caso->id }})
                    </h2>

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

                    <div class="card shadow-sm rounded-3 mb-5 border-0">
                        <div class="card-header bg-body-tertiary p-4 border-bottom">
                            <h3 class="fs-5 fw-bold mb-0 text-secondary">Antecedentes Generales</h3>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <div class="row g-5">
                                {{-- Columna Izquierda: Información del Estudiante --}}
                                <div class="col-md-6">
                                    <h4 class="fs-6 fw-bold text-uppercase text-primary mb-3 border-bottom pb-2">Estudiante</h4>
                                    
                                    <div class="mb-3">
                                        <label class="small text-muted fw-bold">NOMBRE COMPLETO</label>
                                        <div class="fs-5 text-dark">{{ $caso->nombre_estudiante }}</div>
                                        <div class="small text-secondary">{{ $caso->rut_estudiante }}</div>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6"><label class="small text-muted fw-bold">CARRERA</label><div>{{ $caso->carrera }}</div></div>
                                        <div class="col-md-6"><label class="small text-muted fw-bold">CORREO</label><div class="text-break">{{ $caso->correo_estudiante }}</div></div>
                                    </div>

                                    {{-- Datos Adicionales --}}
                                    <div class="accordion accordion-flush border rounded" id="accordionDetalles">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed py-2 small fw-bold text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne">
                                                    <i class="bi bi-plus-circle me-2"></i> Ver Discapacidad y Salud
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionDetalles">
                                                <div class="accordion-body small bg-light">
                                                    <p class="mb-1"><strong>Tipos:</strong> 
                                                        @if(is_array($caso->tipo_discapacidad)) @foreach($caso->tipo_discapacidad as $t) {{ $t }}, @endforeach @else - @endif
                                                    </p>
                                                    <p class="mb-0"><strong>Tratamiento:</strong> {{ $caso->tratamiento_farmacologico ?? 'No indica' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Columna Derecha: Datos del Caso --}}
                                <div class="col-md-6 border-start-md">
                                    <h4 class="fs-6 fw-bold text-uppercase text-primary mb-3 border-bottom pb-2">Estado del Caso</h4>
                                    
                                    <div class="mb-4">
                                        <label class="small text-muted fw-bold">ESTADO ACTUAL</label>
                                        <div>
                                            @php $estadoLimpio = strtolower(trim($caso->estado)); @endphp
                                            <span class="badge rounded-pill fs-6 px-3 py-2
                                                @if(in_array($estadoLimpio, ['pendiente de validacion', 'en revision'])) bg-warning text-dark
                                                @elseif($estadoLimpio == 'reevaluacion') bg-danger text-white
                                                @elseif($estadoLimpio == 'finalizado' || $estadoLimpio == 'aceptado') bg-primary text-white
                                                @elseif($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision') bg-info text-dark
                                                @else bg-secondary text-white @endif">
                                                
                                                @if($estadoLimpio == 'pendiente de validacion') ⏳ Pendiente Validación
                                                @elseif($estadoLimpio == 'en revision') ⏳ En Revisión (Legacy)
                                                @elseif($estadoLimpio == 'reevaluacion') ↩️ En Corrección
                                                @elseif($estadoLimpio == 'finalizado') ✅ Finalizado
                                                @elseif($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision') 📝 En Gestión CTP
                                                @else {{ ucfirst($caso->estado) }} @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-4"><label class="small text-muted fw-bold">FECHA CREACIÓN</label><div>{{ $caso->created_at->format('d/m/Y H:i') }}</div></div>
                                    
                                    <div class="mb-3">
                                        <label class="small text-muted fw-bold">EQUIPO A CARGO</label>
                                        <ul class="list-unstyled small mb-0">
                                            <li><strong>Asesor(a):</strong> {{ $caso->asesor?->name ?? 'N/A' }}</li>
                                            <li><strong>CTP:</strong> {{ $caso->ctp?->name ?? 'Pendiente' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm rounded-3 mb-5 border-0 border-start border-4 border-danger">
                        <div class="card-body p-4">
                            <h5 class="text-danger fw-bold mb-3"><i class="bi bi-chat-quote-fill me-2"></i>Solicitudes del Estudiante (Contexto)</h5>
                            <div class="bg-danger-subtle p-3 rounded">
                                @if(is_array($caso->ajustes_propuestos) && count($caso->ajustes_propuestos) > 0)
                                    <ul class="mb-0 ps-3">
                                        @foreach($caso->ajustes_propuestos as $solicitud)
                                            @if(!empty($solicitud)) <li class="mb-2 text-dark">{{ $solicitud }}</li> @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted fst-italic mb-0">Sin solicitudes específicas.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-lg border-0 rounded-4 border-top border-4 border-primary">
                        <div class="card-header bg-white p-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="fs-4 fw-bold mb-0 text-primary">Evaluación de Ajustes</h3>
                                    <p class="text-muted mb-0 small">Revise cada recurso técnico propuesto por el CTP.</p>
                                </div>
                                <i class="bi bi-check2-circle fs-1 text-primary opacity-25"></i>
                            </div>
                        </div>

                        {{-- ================================================================= --}}
                        {{-- LOGICA PARA MOSTRAR FORMULARIO O SOLO LECTURA                    --}}
                        {{-- ================================================================= --}}
                        
                        @php $estadoLimpio = strtolower(trim($caso->estado)); @endphp
                        
                        @if(in_array($estadoLimpio, ['pendiente de validacion', 'en revision']))
                            
                            {{-- ================= FORMULARIO DE VALIDACIÓN ================= --}}
                            <form method="POST" action="{{ route('director.casos.validar', $caso) }}">
                                @csrf
                                <div class="card-body p-0">
                                    
                                    @if(is_array($caso->ajustes_ctp) && count($caso->ajustes_ctp) > 0)
                                        @foreach($caso->ajustes_ctp as $index => $ajusteTecnico)
                                            
                                            @if(empty(trim($ajusteTecnico))) @continue @endif

                                            <div class="p-4 border-bottom {{ $loop->even ? 'bg-body-tertiary' : '' }}">
                                                <div class="row g-4 align-items-center">
                                                    
                                                    {{-- Columna Izquierda: Propuesta Técnica --}}
                                                    <div class="col-lg-7">
                                                        <h6 class="fw-bold text-secondary small text-uppercase mb-2">Recurso #{{ $index + 1 }}</h6>
                                                        <div class="p-3 bg-info-subtle rounded-3 border border-info-subtle">
                                                            <label class="small fw-bold text-info-emphasis d-flex align-items-center mb-1">
                                                                <i class="bi bi-tools me-2"></i> Propuesta Técnica (CTP)
                                                            </label>
                                                            <div class="fs-5 text-dark fw-medium">{{ $ajusteTecnico }}</div>
                                                        </div>
                                                    </div>

                                                    {{-- Columna Derecha: Decisión --}}
                                                    <div class="col-lg-5 border-start-lg ps-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold text-primary small">Decisión</label>
                                                            {{-- SOLO ACEPTAR O RECHAZAR --}}
                                                            <select class="form-select form-select-sm border-primary" name="decisiones[{{ $index }}]" required>
                                                                <option value="" selected disabled>Seleccione...</option>
                                                                <option value="Aceptado">✅ Aceptar</option>
                                                                <option value="Rechazado">❌ Rechazar</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="form-label fw-bold text-muted small">Observación Específica (Opcional)</label>
                                                            <textarea class="form-control form-control-sm" name="comentarios[{{ $index }}]" rows="1" placeholder="Motivo o comentario del ítem..."></textarea>
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

                                    {{-- NUEVA SECCIÓN: COMENTARIOS GENERALES --}}
                                    <div class="p-4 bg-light border-top">
                                        <label class="form-label fw-bold text-secondary mb-2">
                                            <i class="bi bi-chat-left-text me-2"></i>Observaciones Generales (Opcional)
                                        </label>
                                        <textarea class="form-control" name="comentario_global" rows="3" placeholder="Si desea agregar un comentario general sobre la decisión tomada o instrucciones adicionales..."></textarea>
                                        <div class="form-text">Este comentario aparecerá en el informe final del caso.</div>
                                    </div>

                                </div>

                                <div class="card-footer bg-white p-4 d-flex justify-content-end border-top">
                                    <a href="{{ route('director.casos.pendientes') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                                        <i class="bi bi-check-lg me-2"></i> Finalizar Validación
                                    </button>
                                </div>
                            </form>

                        @else

                            {{-- ================= MODO SOLO LECTURA O ESPERA ================= --}}
                            <div class="card-body p-4">
                                
                                {{-- CASO 1: ESTÁ EN GESTIÓN CTP (AÚN NO LLEGA AL DIRECTOR) --}}
                                @if(in_array($estadoLimpio, ['en gestion ctp', 'sin revision']))
                                    <div class="alert alert-info d-flex align-items-center border-info shadow-sm" role="alert">
                                        <i class="bi bi-hourglass-split fs-2 me-3"></i>
                                        <div>
                                            <h5 class="alert-heading fw-bold mb-1">Caso en Etapa Técnica</h5>
                                            <p class="mb-0">
                                                Este caso está siendo evaluado actualmente por el <strong>Coordinador Técnico Pedagógico (CTP)</strong> para la definición de ajustes razonables. 
                                                Una vez que el CTP complete su propuesta, el caso aparecerá en su bandeja de "Pendientes" para validación.
                                            </p>
                                        </div>
                                    </div>
                                
                                {{-- CASO 2: YA FUE PROCESADO (FINALIZADO/RECHAZADO) --}}
                                @else
                                    <div class="alert alert-secondary d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                                        <div>
                                            Este caso ya fue procesado. <strong>Estado final: {{ ucfirst($caso->estado) }}</strong>
                                        </div>
                                    </div>

                                    <h5 class="fs-6 fw-bold text-uppercase text-muted mb-3 mt-4">Resumen de la Evaluación</h5>
                                    
                                    @if(is_array($caso->evaluacion_director))
                                        <div class="table-responsive border rounded">
                                            <table class="table table-sm mb-0 align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Recurso Evaluado</th>
                                                        <th>Decisión</th>
                                                        <th>Observación</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($caso->evaluacion_director as $index => $eval)
                                                        <tr>
                                                            <td class="fw-medium">
                                                                {{ $caso->ajustes_ctp[$index] ?? 'Ajuste #' . ($index + 1) }}
                                                            </td>
                                                            <td>
                                                                @if($eval['decision'] == 'Aceptado') 
                                                                    <span class="badge bg-success"><i class="bi bi-check"></i> Aceptado</span>
                                                                @elseif($eval['decision'] == 'Rechazado') 
                                                                    <span class="badge bg-danger"><i class="bi bi-x"></i> Rechazado</span>
                                                                @else 
                                                                    <span class="badge bg-warning text-dark">{{ $eval['decision'] }}</span> 
                                                                @endif
                                                            </td>
                                                            <td class="small text-muted">{{ $eval['comentario'] ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted small">No se encontró el detalle ítem por ítem en el historial.</p>
                                    @endif

                                    @if($caso->motivo_decision)
                                        <div class="p-3 bg-light rounded border mt-4">
                                            <strong class="text-secondary d-block mb-1">Comentario General:</strong>
                                            <div class="text-dark">{{ $caso->motivo_decision }}</div>
                                        </div>
                                    @endif
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