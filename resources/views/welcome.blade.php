<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Gestión Inclusiva') }}</title>

        <!-- Fuentes -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

        <!-- Estilos Bootstrap & Iconos -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <style>
            /* --- ESTILOS GLOBALES --- */
            body {
                font-family: 'Nunito', sans-serif;
                background-color: #f8f9fa;
                overflow-x: hidden;
            }

            /* --- NAVBAR FLOTANTE --- */
            .navbar {
                background-color: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(10px);
                box-shadow: 0 4px 30px rgba(0,0,0,0.03);
            }

            /* --- HERO CAROUSEL --- */
            .hero-carousel {
                position: relative;
                height: 85vh;
                overflow: hidden;
            }

            .carousel-item { height: 85vh; }

            .carousel-item img {
                object-fit: cover;
                height: 100%;
                width: 100%;
                animation: zoomEffect 20s infinite alternate; 
            }

            @keyframes zoomEffect {
                0% { transform: scale(1); }
                100% { transform: scale(1.1); }
            }

            .hero-overlay {
                position: absolute;
                top: 0; left: 0; width: 100%; height: 100%;
                background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
                z-index: 1;
            }

            .carousel-caption {
                z-index: 2;
                bottom: 30%;
                text-shadow: 0 2px 10px rgba(0,0,0,0.5);
            }

            /* --- TARJETAS FLOTANTES --- */
            .feature-card {
                transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease;
                border: none;
                border-radius: 1rem;
            }
            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
            }

            .icon-wrapper {
                width: 70px;
                height: 70px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                margin-bottom: 1.5rem;
                font-size: 2rem;
            }

            /* --- SECCIÓN LEY INCLUSIÓN --- */
            .legal-section {
                background-color: #ffffff;
                border-top: 1px solid #e9ecef;
            }
            .accordion-button:not(.collapsed) {
                background-color: #e7f1ff;
                color: #0c63e4;
            }
            .accordion-button:focus {
                box-shadow: none;
                border-color: rgba(0,0,0,.125);
            }
        </style>
    </head>
    <body>

        {{-- NAVBAR --}}
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3">
            <div class="container px-4">
                <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                    <div class="d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 42px; height: 42px; border-radius: 14px; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); color: white;">
                        <i class="bi bi-infinity fs-4"></i>
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <span style="font-weight: 800; font-size: 1.25rem; line-height: 1; color: #344767;">
                            Gestión Inclusiva
                        </span>
                        <span class="fw-bold text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 1.5px; margin-top: 2px;">
                            Plataforma Académica
                        </span>
                    </div>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-2">
                        @if (Route::has('login'))
                            @auth
                                @php
                                    $rol = Auth::user()?->rol?->nombre_rol;
                                    $route = match($rol) {
                                        'Administrador' => route('admin.dashboard'),
                                        'Asesoría Pedagógica' => route('asesoria.dashboard'),
                                        'Director de Carrera' => route('director.dashboard'),
                                        'Docente' => route('docente.dashboard'),
                                        'Estudiante' => route('estudiante.dashboard'),
                                        'Coordinador Técnico Pedagógico' => route('ctp.dashboard'),
                                        'Encargada Inclusión' => route('encargada.dashboard'),
                                        default => url('/dashboard'),
                                    };
                                @endphp
                                <li class="nav-item">
                                    <a href="{{ $route }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                        Ir a mi Dashboard <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link fw-bold text-dark px-3">
                                        Iniciar Sesión
                                    </a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold border-2">
                                            Registrarse
                                        </a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        {{-- HERO SECTION --}}
        <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators mb-5">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>
            
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="hero-overlay"></div>
                    <img src="{{ asset('images/imagen1carrusel.jpg') }}" alt="Gestión Académica">
                    <div class="carousel-caption">
                        <span class="badge bg-primary bg-opacity-75 mb-3 px-3 py-2 rounded-pill fw-bold text-uppercase spacing-2">Bienvenidos</span>
                        <h1 class="display-3 fw-bolder mb-3">Gestión Académica Inclusiva</h1>
                        <p class="fs-4 fw-light opacity-75 mb-4 d-none d-md-block">
                            Una plataforma integral diseñada para centralizar y optimizar el proceso de ajustes académicos razonables.
                        </p>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg mt-3 hover-lift">
                                Ingresar al Portal
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="hero-overlay"></div>
                    <img src="{{ asset('images/imagen2carrusel.jpg') }}" alt="Trazabilidad">
                    <div class="carousel-caption">
                        <span class="badge bg-info bg-opacity-75 mb-3 px-3 py-2 rounded-pill fw-bold text-uppercase spacing-2 text-dark">Procesos Claros</span>
                        <h1 class="display-3 fw-bolder mb-3">Trazabilidad Completa</h1>
                        <p class="fs-4 fw-light opacity-75 mb-4 d-none d-md-block">
                            Seguimiento en tiempo real desde la solicitud de Asesoría hasta la validación del Director.
                        </p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="hero-overlay"></div>
                    <img src="{{ asset('images/imagen3carrusel.jpg') }}" alt="Conexión">
                    <div class="carousel-caption">
                        <span class="badge bg-success bg-opacity-75 mb-3 px-3 py-2 rounded-pill fw-bold text-uppercase spacing-2">Colaboración</span>
                        <h1 class="display-3 fw-bolder mb-3">Conectando Roles</h1>
                        <p class="fs-4 fw-light opacity-75 mb-4 d-none d-md-block">
                            Facilitamos la comunicación efectiva entre Docentes, Estudiantes y el equipo de Inclusión.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FEATURES SECTION --}}
        <div class="container py-5" style="margin-top: -80px; position: relative; z-index: 10;">
            <div class="row g-4 justify-content-center">
                
                {{-- Feature 1: Coordinación --}}
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow p-4 text-center">
                        <div class="card-body">
                            <div class="icon-wrapper bg-primary bg-opacity-10 text-primary mx-auto">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Coordinación Académica</h4>
                            <p class="text-muted">Facilita la interacción fluida entre Docentes, Jefes de Carrera y especialistas en un flujo de trabajo unificado.</p>
                        </div>
                    </div>
                </div>

                {{-- Feature 2: Procesos de Revisión --}}
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow p-4 text-center">
                        <div class="card-body">
                            <div class="icon-wrapper bg-success bg-opacity-10 text-success mx-auto">
                                <i class="bi bi-clipboard-check-fill"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Flujo de Revisión</h4>
                            <p class="text-muted">Cada ajuste razonable pasa por etapas formales de evaluación y aprobación para garantizar la calidad académica.</p>
                        </div>
                    </div>
                </div>

                {{-- Feature 3: Monitoreo --}}
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow p-4 text-center">
                        <div class="card-body">
                            <div class="icon-wrapper bg-info bg-opacity-10 text-info mx-auto">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Seguimiento Activo</h4>
                            <p class="text-muted">Monitoreo constante del estado de cada solicitud, permitiendo una gestión oportuna y transparente.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- NUEVA SECCIÓN: LEY DE INCLUSIÓN (Interactive) --}}
        {{-- ====================================================== --}}
        <section class="legal-section py-5 my-5">
            <div class="container py-4">
                <div class="row align-items-center">
                    
                    {{-- Columna Texto Intro --}}
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-2">Marco Normativo</span>
                        <h2 class="fw-bold display-6 mb-3">¿Qué son los Ajustes Razonables?</h2>
                        <p class="text-muted fs-5 mb-4">
                            Son apoyos o medidas que permiten al estudiante participar y aprender dentro del contexto educativo, sin alterar los criterios de evaluación.
                        </p>
                        <div class="d-flex align-items-center gap-3 text-secondary">
                            <i class="bi bi-bank fs-1"></i>
                            <div>
                                <small class="d-block fw-bold text-uppercase">Normativa Vigente</small>
                                <span>Ley N° 20.422 &bull; Convención CDPD</span>
                            </div>
                        </div>
                    </div>

                    {{-- Columna Acordeón Interactivo --}}
                    <div class="col-lg-7">
                        <div class="accordion shadow-sm rounded-3 overflow-hidden" id="accordionLegal">
                            
                            {{-- Item 1: Definición --}}
                            <div class="accordion-item border-0 border-bottom">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        <i class="bi bi-info-circle-fill me-2 text-primary"></i> Definición y Objetivo Legal
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionLegal">
                                    <div class="accordion-body text-muted">
                                        <p class="mb-2">
                                            De acuerdo al <strong>Artículo 8 de la Ley N° 20.422</strong>, se entenderá por Ajustes Razonables a las modificaciones y adaptaciones necesarias y adecuadas que no impongan una carga desproporcionada o indebida, cuando se requieran en un caso particular.
                                        </p>
                                        <p class="mb-0">
                                            Su objetivo fundamental es <strong>garantizar a las personas con discapacidad el goce o ejercicio, en igualdad de condiciones con las demás, de todos los derechos humanos y libertades fundamentales</strong>. Esta definición está alineada con la Convención Internacional sobre los Derechos de las Personas con Discapacidad (CDCP 2006).
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Item 2: Relevancia --}}
                            <div class="accordion-item border-0 border-bottom">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <i class="bi bi-stars me-2 text-warning"></i> Relevancia en la Práctica
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionLegal">
                                    <div class="accordion-body text-muted">
                                        Se determinan en base a las <strong>características individuales</strong> de las y los estudiantes y se validan en conjunto con ellos. En consecuencia, disponen de mejores oportunidades de aprendizaje para el desarrollo de competencias, impactando el éxito en la educación superior.
                                    </div>
                                </div>
                            </div>

                            {{-- Item 3: Alcance --}}
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <i class="bi bi-shield-lock-fill me-2 text-success"></i> Alcance Académico
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionLegal">
                                    <div class="accordion-body text-muted">
                                        <p class="mb-0">
                                            Son herramientas diseñadas para nivelar la cancha. Permiten que el estudiante participe en clases y rinda sus evaluaciones en igualdad de condiciones, demostrando lo que sabe sin ventajas ni desventajas injustas.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- INFORMACIÓN ADICIONAL ("Chamuyo" Institucional) --}}
                <div class="row mt-5 pt-4">
                    <div class="col-12">
                        <div class="bg-light bg-opacity-50 rounded-4 p-4 border border-light shadow-sm">
                            <h4 class="fw-bold text-dark mb-3"><i class="bi bi-bookmark-star-fill text-warning me-2"></i>Compromiso Institucional y Legal</h4>
                            <p class="text-muted">
                                La implementación de estos ajustes no es solo un acto administrativo, sino el cumplimiento de un mandato legal y ético. La <strong>Ley 20.422</strong>, en concordancia con tratados internacionales ratificados por Chile, establece que las instituciones de educación superior deben contar con mecanismos que aseguren el acceso a la información y al conocimiento.
                            </p>
                            <p class="text-muted">
                                Esto implica la eliminación de barreras del entorno físico y social, promoviendo la <strong>autonomía</strong> y la <strong>vida independiente</strong>. Los ajustes razonables son, por tanto, medidas de acción positiva destinadas a compensar las desventajas que el entorno impone a las personas en situación de discapacidad, permitiéndoles demostrar sus competencias en igualdad de condiciones.
                            </p>
                            <hr class="my-4 opacity-10">
                            <div class="d-flex flex-wrap gap-4 justify-content-center text-center">
                                <div>
                                    <h6 class="fw-bold text-secondary mb-1">Ley N° 20.422</h6>
                                    <small class="text-muted">Igualdad de Oportunidades</small>
                                </div>
                                <div class="vr opacity-25 d-none d-md-block"></div>
                                <div>
                                    <h6 class="fw-bold text-secondary mb-1">Decreto N° 50</h6>
                                    <small class="text-muted">Accesibilidad Universal</small>
                                </div>
                                <div class="vr opacity-25 d-none d-md-block"></div>
                                <div>
                                    <h6 class="fw-bold text-secondary mb-1">Convención ONU</h6>
                                    <small class="text-muted">Derechos Personas con Discapacidad</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- FOOTER --}}
        <footer class="py-5 bg-white border-top">
            <div class="container text-center">
                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-dark text-white" style="width: 30px; height: 30px;">
                        <i class="bi bi-infinity small"></i>
                    </div>
                    <span class="fw-bold text-dark">Gestión Inclusiva</span>
                </div>
                <p class="text-muted small mb-0">&copy; {{ date('Y') }} Sistema de Gestión Académica Inclusiva. Todos los derechos reservados.</p>
            </div>
        </footer>

        <!-- Scripts Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>