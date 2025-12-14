<x-app-layout>
    <div class="container px-4">
        
        {{-- Encabezado --}}
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-0">
                    Gestión de Usuarios
                </h2>
                <p class="text-muted small">Administración de cuentas y roles del sistema.</p>
            </div>
            
            <a href="{{ route('admin.users.create') }}" class="btn btn-dark rounded-pill px-4 shadow-sm fw-medium">
                <i class="bi bi-person-plus-fill me-2"></i>Crear Usuario
            </a>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="mb-4 alert alert-success alert-dismissible fade show rounded-3 border-0 bg-success bg-opacity-10 text-success" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tarjeta Principal --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                        <thead class="table-dark">
                            <tr>
                                {{-- COLUMNA ID --}}
                                <th scope="col" class="ps-4 py-3 text-uppercase small fw-bold">
                                    <a href="{{ route('admin.users.index', ['sort' => 'id', 'direction' => request('sort') == 'id' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-white text-decoration-none d-flex align-items-center gap-1">
                                        ID
                                        @if(request('sort') == 'id')
                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="bi bi-arrow-down-up opacity-50"></i>
                                        @endif
                                    </a>
                                </th>

                                <th scope="col" class="py-3 text-uppercase small fw-bold text-white-50">Nombre</th>
                                <th scope="col" class="py-3 text-uppercase small fw-bold text-white-50">Email</th>
                                <th scope="col" class="py-3 text-uppercase small fw-bold text-white-50">Rol Asignado</th>

                                {{-- COLUMNA FECHA --}}
                                <th scope="col" class="py-3 text-uppercase small fw-bold">
                                    <a href="{{ route('admin.users.index', ['sort' => 'created_at', 'direction' => request('sort') == 'created_at' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-white text-decoration-none d-flex align-items-center gap-1">
                                        Fecha Creación
                                        @if(request('sort') == 'created_at')
                                            <i class="bi bi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="bi bi-arrow-down-up opacity-50"></i>
                                        @endif
                                    </a>
                                </th>

                                <th scope="col" class="pe-4 py-3 text-uppercase small fw-bold text-end text-white-50">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0 bg-white">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="ps-4 py-3 fw-medium text-muted">#{{ $user->id }}</td>
                                    
                                    {{-- Nombre con Avatar --}}
                                    <td class="py-3 fw-bold text-dark">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 border" style="width: 35px; height: 35px;">
                                                <i class="bi bi-person-fill text-secondary"></i>
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 text-muted">{{ $user->email }}</td>
                                    
                                    {{-- LÓGICA DE COLORES CORREGIDA --}}
                                    <td class="py-3">
                                        @php
                                            $rolNombre = $user->rol?->nombre_rol ?? 'Sin Rol';
                                            $rolKey = strtolower($rolNombre); // Convertimos a minúsculas para comparar

                                            // Estilo por defecto (Gris - Para Estudiantes)
                                            $claseBadge = 'bg-secondary-subtle text-secondary-emphasis border-secondary-subtle';
                                            $estiloExtra = '';

                                            // Lógica: Si contiene la palabra clave, aplica el color
                                            if (str_contains($rolKey, 'admin')) {
                                                // NEGRO
                                                $claseBadge = 'bg-dark text-white border-dark';
                                            }
                                            elseif (str_contains($rolKey, 'director')) {
                                                // AZUL
                                                $claseBadge = 'bg-primary-subtle text-primary-emphasis border-primary-subtle';
                                            }
                                            elseif (str_contains($rolKey, 'docente')) {
                                                // VERDE
                                                $claseBadge = 'bg-success-subtle text-success-emphasis border-success-subtle';
                                            }
                                            elseif (str_contains($rolKey, 'encargada') || str_contains($rolKey, 'inclusi')) {
                                                // CYAN / INFO
                                                $claseBadge = 'bg-info-subtle text-info-emphasis border-info-subtle';
                                            }
                                            elseif (str_contains($rolKey, 'ctp') || str_contains($rolKey, 'coordinador')) {
                                                // MORADO (Manual para asegurar que se vea)
                                                $claseBadge = 'border'; 
                                                $estiloExtra = 'background-color: #ff000094; color: #ffffffff; border-color: #ffffff75;';
                                            }
                                        @endphp

                                        <span class="badge rounded-pill px-3 py-2 fw-normal border {{ $claseBadge }}" style="{{ $estiloExtra }}">
                                            {{ $rolNombre }}
                                        </span>
                                    </td>

                                    <td class="py-3 text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                                    
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            {{-- Editar --}}
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-light border rounded-pill text-primary hover-lift" title="Editar">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            
                                            {{-- Eliminar --}}
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Confirma eliminar al usuario {{ $user->name }}? Esta acción no se puede deshacer.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border rounded-pill text-danger hover-lift" title="Eliminar">
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
                                            <i class="bi bi-people fs-1 opacity-25"></i>
                                        </div>
                                        <p class="mb-0">No hay usuarios registrados en el sistema.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer con Paginación --}}
            @if ($users->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    <div class="d-flex justify-content-center">
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>