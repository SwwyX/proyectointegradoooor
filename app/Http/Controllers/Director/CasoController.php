<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caso; 
use App\Models\User; 
use App\Models\Estudiante; // Nuevo import
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\RedirectResponse; 
use Symfony\Component\HttpFoundation\StreamedResponse; 
use App\Notifications\CasoValidadoNotification;
use App\Notifications\CasoCreadoNotification; // Nuevo import
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage; // Nuevo import

class CasoController extends Controller
{
    // ... (Tus métodos pendientes, aceptados, historial, todos, aplicarFiltros se mantienen igual) ...
    public function pendientes(Request $request): View
    {
        $query = Caso::query();
        $query->whereIn('estado', ['Pendiente de Validacion', 'En Revision']);
        $this->aplicarFiltros($query, $request);
        $casos = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('director.casos_pendientes', compact('casos'));
    }

    public function aceptados(Request $request): View
    {
        $query = Caso::query();
        $query->where('estado', 'Finalizado');
        $this->aplicarFiltros($query, $request);
        $casos = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('director.casos_aceptados', compact('casos'));
    }

    public function historial(Request $request): View
    {
        $query = Caso::query();
        $query->whereIn('estado', ['Rechazado', 'Finalizado']); 
        $this->aplicarFiltros($query, $request);
        $casos = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('director.casos_historial', compact('casos'));
    }

    public function todos(Request $request): View
    {
        $query = Caso::query(); 
        $this->aplicarFiltros($query, $request);
        $casos = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('director.casos_todos', compact('casos'));
    }

