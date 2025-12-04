<x-app-layout>
    <div class="container px-4">
        
        {{-- Encabezado --}}
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-0">
                    Listado Maestro de Casos
                </h2>
                <p class="text-muted small">Gestión y auditoría total de solicitudes.</p>
            </div>
            
            {{-- Botón Crear --}}
            <a href="{{ route('casos.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Caso Manual
            </a>
        </div>

        {{-- Tarjeta Principal --}}
        <div class="card border-0 shadow-sm rounded-4">

            {{-- SECCIÓN DE FILTROS --}}
            <div class="card-header bg-white border-bottom pt-4 pb-3 rounded-top-4">
                <form method="GET" action="{{ route('casos.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="search_rut" class="form-label fw-bold small text-muted text-uppercase">RUT Estudiante</label>
                            <input type="text" name="search_rut" class="form-control bg-light border-0 rounded-3" placeholder="Ej: 12.345.678-9" value="{{ request('search_rut') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="search_estado" class="form-label fw-bold small text-muted text-uppercase">Estado</label>
                            <select name="search_estado" class="form-select bg-light border-0 rounded-3">
                                <option value="">Todos los Estados</option>
                                <option value="En Gestion CTP" {{ request('search_estado') == 'En Gestion CTP' ? 'selected' : '' }}>En Gestión CTP</option>
                                <option value="Pendiente de Validacion" {{ request('search_estado') == 'Pendiente de Validacion' ? 'selected' : '' }}>Pendiente de Validación</option>
                                <option value="Finalizado" {{ request('search_estado') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-medium flex-grow-1">
                                <i class="bi bi-filter me-1"></i> Filtrar
                            </button>
                            <a href="{{ route('casos.index') }}" class="btn btn-light rounded-pill px-3 border" title="Limpiar Filtros">
                                <i class="bi bi-arrow-counterclockwise"></i>
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
                                <th scope="col" class="ps-4 py-3 text-secondary text-uppercase small fw-bold">ID</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">RUT</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Estudiante</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Estado Actual</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Creado</th>
                                <th scope="col" class="pe-4 py-3 text-secondary text-uppercase small fw-bold text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($casos as $caso)
                                <tr>
                                    <td class="ps-4 py-3 fw-medium text-muted">#{{ $caso->id }}</td>
                                    <td class="py-3 fw-medium text-dark">{{ $caso->rut_estudiante }}</td>
                                    <td class="py-3 text-muted">{{ $caso->nombre_estudiante }}</td>
                                    
                                    {{-- COLUMNA DE ESTADO --}}
                                    <td class="py-3">
                                        @php $estadoLimpio = strtolower(trim($caso->estado)); @endphp
                                        <span class="badge rounded-pill px-3 py-2 fw-medium border
                                            @if($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision') 
                                                bg-info-subtle text-info-emphasis border-info-subtle
                                            @elseif($estadoLimpio == 'pendiente de validacion' || $estadoLimpio == 'en revision') 
                                                bg-warning-subtle text-warning-emphasis border-warning-subtle
                                            @elseif($estadoLimpio == 'reevaluacion') 
                                                bg-danger-subtle text-danger-emphasis border-danger-subtle
                                            @elseif($estadoLimpio == 'finalizado') 
                                                bg-success-subtle text-success-emphasis border-success-subtle
                                            @else 
                                                bg-light text-dark border 
                                            @endif">
                                            
                                            {{-- Iconos y Texto EXACTOS --}}
                                            @if($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision') 
                                                📝 En Gestión CTP
                                            @elseif($estadoLimpio == 'pendiente de validacion') 
                                                ⏳ Esperando Director
                                            @elseif($estadoLimpio == 'reevaluacion') 
                                                ↩️ Corregir
                                            @elseif($estadoLimpio == 'finalizado') 
                                                ✅ Finalizado
                                            @else 
                                                {{ ucfirst($caso->estado) }} 
                                            @endif
                                        </span>
                                    </td>

                                    <td class="py-3 text-muted">{{ $caso->created_at->format('d/m/Y') }}</td>
                                    
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            {{-- Ver --}}
                                            <a href="{{ route('casos.show', $caso) }}" class="btn btn-sm btn-light border rounded-pill text-primary hover-lift" title="Ver Detalles">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>

                                            {{-- Editar (Solo si no está finalizado) --}}
                                            @if($caso->estado !== 'Finalizado')
                                                <a href="{{ route('casos.edit', $caso) }}" class="btn btn-sm btn-light border rounded-pill text-warning hover-lift" title="Editar Caso">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-sm btn-light border rounded-pill text-muted opacity-50" disabled title="Edición Bloqueada">
                                                    <i class="bi bi-lock-fill"></i>
                                                </button>
                                            @endif

                                            {{-- Eliminar --}}
                                            <form action="{{ route('casos.destroy', $caso) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Esto eliminará el caso permanentemente.');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border rounded-pill text-danger hover-lift" title="Eliminar Definitivamente">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-5 text-center text-muted">
                                        <div class="mb-3">
                                            <i class="bi bi-search fs-1 opacity-25"></i>
                                        </div>
                                        <p class="mb-0">No se encontraron casos con los filtros seleccionados.</p>
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