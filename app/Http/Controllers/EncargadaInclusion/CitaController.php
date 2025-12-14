<?php

namespace App\Http\Controllers\EncargadaInclusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * VISTA 1: CALENDARIO VISUAL (Solo el calendario)
     */
    public function index()
    {
        return view('encargada-inclusion.citas.calendar');
    }

    /**
     * VISTA 2: LISTADO DETALLADO (Solo la tabla)
     */
    public function listado(Request $request)
    {
        $query = Cita::with('estudiante')->whereNotNull('estudiante_id');

        // Filtros
        if ($request->has('ver_todo')) {
            $query->orderBy('fecha', 'desc')->orderBy('hora_inicio', 'desc');
        } else {
            $query->whereDate('fecha', '>=', Carbon::today())
                  ->orderBy('fecha', 'asc')->orderBy('hora_inicio', 'asc');
        }

        $citas = $query->paginate(15);
        
        // Contadores para el encabezado
        $pendientes = Cita::where('estado', 'Pendiente')->count();
        $confirmadas = Cita::where('estado', 'Confirmada')->whereDate('fecha', '>=', Carbon::today())->count();

        return view('encargada-inclusion.citas.listado', compact('citas', 'pendientes', 'confirmadas'));
    }

    /**
     * API: Datos para FullCalendar (CORREGIDO EL NOMBRE NULL)
     */
    public function getEventos(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        // Cargamos la relación estudiante
        $citas = Cita::with('estudiante')
                     ->whereBetween('fecha', [$start, $end])
                     ->get();

        $eventos = [];

        foreach ($citas as $cita) {
            // 1. Definir Color
            $color = match($cita->estado) {
                'Pendiente' => '#ffc107', // Amarillo
                'Confirmada' => '#198754', // Verde
                'Bloqueado' => '#6c757d', // Gris
                'Rechazada', 'Cancelada' => '#dc3545', // Rojo (Opcional, si quieres ver las canceladas en el calendario)
                default => '#adb5bd'
            };

            // 2. Definir Título y Datos (AQUÍ ESTABA EL ERROR)
            if ($cita->estado === 'Bloqueado') {
                $titulo = 'BLOQUEADO';
                $nombreEstudiante = '-';
                $carrera = '-';
                $rut = '-';
            } else {
                // CORRECCIÓN: Usamos 'nombre_completo' según tu modelo Estudiante.php
                $nombreEstudiante = $cita->estudiante ? $cita->estudiante->nombre_completo : 'Estudiante Eliminado';
                $carrera = $cita->estudiante ? $cita->estudiante->carrera : 'Sin Carrera';
                $rut = $cita->estudiante ? $cita->estudiante->rut : 'S/R'; // Tu modelo dice 'rut'
                
                $titulo = $nombreEstudiante; 
            }

            // Si la cita está cancelada/rechazada, NO la enviamos al calendario
            // para que el bloque quede LIBRE visualmente.
            if ($cita->estado === 'Cancelada' || $cita->estado === 'Rechazada') {
                continue; 
            }

            $eventos[] = [
                'id' => $cita->id,
                'title' => $titulo,
                'start' => $cita->fecha . 'T' . $cita->hora_inicio,
                'end' => $cita->fecha . 'T' . $cita->hora_fin,
                'color' => $color,
                'extendedProps' => [
                    'estado' => $cita->estado,
                    'motivo' => $cita->motivo,
                    'nombre_completo' => $nombreEstudiante, // Dato corregido
                    'carrera' => $carrera,
                    'rut' => $rut,
                    'comentario' => $cita->comentario_encargada
                ]
            ];
        }

        return response()->json($eventos);
    }

    /**
     * Acción para BLOQUEAR un horario manualmente
     */
    public function bloquear(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required'
        ]);

        $horaInicio = Carbon::parse($request->hora)->format('H:i:00');
        $horaFin = Carbon::parse($horaInicio)->addMinutes(30)->format('H:i:00');

        // Verificar si ya existe algo ahí
        $existe = Cita::where('fecha', $request->fecha)
                      ->where('hora_inicio', $horaInicio)
                      ->whereIn('estado', ['Pendiente', 'Confirmada', 'Bloqueado'])
                      ->exists();

        if ($existe) {
            return response()->json(['error' => 'El bloque ya está ocupado. Cancela la cita existente primero.'], 422);
        }

        Cita::create([
            'estudiante_id' => null, // Sin estudiante
            'encargada_id' => Auth::id(),
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'estado' => 'Bloqueado',
            'motivo' => 'Bloqueo Administrativo'
        ]);

        return response()->json(['success' => 'Bloque horario cerrado correctamente.']);
    }

    /**
     * Desbloquear un horario
     */
    public function desbloquear($id)
    {
        $cita = Cita::findOrFail($id);
        if ($cita->estado === 'Bloqueado') {
            $cita->delete();
            return response()->json(['success' => 'Bloque liberado.']);
        }
        return response()->json(['error' => 'No se puede desbloquear una cita real.'], 422);
    }

    /**
     * Actualizar estado (Confirmar / Cancelar)
     */
    public function update(Request $request, Cita $cita)
    {
        $request->validate([
            'estado' => 'required|in:Confirmada,Rechazada,Cancelada',
            'comentario_encargada' => 'nullable|string|max:255'
        ]);

        // Si cancelamos, exigimos comentario
        if (($request->estado === 'Cancelada' || $request->estado === 'Rechazada') && empty($request->comentario_encargada)) {
            return back()->with('error', 'Debes escribir un motivo justificativo para cancelar/rechazar.');
        }

        $cita->update([
            'estado' => $request->estado,
            'comentario_encargada' => $request->comentario_encargada,
            'encargada_id' => Auth::id()
        ]);

        return back()->with('success', 'Cita actualizada correctamente.');
    }
}