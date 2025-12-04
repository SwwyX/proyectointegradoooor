<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Estudiante;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = Auth::user();
        
        // Buscamos el perfil de estudiante asociado a este usuario
        $estudiante = Estudiante::where('user_id', $user->id)->first();

        $totalCasos = 0;
        $casosActivos = 0;
        $casosFinalizados = 0;

        if ($estudiante) {
            // Si tiene perfil de estudiante, contamos sus casos
            $totalCasos = $estudiante->casos()->count();
            
            $casosActivos = $estudiante->casos()
                                       ->whereIn('estado', ['En Gestion CTP', 'Pendiente de Validacion', 'Reevaluacion'])
                                       ->count();
                                       
            $casosFinalizados = $estudiante->casos()
                                          ->where('estado', 'Finalizado')
                                          ->count();
        }

        $stats = [
            'total' => $totalCasos,
            'activos' => $casosActivos,
            'finalizados' => $casosFinalizados,
            'tiene_perfil' => (bool) $estudiante // Para saber si mostramos alerta de error
        ];

        return view('estudiante.dashboard', compact('stats'));
    }
}