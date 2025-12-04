<x-app-layout>
    <div class="py-5">
        {{-- Contenedor más estrecho para formularios, centrado --}}
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">

                    <h2 class="fw-semibold fs-2 text-body-emphasis mb-4">
                        Editando Caso #{{ $caso->id }}
                    </h2>

                    {{-- Contenedor del formulario --}}
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body p-4 p-md-5">

                            {{-- No se necesita 'enctype' aquí ya que no subimos archivos en la edición --}}
                            <form method="POST" action="{{ route('casos.update', $caso) }}">
                                @csrf
                                @method('PUT') {{-- MUY IMPORTANTE para la actualización --}}

                                {{-- RUT del Estudiante --}}
                                <div class="mb-4">
                                    <label for="rut_estudiante" class="form-label fw-medium">RUT del Estudiante</label>
                                    {{-- Usamos old() con $caso->rut_estudiante como valor por defecto --}}
                                    <input type="text" class="form-control @error('rut_estudiante') is-invalid @enderror" id="rut_estudiante" name="rut_estudiante" value="{{ old('rut_estudiante', $caso->rut_estudiante) }}" required placeholder="Ej: 12345678-9">
                                    @error('rut_estudiante')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nombre del Estudiante --}}
                                <div class="mb-4">
                                    <label for="nombre_estudiante" class="form-label fw-medium">Nombre Completo del Estudiante</label>
                                    <input type="text" class="form-control @error('nombre_estudiante') is-invalid @enderror" id="nombre_estudiante" name="nombre_estudiante" value="{{ old('nombre_estudiante', $caso->nombre_estudiante) }}" required autofocus placeholder="Ej: Juan Pérez González">
                                    @error('nombre_estudiante')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                {{-- Correo del Estudiante --}}
                                <div class="mb-4">
                                    <label for="correo_estudiante" class="form-label fw-medium">Correo Electrónico del Estudiante</label>
                                    <input type="email" class="form-control @error('correo_estudiante') is-invalid @enderror" id="correo_estudiante" name="correo_estudiante" value="{{ old('correo_estudiante', $caso->correo_estudiante) }}" required placeholder="Ej: juan.perez@dominio.cl">
                                    @error('correo_estudiante')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Carrera del Estudiante --}}
                                <div class="mb-4">
                                    <label for="carrera" class="form-label fw-medium">Carrera del Estudiante</label>
                                    <input type="text" class="form-control @error('carrera') is-invalid @enderror" id="carrera" name="carrera" value="{{ old('carrera', $caso->carrera) }}" required placeholder="Ej: Ingeniería en Informática">
                                    @error('carrera')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Descripción del Ajuste Propuesto (Textarea) --}}
                                <div class="mb-4">
                                    <label for="ajustes_propuestos" class="form-label fw-medium">Ajuste(s) Propuesto(s)</label>
                                    {{-- El valor por defecto en un textarea va entre las etiquetas --}}
                                    <textarea class="form-control @error('ajustes_propuestos') is-invalid @enderror" id="ajustes_propuestos" name="ajustes_propuestos" rows="5" required placeholder="Describe detalladamente los ajustes académicos recomendados...">{{ old('ajustes_propuestos', $caso->ajustes_propuestos) }}</textarea>
                                    @error('ajustes_propuestos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Botones de Acción --}}
                                <div class="d-flex justify-content-end align-items-center mt-5 gap-2">
                                    {{-- Botón Cancelar (btn-secondary) --}}
                                    <a href="{{ route('casos.index') }}" class="btn btn-secondary">
                                        Cancelar
                                    </a>
                                    
                                    {{-- Botón Actualizar (btn-primary) --}}
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save-fill me-2"></i>
                                        Actualizar Caso
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
