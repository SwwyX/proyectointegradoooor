<x-app-layout>
    <div class="container px-4">
        
        {{-- Encabezado --}}
        <div class="mb-4">
            <h2 class="fw-bold text-dark mb-0">
                Casos Pendientes de Redacción de Ajustes
            </h2>
            <p class="text-muted small">
                Casos nuevos creados por Asesoría Pedagógica que requieren su revisión.
            </p>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="mb-4 alert alert-success alert-dismissible fade show rounded-3 border-0 bg-success bg-opacity-10 text-success" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tarjeta Principal --}}
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
                                        {{-- Burbuja de estado con TEXTO ORIGINAL --}}
                                        <span class="badge rounded-pill px-3 py-2 fw-semibold border bg-info-subtle text-info-emphasis border-info-subtle">
                                            {{ ucfirst($caso->estado) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-muted">{{ $caso->created_at->translatedFormat('d M Y, H:i') }}</td>
                                    <td class="pe-4 py-3 text-end">
                                        {{-- Botón Redactar Ajustes --}}
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
                                            <i class="bi bi-check2-all fs-1 opacity-25"></i>
                                        </div>
                                        <p class="mb-0">No hay casos nuevos pendientes de redacción.</p>
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
                    {{ $casos->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>