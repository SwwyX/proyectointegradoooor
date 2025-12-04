<?php

namespace App\Http\Controllers\CTP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caso;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CasoValidadoNotification; 

class CasoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Caso::query();
        $query->whereIn('estado', ['En Gestion CTP', 'Reevaluacion']);
        $casos = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('ctp.casos_index', compact('casos'));
    }

    public function edit(Caso $caso): View
    {
        if (!in_array($caso->estado, ['En Gestion CTP', 'Reevaluacion'])) {
             return redirect()->route('ctp.casos.index')->with('error', 'Este caso ya no está en su bandeja.');
        }
        
        $caso->load('asesor', 'director', 'estudiante');
        return view('ctp.caso_edit', compact('caso'));
    }

    public function update(Request $request, Caso $caso): RedirectResponse
    {
        if (!in_array($caso->estado, ['En Gestion CTP', 'Reevaluacion'])) {
            return redirect()->route('ctp.casos.index')->with('error', 'Este caso ya ha sido procesado.');
        }

        $request->validate([
            'ajustes_ctp' => 'nullable|array',
            'ajustes_ctp.*' => 'nullable|string',
            'estado' => 'Pendiente de Validacion'
        ]);

        // --- CORRECCIÓN: FILTRAR VALORES VACÍOS ---
        // Recuperamos el array crudo
        $ajustesRaw = $request->input('ajustes_ctp', []);
        
        // Filtramos para eliminar nulos o textos vacíos ("") que vienen de los inputs 'Otros'
        $ajustesLimpios = array_values(array_filter($ajustesRaw, function($valor) {
            return !is_null($valor) && trim($valor) !== '';
        }));
        // -------------------------------------------

        $caso->update([
            'ajustes_ctp' => $ajustesLimpios, // Guardamos solo la lista limpia
            'ctp_id' => Auth::id(), 
            'estado' => 'Pendiente de Validacion', 
            'motivo_decision' => null, 
            'director_id' => null, 
        ]);

        // Notificación
        try {
            $directores = User::whereHas('rol', function ($query) {
                $query->where('nombre_rol', 'Director de Carrera');
            })->get();
            
            // Notification::send($directores, new CasoValidadoNotification($caso));
        } catch (\Exception $e) {
            \Log::error('Error notif: ' . $e->getMessage());
        }

        return redirect()->route('ctp.casos.index')->with('success', 'Ajustes guardados y enviados al Director.');
    }
}