<x-app-layout>
    <div class="container px-4 py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Historial de Casos Finalizados</h2>
                <p class="text-muted mb-0">Listado de procesos cerrados y documentación oficial.</p>
            </div>
            <a href="{{ route('encargada.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left me-2"></i> Volver al Inicio
            </a>
        </div>

        {{-- BARRA DE HERRAMIENTAS (FILTROS) --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
            <div class="card-body p-4">
                <form action="{{ route('encargada.casos.finalizados') }}" method="GET" class="row g-3 align-items-end">
                    
                    {{-- Input oculto para mantener orden al filtrar --}}
                    <input type="hidden" name="sort" value="{{ request('sort', 'updated_at') }}">
                    <input type="hidden" name="order" value="{{ request('order', 'desc') }}">

                    {{-- Buscador Textual (CORREGIDO NAME) --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Buscar Estudiante</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search_rut" class="form-control bg-light border-start-0" 
                                   placeholder="RUT..." value="{{ request('search_rut') }}">
                        </div>
                    </div>

                    {{-- Filtro: Fecha Inicio (CORREGIDO NAME) --}}
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Desde</label>
                        <input type="date" name="search_fecha_inicio" class="form-control bg-light" value="{{ request('search_fecha_inicio') }}">
                    </div>

                    {{-- Filtro: Fecha Fin (CORREGIDO NAME) --}}
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Hasta</label>
                        <input type="date" name="search_fecha_fin" class="form-control bg-light" value="{{ request('search_fecha_fin') }}">
                    </div>

                    {{-- Botones --}}
                    <div class="col-md-4 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary fw-bold px-4">Filtrar</button>
                        
                        @if(request()->hasAny(['search_rut', 'search_fecha_inicio', 'search_fecha_fin']))
                            <a href="{{ route('encargada.casos.finalizados') }}" class="btn btn-outline-secondary" title="Limpiar Filtros"><i class="bi bi-x-lg"></i></a>
                        @endif

                        {{-- BOTÓN EXPORTAR EXCEL (CORREGIDO) --}}
                        {{-- Se mezcla el request actual con 'search_estado' => 'Finalizado' para que el Excel sepa que solo debe bajar los cerrados --}}
                        <a href="{{ route('reportes.casos.excel', array_merge(request()->query(), [
                            'search_estado' => 'Finalizado', 
                            'date_col' => 'updated_at' 
                        ])) }}" class="btn btn-success text-white shadow-sm fw-bold px-4">
                            <i class="bi bi-file-earmark-excel-fill me-2"></i> Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLA DE DATOS --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Estudiante</th>
                            <th class="py-3">Carrera</th>
                            <th class="py-3">Estado Final</th>
                            
                            {{-- COLUMNA ORDENABLE (FECHA) --}}
                            <th class="py-3">
                                <a href="{{ route('encargada.casos.finalizados', array_merge(request()->query(), [
                                    'sort' => 'updated_at', 
                                    'order' => request('order') === 'asc' ? 'desc' : 'asc'
                                ])) }}" class="text-dark text-decoration-none fw-bold d-flex align-items-center">
                                    Fecha Cierre
                                    <span class="ms-1 text-muted">
                                        @if(request('sort') === 'updated_at')
                                            <i class="bi bi-arrow-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="bi bi-arrow-down-up" style="font-size: 0.8rem; opacity: 0.5;"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>

                            <th class="text-end pe-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($casos as $caso)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $caso->estudiante->nombre_completo }}</div>
                                    <small class="text-muted">{{ $caso->estudiante->rut }}</small>
                                </td>
                                <td>{{ Str::limit($caso->estudiante->carrera, 25) }}</td>
                                <td>
                                    @php
                                        $color = match($caso->estado) {
                                            'Aceptado', 'Finalizado' => 'success',
                                            'Rechazado' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge rounded-pill bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }}">
                                        {{ ucfirst($caso->estado) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $caso->updated_at->format('d/m/Y') }}</td>
                                <td class="text-end pe-4">
                                    
                                    {{-- VER DETALLE --}}
                                    <a href="{{ route('encargada.casos.show', $caso) }}" class="btn btn-sm btn-light border me-1" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- DESCARGAR PDF --}}
                                    <a href="{{ route('reportes.caso.pdf', $caso) }}" target="_blank" class="btn btn-sm btn-danger text-white shadow-sm" title="Descargar Informe PDF">
                                        <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                    </a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                    No hay casos finalizados con estos criterios.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-white py-3">
                {{-- Mantiene los filtros al paginar --}}
                {{ $casos->appends(request()->query())->links() }}
            </div>
        </div>

    </div>
</x-app-layout>