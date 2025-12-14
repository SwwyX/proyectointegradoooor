<x-app-layout>
    <div class="py-5">
        <div class="container-lg">
            
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-semibold fs-2 text-body-emphasis mb-1">
                        Historial de Casos Aceptados
                    </h2>
                    <p class="text-muted mb-0">Registro histórico de validaciones completadas.</p>
                </div>
            </div>

            <div class="card shadow-sm rounded-4 border-0">
                
                {{-- SECCIÓN DE FILTROS --}}
                <div class="card-header bg-white border-bottom pt-4 pb-4 rounded-top-4">
                    <form method="GET" action="{{ route('director.casos.aceptados') }}">
                        
                        {{-- INPUTS OCULTOS: Mantienen el orden al filtrar --}}
                        <input type="hidden" name="sort" value="{{ request('sort', 'updated_at') }}">
                        <input type="hidden" name="order" value="{{ request('order', 'desc') }}">

                        <div class="row g-3 align-items-end">
                            
                            {{-- Filtro RUT --}}
                            <div class="col-md-3">
                                <label for="search_rut" class="form-label fw-bold small text-muted text-uppercase">RUT Estudiante</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" class="form-control bg-light border-start-0" id="search_rut" name="search_rut" placeholder="Ej: 12.345..." value="{{ request('search_rut') }}">
                                </div>
                            </div>

                            {{-- Filtro Fechas --}}
                            <div class="col-md-2">
                                <label for="search_fecha_inicio" class="form-label fw-bold small text-muted text-uppercase">Desde</label>
                                <input type="date" class="form-control bg-light border-0" id="search_fecha_inicio" name="search_fecha_inicio" value="{{ request('search_fecha_inicio') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="search_fecha_fin" class="form-label fw-bold small text-muted text-uppercase">Hasta</label>
                                <input type="date" class="form-control bg-light border-0" id="search_fecha_fin" name="search_fecha_fin" value="{{ request('search_fecha_fin') }}">
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="col-md-5 d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
                                    <i class="bi bi-filter me-1"></i> Filtrar
                                </button>
                                
                                @if(request()->hasAny(['search_rut', 'search_fecha_inicio', 'search_fecha_fin']))
                                    <a href="{{ route('director.casos.aceptados') }}" class="btn btn-light rounded-pill border" title="Limpiar Filtros">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                @endif

                                {{-- BOTÓN EXPORTAR EXCEL --}}
                                <a href="{{ route('reportes.casos.excel', array_merge(request()->query(), ['search_estado' => 'Finalizado'])) }}" class="btn btn-success text-white shadow-sm fw-bold px-4 rounded-pill">
                                    <i class="bi bi-file-earmark-excel-fill me-2"></i> Excel
                                </a>
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
                                    
                                    {{-- COLUMNA INTERACTIVA (FECHA CIERRE) --}}
                                    <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">
                                        <a href="{{ route('director.casos.aceptados', array_merge(request()->query(), [
                                            'sort' => 'updated_at', 
                                            'order' => request('order') === 'asc' ? 'desc' : 'asc'
                                        ])) }}" class="text-decoration-none text-secondary d-flex align-items-center gap-1">
                                            Fecha Cierre
                                            {{-- Lógica de Iconos --}}
                                            @if(request('sort') === 'updated_at' || !request('sort'))
                                                <i class="bi bi-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                            @else
                                                <i class="bi bi-sort-down text-muted opacity-25"></i>
                                            @endif
                                        </a>
                                    </th>

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
                                            <span class="badge rounded-pill fs-6 bg-success-subtle text-success-emphasis border border-success-subtle">
                                                {{ ucfirst($caso->estado) }}
                                            </span>
                                        </td>
                                        <td class="text-muted">{{ $caso->updated_at->format('d/m/Y') }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('director.casos.show', $caso) }}" class="btn btn-light btn-sm border hover-lift">
                                                <i class="bi bi-eye me-1"></i> Ver Detalle
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <div class="mb-3">
                                                <i class="bi bi-check2-circle fs-1 opacity-25"></i>
                                            </div>
                                            <p class="mb-0">No se encontraron casos aceptados con los filtros seleccionados.</p>
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