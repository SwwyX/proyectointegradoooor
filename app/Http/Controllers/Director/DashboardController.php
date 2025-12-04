<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Caso; 

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        // 1. Contar pendientes (Incluimos 'Sin Revision' y 'En Revision')
        $pendientesCount = Caso::whereIn('estado', ['Pendiente de Validacion','En Revision', 'Sin Revision'])->count();
        
        // 2. Contar FINALIZADOS (Esto engloba todo lo que ya procesó el director)
        $finalizadosCount = Caso::where('estado', 'Finalizado')->count();

        // 3. Agrupar estadisticas (Solo estas dos métricas importan ahora)
        $stats = [
            'pendientes' => $pendientesCount,
            'finalizados' => $finalizadosCount,
        ];

        return view('director.dashboard', compact('stats'));
    }
}