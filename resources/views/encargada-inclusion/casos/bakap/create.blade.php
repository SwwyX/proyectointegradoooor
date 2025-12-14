<x-app-layout>
    <div class="py-5">
        {{-- Contenedor más estrecho para formularios, centrado --}}
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">

                    <h2 class="fw-semibold fs-2 text-body-emphasis mb-4">
                        Registrar Nuevo Caso de Ajuste Académico
                    </h2>

                    {{-- Contenedor del formulario --}}
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body p-4 p-md-5">

                            {{-- IMPORTANTE: Añadimos enctype para subida de archivos --}}
                            <form method="POST" action="{{ route('casos.store') }}" enctype="multipart/form-data">
                                @csrf

                                {{-- RUT del Estudiante --}}
                                {{-- Reemplazamos <x-input-label>, <x-text-input> y <x-input-error> --}}
                                <div class="mb-4">
                                    <label for="rut_estudiante" class="form-label fw-medium">RUT del Estudiante (sin puntos, con guión)</label>
                                    <input type="text" class="form-control @error('rut_estudiante') is-invalid @enderror" id="rut_estudiante" name="rut_estudiante" value="{{ old('rut_estudiante') }}" required placeholder="Ej: 12345678-9">
                                    {{-- Manejo de error de Bootstrap --}}
                                    @error('rut_estudiante')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nombre del Estudiante --}}
                                <div class="mb-4">
                                    <label for="nombre_estudiante" class="form-label fw-medium">Nombre Completo del Estudiante</label>
                                    <input type="text" class="form-control @error('nombre_estudiante') is-invalid @enderror" id="nombre_estudiante" name="nombre_estudiante" value="{{ old('nombre_estudiante') }}" required autofocus placeholder="Ej: Juan Pérez González">
                                    @error('nombre_estudiante')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                {{-- Correo del Estudiante --}}
                                <div class="mb-4">
                                    <label for="correo_estudiante" class="form-label fw-medium">Correo Electrónico del Estudiante</label>
                                    <input type="email" class="form-control @error('correo_estudiante') is-invalid @enderror" id="correo_estudiante" name="correo_estudiante" value="{{ old('correo_estudiante') }}" required placeholder="Ej: juan.perez@dominio.cl">
                                    @error('correo_estudiante')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Carrera del Estudiante --}}
                                <div class="mb-4">
                                    <label for="carrera" class="form-label fw-medium">Carrera del Estudiante</label>
                                    <input type="text" class="form-control @error('carrera') is-invalid @enderror" id="carrera" name="carrera" value="{{ old('carrera') }}" required placeholder="Ej: Ingeniería en Informática">
                                    @error('carrera')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Descripción del Ajuste Propuesto (Textarea) --}}
                                <div class="mb-4">
                                    <label for="ajustes_propuestos" class="form-label fw-medium">Ajuste(s) Propuesto(s)</label>
                                    {{-- Reemplazamos <textarea> de Tailwind con 'form-control' --}}
                                    <textarea class="form-control @error('ajustes_propuestos') is-invalid @enderror" id="ajustes_propuestos" name="ajustes_propuestos" rows="5" required placeholder="Describe detalladamente los ajustes académicos recomendados...">{{ old('ajustes_propuestos') }}</textarea>
                                    @error('ajustes_propuestos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Adjuntar Documentos (File Input) --}}
                                <div class="mb-4">
                                    <label for="documentos" class="form-label fw-medium">Adjuntar Documentos de Respaldo (Opcional, máx 5MB c/u)</label>
                                    {{-- Usamos 'form-control' para 'file' --}}
                                    <input class="form-control @error('documentos') is-invalid @enderror @error('documentos.*') is-invalid @enderror" type="file" id="documentos" name="documentos[]" multiple>
                                    <div class="form-text">PDF, JPG, PNG, DOC, DOCX. Puedes seleccionar varios archivos.</div>
                                    {{-- Manejo de errores para un array de archivos --}}
                                    @error('documentos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('documentos.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Botón de Guardar --}}
                                <div class="d-flex justify-content-end mt-5">
                                    {{-- Reemplazamos el botón de Tailwind con 'btn btn-primary' --}}
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-save-fill me-2"></i>
                                        Guardar Caso y Adjuntos
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
