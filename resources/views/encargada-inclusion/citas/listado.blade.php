<x-app-layout>
    <div class="container px-4 py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Listado de Citas</h2>
                <p class="text-muted mb-0">
                    <span class="text-warning fw-bold">{{ $pendientes }} Pendientes</span> | 
                    <span class="text-success fw-bold">{{ $confirmadas }} Confirmadas Futuras</span>
                </p>
            </div>
            <a href="{{ route('encargada.citas.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-calendar-week me-2"></i> Volver al Calendario
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Estudiante</th>
                            <th>Carrera</th>
                            <th>Fecha y Hora</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($citas as $cita)
                            <tr>
                                {{-- 1. Estudiante --}}
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">
                                        {{ $cita->estudiante ? $cita->estudiante->nombre_completo : 'Estudiante Eliminado' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $cita->estudiante ? $cita->estudiante->rut : '-' }}
                                    </small>
                                </td>
                                
                                {{-- 2. Carrera --}}
                                <td>
                                    <span class="badge bg-light text-secondary border">
                                        {{ $cita->estudiante ? Str::limit($cita->estudiante->carrera, 20) : '-' }}
                                    </span>
                                </td>

                                {{-- 3. Fecha y Hora --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">
                                            {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                        </span>
                                        <span class="text-muted small">
                                            {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }} hrs
                                        </span>
                                    </div>
                                </td>

                                {{-- 4. Motivo --}}
                                <td>{{ $cita->motivo }}</td>

                                {{-- 5. Estado --}}
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 
                                        {{ $cita->estado === 'Pendiente' ? 'bg-warning text-dark' : '' }}
                                        {{ $cita->estado === 'Confirmada' ? 'bg-success' : '' }}
                                        {{ $cita->estado === 'Rechazada' ? 'bg-danger' : '' }}
                                        {{ $cita->estado === 'Cancelada' ? 'bg-secondary' : '' }}">
                                        {{ $cita->estado }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No hay citas registradas con estos filtros.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $citas->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>