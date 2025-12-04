<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-semibold fs-2 text-body-emphasis">
                            Detalle del Caso #{{ $caso->id }}
                        </h2>
                        {{-- Badge de Estado Actualizado --}}
                        @php $estadoLimpio = strtolower(trim($caso->estado)); @endphp
                        <span class="badge rounded-pill fs-5 px-4 py-2
                            @if($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision') 
                                bg-info-subtle text-info-emphasis border border-info-subtle
                            @elseif($estadoLimpio == 'pendiente de validacion' || $estadoLimpio == 'en revision') 
                                bg-warning-subtle text-warning-emphasis border border-warning-subtle
                            @elseif($estadoLimpio == 'reevaluacion') 
                                bg-danger-subtle text-danger-emphasis border border-danger-subtle
                            @elseif($estadoLimpio == 'finalizado' || $estadoLimpio == 'aceptado') 
                                bg-success-subtle text-success-emphasis border border-success-subtle
                            @else 
                                bg-light text-dark border 
                            @endif">
                            
                            @if($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision') 
                                📝 En Gestión CTP
                            @elseif($estadoLimpio == 'pendiente de validacion') 
                                ⏳ Pendiente Validación
                            @elseif($estadoLimpio == 'reevaluacion') 
                                ↩️ En Corrección
                            @elseif($estadoLimpio == 'finalizado') 
                                ✅ Finalizado
                            @else 
                                {{ ucfirst($caso->estado) }} 
                            @endif
                        </span>
                    </div>

                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                        
                        {{-- 1. CABECERA: DATOS DE IDENTIFICACIÓN --}}
                        <div class="card-header bg-white p-4 border-bottom">
                            <h5 class="text-secondary fw-bold mb-3"><i class="bi bi-person-vcard me-2"></i>Datos de Identificación</h5>
                            
                            <div class="alert alert-light border border-primary-subtle d-flex align-items-center mb-4">
                                <i class="bi bi-box-arrow-in-right fs-4 text-primary me-3"></i>
                                <div>
                                    <small class="text-muted text-uppercase fw-bold">Vía de Ingreso al Flujo</small>
                                    <div class="fw-bold fs-5">{{ $caso->via_ingreso ?? 'No registrado' }}</div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-9">
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <label class="small text-muted fw-bold">NOMBRE COMPLETO</label>
                                            <div class="fs-5">{{ $caso->nombre_estudiante }}</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted fw-bold">RUT</label>
                                            <div class="fs-5">{{ $caso->rut_estudiante }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small text-muted fw-bold">CORREO</label>
                                            <div>{{ $caso->correo_estudiante }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small text-muted fw-bold">CARRERA</label>
                                            <div>{{ $caso->carrera }}</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted fw-bold">TELÉFONO</label>
                                            <div>{{ $caso->estudiante->telefono ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted fw-bold">JORNADA</label>
                                            <div>{{ $caso->estudiante->jornada ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted fw-bold">SEDE</label>
                                            <div>{{ $caso->estudiante->sede ?? 'N/A' }}</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted fw-bold">ÁREA ACADÉMICA</label>
                                            <div>{{ $caso->estudiante->area_academica ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    @if($caso->estudiante->foto_perfil)
                                        <img src="{{ asset('storage/' . $caso->estudiante->foto_perfil) }}" class="rounded-circle shadow-sm border" style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/150?text=Foto" class="rounded-circle shadow-sm border" style="width: 120px; height: 120px; object-fit: cover;">
                                    @endif
                                    <div class="mt-2 small text-muted">Estudiante</div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">

                            {{-- 2. SALUD Y DISCAPACIDAD --}}
                            <div class="mb-5">
                                <h5 class="text-primary fw-bold border-bottom pb-2 mb-3"><i class="bi bi-clipboard-pulse me-2"></i>1. Antecedentes de Salud</h5>
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-12">
                                        <label class="small text-muted fw-bold d-block mb-2">TIPOS DE DISCAPACIDAD DECLARADOS:</label>
                                        @if(is_array($caso->tipo_discapacidad) && count($caso->tipo_discapacidad) > 0)
                                            @foreach($caso->tipo_discapacidad as $tipo)
                                                <span class="badge bg-primary me-1 mb-1 fs-6">{{ $tipo }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted fst-italic">Ninguna seleccionada</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row g-3 p-3 bg-light rounded">
                                    <div class="col-md-3">
                                        <label class="small fw-bold text-muted">ORIGEN</label>
                                        <div>{{ $caso->origen_discapacidad ?? 'No indicado' }}</div>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="small fw-bold text-muted">DOCUMENTACIÓN</label>
                                        <div class="d-flex gap-3">
                                            <span class="{{ $caso->credencial_rnd ? 'text-success fw-bold' : 'text-muted' }}">
                                                <i class="bi {{ $caso->credencial_rnd ? 'bi-check-circle-fill' : 'bi-circle' }}"></i> RND
                                            </span>
                                            <span class="{{ $caso->pension_invalidez ? 'text-success fw-bold' : 'text-muted' }}">
                                                <i class="bi {{ $caso->pension_invalidez ? 'bi-check-circle-fill' : 'bi-circle' }}"></i> Pensión
                                            </span>
                                            <span class="{{ $caso->certificado_medico ? 'text-success fw-bold' : 'text-muted' }}">
                                                <i class="bi {{ $caso->certificado_medico ? 'bi-check-circle-fill' : 'bi-circle' }}"></i> Cert. Médico
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 border-top pt-2 mt-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="small fw-bold text-muted">TRATAMIENTO FARMACOLÓGICO</label>
                                                <div class="small">{{ $caso->tratamiento_farmacologico ?? 'No indica' }}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small fw-bold text-muted">ESPECIALISTAS</label>
                                                <div class="small">{{ $caso->acompanamiento_especialista ?? 'No indica' }}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small fw-bold text-muted">REDES DE APOYO</label>
                                                <div class="small">{{ $caso->redes_apoyo ?? 'No indica' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. FAMILIA --}}
                            <div class="mb-5">
                                <h5 class="text-primary fw-bold border-bottom pb-2 mb-3"><i class="bi bi-people me-2"></i>2. Grupo Familiar</h5>
                                @if(is_array($caso->informacion_familiar) && count($caso->informacion_familiar) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Parentesco</th>
                                                    <th>Edad</th>
                                                    <th>Ocupación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($caso->informacion_familiar as $familiar)
                                                    @if(!empty($familiar['nombre'])) 
                                                    <tr>
                                                        <td>{{ $familiar['nombre'] ?? '-' }}</td>
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
                                    <p class="text-muted fst-italic">No se registró información familiar.</p>
                                @endif
                            </div>

                            {{-- 4. ANTECEDENTES ACADÉMICOS Y LABORALES --}}
                            <div class="mb-5">
                                <h5 class="text-primary fw-bold border-bottom pb-2 mb-3"><i class="bi bi-mortarboard me-2"></i>3. Antecedentes Académicos y Laborales</h5>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="card h-100 bg-light border-0">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-dark">Enseñanza Media</h6>
                                                <ul class="list-unstyled small mb-0">
                                                    <li><strong>Modalidad:</strong> {{ $caso->enseñanza_media_modalidad ?? 'N/A' }}</li>
                                                    <li><strong>PIE:</strong> {{ $caso->recibio_apoyos_pie ? 'Sí' : 'No' }} <span class="text-muted">({{ $caso->detalle_apoyos_pie ?? '-' }})</span></li>
                                                    <li><strong>Repitencia:</strong> {{ $caso->repitio_curso ? 'Sí' : 'No' }} <span class="text-muted">({{ $caso->motivo_repeticion ?? '-' }})</span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card h-100 bg-light border-0">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-dark">Superior y Laboral</h6>
                                                <ul class="list-unstyled small mb-0">
                                                    <li><strong>Estudios Previos:</strong> {{ $caso->estudio_previo_superior ? 'Sí' : 'No' }}</li>
                                                    @if($caso->estudio_previo_superior)
                                                        <li class="ms-3 text-muted">- {{ $caso->nombre_institucion_anterior }} ({{ $caso->carrera_anterior }})</li>
                                                    @endif
                                                    <li class="mt-2"><strong>Trabaja:</strong> {{ $caso->trabaja ? 'Sí' : 'No' }}</li>
                                                    @if($caso->trabaja)
                                                        <li class="ms-3 text-muted">- {{ $caso->cargo }} en {{ $caso->empresa }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 5. INTERESES Y SOLICITUDES --}}
                            <div class="mb-5">
                                <h5 class="text-primary fw-bold border-bottom pb-2 mb-3"><i class="bi bi-file-text me-2"></i>4. Solicitud de Ajustes (Anamnesis)</h5>
                                
                                <div class="mb-4">
                                    <label class="small fw-bold text-muted">CARACTERÍSTICAS E INTERESES</label>
                                    <p class="bg-light p-3 rounded">{{ $caso->caracteristicas_intereses ?? 'No registrado' }}</p>
                                </div>

                                <label class="small fw-bold text-muted mb-2">DETALLE DE SOLICITUDES REGISTRADAS:</label>
                                @if($caso->requiere_apoyos)
                                    @if(is_array($caso->ajustes_propuestos) && count($caso->ajustes_propuestos) > 0)
                                        <div class="vstack gap-3">
                                            @foreach($caso->ajustes_propuestos as $index => $solicitud)
                                                <div class="card border-start border-4 border-primary shadow-sm">
                                                    <div class="card-body py-3">
                                                        <h6 class="fw-bold text-primary mb-1">Solicitud #{{ $index + 1 }}</h6>
                                                        <p class="mb-0" style="white-space: pre-wrap;">{{ $solicitud }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning">Se marcó que requiere apoyos, pero no se encontró el detalle.</div>
                                    @endif
                                @else
                                    <div class="alert alert-secondary">
                                        El estudiante indica que <strong>NO</strong> requiere ajustes razonables actualmente.
                                    </div>
                                @endif
                            </div>

                            {{-- GESTIÓN INTERNA (CTP Y DIRECTOR) --}}
                            @if($caso->ajustes_ctp || $caso->motivo_decision)
                                <div class="card border-secondary mb-4">
                                    <div class="card-header bg-secondary text-white fw-bold">Gestión Interna</div>
                                    <div class="card-body bg-light">
                                        
                                        {{-- ⚠️ CORRECCIÓN AQUÍ: MOSTRAR ARRAY DE AJUSTES CTP --}}
                                        @if($caso->ajustes_ctp)
                                            <div class="mb-3">
                                                <h6 class="fw-bold text-dark">Apoyos Metodológicos (CTP)</h6>
                                                <div class="p-3 bg-white border rounded">
                                                    @if(is_array($caso->ajustes_ctp))
                                                        <ul class="mb-0 ps-3">
                                                            @foreach($caso->ajustes_ctp as $ajuste)
                                                                <li class="mb-1">{{ $ajuste }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        {{-- Fallback por si acaso es string --}}
                                                        {{ $caso->ajustes_ctp }}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if($caso->motivo_decision)
                                            <div class="border-top pt-3">
                                                <h6 class="fw-bold text-dark">Resolución Director de Carrera</h6>
                                                <div class="p-3 bg-white border rounded">
                                                    <span class="badge bg-secondary mb-2">{{ $caso->director?->name ?? 'Director' }}</span>
                                                    <p class="mb-0">{{ $caso->motivo_decision }}</p>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="card-footer bg-light p-3 text-end">
                            {{-- Ruta de Volver para Asesoría --}}
                            <a href="{{ route('casos.index') }}" class="btn btn-secondary px-4">
                                <i class="bi bi-arrow-left me-2"></i> Volver al Listado
                            </a>
                            
                            {{-- Botón Editar siempre visible para Asesoría (Superuser) --}}
                            <a href="{{ route('casos.edit', $caso->id) }}" class="btn btn-primary px-4 ms-2">
                                <i class="bi bi-pencil me-2"></i> Editar Caso
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>