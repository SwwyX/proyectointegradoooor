<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-11 col-lg-12">

                    {{-- ENCABEZADO --}}
                    <div class="text-center mb-5">
                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-3 mb-2">
                            <i class="bi bi-pencil-fill me-1"></i> Gestión CTP
                        </span>
                        <h2 class="fw-bold text-body-emphasis">Definición de Apoyos y Ajustes</h2>
                        <p class="text-muted">Caso #{{ $caso->id }} - {{ $caso->nombre_estudiante }}</p>
                    </div>

                    {{-- 1. ALERTA DE DEVOLUCIÓN (Si aplica) --}}
                    @if (in_array($caso->estado, ['Reevaluacion', 'Pendiente']) && $caso->motivo_decision)
                        <div class="alert alert-warning border-0 shadow-sm mb-5 d-flex align-items-start" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-3 me-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="alert-heading fs-5 fw-bold">Caso Devuelto por Dirección</h4>
                                <div class="p-3 bg-white bg-opacity-75 rounded text-dark mt-2 shadow-sm">
                                    <strong>Observación del Director:</strong><br>
                                    {{ $caso->motivo_decision }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- BLOQUE DE ERRORES --}}
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm mb-4 border-danger">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-x-circle-fill fs-3 me-3"></i>
                                <div>
                                    <h5 class="alert-heading fw-bold mb-1">Error al guardar</h5>
                                    <ul class="mb-0 ps-3 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ctp.casos.update', $caso) }}">
                        @csrf
                        @method('PUT')

                        <div class="card shadow-sm border-0 rounded-4 mb-5 overflow-hidden">
                            <div class="card-header bg-body-tertiary p-4 border-bottom">
                                <h5 class="fw-bold text-secondary mb-0"><i class="bi bi-person-vcard me-2"></i>Antecedentes del Estudiante</h5>
                            </div>
                            
                            <div class="card-body p-4">
                                
                                {{-- A. DATOS PERSONALES Y FOTO --}}
                                <div class="row g-4 mb-4 border-bottom pb-4">
                                    <div class="col-md-2 text-center">
                                        @if($caso->estudiante && $caso->estudiante->foto_perfil)
                                            <img src="{{ asset('storage/' . $caso->estudiante->foto_perfil) }}" class="rounded-circle shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px;">
                                                <i class="bi bi-person fs-1 text-secondary opacity-50"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="small text-muted fw-bold text-uppercase">Nombre Completo</label>
                                                <div class="fw-medium">{{ $caso->nombre_estudiante }}</div>
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
                                                <div class="badge bg-primary bg-opacity-10 text-primary">{{ $caso->via_ingreso }}</div>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="small text-muted fw-bold text-uppercase">Teléfono</label>
                                                <div>{{ $caso->estudiante->telefono ?? 'No registrado' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- B. ACORDEÓN DE DETALLES PROFUNDOS --}}
                                <div class="accordion accordion-flush border rounded" id="accordionDetalles">
                                    
                                    {{-- 1. SALUD --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne">
                                                <i class="bi bi-heart-pulse me-2"></i> 1. Salud y Discapacidad (Detalle Completo)
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionDetalles">
                                            <div class="accordion-body bg-light">
                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        <label class="small fw-bold text-muted">DIAGNÓSTICOS:</label>
                                                        <div>
                                                            @if(is_array($caso->tipo_discapacidad))
                                                                @foreach($caso->tipo_discapacidad as $tipo) <span class="badge bg-primary me-1">{{ $tipo }}</span> @endforeach
                                                            @else <span class="text-muted fst-italic">Sin información</span> @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="small fw-bold text-muted">ORIGEN:</label>
                                                        <div>{{ $caso->origen_discapacidad ?? 'No especificado' }}</div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class="small fw-bold text-muted">DOCUMENTACIÓN:</label>
                                                        <div class="d-flex gap-3 small">
                                                            <span class="{{ $caso->credencial_rnd ? 'text-success fw-bold' : 'text-muted text-decoration-line-through' }}"><i class="bi bi-check-square"></i> RND</span>
                                                            <span class="{{ $caso->pension_invalidez ? 'text-success fw-bold' : 'text-muted text-decoration-line-through' }}"><i class="bi bi-check-square"></i> Pensión</span>
                                                            <span class="{{ $caso->certificado_medico ? 'text-success fw-bold' : 'text-muted text-decoration-line-through' }}"><i class="bi bi-check-square"></i> Cert. Médico</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12"><hr class="my-1"></div>
                                                    <div class="col-md-4">
                                                        <label class="small fw-bold text-muted">TRATAMIENTO FARMACOLÓGICO:</label>
                                                        <div class="small">{{ $caso->tratamiento_farmacologico ?? 'No indica' }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="small fw-bold text-muted">ESPECIALISTAS:</label>
                                                        <div class="small">{{ $caso->acompanamiento_especialista ?? 'No indica' }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="small fw-bold text-muted">REDES DE APOYO:</label>
                                                        <div class="small">{{ $caso->redes_apoyo ?? 'No indica' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 2. HISTORIAL ACADÉMICO Y LABORAL --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo">
                                                <i class="bi bi-book me-2"></i> 2. Historial Académico y Laboral
                                            </button>
                                        </h2>
                                        <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionDetalles">
                                            <div class="accordion-body bg-light">
                                                <div class="row g-4">
                                                    {{-- Enseñanza Media --}}
                                                    <div class="col-md-6 border-end">
                                                        <h6 class="fw-bold text-dark border-bottom pb-1">Enseñanza Media</h6>
                                                        <ul class="list-unstyled small mb-0">
                                                            <li class="mb-2"><strong>Modalidad:</strong> {{ $caso->enseñanza_media_modalidad ?? 'No informada' }}</li>
                                                            <li class="mb-2">
                                                                <strong>PIE:</strong> 
                                                                @if($caso->recibio_apoyos_pie) <span class="badge bg-success">SÍ</span> ({{ $caso->detalle_apoyos_pie }}) @else <span class="badge bg-secondary">NO</span> @endif
                                                            </li>
                                                            <li class="mb-2">
                                                                <strong>Repitencia:</strong> 
                                                                @if($caso->repitio_curso) <span class="badge bg-danger">SÍ</span> ({{ $caso->motivo_repeticion }}) @else <span class="badge bg-secondary">NO</span> @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    {{-- Educación Superior --}}
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold text-dark border-bottom pb-1">Educación Superior Previa</h6>
                                                        @if($caso->estudio_previo_superior)
                                                            <ul class="list-unstyled small mb-0">
                                                                <li><strong>Institución:</strong> {{ $caso->nombre_institucion_anterior }} ({{ $caso->tipo_institucion_anterior }})</li>
                                                                <li><strong>Carrera:</strong> {{ $caso->carrera_anterior }}</li>
                                                                <li class="text-danger"><strong>Motivo Abandono:</strong> {{ $caso->motivo_no_termino }}</li>
                                                            </ul>
                                                        @else
                                                            <p class="small text-muted">No registra estudios previos.</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-12"><hr class="my-0"></div>
                                                    {{-- Laboral --}}
                                                    <div class="col-12">
                                                        <h6 class="fw-bold text-dark border-bottom pb-1">Situación Laboral</h6>
                                                        @if($caso->trabaja)
                                                            <p class="small mb-0">Trabaja en <strong>{{ $caso->empresa }}</strong> como <strong>{{ $caso->cargo }}</strong>.</p>
                                                        @else
                                                            <p class="small text-muted mb-0">No trabaja actualmente.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 3. FAMILIA --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree">
                                                <i class="bi bi-people me-2"></i> 3. Grupo Familiar
                                            </button>
                                        </h2>
                                        <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionDetalles">
                                            <div class="accordion-body bg-light p-0">
                                                @if(is_array($caso->informacion_familiar) && count($caso->informacion_familiar) > 0)
                                                    <table class="table table-sm table-striped mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="ps-4">Nombre</th>
                                                                <th>Parentesco</th>
                                                                <th>Edad</th>
                                                                <th>Ocupación</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($caso->informacion_familiar as $fam)
                                                                @if(!empty($fam['nombre']))
                                                                    <tr>
                                                                        <td class="ps-4">{{ $fam['nombre'] }}</td>
                                                                        <td>{{ $fam['parentesco'] }}</td>
                                                                        <td>{{ $fam['edad'] }}</td>
                                                                        <td>{{ $fam['ocupacion'] }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <div class="p-3 small text-muted">No se registró información familiar.</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div> {{-- Fin Accordion --}}

                                {{-- C. CARACTERÍSTICAS E INTERESES --}}
                                <div class="mt-4">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">Intereses y Características Personales</h6>
                                    <div class="bg-light p-3 rounded small text-secondary">
                                        {{ $caso->caracteristicas_intereses ?? 'No se registraron características específicas.' }}
                                    </div>
                                </div>

                                {{-- D. SOLICITUDES DEL ESTUDIANTE (IMPORTANTE) --}}
                                <div class="mt-4">
                                    <h5 class="text-danger fw-bold mb-3 border-bottom border-danger pb-2"><i class="bi bi-chat-quote-fill me-2"></i>Solicitudes del Estudiante (Anamnesis)</h5>
                                    <div class="bg-danger-subtle p-4 rounded-3 border border-danger-subtle">
                                        @if(is_array($caso->ajustes_propuestos) && count($caso->ajustes_propuestos) > 0)
                                            <ul class="mb-0 ps-3">
                                                @foreach($caso->ajustes_propuestos as $solicitud)
                                                    @if(!empty($solicitud))
                                                        <li class="mb-2 fs-5 text-dark fw-medium">{{ $solicitud }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted fst-italic mb-0">El estudiante no realizó solicitudes específicas.</p>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card shadow-lg border-0 rounded-4 border-top border-4 border-primary">
                            <div class="card-header bg-white p-4">
                                <h3 class="fs-4 fw-bold mb-0 text-primary">
                                    <i class="bi bi-check2-square me-2"></i>Resolución Técnica
                                </h3>
                                <p class="text-muted mb-0 mt-1 small">
                                    Seleccione las Ayudas Técnicas y Ajustes Razonables pertinentes.
                                </p>
                            </div>
                            
                            <div class="card-body p-4">
                                @php
                                    $seleccionados = is_array($caso->ajustes_ctp) ? $caso->ajustes_ctp : [];
                                    if(old('ajustes_ctp')) { $seleccionados = old('ajustes_ctp'); }
                                @endphp

                                {{-- TABLA 1: AYUDAS TÉCNICAS --}}
                                <div class="mb-5">
                                    <h5 class="fw-bold text-dark bg-body-tertiary p-3 rounded mb-3">Tabla 1: Ayudas Técnicas</h5>
                                    <div class="row g-2 px-2">
                                        @php
                                            $ayudas = [
                                                'Notebook', 'Tablet/Ipad', 'Teléfono inteligente', 'Asistente personal de apoyo', 
                                                'Intérprete de lengua de señas', 'Servicio de transcripción de información', 
                                                'Persona tomadora de apuntes', 'Atril de lectura', 'Audífonos', 'Bastón canadiense', 
                                                'Bastón guiador', 'Andador ortopédico', 'Calculadora parlante', 'Calzado ortopédico', 
                                                'Plantillas ortopédicas', 'Cojín antiescaras', 'Escáner de bolsillo', 'Grabadora de voz', 
                                                'Hardware de trackeo ocular', 'Línea braille', 'Lupa', 'Magnificador digital', 
                                                'Impresora braille', 'Horno Fuser (impresión en relieve)', 'Mouse accesible', 'Silla de ruedas', 
                                                'Software reconocimiento de caracteres (OCR)', 'Software de seguimiento cefálico', 
                                                'Software lector de pantalla NVDA', 'Software lector de pantalla JAWS', 
                                                'Software magnificador de caracteres', 'Software reconocedor de voz', 
                                                'Servicio de TransVoz', 'Teclado accesible'
                                            ];
                                        @endphp
                                        @foreach($ayudas as $index => $ayuda)
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-check">
                                                    {{-- ID único con prefijo 'at_' (Ayuda Técnica) --}}
                                                    <input class="form-check-input border-secondary" type="checkbox" name="ajustes_ctp[]" value="{{ $ayuda }}" id="at_{{ $index }}"
                                                        {{ in_array($ayuda, $seleccionados) ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="at_{{ $index }}">{{ $ayuda }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-12 mt-3">
                                            <input type="text" name="ajustes_ctp[]" class="form-control form-control-sm border-secondary" placeholder="Otras ayudas técnicas (Especifique)...">
                                        </div>
                                    </div>
                                </div>

                                {{-- TABLA 2: AJUSTES RAZONABLES --}}
                                <h5 class="fw-bold text-dark bg-body-tertiary p-3 rounded mb-4">Tabla 2: Ajustes Razonables</h5>

                                <div class="row g-4">
                                    
                                    {{-- A. Presentación --}}
                                    <div class="col-12">
                                        <div class="p-3 border rounded bg-light">
                                            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="bi bi-eye me-2"></i>A. Presentación de la Información</h6>
                                            <div class="row g-2">
                                                @php
                                                    $ajustesA = [
                                                        'Amplificación de la letra (macrotipo) o imagen',
                                                        'Amplitud de la palabra o sonido',
                                                        'Controlar la velocidad de la animación o sonido',
                                                        'Disponer ayudas tecnológicas',
                                                        'Utilización textos escritos/hablados',
                                                        'Uso de lengua de señas',
                                                        'Uso de sistema braille',
                                                        'Uso de gráficos táctiles',
                                                        'Audiolibros',
                                                        'INSTRUCCIONES CONCRETAS y en punteo',
                                                        'Explicar paso a paso (etapas y tiempos)',
                                                        'Evitar el uso de párrafos extensos',
                                                        'Anticipar criterios de evaluación',
                                                        'Retroalimentar desempeño',
                                                        'Subtítulos en videos',
                                                        'Puntero láser en PPT',
                                                        'Modular natural (no exagerado)',
                                                        'Idiomas: trabajo escrito/imágenes',
                                                        'Acompañar el diálogo con ejemplos en pizarra/PPT',
                                                        'Utilizar organizadores gráficos',
                                                        'Uso adecuado de contrastes de color',
                                                        'Material impreso en relieve / 3D / Accesible'
                                                    ];
                                                @endphp
                                                @foreach($ajustesA as $index => $item)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            {{-- ID único con prefijo 'aa_' (Ajuste A) --}}
                                                            <input class="form-check-input border-secondary" type="checkbox" name="ajustes_ctp[]" value="{{ $item }}" id="aa_{{ $index }}"
                                                                {{ in_array($item, $seleccionados) ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="aa_{{ $index }}">{{ $item }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="col-12 mt-2">
                                                    <input type="text" name="ajustes_ctp[]" class="form-control form-control-sm" placeholder="Otros ajustes de presentación...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- B. Entorno --}}
                                    <div class="col-12">
                                        <div class="p-3 border rounded bg-light">
                                            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="bi bi-geo-alt me-2"></i>B. Entorno</h6>
                                            <div class="row g-2">
                                                @php
                                                    $ajustesB = [
                                                        'Ubicación estratégica (evitar distracción/lectura labial)',
                                                        'Ubicación para favorecer participación',
                                                        'Mobiliario adecuado',
                                                        'Ubicación mobiliario (acceso)',
                                                        'Promover cambios de posición',
                                                        'Intencionar equipos de trabajo (anticipando)',
                                                        'Flexibilizar trabajo colaborativo',
                                                        'Tiempo para ajustes de espacio',
                                                        'Normas y roles colaborativos',
                                                        'Hablar de frente (no dar espalda)',
                                                        'Uso dispositivos tecnológicos en clases'
                                                    ];
                                                @endphp
                                                @foreach($ajustesB as $index => $item)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            {{-- ID único con prefijo 'ab_' (Ajuste B) --}}
                                                            <input class="form-check-input border-secondary" type="checkbox" name="ajustes_ctp[]" value="{{ $item }}" id="ab_{{ $index }}"
                                                                {{ in_array($item, $seleccionados) ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="ab_{{ $index }}">{{ $item }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="col-12 mt-2">
                                                    <input type="text" name="ajustes_ctp[]" class="form-control form-control-sm" placeholder="Otros ajustes de entorno...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- C. Forma de Respuesta --}}
                                    <div class="col-12">
                                        <div class="p-3 border rounded bg-light">
                                            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="bi bi-pencil me-2"></i>C. Forma de Respuesta</h6>
                                            <div class="row g-2">
                                                @php
                                                    $ajustesC = [
                                                        'Texto escrito',
                                                        'Sistema Braille',
                                                        'Lengua de señas',
                                                        'Transcripción de respuesta del estudiante',
                                                        'Ilustraciones',
                                                        'Permitir elementos para bajar ansiedad en exposiciones',
                                                        'Información oral',
                                                        'Flexibilizar nivel de intervención en voz alta',
                                                        'Dar mayor tiempo respuesta',
                                                        'Uso de audios',
                                                        'Recursos gráficos/audiovisuales',
                                                        'Opciones evaluación: oral/representación'
                                                    ];
                                                @endphp
                                                @foreach($ajustesC as $index => $item)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            {{-- ID único con prefijo 'ac_' (Ajuste C) --}}
                                                            <input class="form-check-input border-secondary" type="checkbox" name="ajustes_ctp[]" value="{{ $item }}" id="ac_{{ $index }}"
                                                                {{ in_array($item, $seleccionados) ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="ac_{{ $index }}">{{ $item }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="col-12 mt-2">
                                                    <input type="text" name="ajustes_ctp[]" class="form-control form-control-sm" placeholder="Otros ajustes de respuesta...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- D. Tiempo y Horario --}}
                                    <div class="col-12">
                                        <div class="p-3 border rounded bg-light">
                                            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="bi bi-clock me-2"></i>D. Organización del Tiempo y Horario</h6>
                                            <div class="row g-2">
                                                @php
                                                    // ARRAY CORREGIDO: Cada elemento como string separado
                                                    $ajustesD = [
                                                        'Adecuar tiempo actividad/evaluación',
                                                        'Organizar espacios de distención',
                                                        'Considerar tiempo inicio clase (llegada)',
                                                        'Anticipar cambios actividades',
                                                        '25% tiempo extra evaluaciones',
                                                        '50% tiempo extra evaluaciones',
                                                        '75% tiempo extra evaluaciones'
                                                    ];
                                                @endphp
                                                @foreach($ajustesD as $index => $item)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            {{-- ID único con prefijo 'ad_' (Ajuste D) --}}
                                                            <input class="form-check-input border-secondary" type="checkbox" name="ajustes_ctp[]" value="{{ $item }}" id="ad_{{ $index }}"
                                                                {{ in_array($item, $seleccionados) ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="ad_{{ $index }}">{{ $item }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="col-12 mt-2">
                                                    <input type="text" name="ajustes_ctp[]" class="form-control form-control-sm" placeholder="Otros ajustes de tiempo...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- NUEVA SECCIÓN: COMENTARIOS --}}
                                    <div class="col-12 mt-4">
                                        <div class="p-3 border rounded bg-primary-subtle border-primary">
                                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-chat-left-text-fill me-2"></i>Observaciones o Solicitudes Adicionales</h6>
                                            <label class="form-label small text-muted">Ingrese aquí cualquier comentario extra, solicitud a Dirección o ajuste no listado arriba:</label>
                                            <textarea name="ajustes_ctp[]" class="form-control" rows="3" placeholder="Ej: Se sugiere que el estudiante ...."></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                            <div class="card-footer bg-light p-4 d-flex justify-content-between align-items-center">
                                <a href="{{ route('ctp.casos.index') }}" class="btn btn-lg btn-outline-secondary border-0">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                                    <i class="bi bi-send-check-fill me-2"></i> Guardar y Enviar a Director
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>