<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\EstadisticasService;
use App\Models\Caso;

class AnalyticsController extends Controller
{
    protected $statsService;

    public function __construct(EstadisticasService $service)
    {
        $this->statsService = $service;
    }

    public function __invoke(Request $request): View
    {
        // 1. Filtros (Igual que el dashboard, para mantener coherencia)
        $filtros = [
            'carrera' => $request->input('carrera'),
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_fin' => $request->input('fecha_fin'),
        ];

        // 2. Obtener LA DATA PESADA (Full Analytics)
        $analytics = $this->statsService->getFullAnalytics($filtros);
        
        // 3. Dropdown Carreras
        $carreras = Caso::distinct()->whereNotNull('carrera')->pluck('carrera');

        return view('director.analytics', compact('analytics', 'carreras', 'filtros'));
    }
}