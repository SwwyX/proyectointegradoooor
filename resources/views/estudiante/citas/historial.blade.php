<x-app-layout>
    {{-- Estilos Personalizados --}}
    <style>
        .page-bg {
            background: linear-gradient(135deg, #1a258aff 0%, #00f1ddff 100%);
            min-height: 85vh;
            border-radius: 20px;
        }
        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .btn-epic {
            background-color: #3b82f6;
            color: white;
            border: none;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }
        .btn-epic:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.4);
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }
    </style>

    <div class="container px-4 py-5">

        {{-- Contenedor Principal con Fondo --}}
        <div class="page-bg p-4 p-md-5">
            
            {{-- ENCABEZADO Y BOTÓN --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 gap-3 bg-white p-4 rounded-4 shadow-sm">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Historial de Citas
                    </h2>
                    <p class="text-muted mb-0">Gestiona tus solicitudes de atención presencial.</p>
                </div>
                
                <a href="{{ route('estudiante.citas.create') }}" class="btn btn-epic d-flex align-items-center">
                    <i class="bi bi-plus-lg me-2"></i> Agendar Nueva Hora
                </a>
            </div>

            {{-- ALERTAS DE ÉXITO/ERROR (Toastify style integration via session) --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i> {{ session('success') }}
                </div>
            @endif

            {{-- LISTADO DE CITAS --}}
            @if($misCitas->isEmpty())
                
                {{-- ESTADO VACÍO (Empty State) --}}
                <div class="card card-custom text-center p-5 bg-white">
                    <div class="card-body">
                        <div class="mb-4 text-muted opacity-25">
                            <i class="bi bi-calendar2-range" style="font-size: 5rem;"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">No tienes citas agendadas</h4>
                        <p class="text-muted mb-4">¿Necesitas apoyo o quieres iniciar tu proceso de inclusión?</p>
                        <a href="{{ route('estudiante.citas.create') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                            Agendar mi primera cita
                        </a>
                    </div>
                </div>

            @else

                {{-- TABLA DE CITAS --}}
                <div class="card card-custom overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-4 text-secondary small fw-bold text-uppercase border-bottom-0">Fecha y Hora</th>
                                    <th class="py-4 text-secondary small fw-bold text-uppercase border-bottom-0">Motivo</th>
                                    <th class="py-4 text-secondary small fw-bold text-uppercase border-bottom-0">Estado</th>
                                    <th class="py-4 text-secondary small fw-bold text-uppercase border-bottom-0">Comentarios</th>
                                    <th class="text-end pe-4 py-4 text-secondary small fw-bold text-uppercase border-bottom-0">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($misCitas as $cita)
                                    <tr>
                                        {{-- 1. FECHA --}}
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3 text-center" style="min-width: 50px;">
                                                    <div class="small fw-bold text-uppercase">{{ \Carbon\Carbon::parse($cita->fecha)->translatedFormat('M') }}</div>
                                                    <div class="h5 mb-0 fw-bold">{{ \Carbon\Carbon::parse($cita->fecha)->format('d') }}</div>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bold text-dark">
                                                        {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}
                                                    </span>
                                                    <span class="small text-muted text-capitalize">
                                                        {{ \Carbon\Carbon::parse($cita->fecha)->translatedFormat('l') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- 2. MOTIVO --}}
                                        <td>
                                            <span class="fw-medium text-secondary">{{ $cita->motivo }}</span>
                                        </td>

                                        {{-- 3. ESTADO (Badges Modernos) --}}
                                        <td>
                                            @php
                                                $badgeClass = match($cita->estado) {
                                                    'Pendiente' => 'bg-warning text-dark bg-opacity-25 text-warning-emphasis',
                                                    'Confirmada' => 'bg-success text-success bg-opacity-10 text-success-emphasis',
                                                    'Realizada' => 'bg-primary text-primary bg-opacity-10',
                                                    'Cancelada', 'Rechazada' => 'bg-danger text-danger bg-opacity-10',
                                                    default => 'bg-light text-dark'
                                                };
                                                $icon = match($cita->estado) {
                                                    'Pendiente' => 'bi-hourglass-split',
                                                    'Confirmada' => 'bi-check-circle-fill',
                                                    'Cancelada', 'Rechazada' => 'bi-x-circle-fill',
                                                    default => 'bi-circle'
                                                };
                                            @endphp
                                            <span class="badge rounded-pill {{ $badgeClass }} px-3 py-2 fw-bold border-0">
                                                <i class="bi {{ $icon }} me-1"></i> {{ ucfirst($cita->estado) }}
                                            </span>
                                        </td>

                                        {{-- 4. COMENTARIOS --}}
                                        <td>
                                            @if($cita->comentario_encargada)
                                                <div class="d-flex align-items-start text-muted small fst-italic" style="max-width: 250px;">
                                                    <i class="bi bi-chat-quote-fill me-2 opacity-50"></i>
                                                    "{{ Str::limit($cita->comentario_encargada, 50) }}"
                                                </div>
                                            @else
                                                <span class="text-muted small opacity-50">-</span>
                                            @endif
                                        </td>

                                        {{-- 5. ACCIONES --}}
                                        <td class="text-end pe-4">
                                            @if($cita->estado === 'Pendiente')
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalCancelar{{ $cita->id }}">
                                                    Cancelar
                                                </button>

                                                {{-- MODAL DE CANCELACIÓN --}}
                                                <div class="modal fade text-start" id="modalCancelar{{ $cita->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content border-0 shadow-lg rounded-4">
                                                            <div class="modal-header border-0 bg-danger text-white rounded-top-4">
                                                                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Cancelar Cita</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body p-4 text-center">
                                                                <div class="mb-3 text-danger opacity-25">
                                                                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                                                                </div>
                                                                <h6 class="fw-bold mb-3">¿Estás seguro/a?</h6>
                                                                <p class="text-muted mb-0">Vas a cancelar tu cita del <strong>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</strong>.</p>
                                                                <p class="small text-muted">Esta acción no se puede deshacer y liberará el horario.</p>
                                                            </div>
                                                            <div class="modal-footer border-0 justify-content-center pb-4">
                                                                <form action="{{ route('estudiante.citas.cancel', $cita->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Volver</button>
                                                                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Sí, Cancelar Cita</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <button class="btn btn-sm btn-light text-muted rounded-pill px-3 border-0" disabled>
                                                    <i class="bi bi-lock-fill me-1"></i> Cerrada
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>