    private function aplicarFiltros($query, Request $request)
    {
        if ($request->filled('search_rut')) {
            $query->where('rut_estudiante', 'like', '%' . $request->input('search_rut') . '%');
        }
        if ($request->filled('search_fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->input('search_fecha_inicio'));
        }
        if ($request->filled('search_fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->input('search_fecha_fin'));
        }
    }

    public function show(Caso $caso): View
    {
        $caso->load('asesor', 'documentos', 'ctp', 'estudiante');
        return view('director.caso_show', compact('caso'));
    }

    // --- NUEVOS MÉTODOS PARA CREAR CASO DESDE DIRECCIÓN ---

    public function create(): View
    {
        return view('director.casos_create');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. VALIDACIÓN (Misma que Encargada)
        $validatedData = $request->validate([
            'rut_estudiante' => ['required', 'string', 'max:12'], 
            'nombre_estudiante' => 'required', 
            'correo_estudiante' => 'required', 
            'carrera' => 'required',
            'via_ingreso' => 'required|string',
            'tipo_discapacidad' => 'nullable|array',
            'origen_discapacidad' => 'nullable|string',
            'credencial_rnd' => 'nullable', 
            'pension_invalidez' => 'nullable', 
            'certificado_medico' => 'nullable', 
            'tratamiento_farmacologico' => 'nullable|string',
            'acompanamiento_especialista' => 'nullable|string',
            'redes_apoyo' => 'nullable|string',
            'familia' => 'nullable|array',
            'enseñanza_media_modalidad' => 'nullable|string',
            'recibio_apoyos_pie' => 'nullable',
            'detalle_apoyos_pie' => 'nullable|string',
            'repitio_curso' => 'nullable',
            'motivo_repeticion' => 'nullable|string',
            'estudio_previo_superior' => 'nullable',
            'nombre_institucion_anterior' => 'nullable|string',
            'tipo_institucion_anterior' => 'nullable|string',
            'carrera_anterior' => 'nullable|string',
            'motivo_no_termino' => 'nullable|string',
            'trabaja' => 'nullable',
            'empresa' => 'nullable|string',
            'cargo' => 'nullable|string',
            'caracteristicas_intereses' => 'nullable|string',
            'requiere_apoyos' => 'nullable', 
            'ajustes_propuestos' => 'nullable|array', 
            'ajustes_propuestos.*' => 'nullable|string', 
            'foto_perfil' => 'nullable|image|max:5120',
        ]);

        // 2. BUSCAR ESTUDIANTE
        $rutLimpio = $validatedData['rut_estudiante'];
        $estudiante = Estudiante::where('rut', $rutLimpio)->first();

        if (!$estudiante) {
            return back()->withInput()->withErrors(['rut_estudiante' => 'Estudiante no encontrado.']);
        }

        // 3. ACTUALIZAR FOTO
        if ($request->hasFile('foto_perfil')) {
            if ($estudiante->foto_perfil && Storage::disk('public')->exists($estudiante->foto_perfil)) {
                Storage::disk('public')->delete($estudiante->foto_perfil);
            }
            $estudiante->foto_perfil = $request->file('foto_perfil')->store('perfiles', 'public');
            $estudiante->save();
        }

        // 4. CREAR CASO
        $rutLimpioParaCaso = strtoupper(str_replace(['.', '-'], '', $rutLimpio));

        $caso = Caso::create([
            'estudiante_id' => $estudiante->id,
            'rut_estudiante' => $rutLimpioParaCaso,
            'nombre_estudiante' => $validatedData['nombre_estudiante'],
            'correo_estudiante' => $validatedData['correo_estudiante'],
            'carrera' => $validatedData['carrera'],
            
            // Registramos al Director como 'asesoria_id' para que quede registro de quien creó
            'asesoria_id' => Auth::id(),
            
            // ESTADO INICIAL: Va al CTP para que ponga los ajustes técnicos
            'estado' => 'En Gestion CTP', 
            
            'via_ingreso' => $request->input('via_ingreso'),
            'tipo_discapacidad' => $request->input('tipo_discapacidad'),
            'origen_discapacidad' => $request->input('origen_discapacidad'),
            'credencial_rnd' => $request->has('credencial_rnd'),
            'pension_invalidez' => $request->has('pension_invalidez'),
            'certificado_medico' => $request->has('certificado_medico'),
            'tratamiento_farmacologico' => $request->input('tratamiento_farmacologico'),
            'acompanamiento_especialista' => $request->input('acompanamiento_especialista'),
            'redes_apoyo' => $request->input('redes_apoyo'),
            'informacion_familiar' => $request->input('familia'), 
            'enseñanza_media_modalidad' => $request->input('enseñanza_media_modalidad'),
            'recibio_apoyos_pie' => $request->has('recibio_apoyos_pie'),
            'detalle_apoyos_pie' => $request->input('detalle_apoyos_pie'),
            'repitio_curso' => $request->has('repitio_curso'),
            'motivo_repeticion' => $request->input('motivo_repeticion'),
            'estudio_previo_superior' => $request->has('estudio_previo_superior'),
            'nombre_institucion_anterior' => $request->input('nombre_institucion_anterior'),
            'tipo_institucion_anterior' => $request->input('tipo_institucion_anterior'),
            'carrera_anterior' => $request->input('carrera_anterior'),
            'motivo_no_termino' => $request->input('motivo_no_termino'),
            'trabaja' => $request->has('trabaja'),
            'empresa' => $request->input('empresa'),
            'cargo' => $request->input('cargo'),
            'caracteristicas_intereses' => $request->input('caracteristicas_intereses'),
            'requiere_apoyos' => $request->has('requiere_apoyos'),
            'ajustes_propuestos' => $request->input('ajustes_propuestos'),
        ]);

        // 6. NOTIFICACIÓN AL CTP
        try {
            $revisores = User::whereHas('rol', function ($query) {
                $query->where('nombre_rol', 'Coordinador Técnico Pedagógico');
            })->get();
            Notification::send($revisores, new CasoCreadoNotification($caso));
        } catch (\Exception $e) {
            \Log::error('Error notif: ' . $e->getMessage());
        }

        // Redirigimos al historial o pendientes
        return redirect()->route('director.casos.todos')->with('success', 'Caso iniciado exitosamente. Enviado a CTP para definición técnica.');
    }

    // ... (Validar y métodos de exportación se mantienen igual) ...
    public function validar(Request $request, Caso $caso): RedirectResponse
    {
        // 1. VALIDACIÓN
        $request->validate([
            'decisiones' => 'required|array',
            'decisiones.*' => 'required|string|in:Aceptado,Rechazado,Reevaluacion',
            'comentarios' => 'nullable|array', 
            'comentario_global' => 'nullable|string', 
        ]);
        
        $decisiones = $request->input('decisiones');
        $comentarios = $request->input('comentarios', []);
        $comentarioGlobalInput = $request->input('comentario_global'); 
        
        $evaluacionDetallada = [];
        $hayReevaluacion = false;
        
        foreach ($decisiones as $index => $decision) {
            $evaluacionDetallada[$index] = [
                'decision' => $decision,
                'comentario' => $comentarios[$index] ?? null 
            ];

            if ($decision === 'Reevaluacion') $hayReevaluacion = true;
        }

        if ($hayReevaluacion) {
            $estadoGlobal = 'Reevaluacion';
            $mensajeDefecto = 'Se solicitan correcciones en uno o más ajustes propuestos.';
        } else {
            $estadoGlobal = 'Finalizado'; 
            $mensajeDefecto = 'Validación completa. Caso cerrado por Dirección.';
        }

        $motivoGlobal = $comentarioGlobalInput ? $comentarioGlobalInput : $mensajeDefecto;

        $caso->update([
            'evaluacion_director' => $evaluacionDetallada, 
            'estado' => $estadoGlobal,
            'director_id' => Auth::id(),
            'motivo_decision' => $motivoGlobal, 
        ]);

        try {
            $asesor = $caso->asesor; 
            if ($asesor) { Notification::send($asesor, new CasoValidadoNotification($caso)); }
            $ctp = $caso->ctp;
            if ($ctp && (!$asesor || $ctp->id != $asesor->id)) { Notification::send($ctp, new CasoValidadoNotification($caso)); }
            if ($estadoGlobal == 'Finalizado') {
                $docentes = User::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Docente'))->where('carrera', $caso->carrera)->get();
                if($docentes->isNotEmpty()) { Notification::send($docentes, new CasoValidadoNotification($caso)); }
            }
        } catch (\Exception $e) { \Log::error('Error notif: ' . $e->getMessage()); }

        return redirect()->route('director.casos.pendientes')
                         ->with('success', "El caso ha sido procesado correctamente. Estado: {$estadoGlobal}.");
    }

    public function exportAceptados(Request $request): StreamedResponse
    {
        $query = Caso::query()->where('estado', 'Finalizado');
        $this->aplicarFiltros($query, $request);
        $casos = $query->with('director')->get(); 
        return $this->streamCsv('casos_finalizados.csv', $casos);
    }

    public function exportRechazados(Request $request): StreamedResponse
    {
        $query = Caso::query()->where('estado', 'Rechazado');
        $this->aplicarFiltros($query, $request);
        $casos = $query->with('director')->get();
        return $this->streamCsv('casos_rechazados.csv', $casos);
    }
    
    public function exportTodos(Request $request): StreamedResponse
    {
        $query = Caso::query();
        $this->aplicarFiltros($query, $request);
        $casos = $query->with('director')->get();
        return $this->streamCsv('todos_los_casos.csv', $casos);
    }

    private function streamCsv($fileName, $casos): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($casos) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'RUT', 'Nombre', 'Carrera', 'Estado', 'Fecha', 'Ajustes', 'Director', 'Motivo'], ';'); 
            foreach ($casos as $caso) {
                $ajustes = is_array($caso->ajustes_propuestos) ? implode(" | ", $caso->ajustes_propuestos) : $caso->ajustes_propuestos;
                fputcsv($handle, [
                    $caso->id,
                    $caso->rut_estudiante,
                    $caso->nombre_estudiante,
                    $caso->carrera,
                    $caso->estado, 
                    $caso->created_at->format('Y-m-d H:i:s'),
                    $ajustes, 
                    $caso->director?->name ?? 'N/A',
                    $caso->motivo_decision ?? '',
                ], ';'); 
            }
            fclose($handle);
        };
        return new StreamedResponse($callback, 200, $headers);
    }
}