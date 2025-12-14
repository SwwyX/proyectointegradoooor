<x-app-layout>
    <div class="container px-4 py-5">
        
        {{-- 1. Encabezado y Botón Crear --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">
                    Listado de Casos Activos
                </h2>
                <p class="text-muted small mb-0">Gestión y seguimiento de solicitudes en proceso.</p>
            </div>
            
            {{-- Botón Crear Caso (Movido aquí) --}}
            <a href="{{ route('encargada.casos.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-lg me-2"></i>Registrar Nuevo Caso
            </a>
        </div>

        {{-- 2. Tarjeta Principal --}}
        <div class="card border-0 shadow-sm rounded-4">

            {{-- SECCIÓN DE FILTROS --}}
            <div class="card-header bg-white border-bottom pt-4 pb-3 rounded-top-4">
                <form method="GET" action="{{ route('encargada.casos.index') }}">
                    {{-- Input oculto para mantener el orden actual --}}
                    <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
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

                        {{-- Filtro Estado --}}
                        <div class="col-md-3">
                            <label for="search_estado" class="form-label fw-bold small text-muted text-uppercase">Estado</label>
                            <select name="search_estado" class="form-select bg-light border-0">
                                <option value="">Todos los Activos</option>
                                <option value="En Gestion CTP" {{ request('search_estado') == 'En Gestion CTP' ? 'selected' : '' }}>📝 En Gestión CTP</option>
                                <option value="Pendiente de Validacion" {{ request('search_estado') == 'Pendiente de Validacion' ? 'selected' : '' }}>⏳ Pendiente Validación</option>
                            </select>
                        </div>

                        {{-- Filtro Fechas --}}
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">Desde</label>
                            <input type="date" class="form-control bg-light border-0" name="search_fecha_inicio" value="{{ request('search_fecha_inicio') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted text-uppercase">Hasta</label>
                            <input type="date" class="form-control bg-light border-0" name="search_fecha_fin" value="{{ request('search_fecha_fin') }}">
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-dark rounded-pill w-100 fw-bold">
                                <i class="bi bi-filter me-1"></i> Filtrar
                            </button>
                            @if(request()->hasAny(['search_rut', 'search_estado', 'search_fecha_inicio', 'search_fecha_fin']))
                                <a href="{{ route('encargada.casos.index') }}" class="btn btn-light rounded-pill border" title="Limpiar Filtros">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- 3. TABLA DE DATOS --}}
            <div class="card-body p-0">
                
                {{-- Alertas --}}
                @if(session('success'))
                    <div class="alert alert-success m-3 rounded-3 border-0 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">RUT</th>
                                <th class="py-3 text-secondary text-uppercase small fw-bold">Estudiante</th>
                                <th class="py-3 text-secondary text-uppercase small fw-bold">Carrera</th>
                                <th class="py-3 text-secondary text-uppercase small fw-bold">Estado Actual</th>
                                
                                {{-- COLUMNA ORDENABLE (FECHA) --}}
                                <th class="py-3 text-secondary text-uppercase small fw-bold">
                                    <a href="{{ route('encargada.casos.index', array_merge(request()->query(), [
                                        'sort' => 'created_at', 
                                        'order' => request('order') === 'asc' ? 'desc' : 'asc'
                                    ])) }}" class="text-decoration-none text-secondary d-flex align-items-center gap-1">
                                        Fecha Ingreso
                                        @if(request('sort') === 'created_at')
                                            <i class="bi bi-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                        @else
                                            <i class="bi bi-sort-down text-muted opacity-25"></i>
                                        @endif
                                    </a>
                                </th>

                                <th class="pe-4 py-3 text-secondary text-uppercase small fw-bold text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($casos as $caso)
                                <tr>
                                    <td class="ps-4 py-3 fw-medium text-nowrap text-dark">{{ $caso->rut_estudiante }}</td>
                                    <td class="py-3 text-muted">{{ $caso->nombre_estudiante }}</td>
                                    <td class="py-3 text-muted small">{{ Str::limit($caso->carrera, 20) }}</td>
                                    
                                    {{-- Estado con Badge --}}
                                    <td class="py-3">
                                        @php $estadoLimpio = strtolower(trim($caso->estado)); @endphp
                                        <span class="badge rounded-pill px-3 py-2 fw-medium border
                                            @if($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision') 
                                                bg-info-subtle text-info-emphasis border-info-subtle
                                            @elseif($estadoLimpio == 'pendiente de validacion' || $estadoLimpio == 'en revision') 
                                                bg-warning-subtle text-warning-emphasis border-warning-subtle
                                            @elseif($estadoLimpio == 'reevaluacion') 
                                                bg-danger-subtle text-danger-emphasis border-danger-subtle
                                            @else 
                                                bg-light text-dark border 
                                            @endif">
                                            
                                            @if($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision') 
                                                📝 En Gestión CTP
                                            @elseif($estadoLimpio == 'pendiente de validacion') 
                                                ⏳ Pendiente Validación
                                            @else 
                                                {{ ucfirst($caso->estado) }} 
                                            @endif
                                        </span>
                                    </td>

                                    <td class="py-3 text-muted">{{ $caso->created_at->format('d/m/Y') }}</td>
                                    
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            {{-- Ver --}}
                                            <a href="{{ route('encargada.casos.show', $caso) }}" 
                                               class="btn btn-sm btn-light border rounded-pill text-primary hover-lift" 
                                               title="Ver Detalles">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            
                                            {{-- Editar (Solo si está en etapa inicial) --}}
                                            @if($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision')
                                                <a href="{{ route('encargada.casos.edit', $caso) }}" 
                                                   class="btn btn-sm btn-light border rounded-pill text-primary hover-lift" 
                                                   title="Editar Caso">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-light border rounded-pill text-muted opacity-50" 
                                                        title="Edición bloqueada (En proceso CTP/Director)" disabled>
                                                    <i class="bi bi-lock-fill"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-5 text-center text-muted">
                                        <div class="mb-3">
                                            <i class="bi bi-inbox fs-1 opacity-25"></i>
                                        </div>
                                        <p class="mb-0">No se encontraron casos activos con los filtros seleccionados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer con Paginación --}}
            @if ($casos->hasPages())
                <div class="card-footer bg-white border-top-0 py-3 rounded-bottom-4">
                    {{ $casos->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>