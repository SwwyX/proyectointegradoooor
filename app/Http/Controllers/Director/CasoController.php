<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caso; 
use App\Models\User; 
use App\Models\Estudiante;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CasoValidadoNotification;
use App\Notifications\CasoCreadoNotification;

class CasoController extends Controller
{
    /**
     * Muestra los casos pendientes de validación por el Director.
     */
    public function pendientes(Request $request): View
    {
        $query = Caso::query();
        
        // Filtro base: Solo lo que el Director debe validar (Pendientes o En Revisión antigua)
        $query->whereIn('estado', ['Pendiente de Validacion', 'En Revision']);
        
        // Aplicamos filtros de buscador y fechas
        $this->aplicarFiltros($query, $request);
        
        // Ordenamos descendente (más nuevos primero)
        $casos = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('director.casos_pendientes', compact('casos'));
    }

    /**
     * Muestra el historial de casos ya Aceptados/Finalizados.
     */
    public function aceptados(Request $request): View
    {
        $query = Caso::query();
        
        // Filtro base: Solo finalizados/aceptados
        $query->whereIn('estado', ['Finalizado', 'Aceptado']);
        
        // Aplicar filtros de búsqueda (RUT, Fechas)
        $this->aplicarFiltros($query, $request);
        
        // --- LOGICA DE ORDENAMIENTO DINÁMICA ---
        // Si no se especifica, ordena por updated_at descendente (más reciente primero)
        $sortColumn = $request->input('sort', 'updated_at'); 
        $sortOrder = $request->input('order', 'desc');       

        // Validación simple de columnas permitidas para evitar errores SQL
        if (!in_array($sortColumn, ['updated_at', 'created_at'])) {
            $sortColumn = 'updated_at';
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortColumn, $sortOrder);

        // Paginación manteniendo los filtros y el orden en los enlaces de página
        $casos = $query->paginate(15)->appends($request->all());
        
        return view('director.casos_aceptados', compact('casos'));
    }

    /**
     * Muestra la visión global de todos los casos registrados.
     */
    public function todos(Request $request): View
    {
        $query = Caso::query(); 
        
        // Aquí NO hay filtro base de estado, se muestran todos.
        $this->aplicarFiltros($query, $request);
        
        // Ordenamiento dinámico según lo que se pida en la vista (default: fecha creación desc)
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $query->orderBy($sort, $order);

        $casos = $query->paginate(15)->appends($request->all());
        
        return view('director.casos_todos', compact('casos'));
    }

