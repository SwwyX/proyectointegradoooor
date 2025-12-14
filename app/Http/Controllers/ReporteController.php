<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caso;
use Barryvdh\DomPDF\Facade\Pdf;       // Para generar PDF
use Maatwebsite\Excel\Facades\Excel;  // Para generar Excel bonito (.xlsx)
use App\Exports\CasosExport;          // Tu clase de exportación personalizada

class ReporteController extends Controller
{
    /**
     * Generar Informe PDF Individual de un Caso
     * Muestra el detalle completo de un estudiante en formato PDF.
     */
    public function generarPdfCaso(Caso $caso)
    {
        // 1. Cargamos todas las relaciones necesarias para el reporte.
        // IMPORTANTE: Agregamos 'seguimientos.docente' para incluir la bitácora del profesor.
        $caso->load('estudiante', 'asesor', 'ctp', 'director', 'seguimientos.docente');

        // 2. Preparamos las variables que se enviarán a la vista del PDF
        $data = [
            'caso' => $caso,
            'fecha_impresion' => now()->format('d/m/Y H:i'),
            // 'logo_path' => public_path('img/logo.png'), // Descomentar si usas logo
        ];

        // 3. Generamos el PDF usando la vista blade correspondiente
        $pdf = Pdf::loadView('reportes.caso_detalle_pdf', $data);
        
        // Configuración de papel (Carta, Vertical)
        $pdf->setPaper('letter', 'portrait');

        // Stream para visualizar en el navegador en lugar de descargar directo
        return $pdf->stream('Informe_Caso_' . $caso->rut_estudiante . '.pdf');
    }

    /**
     * Exportar Listado General a Excel (.xlsx)
     * Utiliza la clase App\Exports\CasosExport para dar formato profesional,
     * auto-ajustar columnas y aplicar los filtros activos.
     */
    public function exportarExcel(Request $request)
    {
        // 1. Generamos un nombre de archivo único con fecha y hora
        $nombreArchivo = 'reporte_casos_' . now()->format('dmY_His') . '.xlsx';

        // 2. Llamamos a la clase de Exportación pasándole todos los filtros ($request->all())
        // NOTA: La carga de relaciones (seguimientos) para el Excel ocurre DENTRO de CasosExport.php
        return Excel::download(new CasosExport($request->all()), $nombreArchivo);
    }
}