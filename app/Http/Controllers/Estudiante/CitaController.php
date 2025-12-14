<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function index()
    {
        $estudiante = Estudiante::where('user_id', Auth::id())->firstOrFail();
        $misCitas = Cita::where('estudiante_id', $estudiante->id)->orderBy('fecha', 'desc')->get();
        
        return view('estudiante.citas.historial', compact('misCitas'));
    }

    public function create(Request $request)
    {
        return view('estudiante.citas.create');
    }

    /**
     * API para FullCalendar del Estudiante
     * CORRECCIÓN: Ahora incluye 'Bloqueado' para que se vea rojo.
     */
    public function getEventos(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        // 1. Buscamos citas que ocupen espacio (Pendiente, Confirmada Y BLOQUEADO)
        $citas = Cita::whereIn('estado', ['Pendiente', 'Confirmada', 'Bloqueado'])
                     ->whereBetween('fecha', [$start, $end])
                     ->get();

        $eventos = [];

        foreach ($citas as $cita) {
            $eventos[] = [
                'id' => 'ocupado-' . $cita->id,
                'title' => 'Ocupado',
                'start' => $cita->fecha . 'T' . $cita->hora_inicio,
                'end' => $cita->fecha . 'T' . $cita->hora_fin,
                'display' => 'background', // Se ve como fondo bloqueado
                'color' => '#dc3545', // Rojo
                'overlap' => false,   // Bloquea el clic en FullCalendar
            ];
        }

        // 2. Bloqueo de Almuerzo
        $eventos[] = [
            'id' => 'almuerzo',
            'daysOfWeek' => [1, 2, 3, 4, 5],
            'startTime' => '13:30:00',
            'endTime' => '14:30:00',
            'display' => 'background',
            'color' => '#ffc107', // Amarillo
            'title' => 'Almuerzo',
            'overlap' => false,
        ];

        return response()->json($eventos);
    }

    /**
     * Guardar la reserva
     * CORRECCIÓN: Verificación manual de disponibilidad antes de guardar.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after:today',
            'hora' => 'required',
            'motivo' => 'required|string|max:255'
        ]);

        $estudiante = Estudiante::where('user_id', Auth::id())->firstOrFail();
        
        // Formateo
        $horaInicio = Carbon::parse($request->hora)->format('H:i:00');
        $horaFin = Carbon::parse($horaInicio)->addMinutes(30)->format('H:i:00');
        $fechaCarbon = Carbon::parse($request->fecha);

        // --- REGLA 1: Validar Fines de Semana ---
        if ($fechaCarbon->isWeekend()) {
            return back()->with('error', 'No se pueden agendar citas los fines de semana.');
        }

        // --- REGLA 2: Validar Almuerzo ---
        if ($horaInicio >= '13:30:00' && $horaInicio < '14:30:00') {
            return back()->with('error', 'El horario de 13:30 a 14:30 corresponde al bloque de colación.');
        }

        // --- REGLA 3: Solo 1 Cita por Día por Estudiante ---
        $tengoCitaHoy = Cita::where('estudiante_id', $estudiante->id)
                        ->where('fecha', $request->fecha)
                        ->whereIn('estado', ['Pendiente', 'Confirmada'])
                        ->exists();

        if ($tengoCitaHoy) {
            return back()->with('error', 'Ya tienes una solicitud de hora para este día.');
        }

        // --- REGLA 4 (CRÍTICA): Verificar si el bloque está ocupado por CUALQUIERA ---
        // Esto incluye: Otros alumnos (Pendiente/Confirmada) O la Encargada (Bloqueado)
        $bloqueOcupado = Cita::where('fecha', $request->fecha)
                             ->where('hora_inicio', $horaInicio)
                             ->whereIn('estado', ['Pendiente', 'Confirmada', 'Bloqueado'])
                             ->exists();

        if ($bloqueOcupado) {
            return back()->with('error', 'Lo sentimos, ese horario acaba de ser ocupado o bloqueado. Por favor recarga la página.');
        }

        // Si pasa todo, guardamos
        Cita::create([
            'estudiante_id' => $estudiante->id,
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'motivo' => $request->motivo,
            'estado' => 'Pendiente'
        ]);

        return redirect()->route('estudiante.citas.index')->with('success', 'Solicitud enviada exitosamente.');
    }
    
    public function cancel(Cita $cita)
    {
        if ($cita->estado == 'Pendiente') {
            // Al cancelar, cambiamos el estado para historial o borramos.
            // Si usamos SoftDeletes o cambiamos estado a 'Cancelada', el bloque se libera
            // porque nuestras consultas buscan 'Pendiente', 'Confirmada' o 'Bloqueado'.
            $cita->update(['estado' => 'Cancelada']); 
            
            return back()->with('success', 'Cita cancelada correctamente.');
        }
        return back()->with('error', 'No se puede cancelar una cita ya procesada.');
    }
}