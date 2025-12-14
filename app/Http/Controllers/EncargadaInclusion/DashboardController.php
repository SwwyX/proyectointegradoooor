<?php

namespace App\Http\Controllers\EncargadaInclusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\EstadisticasService;
use App\Models\Caso;
use App\Models\Cita; // <--- IMPORTANTE: Importar Modelo Cita
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;   // <--- IMPORTANTE: Importar Carbon

class DashboardController extends Controller
{
    protected $statsService;

    public function __construct(EstadisticasService $service)
    {
        $this->statsService = $service;
    }

    public function __invoke(Request $request): View
    {
        // 1. Estadísticas Globales (BI)
        $filtros = [
            'carrera' => $request->input('carrera'),
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_fin' => $request->input('fecha_fin'),
        ];
        $stats = $this->statsService->getEstadisticasAvanzadas($filtros);
        $carreras = Caso::distinct()->whereNotNull('carrera')->pluck('carrera');

        // 2. Métricas Personales
        $myId = Auth::id();
        $misMetricas = [
            'mis_ingresados' => Caso::where('asesoria_id', $myId)->count(),
            'mis_en_proceso' => Caso::where('asesoria_id', $myId)
                                    ->whereIn('estado', ['En Revision', 'Reevaluacion', 'Pendiente de Validacion'])
                                    ->count()
        ];

        // 3. NUEVO: ALERTA DE CITAS (KPIs de Agenda)
        $citasPendientes = Cita::where('estado', 'Pendiente')->count();
        
        $citasHoy = Cita::where('estado', 'Confirmada')
                        ->whereDate('fecha', Carbon::today())
                        ->count();

        return view('encargada-inclusion.dashboard', compact('stats', 'carreras', 'filtros', 'misMetricas', 'citasPendientes', 'citasHoy'));
    }
}