    /**
     * Lógica centralizada de filtros para todas las vistas.
     * Importante: Usa los nombres 'search_...' que definimos en las vistas HTML.
     */
    private function aplicarFiltros($query, Request $request)
    {
        // 1. Buscador General (RUT o Nombre)
        if ($request->filled('search_rut')) {
            $busqueda = $request->input('search_rut');
            $query->where(function($q) use ($busqueda) {
                $q->where('rut_estudiante', 'like', "%{$busqueda}%")
                  ->orWhere('nombre_estudiante', 'like', "%{$busqueda}%");
            });
        }

        // 2. Filtro Estado (Solo si viene en el request, útil para la vista 'todos')
        if ($request->filled('search_estado')) {
            $query->where('estado', $request->input('search_estado'));
        }

        // 3. Filtros de Fecha (Inicio y Fin) - Usando nombres corregidos
        if ($request->filled('search_fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->input('search_fecha_inicio'));
        }
        if ($request->filled('search_fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->input('search_fecha_fin'));
        }
    }

    /**
     * Ver el detalle de un caso (para validar o solo ver).
     */
    public function show(Caso $caso): View
    {
        // IMPORTANTE: Cargamos 'seguimientos.docente' para ver la bitácora en el show
        $caso->load('asesor', 'ctp', 'estudiante', 'seguimientos.docente');
        
        return view('director.caso_show', compact('caso'));
    }

    /**
     * PROCESO DE VALIDACIÓN (Aceptar/Rechazar ajustes del CTP)
     */
    public function validar(Request $request, Caso $caso): RedirectResponse
    {
        // 1. Validación de datos del formulario
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
        
        // Procesamos la decisión de cada ítem
        foreach ($decisiones as $index => $decision) {
            $evaluacionDetallada[$index] = [
                'decision' => $decision,
                'comentario' => $comentarios[$index] ?? null 
            ];

            // Si hay algún ítem en reevaluación, todo el caso se va a corrección
            if ($decision === 'Reevaluacion') {
                $hayReevaluacion = true;
            }
        }

        // Definimos el estado final del caso
        if ($hayReevaluacion) {
            $estadoGlobal = 'Reevaluacion';
            $mensajeDefecto = 'Se solicitan correcciones en uno o más ajustes propuestos.';
        } else {
            // Si todo es Aceptado o Rechazado (pero cerrado), se finaliza.
            $estadoGlobal = 'Finalizado'; 
            $mensajeDefecto = 'Validación completa. Caso cerrado por Dirección.';
        }

        $motivoGlobal = $comentarioGlobalInput ? $comentarioGlobalInput : $mensajeDefecto;

        // Actualizamos el caso
        $caso->update([
            'evaluacion_director' => $evaluacionDetallada, 
            'estado' => $estadoGlobal,
            'director_id' => Auth::id(),
            'motivo_decision' => $motivoGlobal, 
        ]);

        // Notificaciones al equipo
        try {
            // Avisar Asesor
            $asesor = $caso->asesor; 
            if ($asesor) { Notification::send($asesor, new CasoValidadoNotification($caso)); }
            
            // Avisar CTP
            $ctp = $caso->ctp;
            if ($ctp && (!$asesor || $ctp->id != $asesor->id)) { Notification::send($ctp, new CasoValidadoNotification($caso)); }
            
            // Si se finalizó, avisar a docentes de la carrera (si aplica lógica de roles)
            if ($estadoGlobal == 'Finalizado') {
                $docentes = User::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Docente'))
                                ->where('carrera', $caso->carrera)->get();
                if($docentes->isNotEmpty()) { Notification::send($docentes, new CasoValidadoNotification($caso)); }
            }
        } catch (\Exception $e) { \Log::error('Error notif: ' . $e->getMessage()); }

        return redirect()->route('director.casos.pendientes')
                         ->with('success', "El caso ha sido procesado correctamente. Estado: {$estadoGlobal}.");
    }

    // --- CREACIÓN DE CASOS (Desde perfil Director) ---

    public function create(): View
    {
        return view('director.casos_create');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. VALIDACIÓN COMPLETA
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

        // 2. BUSCAR ESTUDIANTE EN BD
        $rutLimpio = $validatedData['rut_estudiante'];
        $estudiante = Estudiante::where('rut', $rutLimpio)->first();

        if (!$estudiante) {
            return back()->withInput()->withErrors(['rut_estudiante' => 'Estudiante no encontrado en la base de datos académica.']);
        }

        // 3. ACTUALIZAR FOTO
        if ($request->hasFile('foto_perfil')) {
            if ($estudiante->foto_perfil && Storage::disk('public')->exists($estudiante->foto_perfil)) {
                Storage::disk('public')->delete($estudiante->foto_perfil);
            }
            $estudiante->foto_perfil = $request->file('foto_perfil')->store('perfiles', 'public');
            $estudiante->save();
        }

        // 4. CREAR EL CASO
        $rutLimpioParaCaso = strtoupper(str_replace(['.', '-'], '', $rutLimpio));

        $caso = Caso::create([
            'estudiante_id' => $estudiante->id,
            'rut_estudiante' => $rutLimpioParaCaso,
            'nombre_estudiante' => $validatedData['nombre_estudiante'],
            'correo_estudiante' => $validatedData['correo_estudiante'],
            'carrera' => $validatedData['carrera'],
            
            // Si el Director crea el caso, queda como "asesor" inicial
            'asesoria_id' => Auth::id(),
            
            // Estado inicial para flujo
            'estado' => 'En Gestion CTP',
            
            // Campos del formulario
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

        return redirect()->route('director.casos.todos')->with('success', 'Caso iniciado exitosamente. Enviado a CTP.');
    }
}