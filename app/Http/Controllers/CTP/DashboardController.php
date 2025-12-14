<?php

namespace App\Http\Controllers\CTP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\EstadisticasService;
use App\Models\Caso; 
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $statsService;

    public function __construct(EstadisticasService $service)
    {
        $this->statsService = $service;
    }

    public function __invoke(Request $request): View
    {
        // 1. Estadísticas Generales
        $stats = $this->statsService->getEstadisticasAvanzadas([]);

        // 2. Mi Gestión Personal (KPIs)
        $myId = Auth::id();
        
        $miGestion = [
            // Casos que tengo en mi bandeja ahora mismo
            'por_atender' => Caso::where(function($q) {
                                    $q->where('estado', 'En Gestion CTP')
                                      ->orWhere('estado', 'Reevaluacion');
                                 })->count(),
                                 
            // Casos que TIENEN mi firma (mi ID) pero ya no están en mi bandeja
            // (Significa que ya los envié al director)
            'gestionados' => Caso::where('ctp_id', $myId)
                                 ->whereNotIn('estado', ['En Gestion CTP', 'Reevaluacion']) // <--- CORRECCIÓN AQUÍ
                                 ->count()
        ];

        // 3. RANKING REAL DE AJUSTES RAZONABLES (Top 5)
        $todosLosAjustes = Caso::whereNotNull('ajustes_ctp')->pluck('ajustes_ctp');
        
        $conteo = [];
        foreach ($todosLosAjustes as $lista) {
            // Decodificar si viene como string JSON, o usar directo si es array cast
            $items = is_string($lista) ? json_decode($lista, true) : $lista;
            
            if (is_array($items)) {
                foreach ($items as $ajuste) {
                    $nombre = trim($ajuste);
                    if ($nombre !== '') {
                        if (!isset($conteo[$nombre])) {
                            $conteo[$nombre] = 0;
                        }
                        $conteo[$nombre]++;
                    }
                }
            }
        }

        arsort($conteo);
        $topAjustes = array_slice($conteo, 0, 5);

        $rankingAjustes = [
            'labels' => array_keys($topAjustes), 
            'data' => array_values($topAjustes)  
        ];

        return view('ctp.dashboard', compact('stats', 'miGestion', 'rankingAjustes'));
    }
}