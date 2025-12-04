<x-app-layout>
    <div class="py-5">
        <div class="container-fluid px-4">
            
            <h2 class="fw-semibold fs-2 text-body-emphasis mb-4">
                Casos Pendientes de Validación
            </h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm rounded-3">

                <div class="card-header bg-body-tertiary border-bottom-0 pt-4">
                    <form method="GET" action="{{ route('director.casos.pendientes') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="search_rut" class="form-label fw-medium">RUT Estudiante</label>
                                <input type="text" class="form-control" id="search_rut" name="search_rut" placeholder="Buscar por RUT..." value="{{ request('search_rut') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="search_fecha_inicio" class="form-label fw-medium">Desde</label>
                                <input type="date" class="form-control" id="search_fecha_inicio" name="search_fecha_inicio" value="{{ request('search_fecha_inicio') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="search_fecha_fin" class="form-label fw-medium">Hasta</label>
                                <input type="date" class="form-control" id="search_fecha_fin" name="search_fecha_fin" value="{{ request('search_fecha_fin') }}">
                            </div>
                            <div class="col-md-3 d-flex justify-content-start justify-content-md-end align-self-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i> Buscar
                                </button>
                                <a href="{{ route('director.casos.pendientes') }}" class="btn btn-secondary">Limpiar</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive rounded-3">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col" class="ps-4">RUT Estudiante</th>
                                    <th scope="col">Nombre Estudiante</th>
                                    <th scope="col">Carrera</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Fecha Creación</th>
                                    <th scope="col" class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($casos as $caso)
                                    <tr>
                                        <td class="ps-4 fw-medium">{{ $caso->rut_estudiante }}</td>
                                        <td>{{ $caso->nombre_estudiante }}</td>
                                        <td>{{ $caso->carrera }}</td>
                                        <td>
                                            {{-- LÓGICA DE BADGES ACTUALIZADA --}}
                                            @php $estadoLimpio = strtolower(trim($caso->estado)); @endphp
                                            <span class="badge rounded-pill fs-6
                                                @if($estadoLimpio == 'pendiente de validacion' || $estadoLimpio == 'en revision') 
                                                    bg-warning-subtle text-warning-emphasis border border-warning-subtle
                                                @elseif($estadoLimpio == 'reevaluacion') 
                                                    bg-danger-subtle text-danger-emphasis border border-danger-subtle
                                                @else 
                                                    bg-info-subtle text-info-emphasis border border-info-subtle 
                                                @endif">
                                                
                                                @if($estadoLimpio == 'pendiente de validacion') 
                                                    ⏳ Pendiente de Validación
                                                @elseif($estadoLimpio == 'en revision') 
                                                    ⏳ En Revisión (Legacy)
                                                @elseif($estadoLimpio == 'reevaluacion') 
                                                    ↩️ En Corrección (CTP)
                                                @else 
                                                    {{ ucfirst($caso->estado) }} 
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ $caso->created_at->translatedFormat('d M Y, H:i') }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('director.casos.show', $caso) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye-fill me-1"></i> Revisar / Validar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center p-4">
                                            <span class="text-muted">No hay casos pendientes de validación en este momento.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($casos->hasPages())
                    <div class="card-footer bg-body-tertiary">
                        {{ $casos->appends(request()->query())->links() }}
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</x-app-layout>