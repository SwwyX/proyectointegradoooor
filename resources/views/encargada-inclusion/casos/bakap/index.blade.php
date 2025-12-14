<x-app-layout>
    {{-- Título integrado --}}
    {{-- Usamos py-5 para un espaciado vertical y bg-body-tertiary que respeta el modo oscuro/claro --}}
    <div class="py-5 bg-body-tertiary">
        {{-- container-lg reemplaza a max-w-7xl mx-auto --}}
        <div class="container-lg">
            <h2 class="fw-semibold fs-2 mb-4">
                Listado de Casos Registrados
            </h2>

            {{-- Contenedor principal --}}
            {{-- Reemplazamos el div genérico con el componente 'card' de Bootstrap --}}
            <div class="card shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">

                    {{-- Mensaje de éxito --}}
                    {{-- Usamos el componente 'alert' de Bootstrap --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Botón Crear Nuevo Caso --}}
                    <div class="mb-4">
                        {{-- Cambiamos a 'btn-primary' y un ícono relleno --}}
                        <a href="{{ route('casos.create') }}"
                           class="btn btn-primary d-inline-flex align-items-center shadow-sm">
                            <i class="bi bi-plus-circle-fill me-2" style="font-size: 1rem; align-self: center;"></i>
                            Registrar Nuevo Caso
                        </a>
                    </div>

                    {{-- Tabla de Casos --}}
                    {{-- 'table-responsive' para overflow-x-auto. 'rounded-3' y 'border' para el contenedor de la tabla. --}}
                    <div class="table-responsive shadow-sm rounded-3 border">
                        {{-- Clases de tabla de Bootstrap: 'table' (base), 'table-hover' (hover), 'align-middle' (centrado vertical) --}}
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            {{-- Cabecera azul para el estilo 'dashboard' --}}
                            <thead class="table-primary text-white">
                                <tr>
                                    {{-- 'p-3' para el padding en lugar de 'py-3 px-6' --}}
                                    <th scope="col" class="p-3 text-uppercase">RUT Estudiante</th>
                                    <th scope="col" class="p-3 text-uppercase">Nombre Estudiante</th>
                                    <th scope="col" class="p-3 text-uppercase">Carrera</th>
                                    <th scope="col" class="p-3 text-uppercase">Estado</th>
                                    <th scope="col" class="p-3 text-uppercase">Fecha Creación</th>
                                    <th scope="col" class="p-3 text-uppercase text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($casos as $caso)
                                    {{-- 'table-hover' se encarga del hover. 'border-b' es implícito en 'table'. --}}
                                    <tr>
                                        <td class="p-3 fw-medium text-nowrap">{{ $caso->rut_estudiante }}</td>
                                        <td class="p-3">{{ $caso->nombre_estudiante }}</td>
                                        <td class="p-3">{{ $caso->carrera }}</td>
                                        <td class="p-3">
                                            {{-- Componente 'badge' de Bootstrap. 'rounded-pill' para bordes redondeados. --}}
                                            {{-- Mantenemos los badges modernos de BS5.3 que se ven muy bien --}}
                                            <span class="badge rounded-pill fs-6
                                                 @if(strtolower(trim($caso->estado)) == 'pendiente') bg-warning-subtle text-warning-emphasis border border-warning-subtle
                                                @elseif(strtolower(trim($caso->estado)) == 'aceptado') bg-success-subtle text-success-emphasis border border-success-subtle
                                                @elseif(strtolower(trim($caso->estado)) == 'rechazado') bg-danger-subtle text-danger-emphasis border border-danger-subtle
                                                @else bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle @endif
                                                
                                            ">
                                                {{ ucfirst($caso->estado) }}
                                            </span>
                                        </td>
                                        <td class="p-3">{{ $caso->created_at->translatedFormat('d M Y, H:i') }}</td>
                                        {{-- Usamos botones sólidos con texto e icono --}}
                                        <td class="p-3 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                {{-- Botón amarillo para "Ver" (como "Ver detalle") --}}
                                                <a href="{{ route('casos.show', $caso) }}" class="btn btn-sm btn-warning text-dark" title="Ver Detalles">
                                                    <i class="bi bi-eye-fill me-1"></i> Ver
                                                </a>
                                                {{-- Botón azul para "Editar" --}}
                                                <a href="{{ route('casos.edit', $caso) }}" class="btn btn-sm btn-primary" title="Editar Caso">
                                                    <i class="bi bi-pencil-fill me-1"></i> Editar
                                                </a>
                                                {{-- 'd-inline' para el formulario --}}
                                                <form action="{{ route('casos.destroy', $caso) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este caso? Es una acción irreversible.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    {{-- Botón rojo para "Eliminar" (como "Anular") --}}
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Caso">
                                                        <i class="bi bi-trash-fill me-1"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- 'text-center' y 'text-body-secondary' (para texto gris) --}}
                                        <td colspan="6" class="p-4 text-center text-body-secondary">
                                            No hay casos registrados todavía. ¡Crea el primero!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

