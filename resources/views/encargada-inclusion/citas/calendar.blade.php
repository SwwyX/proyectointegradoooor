<x-app-layout>
    {{-- Scripts y Estilos --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/locales/es.global.min.js'></script>

    <style>
        /* FONDO ESTILIZADO PARA LA VISTA */
        .page-bg {
            background: linear-gradient(135deg, #510a8bff 0%, #0dbfccff 100%);
            min-height: 85vh;
            border-radius: 20px;
        }

        /* ESTILOS DEL CALENDARIO */
        .fc { 
            background: white; 
            padding: 20px; 
            border-radius: 16px; 
            border: none; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            font-family: 'Figtree', sans-serif;
        }
        .fc-toolbar-title { 
            font-size: 1.5rem !important; 
            color: #2d3748; 
            font-weight: 800 !important; 
            letter-spacing: -0.5px;
        }
        .fc-col-header-cell {
            background-color: #f8f9fa;
            padding: 10px 0;
            border-bottom: 2px solid #e9ecef;
        }
        .fc-col-header-cell-cushion {
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 700;
        }
        .fc-timegrid-slot-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #adb5bd;
        }
        .fc-event { 
            cursor: pointer; 
            border: none !important; 
            border-radius: 6px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .fc-event:hover {
            transform: scale(1.02);
        }
        .fc-button-primary { 
            background-color: #4f46e5 !important; 
            border: none !important; 
            border-radius: 8px !important;
            padding: 8px 16px !important;
            font-weight: 600 !important;
            text-transform: capitalize;
        }
        .fc-button-active { 
            background-color: #4338ca !important; 
        }
        
        /* Modal Styles */
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px dashed #f0f0f0; padding-bottom: 8px; }
        .detail-label { font-weight: 700; color: #64748b; font-size: 0.9rem; }
        .detail-value { font-weight: 600; color: #334155; text-align: right; font-size: 0.95rem; }
    </style>

    <div class="container px-4 py-5">
        
        {{-- Header con fondo degradado suave --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 bg-white p-4 rounded-4 shadow-sm border">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold text-dark mb-1">
                    <span class="text-primary"><i class="bi bi-calendar-range me-2"></i>Agenda</span> Visual
                </h2>
                <p class="text-muted mb-0">Gestión de bloques horarios y citas.</p>
            </div>
            <a href="{{ route('encargada.citas.listado') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold hover-lift">
                <i class="bi bi-list-ul me-2"></i> Ver Listado Detallado
            </a>
        </div>

        {{-- Contenedor del Calendario --}}
        <div class="page-bg p-4 shadow-sm">
            <div id="calendarioAdmin"></div>
        </div>

    </div>

    {{-- MODAL DETALLE DE CITA (IGUAL QUE ANTES) --}}
    <div class="modal fade" id="modalDetalleCita" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold"> <i class="bi bi-info-circle me-2"></i>Detalle de la Cita</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    
                    {{-- Información del Estudiante --}}
                    <div class="mb-4">
                        <h6 class="text-uppercase text-primary fw-bold small mb-3 border-bottom pb-2">Información del Estudiante</h6>
                        <div class="detail-row">
                            <span class="detail-label">Nombre:</span>
                            <span class="detail-value text-end" id="modalNombre">Cargando...</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">RUT:</span>
                            <span class="detail-value" id="modalRut">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Carrera:</span>
                            <span class="detail-value text-truncate" style="max-width: 200px;" id="modalCarrera">-</span>
                        </div>
                    </div>

                    {{-- Información de la Cita --}}
                    <div class="mb-4">
                        <h6 class="text-uppercase text-primary fw-bold small mb-3 border-bottom pb-2">Datos de la Reserva</h6>
                        <div class="detail-row">
                            <span class="detail-label">Fecha y Hora:</span>
                            <span class="detail-value" id="modalFecha">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Motivo:</span>
                            <span class="detail-value text-primary fw-bold" id="modalMotivo">-</span>
                        </div>
                        <div class="detail-row border-0">
                            <span class="detail-label">Estado:</span>
                            <span class="badge rounded-pill fs-6" id="modalEstadoBadge">Pendiente</span>
                        </div>
                    </div>

                    <div class="alert alert-light border small text-muted mb-3" id="divComentarioExistente" style="display: none;">
                        <strong>Nota anterior:</strong> <span id="txtComentarioExistente" class="fst-italic"></span>
                    </div>

                    {{-- Formulario de Acciones --}}
                    <form id="formAcciones" method="POST">
                        @csrf @method('PUT')
                        
                        {{-- Área de confirmación --}}
                        <div id="accionesPendientes" class="d-none">
                            <button type="submit" name="estado" value="Confirmada" class="btn btn-success w-100 fw-bold py-2 rounded-pill mb-3 shadow-sm">
                                <i class="bi bi-check-circle-fill me-2"></i> Confirmar Cita
                            </button>
                            <div class="d-flex align-items-center mb-3">
                                <hr class="flex-grow-1">
                                <span class="px-2 text-muted small">O rechazar</span>
                                <hr class="flex-grow-1">
                            </div>
                        </div>

                        {{-- Área de Cancelación --}}
                        <div class="mt-2">
                            <label class="form-label fw-bold small text-danger">Acciones Críticas (Requerido justificación)</label>
                            
                            <textarea name="comentario_encargada" id="txtComentario" class="form-control mb-3 bg-light" rows="2" placeholder="Escribe el motivo aquí..."></textarea>
                            <div id="errorComentario" class="text-danger small mb-2 d-none fw-bold"><i class="bi bi-exclamation-circle me-1"></i> Debes escribir un motivo.</div>

                            <div class="d-grid gap-2">
                                <button type="button" onclick="validarRechazo('Rechazada')" class="btn btn-outline-danger rounded-pill fw-bold d-none" id="btnRechazar">
                                    Rechazar Solicitud
                                </button>
                                <button type="button" onclick="validarRechazo('Cancelada')" class="btn btn-outline-danger rounded-pill fw-bold d-none" id="btnCancelar">
                                    Cancelar Cita Confirmada
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendarioAdmin');
            var modal = new bootstrap.Modal(document.getElementById('modalDetalleCita'));
            var form = document.getElementById('formAcciones');
            
            // Referencias DOM
            const els = {
                nombre: document.getElementById('modalNombre'),
                rut: document.getElementById('modalRut'),
                carrera: document.getElementById('modalCarrera'),
                fecha: document.getElementById('modalFecha'),
                motivo: document.getElementById('modalMotivo'),
                badge: document.getElementById('modalEstadoBadge'),
                divPendientes: document.getElementById('accionesPendientes'),
                btnRechazar: document.getElementById('btnRechazar'),
                btnCancelar: document.getElementById('btnCancelar'),
                txtComentario: document.getElementById('txtComentario'),
                errorComentario: document.getElementById('errorComentario'),
                divComentarioExistente: document.getElementById('divComentarioExistente'),
                txtComentarioExistente: document.getElementById('txtComentarioExistente')
            };

            // Validar Texto antes de enviar
            window.validarRechazo = function(estado) {
                if(els.txtComentario.value.trim() === "") {
                    els.txtComentario.classList.add('is-invalid');
                    els.errorComentario.classList.remove('d-none');
                    return; 
                }
                let input = document.createElement('input');
                input.type = 'hidden'; input.name = 'estado'; input.value = estado;
                form.appendChild(input);
                form.submit();
            };

            els.txtComentario.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                els.errorComentario.classList.add('d-none');
            });
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',
                headerToolbar: { 
                    left: 'prev,next today', 
                    center: 'title', 
                    right: 'timeGridWeek,dayGridMonth' 
                },
                
                // --- AJUSTES VISUALES ---
                slotMinTime: '08:30:00',
                slotMaxTime: '18:30:00',
                slotDuration: '00:30:00',
                slotLabelInterval: '00:30:00', // Muestra etiquetas cada 30 min (8:30, 9:00, 9:30...)
                allDaySlot: false,
                hiddenDays: [0, 6],
                height: 'auto', // Altura automática (no estira)
                expandRows: true, // Expande para llenar huecos
                nowIndicator: true,
                
                selectable: true,
                
                // FUENTE DE EVENTOS
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch('{{ route("encargada.citas.api") }}' + '?start=' + fetchInfo.startStr + '&end=' + fetchInfo.endStr)
                        .then(response => response.json())
                        .then(data => {
                            // Agregamos MANUALMENTE el evento de Almuerzo Recurrente
                            data.push({
                                title: 'Almuerzo',
                                startTime: '13:30:00',
                                endTime: '14:30:00',
                                daysOfWeek: [1, 2, 3, 4, 5], // Lunes a Viernes
                                display: 'background', // Se ve como fondo bloqueado
                                color: '#ffc107', // Amarillo
                                classNames: ['lunch-block'] // Clase opcional
                            });
                            successCallback(data);
                        });
                },

                // --- BLOQUEO ADMINISTRATIVO ---
                select: function(info) {
                    if(!confirm('¿Deseas BLOQUEAR este horario administrativamente?')) {
                        calendar.unselect();
                        return;
                    }
                    
                    fetch('{{ route("encargada.citas.bloquear") }}', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        body: JSON.stringify({ fecha: info.startStr.split('T')[0], hora: info.startStr.split('T')[1].substring(0,5) })
                    }).then(res => res.json()).then(data => {
                        if(data.error) Toastify({text: data.error, style: {background: "#dc3545"}}).showToast();
                        else { calendar.refetchEvents(); Toastify({text: "Bloqueado", style: {background: "#6c757d"}}).showToast(); }
                    });
                },

                // --- CLIC EN EVENTO ---
                eventClick: function(info) {
                    // Si es Almuerzo
                    if (info.event.title === 'Almuerzo') {
                        Toastify({text: "Horario de Colación Institucional", style: {background: "#ffc107", color: "#000"}}).showToast();
                        return;
                    }

                    // Si es Bloqueo Admin
                    if (info.event.title === 'BLOQUEADO') {
                        if(confirm('¿Desbloquear este horario?')) {
                            fetch(`/encargada/api/citas/desbloquear/${info.event.id}`, {
                                method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            }).then(() => { info.event.remove(); });
                        }
                        return;
                    }

                    // --- CARGAR MODAL ---
                    const props = info.event.extendedProps;
                    
                    els.nombre.textContent = props.nombre_completo || 'Estudiante Desconocido';
                    els.rut.textContent = props.rut || 'No reg.';
                    els.carrera.textContent = props.carrera || 'No reg.';
                    els.motivo.textContent = props.motivo || '-';
                    els.fecha.textContent = info.event.start.toLocaleString('es-CL', { weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute:'2-digit'});
                    
                    // Comentario previo
                    if(props.comentario) {
                        els.divComentarioExistente.style.display = 'block';
                        els.txtComentarioExistente.textContent = props.comentario;
                    } else {
                        els.divComentarioExistente.style.display = 'none';
                    }

                    // Badge Color
                    els.badge.textContent = props.estado;
                    els.badge.className = 'badge rounded-pill px-3 py-2 fs-6 ' + 
                        (props.estado === 'Pendiente' ? 'bg-warning text-dark' : 
                        (props.estado === 'Confirmada' ? 'bg-success' : 'bg-secondary'));

                    // Reset Form
                    form.action = `/encargada/citas/${info.event.id}`;
                    els.txtComentario.value = ""; 
                    els.txtComentario.classList.remove('is-invalid');
                    els.errorComentario.classList.add('d-none');

                    // Botones
                    if (props.estado === 'Pendiente') {
                        els.divPendientes.classList.remove('d-none');
                        els.btnRechazar.classList.remove('d-none');
                        els.btnCancelar.classList.add('d-none');
                    } else if (props.estado === 'Confirmada') {
                        els.divPendientes.classList.add('d-none');
                        els.btnRechazar.classList.add('d-none');
                        els.btnCancelar.classList.remove('d-none');
                    } else {
                        els.divPendientes.classList.add('d-none');
                        els.btnRechazar.classList.add('d-none');
                        els.btnCancelar.classList.add('d-none');
                    }

                    modal.show();
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>