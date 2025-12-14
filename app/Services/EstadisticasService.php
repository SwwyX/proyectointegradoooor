<?php

namespace App\Services;

use App\Models\Caso;
use App\Models\Estudiante; // Importante para el gráfico de Cobertura
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EstadisticasService
{
    /**
     * KPIs rápidos para los Dashboards principales.
     */
    public function getEstadisticasAvanzadas(array $filtros = [])
    {
        $query = $this->applyFilters(Caso::query(), $filtros);
        $casos = $query->get();

        // KPIs Generales
        $total = $casos->count();
        
        // Finalizados (Incluyendo históricos Aceptado/Rechazado por si acaso)
        $finalizados = $casos->whereIn('estado', ['Finalizado', 'Aceptado', 'Rechazado'])->count();
        
        // CORRECCIÓN DEL ERROR Y ESTADOS:
        // 1. Cambié el nombre de la variable a $en_revision (con guion bajo)
        // 2. Agregué 'Sin Revision' y 'Reevaluacion' que vi en tus controladores anteriores
        $en_revision = $casos->whereIn('estado', [
            'En Revision', 
            'En Gestion CTP', 
            'Pendiente de Validacion', 
            'Sin Revision', 
            'Reevaluacion'
        ])->count();

        $tasaResolucion = $total > 0 ? round(($finalizados / $total) * 100, 1) : 0;

        // Gráficos Rápidos
        $discapacidades = $casos->pluck('tipo_discapacidad')->flatten()->reject(fn($v) => empty($v))->countBy()->sortDesc()->take(5);
        
        // Ajustes propuestos (Para el dashboard usamos los propuestos como tendencia rápida)
        $ajustesTop = $casos->pluck('ajustes_propuestos')->flatten()->reject(fn($v) => empty($v))->countBy()->sortDesc()->take(5);
        
        $evolucion = $casos->groupBy(fn($v) => Carbon::parse($v->created_at)->format('Y-m'))->map->count();
        $porCarrera = $casos->groupBy('carrera')->map->count()->sortDesc()->take(5);

        return [
            // AHORA SÍ FUNCIONA COMPACT:
            'kpis' => compact('total', 'finalizados', 'en_revision', 'tasaResolucion'),
            'graficos' => [
                'discapacidades' => ['labels' => $discapacidades->keys()->all(), 'data' => $discapacidades->values()->all()],
                'ajustes' => ['labels' => $ajustesTop->keys()->all(), 'data' => $ajustesTop->values()->all()],
                'evolucion' => ['labels' => $evolucion->keys()->all(), 'data' => $evolucion->values()->all()],
                'carreras' => ['labels' => $porCarrera->keys()->all(), 'data' => $porCarrera->values()->all()],
            ]
        ];
    }

    /**
     * Analíticas Profundas "Full Data" (Corregido según tus indicaciones).
     */
    public function getFullAnalytics(array $filtros = [])
    {
        $query = $this->applyFilters(Caso::query(), $filtros);
        $casos = $query->get();

        // 1. PERFIL SOCIOEDUCATIVO (Corregido: Sin Trabajo, PIE renonmbrado)
        $perfil = [
            'Antecedentes PIE (Ens. Media)' => $casos->where('recibio_apoyos_pie', 1)->count(),
            'Con Credencial RND' => $casos->where('credencial_rnd', 1)->count(),
            'Repitencia Previa' => $casos->where('repitio_curso', 1)->count(),
            'Pensión Invalidez' => $casos->where('pension_invalidez', 1)->count(),
            'Estudio Superior Previo' => $casos->where('estudio_previo_superior', 1)->count(),
        ];

        // 2. SITUACIÓN LABORAL (Nuevo Gráfico Separado)
        $laboral = [
            'Trabajador Activo' => $casos->where('trabaja', 1)->count(),
            'Dedicación Exclusiva' => $casos->where('trabaja', 0)->count(),
        ];

        // 3. AJUSTES OFICIALES (Usando ajustes_ctp) - Solo Finalizados
        $casosAprobados = $casos->where('estado', 'Finalizado');
        
        $rankingAjustes = $casosAprobados->pluck('ajustes_ctp') // <--- AHORA SÍ: Datos del CTP
            ->flatten()
            ->reject(fn($v) => empty($v))
            ->countBy()
            ->sortDesc()
            ->take(15); 

        // 4. COBERTURA (Total Universidad vs Casos Activos)
        $totalEstudiantes = Estudiante::count(); // Total en la tabla estudiantes
        $totalConCaso = $casos->unique('estudiante_id')->count(); // Total con caso abierto
        
        // Calculamos el resto (si hay datos inconsistentes evitamos negativos)
        $sinCaso = max(0, $totalEstudiantes - $totalConCaso);

        $cobertura = [
            'Con Ajustes Activos' => $totalConCaso,
            'Sin Programa' => $sinCaso
        ];

        // 5. TIPOS DE DISCAPACIDAD (Traído del dashboard para análisis profundo)
        $discapacidades = $casos->pluck('tipo_discapacidad')
            ->flatten()
            ->reject(fn($v) => empty($v))
            ->countBy()
            ->sortDesc()
            ->take(10);

        // 6. VÍAS DE INGRESO
        $viasIngreso = $casos->groupBy('via_ingreso')->map->count();

        return [
            'total_analizados' => $casos->count(),
            'perfil_estudiante' => [
                'labels' => array_keys($perfil),
                'data' => array_values($perfil)
            ],
            'situacion_laboral' => [ // Nuevo dataset
                'labels' => array_keys($laboral),
                'data' => array_values($laboral)
            ],
            'ajustes_ranking' => [
                'labels' => $rankingAjustes->keys()->all(),
                'data' => $rankingAjustes->values()->all()
            ],
            'cobertura' => [ // Nuevo dataset
                'labels' => array_keys($cobertura),
                'data' => array_values($cobertura)
            ],
            'discapacidades' => [
                'labels' => $discapacidades->keys()->all(),
                'data' => $discapacidades->values()->all()
            ],
            'vias_ingreso' => [
                'labels' => $viasIngreso->keys()->all(),
                'data' => $viasIngreso->values()->all()
            ]
        ];
    }

    // Helper privado
    private function applyFilters($query, $filtros) {
        if (!empty($filtros['carrera'])) {
            $query->where('carrera', $filtros['carrera']);
        }
        if (!empty($filtros['fecha_inicio']) && !empty($filtros['fecha_fin'])) {
            $query->whereBetween('created_at', [$filtros['fecha_inicio'], $filtros['fecha_fin']]);
        }
        return $query;
    }
}