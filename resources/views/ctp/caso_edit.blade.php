<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-11 col-lg-12">

                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-body-emphasis">Definición de Apoyos y Ajustes (Caso #{{ $caso->id }})</h2>
                        <p class="text-muted">Revise los antecedentes y seleccione los ajustes metodológicos necesarios.</p>
                    </div>

                    {{-- 1. ALERTA DE DEVOLUCIÓN (Si aplica) --}}
                    @if (in_array($caso->estado, ['Reevaluacion', 'Pendiente']) && $caso->motivo_decision)
                        <div class="alert alert-warning border-0 shadow-sm mb-5" role="alert">
                            <div class="d-flex">
                                <i class="bi bi-arrow-return-left fs-1 me-3"></i>
                                <div>
                                    <h4 class="alert-heading fs-5 fw-bold">Caso Devuelto por Director de Carrera</h4>
                                    <p class="mb-1"><strong>Observación:</strong></p>
                                    <div class="p-3 bg-white bg-opacity-75 rounded text-dark shadow-sm" style="white-space: pre-wrap;">{{ $caso->motivo_decision }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- BLOQUE DE ERRORES --}}
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm mb-4 border-danger">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-octagon-fill fs-3 me-3"></i>
                                <div>
                                    <h5 class="alert-heading fw-bold mb-1">No se pudo guardar la definición</h5>
                                    <ul class="mb-0 ps-3">
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

                        <!-- ========================================== -->
                        <!-- PARTE 1: ANTECEDENTES COMPLETOS (CONTEXTO) -->
                        <!-- ========================================== -->
                        <div class="card shadow-lg border-0 rounded-4 mb-5">
                            <div class="card-header bg-body-tertiary p-4">
                                <h3 class="fs-5 fw-bold mb-0 text-secondary"><i class="bi bi-file-person me-2"></i>Antecedentes Completos del Caso</h3>
                            </div>
                            
                            <div class="card-body p-4 p-md-5">
                                
                                {{-- A. IDENTIFICACIÓN --}}
                                <div class="row g-4 mb-5">
                                    <div class="col-md-9">
                                        <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Identificación</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="small text-muted fw-bold">NOMBRE</label>
                                                <div class="fs-5">{{ $caso->nombre_estudiante }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted fw-bold">RUT</label>
                                                <div class="fs-5">{{ $caso->rut_estudiante }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted fw-bold">VÍA INGRESO</label>
                                                <div>{{ $caso->via_ingreso }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small text-muted fw-bold">CARRERA</label>
                                                <div>{{ $caso->carrera }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small text-muted fw-bold">CORREO</label>
                                                <div>{{ $caso->correo_estudiante }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center border-start">
                                        <label class="form-label fw-bold mb-3">Fotografía</label>
                                        @if($caso->estudiante && $caso->estudiante->foto_perfil)
                                            <img src="{{ asset('storage/' . $caso->estudiante->foto_perfil) }}" class="rounded-circle shadow-sm border" style="width: 120px; height: 120px; object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/150?text=Foto" class="rounded-circle shadow-sm border" style="width: 120px; height: 120px; object-fit: cover;">
                                        @endif
                                    </div>
                                </div>

                                {{-- B. SOLICITUDES DE ANAMNESIS (SEPARADO Y DESTACADO) --}}
                                <div class="mb-5">
                                    <h5 class="text-danger fw-bold mb-3 border-bottom pb-2">Solicitudes del Estudiante (Anamnesis)</h5>
                                    <div class="bg-danger-subtle p-4 rounded-3 border border-danger-subtle">
                                        @if(is_array($caso->ajustes_propuestos) && count($caso->ajustes_propuestos) > 0)
                                            <ul class="mb-0 ps-3">
                                                @foreach($caso->ajustes_propuestos as $solicitud)
                                                    @if(!empty($solicitud))
                                                        <li class="mb-2 fs-5 text-dark">{{ $solicitud }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted fst-italic mb-0">El estudiante no registró solicitudes específicas o indicó que no requiere ajustes.</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- C. DETALLES TÉCNICOS (ACORDEÓN) --}}
                                <div class="accordion shadow-sm" id="accordionDetalles">
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed fw-bold text-secondary bg-light rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInfo">
                                                <i class="bi bi-eye me-2"></i> Ver Discapacidad, Familia e Historial Completo
                                            </button>
                                        </h2>
                                        <div id="collapseInfo" class="accordion-collapse collapse" data-bs-parent="#accordionDetalles">
                                            <div class="accordion-body bg-light rounded-bottom-3">
                                                
                                                {{-- 1. SALUD --}}
                                                <div class="mb-4">
                                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">1. Salud y Discapacidad</h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <label class="small fw-bold text-muted">TIPOS:</label>
                                                            <div>
                                                                @if(is_array($caso->tipo_discapacidad))
                                                                    @foreach($caso->tipo_discapacidad as $t) <span class="badge bg-primary me-1">{{ $t }}</span> @endforeach
                                                                @else - @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4"><label class="small fw-bold text-muted">ORIGEN:</label> <div>{{ $caso->origen_discapacidad }}</div></div>
                                                        <div class="col-md-8">
                                                            <label class="small fw-bold text-muted">DOCUMENTACIÓN:</label>
                                                            <div class="d-flex gap-3">
                                                                @if($caso->credencial_rnd) <span class="text-success fw-bold"><i class="bi bi-check"></i> RND</span> @endif
                                                                @if($caso->pension_invalidez) <span class="text-success fw-bold"><i class="bi bi-check"></i> Pensión</span> @endif
                                                                @if($caso->certificado_medico) <span class="text-success fw-bold"><i class="bi bi-check"></i> Cert. Médico</span> @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12"><label class="small fw-bold text-muted">TRATAMIENTO:</label> <p class="mb-0 small">{{ $caso->tratamiento_farmacologico }}</p></div>
                                                    </div>
                                                </div>

                                                {{-- 2. FAMILIA --}}
                                                <div class="mb-4">
                                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">2. Grupo Familiar</h6>
                                                    @if(is_array($caso->informacion_familiar))
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered mb-0 bg-white">
                                                                <thead class="table-light"><tr><th>Nombre</th><th>Parentesco</th><th>Edad</th><th>Ocupación</th></tr></thead>
                                                                <tbody>
                                                                    @foreach($caso->informacion_familiar as $fam)
                                                                        @if(!empty($fam['nombre']))
                                                                            <tr><td>{{ $fam['nombre'] }}</td><td>{{ $fam['parentesco'] }}</td><td>{{ $fam['edad'] }}</td><td>{{ $fam['ocupacion'] }}</td></tr>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else <p class="text-muted small">Sin información.</p> @endif
                                                </div>

                                                {{-- 3. HISTORIAL --}}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">3. Historial Académico</h6>
                                                        <ul class="small list-unstyled">
                                                            <li><strong>Ed. Media:</strong> {{ $caso->enseñanza_media_modalidad }}</li>
                                                            <li><strong>PIE:</strong> {{ $caso->recibio_apoyos_pie ? 'Sí' : 'No' }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">4. Intereses</h6>
                                                        <p class="small mb-0">{{ $caso->caracteristicas_intereses }}</p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- ========================================== -->
                        <!-- PARTE 2: SELECCIÓN DE AJUSTES (CATÁLOGO) -->
                        <!-- ========================================== -->
                        <div class="card shadow-lg border-0 rounded-4 border-top border-4 border-primary">
                            <div class="card-header bg-white p-4">
                                <h3 class="fs-4 fw-bold mb-0 text-primary">
                                    <i class="bi bi-check2-square me-2"></i>Selección de Recursos y Ajustes
                                </h3>
                                <p class="text-muted mb-0 mt-1 small">
                                    Seleccione las Ayudas Técnicas y Ajustes Razonables pertinentes para el caso, basándose en las solicitudes del estudiante.
                                </p>
                            </div>
                            
                            <div class="card-body p-4">
                                @php
                                    // Recuperamos los ajustes ya guardados para marcarlos automáticamente al editar
                                    $seleccionados = is_array($caso->ajustes_ctp) ? $caso->ajustes_ctp : [];
                                    // Si hay error de validación, usar old()
                                    if(old('ajustes_ctp')) {
                                        $seleccionados = old('ajustes_ctp');
                                    }
                                @endphp

                                {{-- TABLA 1: AYUDAS TÉCNICAS --}}
                                <div class="mb-5">
                                    <h5 class="fw-bold text-dark bg-body-tertiary p-3 rounded mb-3">Tabla 1: Ayudas Técnicas y Servicios de Apoyo</h5>
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
                                        @foreach($ayudas as $ayuda)
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-check">
                                                    <input class="form-check-input border-secondary" type="checkbox" name="ajustes_ctp[]" value="{{ $ayuda }}" id="at_{{ $loop->index }}"
                                                        {{ in_array($ayuda, $seleccionados) ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="at_{{ $loop->index }}">{{ $ayuda }}</label>
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
                                                        'Amplificación de la letra (macrotipo) o imagen', 'Amplitud de la palabra o sonido', 
                                                        'Controlar la velocidad de la animación o sonido', 'Disponer ayudas tecnológicas', 
                                                        'Utilización textos escritos/hablados', 'Uso de lengua de señas', 'Uso de sistema braille', 
                                                        'Uso de gráficos táctiles', 'Audiolibros', 'INSTRUCCIONES CONCRETAS y en punteo', 
                                                        'Explicar paso a paso (etapas y tiempos)', 'Evitar el uso de párrafos extensos', 
                                                        'Anticipar criterios de evaluación', 'Retroalimentar desempeño', 'Subtítulos en videos', 
                                                        'Puntero láser en PPT', 'Modular natural (no exagerado)', 'Idiomas: trabajo escrito/imágenes', 
                                                        'Acompañar el diálogo con ejemplos en pizarra/PPT', 'Utilizar organizadores gráficos', 
                                                        'Uso adecuado de contrastes de color', 'Material impreso en relieve / 3D / Accesible'
                                                    ];
                                                @endphp
                                                @foreach($ajustesA as $item)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input border-secondary" type="checkbox" name="ajustes_ctp[]" value="{{ $item }}" id="aa_{{ $loop->index }}"
                                                                {{ in_array($item, $seleccionados) ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="aa_{{ $loop->index }}">{{ $item }}</label>
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
                                                        'Ubicación estratégica (evitar distracción/lectura labial)', 'Ubicación para favorecer participación', 
                                                        'Mobiliario adecuado', 'Ubicación mobiliario (acceso)', 'Promover cambios de posición', 
                                                        'Intencionar equipos de trabajo (anticipando)', 'Flexibilizar trabajo colaborativo', 
                                                        'Tiempo para ajustes de espacio', 'Normas y roles colaborativos', 
                                                        'Hablar de frente (no dar espalda)', 'Uso dispositivos tecnológicos en clases'
                                                    ];
                                                @endphp
                                                @foreach($ajustesB as $item)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input border-secondary" type="checkbox" name="ajustes_ctp[]" value="{{ $item }}" id="ab_{{ $loop->index }}"
                                                                {{ in_array($item, $seleccionados) ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="ab_{{ $loop->index }}">{{ $item }}</label>
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
                                                        'Texto escrito', 'Sistema Braille', 'Lengua de señas', 
                                                        'Transcripción de respuesta del estudiante', 'Ilustraciones', 
                                                        'Permitir elementos para bajar ansiedad en exposiciones', 
                                                        'Información oral', 
                                                        'Flexibilizar nivel de intervención en voz alta', 
                                                        'Dar mayor tiempo respuesta', 
                                                        'Uso de audios', 'Recursos gráficos/audiovisuales', 'Opciones evaluación: oral/representación'
                                                    ];
                                                @endphp
                                                @foreach($ajustesC as $item)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input border-secondary" type="checkbox" name="ajustes_ctp[]" value="{{ $item }}" id="ac_{{ $loop->index }}"
                                                                {{ in_array($item, $seleccionados) ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="ac_{{ $loop->index }}">{{ $item }}</label>
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
                                                    $ajustesD = [
                                                        'Adecuar tiempo actividad/evaluación', 'Organizar espacios de distención', 
                                                        'Considerar tiempo inicio clase (llegada)', 'Anticipar cambios actividades', 
                                                        '25% tiempo extra evaluaciones', '50% tiempo extra evaluaciones', '75% tiempo extra evaluaciones'
                                                    ];
                                                @endphp
                                                @foreach($ajustesD as $item)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input border-secondary" type="checkbox" name="ajustes_ctp[]" value="{{ $item }}" id="ad_{{ $loop->index }}"
                                                                {{ in_array($item, $seleccionados) ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="ad_{{ $loop->index }}">{{ $item }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="col-12 mt-2">
                                                    <input type="text" name="ajustes_ctp[]" class="form-control form-control-sm" placeholder="Otros ajustes de tiempo...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- NUEVA SECCIÓN: COMENTARIOS Y SOLICITUDES EXTRA --}}
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