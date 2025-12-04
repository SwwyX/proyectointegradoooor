<?php

namespace App\Http\Controllers\EncargadaInclusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caso;
use App\Models\Documento;
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
    public function index(Request $request): View
    {
        $query = Caso::query();

        // --- FILTRO DE ESTADO ---
        // La Encargada NO ve los casos terminados.
        $query->whereNotIn('estado', ['Finalizado', 'Rechazado', 'Aceptado']);

        $this->aplicarFiltros($query, $request);
        
        $casos = $query->latest()->paginate(15);
        
        return view('encargada-inclusion.casos.index', compact('casos'));
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

    public function create(): View
    {
        return view('encargada-inclusion.casos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. VALIDACIÓN
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

        // 4. CREAR CASO
        $rutLimpioParaCaso = strtoupper(str_replace(['.', '-'], '', $rutLimpio));

        $caso = Caso::create([
            'estudiante_id' => $estudiante->id,
            'rut_estudiante' => $rutLimpioParaCaso,
            'nombre_estudiante' => $validatedData['nombre_estudiante'],
            'correo_estudiante' => $validatedData['correo_estudiante'],
            'carrera' => $validatedData['carrera'],
            'asesoria_id' => Auth::id(),
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

        // 6. NOTIFICACIÓN
        try {
            $revisores = User::whereHas('rol', function ($query) {
                $query->where('nombre_rol', 'Coordinador Técnico Pedagógico');
            })->get();
            Notification::send($revisores, new CasoCreadoNotification($caso));
        } catch (\Exception $e) {
            \Log::error('Error notif: ' . $e->getMessage());
        }

        return redirect()->route('encargada.casos.index')->with('success', 'Entrevista guardada correctamente.');
    }

    public function show(Caso $caso): View
    {
        $caso->load('ctp', 'director', 'estudiante');
        return view('encargada-inclusion.casos.show', compact('caso'));
    }

    // CORRECCIÓN: Quitamos el tipo de retorno estricto :View para permitir redirección
    public function edit(Caso $caso)
    {
        // CORRECCIÓN LÓGICA: strtolower convierte a minusculas, debemos comparar con 'en gestion ctp'
        // También aceptamos 'sin revision' por compatibilidad
        $estado = strtolower(trim($caso->estado));
        if (!in_array($estado, ['en gestion ctp', 'sin revision'])) {
            return redirect()->route('encargada.casos.index')->with('error', 'Este caso ya está siendo procesado y no se puede editar.');
        }
        return view('encargada-inclusion.casos.edit', compact('caso'));
    }

    public function update(Request $request, Caso $caso): RedirectResponse
    {
        $estado = strtolower(trim($caso->estado));
        if (!in_array($estado, ['en gestion ctp', 'sin revision'])) {
             abort(403, 'Caso ya procesado.');
        }

        // Validamos TODOS los campos editables (igual que en store, pero nullable para no obligar re-ingreso)
        $validatedData = $request->validate([
            'via_ingreso' => 'required|string',
            'tipo_discapacidad' => 'nullable|array',
            'origen_discapacidad' => 'nullable|string',
            'tratamiento_farmacologico' => 'nullable|string',
            'acompanamiento_especialista' => 'nullable|string',
            'redes_apoyo' => 'nullable|string',
            'familia' => 'nullable|array',
            'enseñanza_media_modalidad' => 'nullable|string',
            'detalle_apoyos_pie' => 'nullable|string',
            'motivo_repeticion' => 'nullable|string',
            'nombre_institucion_anterior' => 'nullable|string',
            'tipo_institucion_anterior' => 'nullable|string',
            'carrera_anterior' => 'nullable|string',
            'motivo_no_termino' => 'nullable|string',
            'empresa' => 'nullable|string',
            'cargo' => 'nullable|string',
            'caracteristicas_intereses' => 'nullable|string',
            'ajustes_propuestos' => 'nullable|array',
            'foto_perfil' => 'nullable|image|max:5120',
        ]);

        // Manejo de checkboxes (booleanos) que no vienen en el request si no se marcan
        $caso->credencial_rnd = $request->has('credencial_rnd');
        $caso->pension_invalidez = $request->has('pension_invalidez');
        $caso->certificado_medico = $request->has('certificado_medico');
        $caso->recibio_apoyos_pie = $request->has('recibio_apoyos_pie');
        $caso->repitio_curso = $request->has('repitio_curso');
        $caso->estudio_previo_superior = $request->has('estudio_previo_superior');
        $caso->trabaja = $request->has('trabaja');
        $caso->requiere_apoyos = $request->has('requiere_apoyos');

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

        // Actualizamos el resto de campos validados
        $caso->update($validatedData);

        return redirect()->route('encargada.casos.index')->with('success', 'Caso actualizado con éxito.');
    }

    public function destroy(Caso $caso): RedirectResponse
    {
        try {
            Storage::disk('public')->deleteDirectory('casos/' . $caso->id);
            $caso->delete();
            return redirect()->route('encargada.casos.index')->with('success', 'Caso eliminado con éxito.');
        } catch (\Exception $e) {
            \Log::error('Error al eliminar caso: ' . $e->getMessage());
            return redirect()->route('encargada.casos.index')->with('error', 'Error al eliminar el caso.');
        }
    }
}