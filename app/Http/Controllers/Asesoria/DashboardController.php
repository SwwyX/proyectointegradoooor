<?php

namespace App\Http\Controllers\Asesoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\EstadisticasService;
use App\Models\Caso; 
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $statsService;

    public function __construct(EstadisticasService $service)
    {
        $this->statsService = $service;
    }

    public function __invoke(Request $request): View
    {
        // 1. INTELIGENCIA DE NEGOCIOS (BI)
        $filtros = [
            'carrera' => $request->input('carrera'),
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_fin' => $request->input('fecha_fin'),
        ];
        
        // Obtenemos estadísticas base del servicio
        $stats = $this->statsService->getEstadisticasAvanzadas($filtros);

        // --- CORRECCIÓN DE TASA DE RESOLUCIÓN ---
        // Calculamos manualmente la tasa para asegurarnos de que exista en el array
        $totalGlobal = Caso::count();
        $totalCerrados = Caso::whereIn('estado', ['Finalizado', 'Aceptado', 'Rechazado'])->count();
        
        $tasaResolucion = $totalGlobal > 0 ? round(($totalCerrados / $totalGlobal) * 100) : 0;

        // Inyectamos la tasa en ambos lugares posibles donde la vista podría buscarla
        $stats['tasa_resolucion'] = $tasaResolucion;         // Opción A: $stats['tasa_resolucion']
        $stats['kpis']['tasa_resolucion'] = $tasaResolucion; // Opción B: $stats['kpis']['tasa_resolucion']
        // ----------------------------------------

        // 2. FILTROS DE CARRERA (Variable que faltaba antes)
        $carreras = Caso::distinct()->whereNotNull('carrera')->pluck('carrera');
        
        // 3. GESTIÓN OPERATIVA (KPIs)
        $miGestion = [
            'por_atender' => Caso::whereIn('estado', ['Sin Revision', 'En Revision'])->count(),
            'gestionados' => Caso::whereNotIn('estado', ['Sin Revision', 'En Revision'])->count()
        ];

        // 4. ALERTAS OPERATIVAS (Casos antiguos)
        $fechaLimite = Carbon::now()->subDays(7);
        $casosAntiguos = Caso::whereIn('estado', ['En Gestion CTP', 'Sin Revision'])
                             ->where('created_at', '<', $fechaLimite)
                             ->take(5)
                             ->get();

        // 5. RANKING DE AJUSTES
        $todosLosAjustes = Caso::whereNotNull('ajustes_ctp')->pluck('ajustes_ctp');
        $conteo = [];
        foreach ($todosLosAjustes as $lista) {
            $items = is_string($lista) ? json_decode($lista, true) : $lista;
            if (is_array($items)) {
                foreach ($items as $ajuste) {
                    $nombre = trim($ajuste);
                    if ($nombre !== '') {
                        if (!isset($conteo[$nombre])) { $conteo[$nombre] = 0; }
                        $conteo[$nombre]++;
                    }
                }
            }
        }
        arsort($conteo);
        $topAjustes = array_slice($conteo, 0, 5);

        $rankingAjustes = [
            'labels' => !empty($topAjustes) ? array_keys($topAjustes) : ['Sin datos'], 
            'data' => !empty($topAjustes) ? array_values($topAjustes) : [0]
        ];

        // 6. Retornar la vista
        return view('asesoria.dashboard', compact('stats', 'miGestion', 'rankingAjustes', 'casosAntiguos', 'filtros', 'carreras'));
    }
}