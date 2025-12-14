<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\EstadisticasService;
use App\Models\Caso; // <--- Importante: Agregar el modelo Caso

class DashboardController extends Controller
{
    protected $statsService;

    public function __construct(EstadisticasService $service)
    {
        $this->statsService = $service;
    }

    public function __invoke(Request $request): View
    {
        // 1. Obtener Estadísticas Generales (Gráficos, KPIs globales)
        $filtros = [
            'carrera' => $request->input('carrera'),
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_fin' => $request->input('fecha_fin'),
        ];
        
        $stats = $this->statsService->getEstadisticasAvanzadas($filtros);
        
        // 2. Obtener lista de carreras para el filtro
        $carreras = Caso::distinct()->whereNotNull('carrera')->pluck('carrera');

        // 3. CORRECCIÓN: KPI PERSONALIZADO PARA EL DIRECTOR
        // Sobreescribimos el valor de 'en_revision' solo para la vista del director
        // Queremos que "Pendientes" signifique "Pendientes DE MI FIRMA", no "En proceso general"
        
        $misPendientes = Caso::query();
        
        // Aplicamos el filtro de carrera si existe
        if ($request->filled('carrera')) {
            $misPendientes->where('carrera', $request->input('carrera'));
        }

        // Contamos SOLO los que están esperando al Director
        $stats['kpis']['en_revision'] = $misPendientes->where('estado', 'Pendiente de Validacion')->count();


        return view('director.dashboard', compact('stats', 'carreras', 'filtros'));
    }
}