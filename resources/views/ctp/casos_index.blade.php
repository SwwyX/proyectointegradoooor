<x-app-layout>
    <div class="container px-4 py-5">
        
        {{-- Encabezado --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    Bandeja de Casos
                </h2>
                <p class="text-muted small mb-0">
                    Casos asignados pendientes de redacción de ajustes.
                </p>
            </div>
        </div>

        {{-- BARRA DE FILTROS (NUEVO) --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
            <div class="card-body p-4">
                <form action="{{ route('ctp.casos.index') }}" method="GET" class="row g-3 align-items-end">
                    
                    {{-- Buscador Textual --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Buscar Estudiante</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-start-0" 
                                   placeholder="RUT o Nombre..." value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Filtro Fechas --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Desde</label>
                        <input type="date" name="fecha_inicio" class="form-control bg-light" value="{{ request('fecha_inicio') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Hasta</label>
                        <input type="date" name="fecha_fin" class="form-control bg-light" value="{{ request('fecha_fin') }}">
                    </div>

                    {{-- Botones --}}
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Filtrar</button>
                        @if(request()->has('search') || request()->has('fecha_inicio'))
                            <a href="{{ route('ctp.casos.index') }}" class="btn btn-outline-secondary w-100" title="Limpiar"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="mb-4 alert alert-success alert-dismissible fade show rounded-3 border-0 bg-success bg-opacity-10 text-success" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tabla --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive rounded-4">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="ps-4 py-3 text-secondary text-uppercase small fw-bold">RUT Estudiante</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Nombre Estudiante</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Carrera</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Estado</th>
                                <th scope="col" class="py-3 text-secondary text-uppercase small fw-bold">Fecha Creación</th>
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
                                        <span class="badge rounded-pill px-3 py-2 fw-semibold border bg-info-subtle text-info-emphasis border-info-subtle">
                                            {{ ucfirst($caso->estado) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-muted">{{ $caso->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="pe-4 py-3 text-end">
                                        <a href="{{ route('ctp.casos.edit', $caso) }}" 
                                           class="btn btn-sm btn-primary rounded-pill px-3 fw-medium shadow-sm">
                                            <i class="bi bi-pencil-square me-1"></i> Redactar Ajustes
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-5 text-center text-muted">
                                        <div class="mb-3">
                                            <i class="bi bi-search fs-1 opacity-25"></i>
                                        </div>
                                        <p class="mb-0">No se encontraron casos con los filtros aplicados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($casos->hasPages())
                <div class="card-footer bg-white border-top-0 py-3 rounded-bottom-4">
                    {{ $casos->withQueryString()->links() }} {{-- withQueryString mantiene los filtros al paginar --}}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>