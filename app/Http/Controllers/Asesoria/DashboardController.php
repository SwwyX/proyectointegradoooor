<?php

namespace App\Http\Controllers\Asesoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Caso;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        // 1. Total de casos en el sistema
        $totalSistema = Caso::count();

        // 2. Casos en cancha del CTP
        // CORRECCIÓN: Agregué 'En Gestion CTP' al array.
        // Ahora busca: El estado nuevo, el antiguo (por si acaso) y los que están en corrección.
        $enGestionCTP = Caso::whereIn('estado', ['En Gestion CTP', 'Sin Revision', 'Reevaluacion'])->count();
        
        // 3. Casos esperando al Director (Listos para validar)
        $pendientesDirector = Caso::whereIn('estado', ['Pendiente de Validacion', 'En Revision'])->count();

        // 4. Casos Finalizados (Incluimos Aceptado/Rechazado por compatibilidad histórica)
        $cerrados = Caso::whereIn('estado', ['Finalizado', 'Aceptado', 'Rechazado'])->count();

        $stats = [
            'total' => $totalSistema,
            'esperando_ctp' => $enGestionCTP,
            'esperando_director' => $pendientesDirector,
            'cerrados' => $cerrados,
        ];

        // Alerta de casos antiguos (Más de 7 días sin moverse)
        // También actualicé esto para que busque por el estado nuevo o el antiguo
        $fechaLimite = Carbon::now()->subDays(7);
        $casosAntiguos = Caso::whereIn('estado', ['En Gestion CTP', 'Sin Revision'])
                             ->where('created_at', '<', $fechaLimite)
                             ->get();

        return view('asesoria.dashboard', compact('stats', 'casosAntiguos'));
    }
}