<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caso;
use App\Models\Estudiante;
use App\Models\Documento;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CasoController extends Controller
{
    /**
     * Listado de mis casos.
     */
    public function index(): View
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('user_id', $user->id)->first();

        if (!$estudiante) {
            // Si no tiene perfil, enviamos una colección vacía
            $casos = collect(); 
        } else {
            $casos = $estudiante->casos()->latest()->paginate(10);
        }

        return view('estudiante.casos.index', compact('casos'));
    }

    /**
     * Ver detalle de mi caso.
     */
    public function show(Caso $caso): View
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('user_id', $user->id)->first();

        // SEGURIDAD: Verificar que el caso pertenezca al estudiante logueado
        if (!$estudiante || $caso->estudiante_id !== $estudiante->id) {
            abort(403, 'No tienes permiso para ver este caso.');
        }

        return view('estudiante.casos.show', compact('caso'));
    }

    /**
     * Subir archivo adicional (Evidencia).
     */
    public function upload(Request $request, Caso $caso): RedirectResponse
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('user_id', $user->id)->first();

        if (!$estudiante || $caso->estudiante_id !== $estudiante->id) {
            abort(403);
        }

        $request->validate([
            'documento' => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        if ($request->hasFile('documento')) {
            $file = $request->file('documento');
            $path = $file->store('casos/' . $caso->id, 'public');

            $caso->documentos()->create([
                'nombre_original' => $file->getClientOriginalName(),
                'ruta' => $path,
                'tipo' => 'Evidencia Estudiante',
            ]);

            return back()->with('success', 'Documento subido correctamente.');
        }

        return back()->with('error', 'No se pudo subir el archivo.');
    }
}