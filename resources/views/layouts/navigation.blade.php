@php
    // --- Lógica de Rutas (Calculada una sola vez) ---
    $user = Auth::user();
    $userRolName = $user?->rol?->nombre_rol;

    // Determina la ruta del dashboard según el rol
    $navDashboardRoute = match($userRolName) {
        'Administrador' => route('admin.dashboard'),
        'Asesoría Pedagógica' => route('asesoria.dashboard'),
        'Director de Carrera' => route('director.dashboard'),
        'Docente' => route('docente.dashboard'),
        'Estudiante' => route('estudiante.dashboard'),
        'Coordinador Técnico Pedagógico' => route('ctp.dashboard'),
        'Encargada Inclusión' => route('encargada.dashboard'),
        default => url('/'), 
    };

    $isDashboardActive = request()->routeIs('*.dashboard');
    
    // NUEVA LÓGICA: Determinar si estamos en la vista de Analíticas
    $isAnalyticsActive = request()->routeIs('*.analiticas');
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 sticky-top" style="box-shadow: 0 4px 30px rgba(0,0,0,0.03);">
    
    <div class="container px-4"> 

        {{-- MARCA / LOGO --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ $navDashboardRoute }}">
            <div class="d-flex align-items-center justify-content-center shadow-sm" 
                 style="width: 42px; height: 42px; border-radius: 14px; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); color: white;">
                <i class="bi bi-infinity fs-4"></i>
            </div>
            <div class="d-flex flex-column justify-content-center">
                <span style="font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 1.25rem; line-height: 1; color: #344767;">
                    Gestión Inclusiva
                </span>
                <span class="fw-bold text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1.5px; margin-top: 2px;">
                    Plataforma Académica
                </span>
            </div>
        </a>

        {{-- BOTÓN HAMBURGUESA (Móvil) --}}
        <button class="navbar-toggler border-0 shadow-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbarContent" aria-controls="mainNavbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbarContent">

            {{-- MENÚ CENTRAL (Enlaces) --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                
                {{-- ITEM: INICIO --}}
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill fw-medium {{ $isDashboardActive ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : 'text-secondary' }}" 
                       href="{{ $navDashboardRoute }}" 
                       @if($isDashboardActive) aria-current="page" @endif>
                        <i class="bi bi-grid-fill me-1 d-lg-none"></i> {{ __('Inicio') }}
                    </a>
                </li>

                {{-- ========================================================= --}}
                {{-- NUEVO BOTÓN: ANALÍTICAS BI (Solo para roles de gestión)   --}}
                {{-- ========================================================= --}}
                @if(in_array($userRolName, ['Asesoría Pedagógica', 'Director de Carrera', 'Coordinador Técnico Pedagógico', 'Encargada Inclusión']))
                    @php
                        $analyticsRoute = match($userRolName) {
                            'Asesoría Pedagógica' => route('asesoria.analiticas'),
                            'Director de Carrera' => route('director.analiticas'),
                            'Coordinador Técnico Pedagógico' => route('ctp.analiticas'),
                            'Encargada Inclusión' => route('encargada.analiticas'),
                            default => '#'
                        };
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-medium {{ $isAnalyticsActive ? 'active bg-indigo-subtle text-primary fw-bold' : 'text-secondary' }}" 
                           href="{{ $analyticsRoute }}"
                           style="{{ $isAnalyticsActive ? 'background-color: #e0e7ff; color: #4338ca;' : '' }}">
                            <i class="bi bi-bar-chart-fill me-1"></i> Analíticas BI
                        </a>
                    </li>
                @endif

                {{-- ========================================================= --}}
                {{-- MENÚS ESPECÍFICOS POR ROL                                 --}}
                {{-- ========================================================= --}}
                
                {{-- 1. Asesoría --}}
                @if($userRolName == 'Asesoría Pedagógica')
                    @php $isActive = request()->routeIs('casos.*'); @endphp
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-medium {{ $isActive ? 'active bg-teal-subtle text-teal fw-bold' : 'text-secondary' }}" 
                           href="{{ route('casos.index') }}"
                           style="{{ $isActive ? 'color: #008080; background-color: #e6f2f2;' : '' }}">
                            Supervisión Global
                        </a>
                    </li>
                @endif

                {{-- 2. Administrador --}}
                @if($userRolName == 'Administrador')
                    @php $isActive = request()->routeIs('admin.users.*'); @endphp
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-medium {{ $isActive ? 'active bg-dark text-white fw-bold' : 'text-secondary' }}" 
                           href="{{ route('admin.users.index') }}">
                            Gestionar Usuarios
                        </a>
                    </li>
                @endif

                {{-- 3. Director --}}
                @if($userRolName == 'Director de Carrera')
                    @php $isActive = request()->routeIs('director.casos.pendientes'); @endphp
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-medium {{ $isActive ? 'active bg-warning bg-opacity-10 text-warning-emphasis fw-bold' : 'text-secondary' }}" 
                           href="{{ route('director.casos.pendientes') }}">
                            Casos Pendientes
                        </a>
                    </li>
                @endif
                
                {{-- 4. CTP --}}
                @if($userRolName == 'Coordinador Técnico Pedagógico')
                    @php $isActive = request()->routeIs('ctp.casos.*'); @endphp
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-medium {{ $isActive ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : 'text-secondary' }}" 
                           href="{{ route('ctp.casos.index') }}">
                            Bandeja de Casos
                        </a>
                    </li>
                @endif
                
                {{-- 5. Docente --}}
                @if($userRolName == 'Docente')
                    @php $isActive = request()->routeIs('docente.ajustes.pendientes'); @endphp
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-medium {{ $isActive ? 'active bg-success bg-opacity-10 text-success fw-bold' : 'text-secondary' }}" 
                           href="{{ route('docente.ajustes.pendientes') }}">
                            Pendientes de Lectura
                        </a>
                    </li>
                @endif

                {{-- 6. Estudiante --}}
                @if($userRolName == 'Estudiante')
                    @php $isActive = request()->routeIs('estudiante.casos.*'); @endphp
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-medium {{ $isActive ? 'active bg-info bg-opacity-10 text-info-emphasis fw-bold' : 'text-secondary' }}" 
                           href="{{ route('estudiante.casos.index') }}">
                            Mis Solicitudes
                        </a>
                    </li>
                @endif

                {{-- 7. Encargada --}}
                @if($userRolName == 'Encargada Inclusión')
                    @php $isActive = request()->routeIs('encargada.casos.*'); @endphp
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-medium {{ $isActive ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : 'text-secondary' }}" 
                           href="{{ route('encargada.casos.index') }}">
                            Mis Casos
                        </a>
                    </li>
                @endif
                
            </ul>

            {{-- MENÚ DERECHO (Usuario y Fecha) --}}
            <div class="d-flex align-items-center ms-auto">
                <div class="d-none d-lg-block me-4 text-muted small border-end pe-4">
                    <i class="bi bi-calendar4-week me-1"></i> {{ now()->translatedFormat('d \d\e F, Y') }}
                </div>

                {{-- Dropdown de Usuario --}}
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-light rounded-circle border d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                            <span class="fw-bold text-secondary">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div class="d-none d-md-block text-start lh-sm">
                            <div class="fw-bold small">{{ $user->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $userRolName ?? 'Usuario' }}</div>
                        </div>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2" aria-labelledby="userDropdown" style="min-width: 200px;">
                        <li class="d-md-none px-3 py-2 border-bottom mb-2">
                            <div class="fw-bold">{{ $user->name }}</div>
                            <div class="small text-muted">{{ $userRolName }}</div>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-gear me-2 text-secondary"></i> {{ __('Mi Perfil') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-2"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 d-flex align-items-center text-danger" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> {{ __('Cerrar Sesión') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</nav>