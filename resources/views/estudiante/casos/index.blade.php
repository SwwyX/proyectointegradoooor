<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            
            <h2 class="fw-semibold fs-2 mb-4">Mis Solicitudes de Ajuste</h2>

            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="p-3">Folio</th>
                                    <th class="p-3">Fecha Solicitud</th>
                                    <th class="p-3">Carrera</th>
                                    <th class="p-3">Estado Actual</th>
                                    <th class="p-3 text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($casos as $caso)
                                    <tr>
                                        <td class="p-3 fw-bold">#{{ str_pad($caso->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="p-3">{{ $caso->created_at->format('d/m/Y') }}</td>
                                        <td class="p-3">{{ $caso->carrera }}</td>
                                        <td class="p-3">
                                            @php $estado = strtolower(trim($caso->estado)); @endphp
                                            <span class="badge rounded-pill fw-normal px-3 py-2
                                                @if($estado == 'finalizado' || $estado == 'aceptado') bg-success-subtle text-success border border-success-subtle
                                                @elseif($estado == 'rechazado') bg-danger-subtle text-danger border border-danger-subtle
                                                @elseif($estado == 'en gestion ctp' || $estado == 'sin revision') bg-info-subtle text-info-emphasis border border-info-subtle
                                                @else bg-warning-subtle text-warning-emphasis border border-warning-subtle @endif">
                                                
                                                {{-- Traducción de estados técnicos a lenguaje alumno --}}
                                                @if($estado == 'en gestion ctp' || $estado == 'sin revision') En Evaluación Técnica
                                                @elseif($estado == 'pendiente de validacion' || $estado == 'en revision') En Validación Directiva
                                                @elseif($estado == 'reevaluacion') En Revisión Interna
                                                @elseif($estado == 'finalizado' || $estado == 'aceptado') Finalizado / Aprobado
                                                @else {{ ucfirst($caso->estado) }} @endif
                                            </span>
                                        </td>
                                        <td class="p-3 text-end">
                                            <a href="{{ route('estudiante.casos.show', $caso->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                Ver Detalle
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                            No tienes solicitudes registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($casos->hasPages())
                    <div class="card-footer bg-white">
                        {{ $casos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>