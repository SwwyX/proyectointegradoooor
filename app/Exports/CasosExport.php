<?php

namespace App\Exports;

use App\Models\Caso;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CasosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $filtros;

    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
    }

    public function query()
    {
        // AQUÍ AGREGAMOS 'seguimientos.docente' PARA CARGAR LOS COMENTARIOS
        $query = Caso::query()->with('estudiante', 'director', 'asesor', 'seguimientos.docente');

        // --- FILTROS ---
        if (!empty($this->filtros['search_rut'])) {
            $busqueda = $this->filtros['search_rut'];
            $query->where(function($q) use ($busqueda) {
                $q->where('rut_estudiante', 'like', "%{$busqueda}%")
                  ->orWhere('nombre_estudiante', 'like', "%{$busqueda}%");
            });
        }

        if (!empty($this->filtros['search_estado'])) {
            $query->where('estado', $this->filtros['search_estado']);
        }

        $dateCol = $this->filtros['date_col'] ?? 'created_at'; 

        if (!empty($this->filtros['search_fecha_inicio'])) {
            $query->whereDate($dateCol, '>=', $this->filtros['search_fecha_inicio']);
        }
        if (!empty($this->filtros['search_fecha_fin'])) {
            $query->whereDate($dateCol, '<=', $this->filtros['search_fecha_fin']);
        }

        return $query->orderBy($dateCol, 'desc');
    }

    public function map($caso): array
    {
        // Formatear ajustes
        $ajustes = '';
        if (is_array($caso->ajustes_propuestos)) {
            $ajustes = implode(" | ", array_filter($caso->ajustes_propuestos));
        } elseif (is_string($caso->ajustes_propuestos)) {
            $ajustes = $caso->ajustes_propuestos;
        }

        // --- NUEVO: FORMATEAR BITÁCORA DOCENTE ---
        $bitacora = '';
        if ($caso->seguimientos->isNotEmpty()) {
            $bitacora = $caso->seguimientos->map(function($seg) {
                // Formato: [14/12/2025 - Juan Perez]: El estudiante cumplió...
                return "[" . $seg->created_at->format('d/m/Y') . " - " . ($seg->docente->name ?? 'Docente') . "]: " . $seg->comentario;
            })->implode("\n"); // Salto de línea dentro de la celda de Excel
        } else {
            $bitacora = 'Sin observaciones registradas.';
        }

        return [
            $caso->id,
            $caso->rut_estudiante,
            $caso->nombre_estudiante,
            $caso->carrera,
            $caso->estudiante->sede ?? 'N/A',
            $caso->estudiante->jornada ?? 'N/A',
            $caso->estado,
            $caso->created_at->format('d/m/Y H:i'),
            $caso->updated_at->format('d/m/Y H:i'),
            $caso->via_ingreso,
            $ajustes,
            $caso->asesor->name ?? 'N/A',
            $caso->director->name ?? 'Pendiente',
            $caso->motivo_decision ?? '',
            $bitacora // <--- NUEVA COLUMNA AL FINAL
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'RUT',
            'Estudiante',
            'Carrera',
            'Sede',
            'Jornada',
            'Estado',
            'Fecha Creación',
            'Fecha Cierre',
            'Vía Ingreso',
            'Ajustes Solicitados',
            'Asesor',
            'Director',
            'Resolución',
            'Bitácora de Seguimiento Docente' // <--- NUEVO ENCABEZADO
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Ajustar texto para la columna de Bitácora (La columna 'O' es la 15)
        $sheet->getStyle('O')->getAlignment()->setWrapText(true);
        
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}