@props(['icon', 'color', 'title', 'description', 'route', 'buttonText', 'count' => null])

<div class="col-md-6 col-lg-3">
    <div class="card h-100 rounded-4 hover-lift border-0" 
         {{-- Si es una tarjeta tipo "warning" o "success", le ponemos el borde inferior de color --}}
         @if($color == 'warning') style="border-bottom: 4px solid #ffc107 !important;" @endif
    >
        <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-4">
                {{-- Caja del Icono --}}
                <div class="icon-box bg-{{ $color }} bg-opacity-10 text-{{ $color }}">
                    <i class="bi bi-{{ $icon }} fs-4"></i>
                </div>
                
                {{-- Si pasamos un número (count), mostramos el badge --}}
                @if($count !== null)
                    <span class="badge bg-{{ $color }} @if($color != 'warning') text-white @else text-dark @endif rounded-pill px-3 py-2">
                        {{ $count }}
                    </span>
                @endif
            </div>

            <h4 class="fw-bold fs-5 text-dark">{{ $title }}</h4>
            <p class="text-muted small mb-4 flex-grow-1">{{ $description }}</p>

            {{-- Botón --}}
            <a href="{{ $route }}" class="btn @if($color == 'warning') btn-outline-warning text-dark @else btn-{{ $color }} text-white @endif w-100 rounded-pill fw-medium stretched-link py-2">
                {{ $buttonText }}
                @if($color != 'warning') <i class="bi bi-arrow-right-circle ms-1"></i> @endif
            </a>
        </div>
    </div>
</div>