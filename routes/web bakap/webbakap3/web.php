<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// api

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

// Controladores de Lógica
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Director\CasoController as DirectorCasoController;
use App\Http\Controllers\Docente\AjusteController as DocenteAjusteController;
use App\Http\Controllers\Estudiante\CasoController as EstudianteCasoController;
use App\Http\Controllers\CTP\CasoController as CTPCasoController;
use App\Http\Controllers\EncargadaInclusion\CasoController as EICasoController;
use App\Http\Controllers\CasoController; // (Para Asesoría/Supervisión)

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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

Route::middleware(['auth', 'verified'])->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboards
    Route::get('/admin/dashboard', AdminDashboardController::class)->name('admin.dashboard');
    Route::get('/asesoria/dashboard', AsesoriaDashboardController::class)->name('asesoria.dashboard');
    Route::get('/director/dashboard', DirectorDashboardController::class)->name('director.dashboard');
    Route::get('/docente/dashboard', DocenteDashboardController::class)->name('docente.dashboard');
    Route::get('/estudiante/dashboard', EstudianteDashboardController::class)->name('estudiante.dashboard');
    Route::get('/ctp/dashboard', CTPDashboardController::class)->name('ctp.dashboard');
    Route::get('/encargada/dashboard', EIDashboardController::class)->name('encargada.dashboard');

    // Admin
    Route::middleware('role:Administrador')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Director
    Route::middleware('role:Director de Carrera')->prefix('director')->name('director.')->group(function () {
        Route::get('/casos/pendientes', [DirectorCasoController::class, 'pendientes'])->name('casos.pendientes');
        Route::get('/casos/aceptados', [DirectorCasoController::class, 'aceptados'])->name('casos.aceptados');
        Route::get('/casos/historial', [DirectorCasoController::class, 'historial'])->name('historial'); 
        Route::get('/casos/todos', [DirectorCasoController::class, 'todos'])->name('casos.todos');
        Route::get('/casos/{caso}', [DirectorCasoController::class, 'show'])->name('casos.show');
        Route::post('/casos/{caso}/validar', [DirectorCasoController::class, 'validar'])->name('casos.validar');
        Route::get('/export/aceptados', [DirectorCasoController::class, 'exportAceptados'])->name('casos.aceptados.export');
        Route::get('/export/rechazados', [DirectorCasoController::class, 'exportRechazados'])->name('casos.rechazados.export');
        Route::get('/export/todos', [DirectorCasoController::class, 'exportTodos'])->name('casos.todos.export');
    });
    
    // Docente
    Route::middleware('role:Docente')->prefix('docente')->name('docente.')->group(function () {
        Route::get('/ajustes/pendientes', [DocenteAjusteController::class, 'pendientes'])->name('ajustes.pendientes');
        Route::get('/ajustes/todos', [DocenteAjusteController::class, 'todos'])->name('ajustes.todos');
        Route::get('/ajustes/{caso}', [DocenteAjusteController::class, 'show'])->name('ajustes.show');
        Route::post('/ajustes/{caso}/confirmar', [DocenteAjusteController::class, 'confirmar'])->name('ajustes.confirmar');
    });

    // Estudiante
    Route::middleware('role:Estudiante')->prefix('estudiante')->name('estudiante.')->group(function () {
        Route::get('/casos', [EstudianteCasoController::class, 'index'])->name('casos.index');
        Route::get('/casos/{caso}', [EstudianteCasoController::class, 'show'])->name('casos.show');
        Route::post('/casos/{caso}/upload', [EstudianteCasoController::class, 'upload'])->name('casos.upload');
    });
    
    // CTP
    Route::middleware('role:Coordinador Técnico Pedagógico')->prefix('ctp')->name('ctp.')->group(function () {
        Route::get('/casos', [CTPCasoController::class, 'index'])->name('casos.index');
        Route::get('/casos/{caso}/edit', [CTPCasoController::class, 'edit'])->name('casos.edit');
        Route::put('/casos/{caso}', [CTPCasoController::class, 'update'])->name('casos.update');
    });
    
    // --- ENCARGADA INCLUSIÓN ---
    Route::middleware('role:Encargada Inclusión')->prefix('encargada')->name('encargada.')->group(function () {
        Route::get('/casos', [EICasoController::class, 'index'])->name('casos.index');
        Route::get('/casos/crear', [EICasoController::class, 'create'])->name('casos.create');
        Route::post('/casos', [EICasoController::class, 'store'])->name('casos.store');
        Route::get('/casos/{caso}', [EICasoController::class, 'show'])->name('casos.show');
        Route::get('/casos/{caso}/edit', [EICasoController::class, 'edit'])->name('casos.edit');
        Route::put('/casos/{caso}', [EICasoController::class, 'update'])->name('casos.update');
        
        // Rutas para editar
        Route::get('/casos/{caso}/edit', [EICasoController::class, 'edit'])->name('casos.edit');
        Route::put('/casos/{caso}', [EICasoController::class, 'update'])->name('casos.update');
    });

    // Asesoría (Supervisor)
    Route::middleware('role:Asesoría Pedagógica')->group(function () {
        Route::resource('casos', CasoController::class)->only(['index', 'show']);
    });


    Route::get('/api/buscar-estudiante', [EstudianteController::class, 'buscar'])->name('api.buscar.estudiante');

});

require __DIR__.'/auth.php';