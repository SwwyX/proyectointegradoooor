<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- CONTROLADOR API (Buscador) ---
use App\Http\Controllers\Api\EstudianteController;

// Controladores de Autenticación y Perfil
use App\Http\Controllers\ProfileController;

// Controladores de Dashboards
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Asesoria\DashboardController as AsesoriaDashboardController;
use App\Http\Controllers\Director\DashboardController as DirectorDashboardController;
use App\Http\Controllers\Docente\DashboardController as DocenteDashboardController;
use App\Http\Controllers\Estudiante\DashboardController as EstudianteDashboardController;
use App\Http\Controllers\CTP\DashboardController as CTPDashboardController;
use App\Http\Controllers\EncargadaInclusion\DashboardController as EIDashboardController;

// Controladores de Lógica de Negocio
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Director\CasoController as DirectorCasoController;
use App\Http\Controllers\Director\AnalyticsController as DirectorAnalyticsController;
use App\Http\Controllers\Asesoria\AnalyticsController as AsesoriaAnalyticsController;
use App\Http\Controllers\CTP\AnalyticsController as CTPAnalyticsController;
use App\Http\Controllers\EncargadaInclusion\AnalyticsController as EncargadaAnalyticsController;

use App\Http\Controllers\Docente\AjusteController as DocenteAjusteController;
use App\Http\Controllers\Estudiante\CasoController as EstudianteCasoController;
use App\Http\Controllers\CTP\CasoController as CTPCasoController;
use App\Http\Controllers\EncargadaInclusion\CasoController as EICasoController;
use App\Http\Controllers\Asesoria\CasoController as AsesoriaCasoController; // Alias correcto

// --- CONTROLADORES DE CITAS (Alias para evitar conflictos de nombre) ---
use App\Http\Controllers\Estudiante\CitaController; 
use App\Http\Controllers\EncargadaInclusion\CitaController as EncargadaCitaController; 
use App\Http\Controllers\ReporteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirección inicial según Rol
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        $rol = $user->rol->nombre_rol ?? null; 
        $routeName = match($rol) {
            'Administrador' => 'admin.dashboard',
            'Asesoría Pedagógica' => 'asesoria.dashboard',
            'Director de Carrera' => 'director.dashboard',
            'Docente' => 'docente.dashboard',
            'Estudiante' => 'estudiante.dashboard',
            'Coordinador Técnico Pedagógico' => 'ctp.dashboard',
            'Encargada Inclusión' => 'encargada.dashboard',
            default => null, 
        };
        if ($routeName === null) {
            Auth::logout(); 
            request()->session()->invalidate(); 
            request()->session()->regenerateToken(); 
            return redirect('/'); 
        }
        return redirect()->route($routeName);
    }
     return view('welcome');
});

