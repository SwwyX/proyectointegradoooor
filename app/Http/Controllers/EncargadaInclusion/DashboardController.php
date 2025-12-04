<?php

namespace App\Http\Controllers\EncargadaInclusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caso;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // ID del usuario actual (Encargada)
        $myId = Auth::id();

        // 1. Total Histórico (Aquí mantenemos el filtro para ver TUS casos creados)
        $total = Caso::where('asesoria_id', $myId)->count();

        // 2. En Gestión CTP (CORREGIDO)
        // Quitamos "where('asesoria_id', $myId)" para que funcione igual que el Dashboard de CTP.
        // Ahora contará cualquier caso que esté en estado inicial, independiente de quién lo creó.
        $gestionCtp = Caso::whereIn('estado', ['En Gestion CTP', 'Sin Revision', 'En Gestión CTP'])
                          ->count();

        // 3. En Proceso / Pendientes de Validación
        // Mantenemos el filtro aquí para que sigas tus propios casos en flujo
        $proceso = Caso::where('asesoria_id', $myId)
                       ->whereIn('estado', ['En Revision', 'Reevaluacion', 'Pendiente de Validacion'])
                       ->count();

        $stats = [
            'total' => $total,
            'gestion_ctp' => $gestionCtp,
            'proceso' => $proceso,
        ];

        return view('encargada-inclusion.dashboard', compact('stats'));
    }
}