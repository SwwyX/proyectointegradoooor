<?php

namespace App\Http\Controllers\CTP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Caso;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $ctpId = Auth::id();

        // 1. Casos Nuevos (Por Redactar)
        // Incluimos 'En Gestion CTP' (Nuevo nombre) y 'Sin Revision' (Antiguo/Compatibilidad)
        // Así nos aseguramos de que cuente los 3 casos que acabas de crear.
        $nuevosCasos = Caso::whereIn('estado', ['En Gestion CTP', 'Sin Revision'])->count();

        // 2. Total Gestionados / Enviados al Director
        // Son los casos donde este CTP ya metió mano (tiene su ID) y ya no están en estado inicial.
        $totalGestionados = Caso::where('ctp_id', $ctpId)
                                ->whereNotIn('estado', ['En Gestion CTP', 'Sin Revision'])
                                ->count();

        $stats = [
            'nuevos' => $nuevosCasos,
            'gestionados' => $totalGestionados,
        ];

        return view('ctp.dashboard', compact('stats'));
    }
}