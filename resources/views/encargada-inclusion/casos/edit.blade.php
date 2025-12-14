<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-11 col-lg-12">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold text-body-emphasis mb-1">Editar Caso #{{ $caso->id }}</h2>
                            <p class="text-muted mb-0">Modifique los antecedentes del estudiante o agregue nuevas solicitudes.</p>
                        </div>
                        <span class="badge bg-warning text-dark border border-warning-subtle px-3 py-2 fs-6">
                            {{ ucfirst($caso->estado) }}
                        </span>
                    </div>

                    {{-- Bloque de Errores --}}
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm mb-4 border-danger">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-octagon-fill fs-3 me-3"></i>
                                <div>
                                    <h5 class="alert-heading fw-bold mb-1">Error al actualizar</h5>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4 p-md-5">

                            {{-- IMPORTANTE: Agregado enctype="multipart/form-data" para permitir subir fotos --}}
                            <form method="POST" action="{{ route('encargada.casos.update', $caso) }}" enctype="multipart/form-data" id="form-editar-caso">
                                @csrf
                                @method('PUT')

                                {{-- CAMPOS OCULTOS PARA MANTENER DATOS VITALES --}}
                                <input type="hidden" name="rut_estudiante" value="{{ $caso->rut_estudiante }}">
                                <input type="hidden" name="nombre_estudiante" value="{{ $caso->nombre_estudiante }}">
                                <input type="hidden" name="correo_estudiante" value="{{ $caso->correo_estudiante }}">
                                <input type="hidden" name="carrera" value="{{ $caso->carrera }}">
                                
                                {{-- SECCIÓN VISIBLE --}}
                                <h4 class="text-secondary fw-bold mb-4"><i class="bi bi-person-vcard me-2"></i>Datos de Identificación</h4>
                                
                                <div class="alert alert-light border mb-4 shadow-sm">
                                    <div class="row align-items-center">
                                        <div class="col-md-5">
                                            <label class="form-label fw-bold text-primary mb-0">Estudiante ingresa por:</label>
                                        </div>
                                        <div class="col-md-7">
                                            <select name="via_ingreso" class="form-select">
                                                <option value="Declaración FUP" {{ $caso->via_ingreso == 'Declaración FUP' ? 'selected' : '' }}>Declaración FUP</option>
                                                <option value="Derivación" {{ $caso->via_ingreso == 'Derivación' ? 'selected' : '' }}>Derivación Profesional</option>
                                                <option value="Solicitud Espontánea" {{ $caso->via_ingreso == 'Solicitud Espontánea' ? 'selected' : '' }}>Solicitud Espontánea</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-9">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label class="form-label small text-muted fw-bold">Nombre Completo</label>
                                                <input type="text" class="form-control bg-light" value="{{ $caso->nombre_estudiante }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small text-muted fw-bold">RUT</label>
                                                <input type="text" class="form-control bg-light" value="{{ $caso->rut_estudiante }}" readonly>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted fw-bold">Correo Institucional</label>
                                                <input type="email" class="form-control bg-light" value="{{ $caso->correo_estudiante }}" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small text-muted fw-bold">Teléfono</label>
                                                <input type="text" class="form-control bg-light" value="{{ $caso->estudiante->telefono ?? '' }}" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small text-muted fw-bold">Jornada</label>
                                                <input type="text" class="form-control bg-light" value="{{ $caso->estudiante->jornada ?? '' }}" readonly>
                                            </div>

                                            <div class="col-md-5">
                                                <label class="form-label small text-muted fw-bold">Carrera</label>
                                                <input type="text" class="form-control bg-light" value="{{ $caso->carrera }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small text-muted fw-bold">Sede</label>
                                                <input type="text" class="form-control bg-light" value="{{ $caso->estudiante->sede ?? '' }}" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small text-muted fw-bold">Área Académica</label>
                                                <input type="text" class="form-control bg-light" value="{{ $caso->estudiante->area_academica ?? '' }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 text-center border-start">
                                        <label class="form-label fw-bold mb-3">Fotografía Actual</label>
                                        <div class="position-relative d-inline-block mb-3">
                                            @if($caso->estudiante && $caso->estudiante->foto_perfil)
                                                <img id="preview_foto" src="{{ asset('storage/' . $caso->estudiante->foto_perfil) }}" 
                                                     class="rounded-circle shadow-sm border border-3 border-white" 
                                                     style="width: 160px; height: 160px; object-fit: cover;">
                                            @else
                                                <img id="preview_foto" src="https://via.placeholder.com/150?text=Sin+Foto" 
                                                     class="rounded-circle shadow-sm border border-3 border-white" 
                                                     style="width: 160px; height: 160px; object-fit: cover;">
                                            @endif
                                            
                                            <label for="foto_perfil" class="position-absolute bottom-0 end-0 btn btn-primary rounded-circle shadow" 
                                                   style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                                                   title="Cambiar foto">
                                                <i class="bi bi-camera-fill"></i>
                                            </label>
                                        </div>
                                        <input type="file" id="foto_perfil" name="foto_perfil" class="d-none" accept="image/*" onchange="previewImage(this)">
                                        <div class="text-muted small">Clic para cambiar foto.</div>
                                    </div>
                                </div>

                                <div class="accordion shadow-sm mb-4" id="accordionEditar">
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSalud">
                                                <i class="bi bi-clipboard-pulse me-2"></i> 1. Certificación de Discapacidad y Salud
                                            </button>
                                        </h2>
                                        <div id="collapseSalud" class="accordion-collapse collapse show" data-bs-parent="#accordionEditar">
                                            <div class="accordion-body bg-light">
                                                
                                                <label class="fw-bold mb-3 small text-uppercase text-muted">Tipo de Discapacidad (Marque todas las que apliquen):</label>
                                                
                                                <div class="row g-3 mb-4">
                                                    @php
                                                        $discapacidades = ['Visual', 'Auditiva', 'Física', 'Mental/Psíquica', 'Intelectual', 'Trastorno del Espectro Autista (TEA)', 'Visceral', 'Otro'];
                                                        // Aseguramos que sea array para in_array
                                                        $seleccionadas = is_array($caso->tipo_discapacidad) ? $caso->tipo_discapacidad : [];
                                                    @endphp
                                                    @foreach($discapacidades as $discapacidad)
                                                        <div class="col-md-3 col-sm-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="tipo_discapacidad[]" value="{{ $discapacidad }}" id="d_{{ $loop->index }}"
                                                                    {{ in_array($discapacidad, $seleccionadas) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="d_{{ $loop->index }}">
                                                                    {{ $discapacidad }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold">Origen de la discapacidad</label>
                                                        <select class="form-select" name="origen_discapacidad">
                                                            <option value="">Seleccione...</option>
                                                            <option {{ $caso->origen_discapacidad == 'Nacimiento' ? 'selected' : '' }}>Nacimiento</option>
                                                            <option {{ $caso->origen_discapacidad == 'Adquirida en Infancia' ? 'selected' : '' }}>Adquirida en Infancia</option>
                                                            <option {{ $caso->origen_discapacidad == 'Adquirida en Adolescencia' ? 'selected' : '' }}>Adquirida en Adolescencia</option>
                                                            <option {{ $caso->origen_discapacidad == 'Adquirida en Adultez' ? 'selected' : '' }}>Adquirida en Adultez</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class="form-label fw-bold">Documentación Presentada</label>
                                                        <div class="d-flex gap-4 mt-2">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="credencial_rnd" id="credencial_rnd" {{ $caso->credencial_rnd ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="credencial_rnd">RND</label>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="pension_invalidez" id="pension_invalidez" {{ $caso->pension_invalidez ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="pension_invalidez">Pensión Invalidez</label>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="certificado_medico" id="certificado_medico" {{ $caso->certificado_medico ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="certificado_medico">Certificado Médico</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mt-2">
                                                    <div class="col-12">
                                                        <label class="form-label fw-medium">Tratamiento farmacológico (Si/No, ¿Cuáles?):</label>
                                                        <textarea class="form-control" name="tratamiento_farmacologico" rows="2">{{ $caso->tratamiento_farmacologico }}</textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium">Acompañamiento especialista:</label>
                                                        <input type="text" class="form-control" name="acompanamiento_especialista" value="{{ $caso->acompanamiento_especialista }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium">Redes de Apoyo:</label>
                                                        <input type="text" class="form-control" name="redes_apoyo" value="{{ $caso->redes_apoyo }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFamilia">
                                                <i class="bi bi-people me-2"></i> 2. Información Familiar
                                            </button>
                                        </h2>
                                        <div id="collapseFamilia" class="accordion-collapse collapse" data-bs-parent="#accordionEditar">
                                            <div class="accordion-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <p class="small text-muted mb-0">Personas con quien vive el estudiante.</p>
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="agregarFamiliar()">
                                                        <i class="bi bi-plus-lg"></i> Agregar Familiar
                                                    </button>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover" id="tablaFamilia">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Nombre</th><th>Parentesco</th><th style="width: 80px;">Edad</th><th>Ocupación</th><th style="width: 50px;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(is_array($caso->informacion_familiar))
                                                                @foreach($caso->informacion_familiar as $index => $fam)
                                                                    <tr>
                                                                        <td><input type="text" class="form-control form-control-sm border-0" name="familia[{{ $index }}][nombre]" value="{{ $fam['nombre'] ?? '' }}"></td>
                                                                        <td><input type="text" class="form-control form-control-sm border-0" name="familia[{{ $index }}][parentesco]" value="{{ $fam['parentesco'] ?? '' }}"></td>
                                                                        <td><input type="number" class="form-control form-control-sm border-0" name="familia[{{ $index }}][edad]" value="{{ $fam['edad'] ?? '' }}"></td>
                                                                        <td><input type="text" class="form-control form-control-sm border-0" name="familia[{{ $index }}][ocupacion]" value="{{ $fam['ocupacion'] ?? '' }}"></td>
                                                                        <td><button type="button" class="btn btn-link text-danger btn-sm p-0" onclick="eliminarFila(this)"><i class="bi bi-trash"></i></button></td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                {{-- Fila vacía si no hay datos --}}
                                                                <tr>
                                                                    <td><input type="text" class="form-control form-control-sm border-0" name="familia[0][nombre]"></td>
                                                                    <td><input type="text" class="form-control form-control-sm border-0" name="familia[0][parentesco]"></td>
                                                                    <td><input type="number" class="form-control form-control-sm border-0" name="familia[0][edad]"></td>
                                                                    <td><input type="text" class="form-control form-control-sm border-0" name="familia[0][ocupacion]"></td>
                                                                    <td><button type="button" class="btn btn-link text-danger btn-sm p-0" onclick="eliminarFila(this)"><i class="bi bi-trash"></i></button></td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAcademico">
                                                <i class="bi bi-mortarboard me-2"></i> 3. Antecedentes Académicos y Laborales
                                            </button>
                                        </h2>
                                        <div id="collapseAcademico" class="accordion-collapse collapse" data-bs-parent="#accordionEditar">
                                            <div class="accordion-body bg-light">
                                                
                                                <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Enseñanza Media</h6>
                                                <div class="row g-3 mb-4">
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold">Modalidad</label>
                                                        <select name="enseñanza_media_modalidad" class="form-select">
                                                            <option value="">Seleccione...</option>
                                                            <option {{ $caso->enseñanza_media_modalidad == 'Científico Humanista' ? 'selected' : '' }}>Científico Humanista</option>
                                                            <option {{ $caso->enseñanza_media_modalidad == 'Técnico Profesional' ? 'selected' : '' }}>Técnico Profesional</option>
                                                            <option {{ $caso->enseñanza_media_modalidad == 'Educación de Adultos' ? 'selected' : '' }}>Educación de Adultos</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-check form-switch mt-4">
                                                            <input class="form-check-input" type="checkbox" name="recibio_apoyos_pie" id="check_pie" {{ $caso->recibio_apoyos_pie ? 'checked' : '' }} onchange="toggleInput('div_pie', this)">
                                                            <label class="form-check-label fw-bold">¿Recibió Apoyos (PIE)?</label>
                                                        </div>
                                                        <div id="div_pie" class="{{ $caso->recibio_apoyos_pie ? '' : 'd-none' }} mt-2">
                                                            <input type="text" name="detalle_apoyos_pie" class="form-control" value="{{ $caso->detalle_apoyos_pie }}" placeholder="Detalle tipo y año...">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-check form-switch mt-4">
                                                            <input class="form-check-input" type="checkbox" name="repitio_curso" id="check_rep" {{ $caso->repitio_curso ? 'checked' : '' }} onchange="toggleInput('div_rep', this)">
                                                            <label class="form-check-label fw-bold">¿Repitió algún curso?</label>
                                                        </div>
                                                        <div id="div_rep" class="{{ $caso->repitio_curso ? '' : 'd-none' }} mt-2">
                                                            <input type="text" name="motivo_repeticion" class="form-control" value="{{ $caso->motivo_repeticion }}" placeholder="Curso y motivo...">
                                                        </div>
                                                    </div>
                                                </div>

                                                <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Educación Superior Previa</h6>
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="estudio_previo_superior" id="check_sup" {{ $caso->estudio_previo_superior ? 'checked' : '' }} onchange="toggleInput('div_sup', this)">
                                                    <label class="form-check-label fw-bold">¿Asistió a otra institución de Educación Superior?</label>
                                                </div>
                                                <div id="div_sup" class="{{ $caso->estudio_previo_superior ? '' : 'd-none' }} bg-white p-3 rounded border mb-4">
                                                    <div class="row g-3">
                                                        <div class="col-md-4"><label class="form-label">Institución</label><input type="text" name="nombre_institucion_anterior" class="form-control" value="{{ $caso->nombre_institucion_anterior }}"></div>
                                                        <div class="col-md-3"><label class="form-label">Tipo</label><select name="tipo_institucion_anterior" class="form-select"><option>CFT</option><option>IP</option><option>Universidad</option></select></div>
                                                        <div class="col-md-5"><label class="form-label">Carrera</label><input type="text" name="carrera_anterior" class="form-control" value="{{ $caso->carrera_anterior }}"></div>
                                                        <div class="col-12"><label class="form-label">Motivo no término</label><input type="text" name="motivo_no_termino" class="form-control" value="{{ $caso->motivo_no_termino }}"></div>
                                                    </div>
                                                </div>

                                                <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Información Laboral</h6>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="trabaja" id="check_trabajo" {{ $caso->trabaja ? 'checked' : '' }} onchange="toggleInput('div_trabajo', this)">
                                                    <label class="form-check-label fw-bold">¿Trabaja actualmente?</label>
                                                </div>
                                                <div id="div_trabajo" class="{{ $caso->trabaja ? '' : 'd-none' }} mt-3 row g-3 bg-white p-3 rounded border">
                                                    <div class="col-md-6"><label class="form-label">Empresa</label><input type="text" name="empresa" class="form-control" value="{{ $caso->empresa }}"></div>
                                                    <div class="col-md-6"><label class="form-label">Cargo</label><input type="text" name="cargo" class="form-control" value="{{ $caso->cargo }}"></div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFinal">
                                                <i class="bi bi-pencil-square me-2"></i> 4. Intereses y Solicitud de Apoyos
                                            </button>
                                        </h2>
                                        <div id="collapseFinal" class="accordion-collapse collapse show" data-bs-parent="#accordionEditar">
                                            <div class="accordion-body">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold">Características e Intereses del Estudiante</label>
                                                    <textarea class="form-control" name="caracteristicas_intereses" rows="3">{{ $caso->caracteristicas_intereses }}</textarea>
                                                </div>
                                                
                                                <div class="card bg-light border-primary mb-4">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h6 class="fw-bold text-primary mb-0">Solicitud de Apoyos Requeridos</h6>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input fs-5" type="checkbox" name="requiere_apoyos" id="requiere_apoyos" {{ $caso->requiere_apoyos ? 'checked' : '' }} onchange="toggleInput('div_apoyos', this)">
                                                                <label class="form-check-label fw-bold ms-2 pt-1" for="requiere_apoyos">¿Requiere Ajustes?</label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div id="div_apoyos" class="{{ $caso->requiere_apoyos ? '' : 'd-none' }}">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <label class="fw-bold text-danger mb-0">Detalle de Solicitudes (Anamnesis) *</label>
                                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="agregarAnamnesis()">
                                                                    <i class="bi bi-plus-circle"></i> Agregar otra solicitud
                                                                </button>
                                                            </div>
                                                            
                                                            <div id="contenedor-anamnesis">
                                                                @if(is_array($caso->ajustes_propuestos) && count($caso->ajustes_propuestos) > 0)
                                                                    @foreach($caso->ajustes_propuestos as $index => $ajuste)
                                                                        <div class="input-group mb-2">
                                                                            <span class="input-group-text bg-white text-muted">{{ $index + 1 }}</span>
                                                                            {{-- IMPORTANTE: name="ajustes_propuestos[]" para que funcione el array --}}
                                                                            <textarea class="form-control" name="ajustes_propuestos[]" rows="2">{{ $ajuste }}</textarea>
                                                                            @if($index > 0)
                                                                                <button type="button" class="btn btn-outline-danger" onclick="eliminarAnamnesis(this)"><i class="bi bi-trash"></i></button>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    {{-- Campo por defecto si está vacío --}}
                                                                    <div class="input-group mb-2">
                                                                        <span class="input-group-text bg-white text-muted">1</span>
                                                                        <textarea class="form-control" name="ajustes_propuestos[]" rows="2" placeholder="Describa la solicitud..."></textarea>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div> <div class="d-flex justify-content-end mt-5 gap-2">
                                    <a href="{{ route('encargada.casos.index') }}" class="btn btn-lg btn-outline-secondary">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-lg btn-primary px-5">
                                        <i class="bi bi-save me-2"></i> Actualizar Caso
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) { document.getElementById('preview_foto').src = e.target.result; }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Inicializar contadores basados en datos existentes
        let contadorFamilia = {{ is_array($caso->informacion_familiar) ? count($caso->informacion_familiar) : 1 }};
        let contadorAnamnesis = {{ is_array($caso->ajustes_propuestos) ? count($caso->ajustes_propuestos) + 1 : 2 }};

        function agregarFamiliar() {
            let tabla = document.getElementById('tablaFamilia').getElementsByTagName('tbody')[0];
            let fila = tabla.insertRow();
            fila.innerHTML = `
                <td><input type="text" class="form-control form-control-sm border-0" name="familia[${contadorFamilia}][nombre]" placeholder="Nombre"></td>
                <td><input type="text" class="form-control form-control-sm border-0" name="familia[${contadorFamilia}][parentesco]" placeholder="Parentesco"></td>
                <td><input type="number" class="form-control form-control-sm border-0" name="familia[${contadorFamilia}][edad]"></td>
                <td><input type="text" class="form-control form-control-sm border-0" name="familia[${contadorFamilia}][ocupacion]"></td>
                <td><button type="button" class="btn btn-link text-danger btn-sm p-0" onclick="eliminarFila(this)">x</button></td>
            `;
            contadorFamilia++;
        }

        function eliminarFila(btn) {
            let row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }

        function toggleInput(divId, checkbox) { 
            let div = document.getElementById(divId); 
            if(checkbox.checked) div.classList.remove('d-none'); 
            else div.classList.add('d-none'); 
        }

        function agregarAnamnesis() {
            let div = document.createElement('div');
            div.className = 'input-group mb-2 animate__animated animate__fadeIn';
            div.innerHTML = `
                <span class="input-group-text bg-white text-muted">${contadorAnamnesis}</span>
                <textarea class="form-control" name="ajustes_propuestos[]" rows="2" placeholder="Nueva solicitud..."></textarea>
                <button type="button" class="btn btn-outline-danger" onclick="eliminarAnamnesis(this)"><i class="bi bi-trash"></i></button>
            `;
            document.getElementById('contenedor-anamnesis').appendChild(div);
            contadorAnamnesis++;
        }

        function eliminarAnamnesis(btn) {
            btn.parentElement.remove();
        }
    </script>
    @endpush
</x-app-layout>