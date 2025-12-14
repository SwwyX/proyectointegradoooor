<x-app-layout>
    {{-- Ya no usamos el <x-slot name="header">, integramos el título --}}

    <div class="py-5">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    
                    <h2 class="fw-semibold fs-2 text-body-emphasis mb-4">
                        Crear Nuevo Usuario
                    </h2>

                    {{-- Contenedor del formulario --}}
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body p-4 p-md-5">

                            <form method="POST" action="{{ route('admin.users.store') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="name" class="form-label fw-medium">Nombre</label>
                                    <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="form-label fw-medium">Correo Electrónico</label>
                                    <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="rol_id" class="form-label fw-medium">Rol del Usuario</label>
                                    <select id="rol_id" name="rol_id" class="form-select @error('rol_id') is-invalid @enderror" required>
                                        <option value="" disabled selected>Selecciona un rol</option>
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                                                {{ $rol->nombre_rol }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('rol_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="mb-4">
                                    <label for="password" class="form-label fw-medium">Contraseña</label>
                                    <input id="password" class="form-control @error('password') is-invalid @enderror"
                                           type="password"
                                           name="password"
                                           required autocomplete="new-password" />
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-medium">Confirmar Contraseña</label>
                                    <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                                           type="password"
                                           name="password_confirmation" required autocomplete="new-password" />
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Botones de acción --}}
                                <div class="d-flex justify-content-end align-items-center mt-4 gap-3">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-person-plus-fill me-2"></i> Registrar Usuario
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>