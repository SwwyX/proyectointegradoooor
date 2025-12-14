<x-app-layout>
    {{-- LIBRERÍAS --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/locales/es.global.min.js'></script>

    <style>
        /* FONDO ESTILIZADO */
        .page-bg {
            background: linear-gradient(135deg, #00f1ddff 0%, #1a258aff 100%);
            min-height: 85vh;
            border-radius: 20px;
        }

        /* CALENDARIO */
        .fc { 
            background: white; 
            padding: 20px; 
            border-radius: 16px; 
            border: none; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            font-family: 'Figtree', sans-serif;
        }
        .fc-toolbar-title { font-size: 1.4rem !important; font-weight: 800; color: #1e293b; letter-spacing: -0.5px; }
        .fc-col-header-cell { background-color: #f8fafc; padding: 10px 0; border-bottom: 2px solid #e2e8f0; }
        .fc-col-header-cell-cushion { text-transform: uppercase; font-size: 0.8rem; font-weight: 700; color: #64748b; }
        .fc-timegrid-slot-label { font-size: 0.8rem; font-weight: 600; color: #94a3b8; }
        
        /* EVENTOS */
        .fc-event { border: none !important; border-radius: 4px; opacity: 0.8; }
        
        /* BOTONES CALENDARIO */
        .fc-button-primary { 
            background-color: #3b82f6 !important; border: none !important; border-radius: 8px !important; 
            font-weight: 600 !important; text-transform: capitalize; padding: 8px 16px !important;
        }
        .fc-button-active { background-color: #2563eb !important; }

        /* FORMULARIO LATERAL */
        .form-card { border: none; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; }
        .form-header { background: #3b82f6; color: white; padding: 20px; }

        /* --- CORRECCIÓN BOTÓN CONFIRMAR --- */
        #btnConfirmar {
            background-color: #3b82f6;
            color: #ffffff;
            border: none;
            transition: all 0.3s ease;
        }
        #btnConfirmar:disabled {
            background-color: #e2e8f0 !important; /* Gris claro */
            color: #94a3b8 !important; /* Texto gris oscuro */
            cursor: not-allowed;
            box-shadow: none;
        }
        #btnConfirmar:hover:not(:disabled) {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
    </style>

    <div class="container px-4 py-5">
        
        {{-- ERRORES DE SERVIDOR --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm rounded-4 mb-4 border-0 d-flex align-items-center">
                <i class="bi bi-x-circle-fill fs-4 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">No se pudo agendar</h6>
                    <ul class="mb-0 small ps-3">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger shadow-sm rounded-4 mb-4 border-0 d-flex align-items-center">
                <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i> {{ session('error') }}
            </div>
        @endif

        <div class="row g-4">
            
            {{-- COLUMNA IZQUIERDA: FORMULARIO --}}
            <div class="col-lg-4">
                <div class="card form-card h-100">
                    <div class="form-header">
                        <h4 class="fw-bold mb-1"><i class="bi bi-calendar-plus me-2"></i>Nueva Cita</h4>
                        <p class="mb-0 small text-white-50">Selecciona un bloque disponible.</p>
                    </div>
                    
                    <div class="card-body p-4 d-flex flex-column">
                        
                        <form action="{{ route('estudiante.citas.store') }}" method="POST" id="formReserva" onsubmit="btnLoading()">
                            @csrf
                            
                            {{-- Resumen Visual --}}
                            <div class="bg-light p-3 rounded-3 mb-4 border">
                                <div class="mb-3">
                                    <label class="small fw-bold text-muted text-uppercase mb-1">Fecha Seleccionada</label>
                                    <input type="text" class="form-control fw-bold text-dark bg-white border-0 shadow-sm" id="txtFecha" value="-" readonly>
                                    <input type="hidden" name="fecha" id="inputFecha">
                                </div>
                                <div>
                                    <label class="small fw-bold text-muted text-uppercase mb-1">Hora de Inicio</label>
                                    <input type="text" class="form-control fw-bold text-primary bg-white border-0 shadow-sm fs-5" id="txtHora" value="-" readonly>
                                    <input type="hidden" name="hora" id="inputHora">
                                </div>
                            </div>

                            {{-- Motivo --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-secondary text-uppercase">Motivo de la visita</label>
                                <select name="motivo" class="form-select form-select-lg shadow-sm border-light" required>
                                    <option value="Entrevista Inicial">Entrevista Inicial (Anamnesis)</option>
                                    <option value="Seguimiento">Seguimiento / Dudas</option>
                                </select>
                            </div>

                            <button type="submit" class="btn w-100 py-3 rounded-pill fw-bold shadow-sm" id="btnConfirmar" disabled>
                                Selecciona una hora...
                            </button>
                        </form>

                        {{-- Leyenda --}}
                        <div class="mt-auto pt-4 border-top">
                            <div class="d-flex justify-content-center gap-3 small text-muted fw-bold">
                                <div class="d-flex align-items-center"><span class="badge bg-danger rounded-circle p-1 me-2"> </span> Ocupado</div>
                                <div class="d-flex align-items-center"><span class="badge bg-warning rounded-circle p-1 me-2"> </span> Almuerzo</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: FULLCALENDAR --}}
            <div class="col-lg-8">
                <div class="page-bg p-4 shadow-sm">
                    <div id="calendario"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        function btnLoading() {
            const btn = document.getElementById('btnConfirmar');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Enviando...';
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendario');
            var btnConfirmar = document.getElementById('btnConfirmar');
            var txtFecha = document.getElementById('txtFecha');
            var txtHora = document.getElementById('txtHora');
            var inputFecha = document.getElementById('inputFecha');
            var inputHora = document.getElementById('inputHora');

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
                slotLabelInterval: '00:30:00',
                allDaySlot: false,
                hiddenDays: [0, 6],
                height: 'auto',
                expandRows: true,
                nowIndicator: true,
                
                selectable: true,
                selectMirror: true,
                selectConstraint: { startTime: '00:00', endTime: '24:00' },
                
                // Validación de Fecha (Mañana en adelante)
                validRange: { start: '{{ \Carbon\Carbon::tomorrow()->format("Y-m-d") }}' },

                // --- CARGA DE EVENTOS (INCLUYENDO ALMUERZO VISUAL) ---
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch('{{ route("estudiante.citas.api") }}' + '?start=' + fetchInfo.startStr + '&end=' + fetchInfo.endStr)
                        .then(response => response.json())
                        .then(data => {
                            // Agregamos MANUALMENTE el Almuerzo para asegurar que se vea
                            data.push({
                                title: 'Almuerzo',
                                startTime: '13:30:00',
                                endTime: '14:30:00',
                                daysOfWeek: [1, 2, 3, 4, 5],
                                display: 'background',
                                color: '#ffc107',
                                classNames: ['lunch-block']
                            });
                            successCallback(data);
                        });
                },

                // --- LÓGICA DE SELECCIÓN ---
                select: function(info) {
                    var ahora = new Date();
                    var seleccion = new Date(info.startStr);
                    var hoy = new Date(); hoy.setHours(0,0,0,0);

                    // Validar pasado
                    if (seleccion <= hoy) { 
                         calendar.unselect();
                         mostrarError("Solo se puede agendar con 24 horas de anticipación.");
                         return;
                    }

                    // DETECCIÓN DE COLISIONES (Incluye Almuerzo)
                    let eventos = calendar.getEvents();
                    let choca = eventos.some(function(evento) {
                        return info.start < evento.end && info.end > evento.start;
                    });

                    if (choca) {
                        calendar.unselect();
                        mostrarError("Este horario no está disponible.");
                        return;
                    }

                    // Llenar Formulario
                    var fechaStr = info.startStr.split('T')[0];
                    var horaStr = info.startStr.split('T')[1].substring(0, 5);

                    txtFecha.value = new Date(fechaStr + 'T00:00:00').toLocaleDateString('es-CL', { weekday: 'long', day: 'numeric', month: 'long' });
                    txtHora.value = horaStr + " hrs";
                    inputFecha.value = fechaStr;
                    inputHora.value = horaStr;

                    // ACTIVAR BOTÓN Y CAMBIAR TEXTO
                    btnConfirmar.disabled = false;
                    btnConfirmar.innerHTML = "Confirmar para las " + horaStr;
                    
                    Toastify({ text: "Seleccionado: " + horaStr, style: { background: "#3b82f6" } }).showToast();
                },

                eventClick: function(info) {
                    if (info.event.title === 'Almuerzo') {
                        mostrarError("Horario de colación.");
                    } else {
                        mostrarError("Horario ocupado.");
                    }
                }
            });

            calendar.render();

            function mostrarError(mensaje) {
                Toastify({
                    text: mensaje,
                    style: { background: "#ef4444", boxShadow: "0 4px 15px rgba(220, 53, 69, 0.4)" },
                    duration: 3000
                }).showToast();
            }
        });
    </script>
</x-app-layout>