// GRUPO PRINCIPAL: Solo usuarios logueados y verificados
Route::middleware(['auth', 'verified'])->group(function () {

    // --- PERFIL DE USUARIO ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // --- DASHBOARDS ---
    Route::get('/admin/dashboard', AdminDashboardController::class)->name('admin.dashboard');
    Route::get('/asesoria/dashboard', AsesoriaDashboardController::class)->name('asesoria.dashboard');
    Route::get('/director/dashboard', DirectorDashboardController::class)->name('director.dashboard');
    Route::get('/docente/dashboard', DocenteDashboardController::class)->name('docente.dashboard');
    Route::get('/estudiante/dashboard', EstudianteDashboardController::class)->name('estudiante.dashboard');
    Route::get('/ctp/dashboard', CTPDashboardController::class)->name('ctp.dashboard');
    Route::get('/encargada/dashboard', EIDashboardController::class)->name('encargada.dashboard');

    // --- REPORTES Y EXPORTACIONES (ACCESIBLES GLOBALMENTE) ---
    // PDF Individual
    Route::get('/reportes/caso/{caso}/pdf', [ReporteController::class, 'generarPdfCaso'])->name('reportes.caso.pdf');
    
    // Excel General (Esta es la ruta que te faltaba para Asesoría)
    Route::get('/reportes/casos/excel', [ReporteController::class, 'exportarExcel'])->name('reportes.casos.excel');

    // Ruta específica de Director (Mantener por compatibilidad si se usa en vistas de director)
    Route::get('/director/casos/aceptados/export', [ReporteController::class, 'exportarExcel'])->name('director.casos.aceptados.export');

    // --- ADMIN ---
    Route::middleware('role:Administrador')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });

    // --- DIRECTOR DE CARRERA ---
    Route::middleware('role:Director de Carrera')->prefix('director')->name('director.')->group(function () {
        Route::get('/casos/pendientes', [DirectorCasoController::class, 'pendientes'])->name('casos.pendientes');
        Route::get('/casos/aceptados', [DirectorCasoController::class, 'aceptados'])->name('casos.aceptados');
        Route::get('/casos/historial', [DirectorCasoController::class, 'historial'])->name('historial'); 
        Route::get('/casos/todos', [DirectorCasoController::class, 'todos'])->name('casos.todos');
        
        // Rutas Crear Caso Director
        Route::get('/casos/crear', [DirectorCasoController::class, 'create'])->name('casos.create');
        Route::post('/casos', [DirectorCasoController::class, 'store'])->name('casos.store');

        Route::get('/casos/{caso}', [DirectorCasoController::class, 'show'])->name('casos.show');
        Route::post('/casos/{caso}/validar', [DirectorCasoController::class, 'validar'])->name('casos.validar');
        
        // Exportaciones Específicas de Director
        Route::get('/export/aceptados', [DirectorCasoController::class, 'exportAceptados'])->name('casos.aceptados.export');
        Route::get('/export/rechazados', [DirectorCasoController::class, 'exportRechazados'])->name('casos.rechazados.export');
        Route::get('/export/todos', [DirectorCasoController::class, 'exportTodos'])->name('casos.todos.export');

        // --- ANALÍTICAS BI ---
        Route::get('/analiticas', DirectorAnalyticsController::class)->name('analiticas');
    });
    
    // --- DOCENTE ---
    Route::middleware('role:Docente')->prefix('docente')->name('docente.')->group(function () {
        Route::get('/ajustes/pendientes', [DocenteAjusteController::class, 'pendientes'])->name('ajustes.pendientes');
        Route::get('/ajustes/todos', [DocenteAjusteController::class, 'todos'])->name('ajustes.todos');
        Route::get('/ajustes/{caso}', [DocenteAjusteController::class, 'show'])->name('ajustes.show');
        Route::post('/ajustes/{caso}/confirmar', [DocenteAjusteController::class, 'confirmar'])->name('ajustes.confirmar');
        Route::post('/ajustes/{caso}/comentario', [DocenteAjusteController::class, 'storeComentario'])->name('ajustes.comentario');
    });

    // --- ESTUDIANTE ---
    Route::middleware('role:Estudiante')->prefix('estudiante')->name('estudiante.')->group(function () {
        Route::get('/casos', [EstudianteCasoController::class, 'index'])->name('casos.index');
        Route::get('/casos/{caso}', [EstudianteCasoController::class, 'show'])->name('casos.show');
        Route::post('/casos/{caso}/upload', [EstudianteCasoController::class, 'upload'])->name('casos.upload');

        // --- RUTAS DE CITAS ESTUDIANTE ---
        // API para el calendario del estudiante (ver ocupados)
        Route::get('/api/citas/ocupadas', [CitaController::class, 'getEventos'])->name('citas.api');
        
        Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
        Route::get('/citas/agendar', [CitaController::class, 'create'])->name('citas.create');
        Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
        Route::delete('/citas/{cita}', [CitaController::class, 'cancel'])->name('citas.cancel');
    });
    
    // --- CTP ---
    Route::middleware('role:Coordinador Técnico Pedagógico')->prefix('ctp')->name('ctp.')->group(function () {
        Route::get('/casos', [CTPCasoController::class, 'index'])->name('casos.index');
        Route::get('/casos/{caso}/edit', [CTPCasoController::class, 'edit'])->name('casos.edit');
        Route::put('/casos/{caso}', [CTPCasoController::class, 'update'])->name('casos.update');

        // --- ANALÍTICAS BI ---
        Route::get('/analiticas', CTPAnalyticsController::class)->name('analiticas');
    });
    
    // --- ENCARGADA INCLUSIÓN ---
    Route::middleware('role:Encargada Inclusión')->prefix('encargada')->name('encargada.')->group(function () {
        Route::get('/casos', [EICasoController::class, 'index'])->name('casos.index');
        Route::get('/casos/crear', [EICasoController::class, 'create'])->name('casos.create');
        Route::post('/casos', [EICasoController::class, 'store'])->name('casos.store');
        Route::get('/casos/finalizados', [EICasoController::class, 'finalizados'])->name('casos.finalizados');
        Route::get('/casos/{caso}', [EICasoController::class, 'show'])->name('casos.show');
        Route::get('/casos/{caso}/edit', [EICasoController::class, 'edit'])->name('casos.edit');
        Route::put('/casos/{caso}', [EICasoController::class, 'update'])->name('casos.update');
        
        

        // --- ANALÍTICAS BI ---
        Route::get('/analiticas', EncargadaAnalyticsController::class)->name('analiticas');

        // --- GESTIÓN DE CITAS ENCARGADA ---
        Route::get('/agenda', [EncargadaCitaController::class, 'index'])->name('citas.index'); // Calendario Visual
        Route::get('/citas/listado', [EncargadaCitaController::class, 'listado'])->name('citas.listado'); // Tabla Detalle
        
        Route::put('/citas/{cita}', [EncargadaCitaController::class, 'update'])->name('citas.update');
        
        // Rutas API para el Calendario de Gestión (Bloqueos y Eventos)
        Route::get('/api/citas/eventos', [EncargadaCitaController::class, 'getEventos'])->name('citas.api');
        Route::post('/api/citas/bloquear', [EncargadaCitaController::class, 'bloquear'])->name('citas.bloquear');
        Route::delete('/api/citas/desbloquear/{id}', [EncargadaCitaController::class, 'desbloquear'])->name('citas.desbloquear');
    });

    // --- ASESORÍA (Supervisor) ---
    Route::middleware('role:Asesoría Pedagógica')->group(function () {
        // Usamos el alias correcto (AsesoriaCasoController)
        Route::resource('casos', AsesoriaCasoController::class);
        
        // --- ANALÍTICAS BI ---
        Route::get('asesoria/analiticas', AsesoriaAnalyticsController::class)->name('asesoria.analiticas');
    });

    // ==========================================
    // RUTA API AJAX PARA BUSCADOR
    // ==========================================
    Route::get('/api/buscar-estudiante', [EstudianteController::class, 'buscar'])->name('api.buscar.estudiante');

});

require __DIR__.'/auth.php';