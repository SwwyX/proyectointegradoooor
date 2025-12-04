<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caso;
use App\Models\User;
use App\Models\Estudiante;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Notifications\CasoCreadoNotification; 
use Illuminate\Support\Facades\Notification;

class CasoController extends Controller
{
    /**
     * Muestra TODOS los casos del sistema (Listado Maestro).
     */
    public function index(Request $request): View
    {
        $query = Caso::query();

        // Filtros (Buscador)
        if ($request->filled('search_rut')) {
            $query->where('rut_estudiante', 'like', '%' . $request->input('search_rut') . '%');
        }
        if ($request->filled('search_estado')) {
            $query->where('estado', $request->input('search_estado'));
        }

        // Ordenamos por fecha descendente
        $casos = $query->latest()->paginate(20);

        return view('asesoria.casos.index', compact('casos'));
    }

    /**
     * Formulario para crear un caso nuevo (Si Asesoría necesita ingresar uno manualmente).
     */
    public function create(): View
    {
        return view('asesoria.casos.create');
    }

    /**
     * Guardar nuevo caso.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validación Completa
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

        // 2. Buscar/Gestionar Estudiante
        $rutLimpio = $validatedData['rut_estudiante'];
        $estudiante = Estudiante::where('rut', $rutLimpio)->first();

        if (!$estudiante) {
            return back()->withInput()->withErrors(['rut_estudiante' => 'Estudiante no encontrado.']);
        }

        // Actualizar foto si viene
        if ($request->hasFile('foto_perfil')) {
            if ($estudiante->foto_perfil && Storage::disk('public')->exists($estudiante->foto_perfil)) {
                Storage::disk('public')->delete($estudiante->foto_perfil);
            }
            $estudiante->foto_perfil = $request->file('foto_perfil')->store('perfiles', 'public');
            $estudiante->save();
        }

        // 3. Crear el Caso
        $rutLimpioParaCaso = strtoupper(str_replace(['.', '-'], '', $rutLimpio));

        $caso = Caso::create([
            'estudiante_id' => $estudiante->id,
            'rut_estudiante' => $rutLimpioParaCaso,
            'nombre_estudiante' => $validatedData['nombre_estudiante'],
            'correo_estudiante' => $validatedData['correo_estudiante'],
            'carrera' => $validatedData['carrera'],
            'asesoria_id' => Auth::id(), 
            'estado' => 'En Gestion CTP', // Estado inicial correcto
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

        // Notificar al CTP
        try {
            $revisores = User::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Coordinador Técnico Pedagógico'))->get();
            Notification::send($revisores, new CasoCreadoNotification($caso));
        } catch (\Exception $e) { \Log::error('Error notif: ' . $e->getMessage()); }

        return redirect()->route('casos.index')->with('success', 'Caso creado administrativamente.');
    }

    /**
     * Ver detalles del caso (Lectura).
     */
    public function show(Caso $caso): View
    {
        $caso->load('ctp', 'director', 'estudiante');
        return view('asesoria.casos.show', compact('caso'));
    }

    /**
     * Formulario de Edición.
     */
    public function edit(Caso $caso): View
    {
        // 1. BLOQUEO DE SEGURIDAD
        // Si está Finalizado, no dejamos entrar.
        if ($caso->estado === 'Finalizado') {
            return redirect()->route('casos.index')
                ->with('error', 'No se puede editar un caso que ya ha sido Finalizado por el Director.');
        }

        return view('asesoria.casos.edit', compact('caso'));
    }

    /**
     * Actualizar caso.
     */
    public function update(Request $request, Caso $caso): RedirectResponse
    {
        // 1. BLOQUEO DE SEGURIDAD (Antes de validar)
        if ($caso->estado === 'Finalizado') {
             abort(403, 'Acción no autorizada: El caso está Finalizado.');
        }

        // 2. Validar
        $validatedData = $request->validate([
            'ajustes_propuestos' => 'nullable|array',
            'estado' => 'nullable|string', 
            'foto_perfil' => 'nullable|image|max:5120',
        ]);

        // 3. Foto
        if ($request->hasFile('foto_perfil')) {
            $estudiante = $caso->estudiante;
            if ($estudiante) {
                if ($estudiante->foto_perfil && Storage::disk('public')->exists($estudiante->foto_perfil)) {
                    Storage::disk('public')->delete($estudiante->foto_perfil);
                }
                $estudiante->foto_perfil = $request->file('foto_perfil')->store('perfiles', 'public');
                $estudiante->save();
            }
        }

        // 4. Actualizar
        $caso->update($validatedData);

        return redirect()->route('casos.index')->with('success', 'Caso actualizado por Supervisión.');
    }

    /**
     * Eliminar caso.
     */
    public function destroy(Caso $caso): RedirectResponse
    {
        try {
            Storage::disk('public')->deleteDirectory('casos/' . $caso->id);
            $caso->delete();
            return redirect()->route('casos.index')->with('success', 'Caso eliminado permanentemente.');
        } catch (\Exception $e) {
            return redirect()->route('casos.index')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}