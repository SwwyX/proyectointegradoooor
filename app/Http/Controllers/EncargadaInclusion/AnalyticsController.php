<?php
namespace App\Http\Controllers\EncargadaInclusion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EstadisticasService;
use App\Models\Caso;

class AnalyticsController extends Controller {
    protected $stats;
    public function __construct(EstadisticasService $s) { $this->stats = $s; }
    public function __invoke(Request $r) {
        $filtros = ['carrera' => $r->carrera, 'fecha_inicio' => $r->fecha_inicio, 'fecha_fin' => $r->fecha_fin];
        $analytics = $this->stats->getFullAnalytics($filtros);
        $carreras = Caso::distinct()->pluck('carrera');
        // Reutilizamos la misma vista 'director.analytics' o creas copias si quieres personalizarlas
        return view('director.analytics', compact('analytics', 'carreras', 'filtros')); 
    }
}