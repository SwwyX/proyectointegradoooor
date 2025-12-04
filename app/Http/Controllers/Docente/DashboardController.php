<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Caso;
use App\Models\Seccion;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $docenteId = Auth::id();

        // 1. Obtener las secciones del docente con sus asignaturas y alumnos que tengan casos FINALIZADOS
        $seccionesConAlumnos = Seccion::where('docente_id', $docenteId)
            ->with(['asignatura', 'estudiantes' => function($query) {
                // Filtramos estudiantes que tengan al menos un caso finalizado
                $query->whereHas('casos', function($q) {
                    $q->where('estado', 'Finalizado');
                })->with(['casos' => function($q) {
                    // Cargamos solo los casos finalizados
                    $q->where('estado', 'Finalizado')->latest();
                }]);
            }])
            ->get();

        // Calcular estadísticas
        $totalAlumnos = 0;
        $totalCasos = 0;

        foreach ($seccionesConAlumnos as $seccion) {
            $totalAlumnos += $seccion->estudiantes->count();
            foreach ($seccion->estudiantes as $estudiante) {
                $totalCasos += $estudiante->casos->count();
            }
        }

        $stats = [
            'total_alumnos_ajustes' => $totalAlumnos,
            'total_casos' => $totalCasos
        ];

        return view('docente.dashboard', compact('seccionesConAlumnos', 'stats'));
    }
}