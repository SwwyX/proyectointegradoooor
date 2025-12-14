<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Caso;
use App\Models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AjusteController extends Controller
{
    /**
     * Muestra la página de detalle de un ajuste para confirmar.
     */
    public function show(Caso $caso): View
    {
        $docenteId = Auth::id();

        // 1. SEGURIDAD DE ESTADO
        // El error 404 ocurría aquí. Ahora validamos por 'Finalizado'.
        if ($caso->estado !== 'Finalizado') {
            // Si el caso no está finalizado, el docente no debe verlo.
            abort(404, 'El documento no está disponible o no ha sido finalizado.');
        }

        // 2. SEGURIDAD DE ACCESO (¿Es mi alumno?)
        // Verificamos si existe un cruce entre las secciones del profe y las del alumno.
        $tieneAcceso = DB::table('secciones')
            ->join('estudiante_seccion', 'secciones.id', '=', 'estudiante_seccion.seccion_id')
            ->where('secciones.docente_id', $docenteId)
            ->where('estudiante_seccion.estudiante_id', $caso->estudiante_id)
            ->exists();

        if (!$tieneAcceso) {
            abort(403, 'Acceso Denegado: Este estudiante no está inscrito en sus secciones activas.');
        }

        // 3. Cargar datos
        $caso->load('asesor', 'director', 'estudiante');

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

        // Validar acceso nuevamente por seguridad
        if ($caso->estado !== 'Finalizado') {
            return redirect()->back()->with('error', 'El caso no está en estado válido.');
        }

        // Registrar la firma en la tabla pivote
        // syncWithoutDetaching evita borrar confirmaciones de otros profesores del mismo alumno
        $caso->docentesQueConfirmaron()->syncWithoutDetaching($docenteId);

        // Volvemos al Dashboard
        return redirect()->route('docente.dashboard')
            ->with('success', '¡Lectura confirmada! Ha quedado registrado el acuse de recibo.');
    }

    // --- MÉTODOS LEGACY ---
    // Como ahora usas el Dashboard centralizado, estos métodos redirigen al dashboard
    // para evitar enlaces rotos si quedaron en algún menú antiguo.
    
    public function pendientes(): RedirectResponse
    {
        return redirect()->route('docente.dashboard');
    }

    public function todos(): RedirectResponse
    {
        return redirect()->route('docente.dashboard');
    }
}