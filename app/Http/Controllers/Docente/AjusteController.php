<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Caso;
use App\Models\Seccion;
use App\Models\SeguimientoDocente; // <--- Importante: Modelo para guardar comentarios
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AjusteController extends Controller
{
    /**
     * Muestra la página de detalle de un ajuste para confirmar y ver bitácora.
     */
    public function show(Caso $caso): View
    {
        $docenteId = Auth::id();

        // 1. SEGURIDAD DE ESTADO: El caso debe estar Finalizado
        if ($caso->estado !== 'Finalizado') {
            abort(404, 'El documento no está disponible o no ha sido finalizado.');
        }

        // 2. SEGURIDAD DE ACCESO: ¿Es mi alumno?
        // Verificamos si existe un cruce entre las secciones del profe y las del alumno.
        $tieneAcceso = DB::table('secciones')
            ->join('estudiante_seccion', 'secciones.id', '=', 'estudiante_seccion.seccion_id')
            ->where('secciones.docente_id', $docenteId)
            ->where('estudiante_seccion.estudiante_id', $caso->estudiante_id)
            ->exists();

        if (!$tieneAcceso) {
            abort(403, 'Acceso Denegado: Este estudiante no está inscrito en sus secciones activas.');
        }

        // 3. Cargar datos + Bitácora de Seguimiento
        // 'seguimientos.docente' carga los comentarios y el nombre del profesor que los hizo
        $caso->load('asesor', 'director', 'estudiante', 'seguimientos.docente');

        // 4. Verificar si ya confirmó lectura
        $yaConfirmado = $caso->docentesQueConfirmaron()
                             ->where('user_id', $docenteId)
                             ->exists();

        return view('docente.ajuste_show', compact('caso', 'yaConfirmado'));
    }

    /**
     * Marca un caso como "leído" por el docente actual.
     */
    public function confirmar(Request $request, Caso $caso): RedirectResponse
    {
        $docenteId = Auth::id();

        if ($caso->estado !== 'Finalizado') {
            return redirect()->back()->with('error', 'El caso no está en estado válido.');
        }

        // Registra la lectura en la tabla pivote sin borrar las de otros profes
        $caso->docentesQueConfirmaron()->syncWithoutDetaching($docenteId);

        return redirect()->route('docente.dashboard')
            ->with('success', '¡Lectura confirmada! Ha quedado registrado el acuse de recibo.');
    }

    /**
     * Guarda un nuevo comentario en la bitácora de seguimiento.
     */
    public function storeComentario(Request $request, Caso $caso): RedirectResponse
    {
        // 1. Validar formulario
        $request->validate([
            'comentario' => 'required|string|min:5|max:1000',
        ]);

        $docenteId = Auth::id();

        // 2. Re-verificar seguridad (Para evitar trucos por URL)
        $tieneAcceso = DB::table('secciones')
            ->join('estudiante_seccion', 'secciones.id', '=', 'estudiante_seccion.seccion_id')
            ->where('secciones.docente_id', $docenteId)
            ->where('estudiante_seccion.estudiante_id', $caso->estudiante_id)
            ->exists();

        if (!$tieneAcceso) {
            abort(403, 'No tiene permiso para registrar seguimiento a este estudiante.');
        }

        // 3. Crear el registro
        SeguimientoDocente::create([
            'caso_id' => $caso->id,
            'user_id' => $docenteId,
            'comentario' => $request->input('comentario'),
        ]);

        // 4. Volver a la misma vista con mensaje
        return redirect()->route('docente.ajustes.show', $caso->id)
            ->with('success', 'Observación de seguimiento registrada exitosamente.');
    }

    // --- MÉTODOS LEGACY (Para evitar errores 404 si quedaron links viejos) ---
    
    public function pendientes(): RedirectResponse
    {
        return redirect()->route('docente.dashboard');
    }

    public function todos(): RedirectResponse
    {
        return redirect()->route('docente.dashboard');
    }
}