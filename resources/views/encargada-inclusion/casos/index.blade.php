<x-app-layout>
    <div class="container px-4">
        
        {{-- Título de la Página --}}
        <div class="mb-4">
            <h2 class="fw-bold text-dark mb-0">
                Listado de Casos
            </h2>
            <p class="text-muted small">Gestión y seguimiento de solicitudes de inclusión.</p>
        </div>

        {{-- Tarjeta Principal --}}
        <div class="card border-0 shadow-sm rounded-4">

            {{-- SECCIÓN DE FILTROS --}}
            <div class="card-header bg-white border-bottom pt-4 pb-3 rounded-top-4">
                <form method="GET" action="{{ route('encargada.casos.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="search_rut" class="form-label fw-bold small text-muted text-uppercase">RUT Estudiante</label>
                            <input type="text" class="form-control bg-light border-0" id="search_rut" name="search_rut" placeholder="Ej: 12.345.678-9" value="{{ request('search_rut') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="search_fecha_inicio" class="form-label fw-bold small text-muted text-uppercase">Desde</label>
                            <input type="date" class="form-control bg-light border-0" id="search_fecha_inicio" name="search_fecha_inicio" value="{{ request('search_fecha_inicio') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="search_fecha_fin" class="form-label fw-bold small text-muted text-uppercase">Hasta</label>
                            <input type="date" class="form-control bg-light border-0" id="search_fecha_fin" name="search_fecha_fin" value="{{ request('search_fecha_fin') }}">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium flex-grow-1">
                                <i class="bi bi-search me-1"></i> Buscar
                            </button>
                            <a href="{{ route('encargada.casos.index') }}" class="btn btn-light rounded-pill px-3 border" title="Limpiar Filtros">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                
                {{-- Alertas --}}
                @if(session('success'))
                    <div class="m-4 alert alert-success alert-dismissible fade show rounded-3 border-0 bg-success bg-opacity-10 text-success" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="m-4 alert alert-danger alert-dismissible fade show rounded-3 border-0 bg-danger bg-opacity-10 text-danger" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Botón Registrar (Flotante o alineado) --}}
                <div class="p-4 d-flex justify-content-end border-bottom bg-light bg-opacity-25">
                    <a href="{{ route('encargada.casos.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium">
                        <i class="bi bi-plus-lg me-2"></i>Registrar Nuevo Caso
                    </a>
                </div>

                {{-- Tabla --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="ps-4 py-3 text-secondary text-uppercase small fw-bold">RUT</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Estudiante</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Carrera</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Estado</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Fecha</th>
                                <th scope="col" class="pe-4 py-3 text-secondary text-uppercase small fw-bold text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse ($casos as $caso)
                                <tr>
                                    <td class="ps-4 py-3 fw-medium text-nowrap text-dark">{{ $caso->rut_estudiante }}</td>
                                    <td class="py-3 text-muted">{{ $caso->nombre_estudiante }}</td>
                                    <td class="py-3 text-muted">{{ $caso->carrera }}</td>
                                    <td class="py-3">
                                        @php $estadoLimpio = strtolower(trim($caso->estado)); @endphp
                                        <span class="badge rounded-pill px-3 py-2 fw-normal
                                            @if($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision') bg-info bg-opacity-10 text-info border border-info border-opacity-25
                                            @elseif($estadoLimpio == 'pendiente de validacion' || $estadoLimpio == 'en revision') bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25
                                            @elseif($estadoLimpio == 'aceptado' || $estadoLimpio == 'finalizado') bg-success bg-opacity-10 text-success border border-success border-opacity-25
                                            @elseif($estadoLimpio == 'rechazado' || $estadoLimpio == 'reevaluacion') bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25
                                            @else bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 @endif
                                        ">
                                            @if($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision') En Gestión CTP
                                            @elseif($estadoLimpio == 'pendiente de validacion') Pendiente Validación
                                            @elseif($estadoLimpio == 'reevaluacion') En Corrección
                                            @elseif($estadoLimpio == 'finalizado') Finalizado
                                            @else {{ ucfirst($caso->estado) }} @endif
                                        </span>
                                    </td>
                                    <td class="py-3 text-muted">{{ $caso->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            {{-- Botón Ver --}}
                                            <a href="{{ route('encargada.casos.show', $caso) }}" 
                                               class="btn btn-sm btn-light border rounded-pill text-muted hover-lift" 
                                               title="Ver Detalles">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            
                                            {{-- Botón Editar --}}
                                            @if($estadoLimpio == 'en gestion ctp' || $estadoLimpio == 'sin revision')
                                                <a href="{{ route('encargada.casos.edit', $caso) }}" 
                                                   class="btn btn-sm btn-light border rounded-pill text-primary hover-lift" 
                                                   title="Editar Caso">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-light border rounded-pill text-muted opacity-50" 
                                                        title="El caso ya fue tomado por CTP" disabled>
                                                    <i class="bi bi-pencil-fill"></i>
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
                                        <p class="mb-0">No se encontraron casos registrados con los filtros seleccionados.</p>
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