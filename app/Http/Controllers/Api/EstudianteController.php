<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudiante; // <--- VITAL: Importar el modelo

class EstudianteController extends Controller
{
    public function buscar(Request $request)
    {
        // 1. Recibimos el RUT del buscador
        $rut = $request->input('rut');

        // 2. Buscamos coincidencia exacta. 
        // Como en tu base de datos el RUT es "2724560-9" (según me dijiste), buscamos tal cual.
        // Si no funciona, prueba descomentando la línea de abajo para limpiar espacios:
        // $rut = trim($rut); 

        $estudiante = Estudiante::where('rut', $rut)->first();

        if ($estudiante) {
            return response()->json([
                'success' => true,
                'estudiante' => $estudiante,
                // Si hay foto, devolvemos la URL completa, si no, null
                'foto_url' => $estudiante->foto_perfil ? asset('storage/' . $estudiante->foto_perfil) : null
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ]);
        }
    }
}