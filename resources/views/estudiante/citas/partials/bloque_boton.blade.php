@if($bloque['estado'] == 'Disponible')
    <button type="button" 
            class="btn btn-outline-primary border-2 w-100 py-2 d-flex justify-content-between align-items-center px-3 shadow-sm hover-lift"
            data-bs-toggle="modal" 
            data-bs-target="#modalConfirmar{{ str_replace(':','',$bloque['hora']) }}">
        <span class="fw-bold">{{ $bloque['hora'] }}</span>
        <span class="badge bg-primary rounded-pill">Disponible</span>
    </button>
@elseif($bloque['estado'] == 'Ocupado')
    <button class="btn btn-light border-0 w-100 py-2 d-flex justify-content-between align-items-center px-3 text-muted" disabled style="background-color: #f8f9fa;">
        <span class="fw-medium text-decoration-line-through">{{ $bloque['hora'] }}</span>
        <span class="badge bg-secondary bg-opacity-25 text-secondary rounded-pill">Ocupado</span>
    </button>
@endif