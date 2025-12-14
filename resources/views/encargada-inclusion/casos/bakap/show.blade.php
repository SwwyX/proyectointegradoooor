<x-app-layout>
    <div class="py-5">
        {{-- Contenedor más estrecho y centrado --}}
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">

                    <h2 class="fw-semibold fs-2 text-body-emphasis mb-4">
                        Revisar Caso #{{ $caso->id }}
                    </h2>

                    <div class="card shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-body-tertiary p-4">
                            <h3 class="fs-4 fw-semibold mb-0">Detalles del Caso</h3>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <div class="row g-5">
                                {{-- Columna de Información del Estudiante --}}
                                <div class="col-md-6">
                                    <h4 class="fs-5 fw-semibold mb-4">Información del Estudiante</h4>
                                    <div class="mb-4">
                                        <label class="small text-muted text-uppercase fw-semibold">RUT Estudiante</label>
                                        <p class="fs-5 mb-0 fw-medium">{{ $caso->rut_estudiante }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="small text-muted text-uppercase fw-semibold">Nombre Completo</label>
                                        <p class="fs-5 mb-0">{{ $caso->nombre_estudiante }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="small text-muted text-uppercase fw-semibold">Correo Electrónico</label>
                                        <p class="fs-5 mb-0">{{ $caso->correo_estudiante }}</p>
                                    </div>
                                    <div>
                                        <label class="small text-muted text-uppercase fw-semibold">Carrera</label>
                                        <p class="fs-5 mb-0">{{ $caso->carrera }}</p>
                                    </div>
                                </div>

                                {{-- Columna de Información del Caso --}}
                                <div class="col-md-6">
                                    <h4 class="fs-5 fw-semibold mb-4">Datos del Caso</h4>
                                    <div class="mb-4">
                                        <label class="small text-muted text-uppercase fw-semibold">Estado Actual</label>
                                        <p class="mb-0">
                                            <span class="badge rounded-pill fs-6
                                                @if(strtolower(trim($caso->estado)) == 'pendiente') bg-warning-subtle text-warning-emphasis border border-warning-subtle
                                                @elseif(strtolower(trim($caso->estado)) == 'aceptado') bg-success-subtle text-success-emphasis border border-success-subtle
                                                @elseif(strtolower(trim($caso->estado)) == 'rechazado') bg-danger-subtle text-danger-emphasis border border-danger-subtle
                                                @else bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle @endif
                                            ">
                                                {{ ucfirst($caso->estado) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="mb-4">
                                        <label class="small text-muted text-uppercase fw-semibold">Fecha de Creación</label>
                                        <p class="fs-5 mb-0">{{ $caso->created_at->translatedFormat('d F, Y \a \l\a\s H:i') }}</p>
                                    </div>
                                    <div>
                                        <label class="small text-muted text-uppercase fw-semibold">Creado por (Asesor/a)</label>
                                        <p class="fs-5 mb-0">{{ $caso->asesor?->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Ajustes Propuestos --}}
                            <div class="border-top pt-4 mt-4">
                                <label class="small text-muted text-uppercase fw-semibold">Ajuste(s) Propuesto(s)</label>
                                <div class="p-3 bg-body-tertiary rounded-3 mt-2" style="white-space: pre-wrap;">{{ $caso->ajustes_propuestos }}</div>
                            </div>
                            
                            {{-- ====================================================== --}}
                            {{-- ¡NUEVA SECCIÓN DE DOCUMENTOS! --}}
                            {{-- ====================================================== --}}
                            <div class="border-top pt-4 mt-4">
                                <label class="small text-muted text-uppercase fw-semibold">Documentos Adjuntos</label>
                                <div class="list-group mt-2">
                                    {{-- Asumimos que 'documentos' es la relación y 'ruta'/'nombre_original' son columnas --}}
                                    @forelse ($caso->documentos as $documento)
                                        <a href="{{ asset('storage/' . $documento->ruta) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <span>
                                                <i class="bi bi-file-earmark-text-fill me-2"></i>
                                                {{ $documento->nombre_original ?? 'Ver Documento' }}
                                            </span>
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @empty
                                        <div class="list-group-item">
                                            <span class="text-muted">No se adjuntaron documentos para este caso.</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            {{-- ====================================================== --}}
                            {{-- FIN DE LA NUEVA SECCIÓN --}}
                            {{-- ====================================================== --}}

                        </div>
                    </div>

                    {{-- Tarjeta de Formulario de Decisión --}}
                    <div class="card shadow-sm rounded-3">
                        <div class="card-header bg-body-tertiary p-4">
                            <h3 class="fs-4 fw-semibold mb-0">Decisión del Caso</h3>
                        </div>
                        <div class="card-body p-4 p-md-5">

                            {{-- 
                              Comprobación robusta del estado (ignora mayúsculas/espacios)
                              Si el estado NO es 'pendiente', muestra la decisión ya tomada.
                            --}}
                            @if(strtolower(trim($caso->estado)) == 'pendiente')
                                
                                {{-- SI ESTÁ PENDIENTE: Muestra el formulario de decisión --}}
                                <form method="POST" action="{{ route('director.casos.validar', $caso) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="motivo_decision" class="form-label fw-medium">Comentarios / Razón de la Decisión</label>
                                        <textarea class="form-control @error('motivo_decision') is-invalid @enderror" id="motivo_decision" name="motivo_decision" rows="4" placeholder="Añada un comentario para justificar su decisión (opcional para 'Aceptado', recomendado para 'Rechazado' o 'En Revisión')">{{ old('motivo_decision') }}</textarea>
                                        @error('motivo_decision')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <p class="fw-medium mt-4">Tomar Decisión:</p>
                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                        {{-- Botón En Revisión (vuelve a Pendiente) --}}
                                        <button type="submit" name="decision" value="En Revisión" classG="btn btn-warning">
                                            <i class="bi bi-clock-history me-2"></i> Dejar "En Revisión"
                                        </button>
                                        {{-- Botón Rechazar --}}
                                        <button type="submit" name="decision" value="Rechazado" class="btn btn-danger">
                                            <i class="bi bi-x-circle-fill me-2"></i> Rechazar Caso
                                        </button>
                                        {{-- Botón Aceptar --}}
                                        <button type="submit" name="decision" value="Aceptado" class="btn btn-success">
                                            <i class="bi bi-check-circle-fill me-2"></i> Aceptar Caso
                                        </button>
                                    </div>
                                    @error('decision')
                                        <div class="text-danger small mt-2 text-end">{{ $message }}</div>
                                    @enderror
                                </form>

                            @else

                                {{-- SI YA NO ESTÁ PENDIENTE: Muestra la decisión tomada --}}
                                <div class="mb-3">
                                    <label class="small text-muted text-uppercase fw-semibold">Decisión Tomada por</label>
                                    <p class="fs-5 mb-0">{{ $caso->director?->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="small text-muted text-uppercase fw-semibold">Comentarios / Razón</label>
                                    <div class="p-3 bg-body-tertiary rounded-3 mt-2" style="white-space: pre-wrap;">{{ $caso->motivo_decision ?? '(Sin comentarios)' }}</div>
                                </div>
                                <div class="text-end mt-4">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left-circle me-2"></i> Volver
                                    </a>
                                </div>

                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>