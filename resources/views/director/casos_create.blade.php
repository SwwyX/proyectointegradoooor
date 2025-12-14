<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-11 col-lg-12">

                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-body-emphasis">Registro de Nuevo Caso (Dirección)</h2>
                        <p class="text-muted">Complete los antecedentes para ingresar una solicitud directamente desde Dirección.</p>
                    </div>

                    {{-- BLOQUE DE ERRORES --}}
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm mb-4 border-danger">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-octagon-fill fs-3 me-3"></i>
                                <div>
                                    <h5 class="alert-heading fw-bold mb-1">No se pudo guardar el caso</h5>
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

                            {{-- PASO 1: BUSCADOR DE ESTUDIANTE --}}
                            <div class="row justify-content-center mb-4">
                                <div class="col-md-8">
                                    <label for="rut_busqueda" class="form-label fw-bold text-primary">
                                        <i class="bi bi-search me-1"></i> Paso 1: Buscar Estudiante
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <input type="text" class="form-control" id="rut_busqueda" 
                                               placeholder="Ej: 11111111-1" autofocus>
                                        <button class="btn btn-primary" type="button" onclick="buscarEstudiante()">
                                            Buscar
                                        </button>
                                    </div>
                                    <div id="feedback-busqueda" class="form-text mt-2"></div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('director.casos.store') }}" enctype="multipart/form-data" id="form-crear-caso">
                                @csrf
                                {{-- Input oculto para enviar el RUT real al controlador --}}
                                <input type="hidden" name="rut_estudiante" id="rut_final">
                                
                                {{-- SECCIÓN DETALLE (Oculta hasta encontrar estudiante) --}}
                                <div id="seccion_detalle_caso" class="d-none animate__animated animate__fadeIn">
                                    
                                    <hr class="my-4 text-muted">

                                    <h4 class="text-secondary fw-bold mb-4"><i class="bi bi-person-vcard me-2"></i>Datos de Identificación</h4>
                                    
                                    {{-- Vía de Ingreso --}}
                                    <div class="alert alert-light border mb-4 shadow-sm">
                                        <div class="row align-items-center">
                                            <div class="col-md-5">
                                                <label class="form-label fw-bold text-primary mb-0">Estudiante ingresa al Flujo por: <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-7">
                                                <select name="via_ingreso" class="form-select" required>
                                                    <option value="" selected disabled>-- Seleccione una opción --</option>
                                                    <option value="Declaración FUP">Declaración FUP</option>
                                                    <option value="Derivación">Derivación</option>
                                                    <option value="Solicitud Espontánea">Solicitud Espontánea</option>
                                                    <option value="Solicitud Dirección">Solicitud Directa a Dirección</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Datos Personales (Readonly) --}}
                                    <div class="row mb-5">
                                        <div class="col-md-9">
                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <label class="form-label small text-muted fw-bold">Nombre Completo</label>
                                                    <input type="text" class="form-control bg-light" id="nombre_estudiante" name="nombre_estudiante" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small text-muted fw-bold">RUT</label>
                                                    <input type="text" class="form-control bg-light" id="rut_visible" readonly>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label class="form-label small text-muted fw-bold">Correo Institucional</label>
                                                    <input type="email" class="form-control bg-light" id="correo_estudiante" name="correo_estudiante" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted fw-bold">Teléfono</label>
                                                    <input type="text" class="form-control bg-light" id="telefono" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted fw-bold">Jornada</label>
                                                    <input type="text" class="form-control bg-light" id="jornada" readonly>
                                                </div>

                                                <div class="col-md-5">
                                                    <label class="form-label small text-muted fw-bold">Carrera</label>
                                                    <input type="text" class="form-control bg-light" id="carrera" name="carrera" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small text-muted fw-bold">Sede</label>
                                                    <input type="text" class="form-control bg-light" id="sede" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted fw-bold">Área Académica</label>
                                                    <input type="text" class="form-control bg-light" id="area_academica" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-center border-start">
                                            <label class="form-label fw-bold mb-3">Fotografía</label>
                                            <div class="position-relative d-inline-block mb-3">
                                                <img id="preview_foto" src="https://via.placeholder.com/150?text=Sin+Foto" 
                                                     class="rounded-circle shadow-sm border border-3 border-white" 
                                                     style="width: 160px; height: 160px; object-fit: cover;">
                                                
                                                <label for="foto_perfil" class="position-absolute bottom-0 end-0 btn btn-primary rounded-circle shadow" 
                                                       style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                                                       title="Cambiar foto">
                                                    <i class="bi bi-camera-fill"></i>
                                                </label>
                                            </div>
                                            <input type="file" id="foto_perfil" name="foto_perfil" class="d-none" accept="image/*" onchange="previewImage(this)">
                                            <div class="text-muted small">Haga clic para actualizar.</div>
                                        </div>
                                    </div>

                                    {{-- ACORDEÓN DE ENTREVISTA --}}
                                    <div class="accordion shadow-sm mb-4" id="accordionEntrevista">
                                        
                                        {{-- 1. SALUD --}}
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSalud">
                                                    <i class="bi bi-clipboard-pulse me-2"></i> 1. Certificación de Discapacidad y Salud
                                                </button>
                                            </h2>
                                            <div id="collapseSalud" class="accordion-collapse collapse show" data-bs-parent="#accordionEntrevista">
                                                <div class="accordion-body bg-light">
                                                    <label class="fw-bold mb-3 small text-uppercase text-muted">Tipo de Discapacidad (Marque todas las que apliquen):</label>
                                                    <div class="row g-3 mb-4">
                                                        @php
                                                            $discapacidades = ['Visual', 'Auditiva', 'Física', 'Mental/Psíquica', 'Intelectual', 'Trastorno del Espectro Autista (TEA)', 'Visceral', 'Otro'];
                                                        @endphp
                                                        @foreach($discapacidades as $discapacidad)
                                                            <div class="col-md-3 col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="tipo_discapacidad[]" value="{{ $discapacidad }}" id="d_{{ $loop->index }}">
                                                                    <label class="form-check-label" for="d_{{ $loop->index }}">{{ $discapacidad }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Origen de la discapacidad</label>
                                                            <select class="form-select" name="origen_discapacidad">
                                                                <option value="">Seleccione...</option>
                                                                <option>Nacimiento</option>
                                                                <option>Adquirida en Infancia</option>
                                                                <option>Adquirida en Adolescencia</option>
                                                                <option>Adquirida en Adultez</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class="form-label fw-bold">Documentación Presentada</label>
                                                            <div class="d-flex gap-4 mt-2">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="credencial_rnd" id="credencial_rnd">
                                                                    <label class="form-check-label" for="credencial_rnd">RND</label>
                                                                </div>
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="pension_invalidez" id="pension_invalidez">
                                                                    <label class="form-check-label" for="pension_invalidez">Pensión Invalidez</label>
                                                                </div>
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="certificado_medico" id="certificado_medico">
                                                                    <label class="form-check-label" for="certificado_medico">Certificado Médico</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mt-2">
                                                        <div class="col-12">
                                                            <label class="form-label fw-medium">Tratamiento farmacológico (Si/No, ¿Cuáles?):</label>
                                                            <textarea class="form-control" name="tratamiento_farmacologico" rows="2" placeholder="Ej: Sí, utiliza..."></textarea>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-medium">Acompañamiento especialista:</label>
                                                            <input type="text" class="form-control" name="acompanamiento_especialista" placeholder="Ej: Psicólogo, Kinesiólogo...">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-medium">Redes de Apoyo:</label>
                                                            <input type="text" class="form-control" name="redes_apoyo" placeholder="Ej: Municipalidad, SENADIS...">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 2. FAMILIA --}}
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFamilia">
                                                    <i class="bi bi-people me-2"></i> 2. Información Familiar
                                                </button>
                                            </h2>
                                            <div id="collapseFamilia" class="accordion-collapse collapse" data-bs-parent="#accordionEntrevista">
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
                                                                    <th>Nombre</th>
                                                                    <th>Parentesco</th>
                                                                    <th style="width: 80px;">Edad</th>
                                                                    <th>Ocupación</th>
                                                                    <th style="width: 50px;"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><input type="text" class="form-control form-control-sm border-0" name="familia[0][nombre]" placeholder="Nombre completo"></td>
                                                                    <td><input type="text" class="form-control form-control-sm border-0" name="familia[0][parentesco]" placeholder="Ej: Madre"></td>
                                                                    <td><input type="number" class="form-control form-control-sm border-0" name="familia[0][edad]"></td>
                                                                    <td><input type="text" class="form-control form-control-sm border-0" name="familia[0][ocupacion]"></td>
                                                                    <td><button type="button" class="btn btn-link text-danger btn-sm p-0" onclick="eliminarFila(this)"><i class="bi bi-trash"></i></button></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 3. ANTECEDENTES ACADÉMICOS --}}
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAcademico">
                                                    <i class="bi bi-mortarboard me-2"></i> 3. Antecedentes Académicos y Laborales
                                                </button>
                                            </h2>
                                            <div id="collapseAcademico" class="accordion-collapse collapse" data-bs-parent="#accordionEntrevista">
                                                <div class="accordion-body bg-light">
                                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Enseñanza Media</h6>
                                                    <div class="row g-3 mb-4">
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Modalidad</label>
                                                            <select name="enseñanza_media_modalidad" class="form-select">
                                                                <option value="">Seleccione...</option>
                                                                <option>Científico Humanista</option>
                                                                <option>Técnico Profesional</option>
                                                                <option>Educación de Adultos</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-check form-switch mt-4">
                                                                <input class="form-check-input" type="checkbox" name="recibio_apoyos_pie" id="check_pie" onchange="toggleInput('div_pie', this)">
                                                                <label class="form-check-label fw-bold">¿Recibió Apoyos (PIE)?</label>
                                                            </div>
                                                            <div id="div_pie" class="d-none mt-2">
                                                                <input type="text" name="detalle_apoyos_pie" class="form-control" placeholder="Detalle tipo y año...">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-check form-switch mt-4">
                                                                <input class="form-check-input" type="checkbox" name="repitio_curso" id="check_rep" onchange="toggleInput('div_rep', this)">
                                                                <label class="form-check-label fw-bold">¿Repitió algún curso?</label>
                                                            </div>
                                                            <div id="div_rep" class="d-none mt-2">
                                                                <input type="text" name="motivo_repeticion" class="form-control" placeholder="Curso y motivo...">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Educación Superior Previa</h6>
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" type="checkbox" name="estudio_previo_superior" id="check_sup" onchange="toggleInput('div_sup', this)">
                                                        <label class="form-check-label fw-bold">¿Asistió a otra institución de Educación Superior?</label>
                                                    </div>
                                                    <div id="div_sup" class="d-none bg-white p-3 rounded border mb-4">
                                                        <div class="row g-3">
                                                            <div class="col-md-4"><label class="form-label">Institución</label><input type="text" name="nombre_institucion_anterior" class="form-control" placeholder="Nombre"></div>
                                                            <div class="col-md-3"><label class="form-label">Tipo</label><select name="tipo_institucion_anterior" class="form-select"><option>CFT</option><option>IP</option><option>Universidad</option></select></div>
                                                            <div class="col-md-5"><label class="form-label">Carrera</label><input type="text" name="carrera_anterior" class="form-control" placeholder="Nombre carrera"></div>
                                                            <div class="col-12"><label class="form-label">Motivo no término</label><input type="text" name="motivo_no_termino" class="form-control" placeholder="Si aplica..."></div>
                                                        </div>
                                                    </div>

                                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Información Laboral</h6>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="trabaja" id="check_trabajo" onchange="toggleInput('div_trabajo', this)">
                                                        <label class="form-check-label fw-bold">¿Trabaja actualmente?</label>
                                                    </div>
                                                    <div id="div_trabajo" class="d-none mt-3 row g-3 bg-white p-3 rounded border">
                                                        <div class="col-md-6"><label class="form-label">Empresa</label><input type="text" name="empresa" class="form-control" placeholder="Nombre Empresa"></div>
                                                        <div class="col-md-6"><label class="form-label">Cargo</label><input type="text" name="cargo" class="form-control" placeholder="Cargo / Funciones"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 4. SOLICITUDES DE APOYO --}}
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFinal">
                                                    <i class="bi bi-pencil-square me-2"></i> 4. Intereses y Solicitud de Apoyos
                                                </button>
                                            </h2>
                                            <div id="collapseFinal" class="accordion-collapse collapse" data-bs-parent="#accordionEntrevista">
                                                <div class="accordion-body">
                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold">Características e Intereses del Estudiante</label>
                                                        <textarea class="form-control" name="caracteristicas_intereses" rows="3" placeholder="Gustos, intereses, motivadores, habilidades actuales, elementos que generan ansiedad..."></textarea>
                                                    </div>
                                                    
                                                    <div class="card bg-light border-primary mb-4">
                                                        <div class="card-body">
                                                            <h6 class="fw-bold text-primary">Solicitud de Apoyos Requeridos</h6>
                                                            <div class="form-check form-switch my-3">
                                                                <input class="form-check-input fs-5" type="checkbox" name="requiere_apoyos" id="requiere_apoyos" onchange="toggleInput('div_apoyos', this)">
                                                                <label class="form-check-label fw-bold ms-2 pt-1" for="requiere_apoyos">¿El estudiante manifiesta requerir Ajustes Razonables?</label>
                                                            </div>
                                                            
                                                            <div id="div_apoyos" class="d-none">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <label class="fw-bold text-danger mb-0">Detalle de Solicitudes (Anamnesis) *</label>
                                                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="agregarAnamnesis()">
                                                                        <i class="bi bi-plus-circle"></i> Agregar otra solicitud
                                                                    </button>
                                                                </div>
                                                                
                                                                <div id="contenedor-anamnesis">
                                                                    <div class="input-group mb-2">
                                                                        <span class="input-group-text bg-white text-muted">1</span>
                                                                        <textarea class="form-control" name="ajustes_propuestos[]" rows="2" placeholder="Describa la solicitud..."></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div> {{-- Fin Acordeón --}}

                                    <div class="alert alert-warning d-flex align-items-center border-warning shadow-sm" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-warning"></i>
                                        <div>
                                            <strong>Importante:</strong> Antes de guardar, recuerde validar que el estudiante haya firmado el <strong>Consentimiento Informado</strong> o Constancia de Renuncia.
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-5 gap-2">
                                        <a href="{{ route('director.dashboard') }}" class="btn btn-lg btn-outline-secondary">
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-lg btn-primary px-5">
                                            <i class="bi bi-save me-2"></i> Guardar Caso
                                        </button>
                                    </div>

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
        function previewImage(input) { if (input.files && input.files[0]) { var reader = new FileReader(); reader.onload = function(e) { document.getElementById('preview_foto').src = e.target.result; }; reader.readAsDataURL(input.files[0]); } }
        
        function buscarEstudiante() {
            let rut = document.getElementById('rut_busqueda').value.trim();
            let feedback = document.getElementById('feedback-busqueda');
            if(rut.length < 8) { feedback.innerHTML = '<span class="text-danger">Ingrese un RUT válido.</span>'; return; }
            feedback.innerHTML = '<span class="spinner-border spinner-border-sm text-primary"></span> Buscando...';
            
            fetch(`{{ route('api.buscar.estudiante') }}?rut=${rut}`).then(r => r.json()).then(data => {
                if(data.success) {
                    feedback.innerHTML = '<span class="text-success fw-bold">Encontrado</span>';
                    document.getElementById('rut_final').value = data.estudiante.rut;
                    document.getElementById('rut_visible').value = data.estudiante.rut;
                    document.getElementById('nombre_estudiante').value = data.estudiante.nombre_completo;
                    document.getElementById('correo_estudiante').value = data.estudiante.correo;
                    document.getElementById('carrera').value = data.estudiante.carrera;
                    document.getElementById('telefono').value = data.estudiante.telefono || 'Sin datos';
                    document.getElementById('jornada').value = data.estudiante.jornada || 'Diurna';
                    document.getElementById('sede').value = data.estudiante.sede || 'Central';
                    document.getElementById('area_academica').value = data.estudiante.area_academica || 'General';
                    if(data.foto_url) document.getElementById('preview_foto').src = data.foto_url;
                    else document.getElementById('preview_foto').src = 'https://via.placeholder.com/150?text=Foto';
                    document.getElementById('seccion_detalle_caso').classList.remove('d-none');
                    document.getElementById('rut_busqueda').disabled = true;
                } else { feedback.innerHTML = '<span class="text-danger">No encontrado</span>'; }
            });
        }

        let contadorFamilia = 1;
        function agregarFamiliar() {
            let tabla = document.getElementById('tablaFamilia').getElementsByTagName('tbody')[0];
            let fila = tabla.insertRow();
            fila.innerHTML = `<td><input type="text" class="form-control form-control-sm border-0" name="familia[${contadorFamilia}][nombre]" placeholder="Nombre"></td><td><input type="text" class="form-control form-control-sm border-0" name="familia[${contadorFamilia}][parentesco]" placeholder="Ej: Madre"></td><td><input type="number" class="form-control form-control-sm border-0" name="familia[${contadorFamilia}][edad]"></td><td><input type="text" class="form-control form-control-sm border-0" name="familia[${contadorFamilia}][ocupacion]"></td><td><button type="button" class="btn btn-link text-danger btn-sm p-0" onclick="eliminarFila(this)">x</button></td>`;
            contadorFamilia++;
        }
        
        function eliminarFila(btn) { btn.parentNode.parentNode.remove(); }
        
        function toggleInput(divId, checkbox) { 
            let div = document.getElementById(divId); 
            if(checkbox.checked) div.classList.remove('d-none'); 
            else { div.classList.add('d-none'); div.querySelectorAll('input').forEach(i => i.value=''); } 
        }

        // AGREGAR ANAMNESIS DINÁMICA
        let contadorAnamnesis = 2;
        function agregarAnamnesis() {
            let div = document.createElement('div');
            div.className = 'input-group mb-2 animate__animated animate__fadeIn';
            div.innerHTML = `
                <span class="input-group-text bg-white text-muted">${contadorAnamnesis}</span>
                <textarea class="form-control" name="ajustes_propuestos[]" rows="2" placeholder="Describa la solicitud..."></textarea>
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