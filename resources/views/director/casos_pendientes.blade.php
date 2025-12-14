<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            
            {{-- Encabezado --}}
            <div class="mb-4">
                <h2 class="fw-semibold fs-2 text-body-emphasis mb-1">
                    Casos Pendientes de Validación
                </h2>
                <p class="text-muted mb-0">Listado de casos con propuesta técnica CTP lista para revisión.</p>
            </div>

            {{-- Alertas --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-success bg-success bg-opacity-10 text-success" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm rounded-4 border-0">

                {{-- Filtros --}}
                <div class="card-header bg-white border-bottom pt-4 pb-4 rounded-top-4">
                    <form method="GET" action="{{ route('director.casos.pendientes') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="search_rut" class="form-label fw-bold small text-muted text-uppercase">RUT Estudiante</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0" id="search_rut" name="search_rut" placeholder="Ej: 12.345..." value="{{ request('search_rut') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="search_fecha_inicio" class="form-label fw-bold small text-muted text-uppercase">Desde</label>
                                <input type="date" class="form-control bg-light border-0" id="search_fecha_inicio" name="search_fecha_inicio" value="{{ request('search_fecha_inicio') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="search_fecha_fin" class="form-label fw-bold small text-muted text-uppercase">Hasta</label>
                                <input type="date" class="form-control bg-light border-0" id="search_fecha_fin" name="search_fecha_fin" value="{{ request('search_fecha_fin') }}">
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-dark rounded-pill w-100 fw-bold">
                                    <i class="bi bi-filter me-1"></i> Filtrar
                                </button>
                                @if(request()->hasAny(['search_rut', 'search_fecha_inicio', 'search_fecha_fin']))
                                    <a href="{{ route('director.casos.pendientes') }}" class="btn btn-light rounded-pill border" title="Limpiar Filtros">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="ps-4 py-3 text-secondary text-uppercase small fw-bold">RUT</th>
                                    <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Estudiante</th>
                                    <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Carrera</th>
                                    <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Estado</th>
                                    <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Fecha Ingreso</th>
                                    <th scope="col" class="text-end pe-4 py-3 text-secondary text-uppercase small fw-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse ($casos as $caso)
                                    <tr>
                                        <td class="ps-4 fw-medium text-dark">{{ $caso->rut_estudiante }}</td>
                                        <td class="text-muted">{{ $caso->nombre_estudiante }}</td>
                                        <td class="text-muted small">{{ Str::limit($caso->carrera, 25) }}</td>
                                        <td>
                                            @php $estadoLimpio = strtolower(trim($caso->estado)); @endphp
                                            <span class="badge rounded-pill px-3 py-2 fw-medium border
                                                @if($estadoLimpio == 'pendiente de validacion' || $estadoLimpio == 'en revision') 
                                                    bg-warning-subtle text-warning-emphasis border-warning-subtle
                                                @elseif($estadoLimpio == 'reevaluacion') 
                                                    bg-danger-subtle text-danger-emphasis border-danger-subtle
                                                @else 
                                                    bg-info-subtle text-info-emphasis border border-info-subtle 
                                                @endif">
                                                
                                                @if($estadoLimpio == 'pendiente de validacion') 
                                                    ⏳ Pendiente Validación
                                                @elseif($estadoLimpio == 'reevaluacion') 
                                                    ↩️ En Corrección (CTP)
                                                @else 
                                                    {{ ucfirst($caso->estado) }} 
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-muted">{{ $caso->created_at->format('d/m/Y') }}</td>
                                        <td class="text-end pe-4">
                                            {{-- BOTÓN REVISAR CORREGIDO --}}
                                            <a href="{{ route('director.casos.show', $caso) }}" 
                                               class="btn btn-primary btn-sm rounded-pill px-3 fw-bold text-white shadow-sm border-0">
                                                <i class="bi bi-pencil-square me-1"></i> Revisar Caso
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <div class="mb-3">
                                                <i class="bi bi-inbox fs-1 opacity-25"></i>
                                            </div>
                                            <p class="mb-0">No hay casos pendientes de validación en este momento.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($casos->hasPages())
                    <div class="card-footer bg-white border-top-0 py-3 rounded-bottom-4">
                        {{ $casos->appends(request()->query())->links() }}
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</x-app-layout>