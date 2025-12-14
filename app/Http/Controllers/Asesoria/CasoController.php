<?php

namespace App\Http\Controllers\Asesoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caso; 
use App\Models\Estudiante;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\RedirectResponse; 

class CasoController extends Controller
{
    /**
     * Muestra el listado general de casos con filtros y ordenamiento.
     */
    public function index(Request $request): View
    {
        // 1. Iniciar la consulta
        $query = Caso::query();

        // 2. Filtro por RUT (Búsqueda parcial)
        if ($request->filled('search_rut')) {
            $query->where('rut_estudiante', 'like', '%' . $request->input('search_rut') . '%');
        }

        // 3. Filtro por Estado (Búsqueda exacta)
        if ($request->filled('search_estado')) {
            $query->where('estado', $request->input('search_estado'));
        }

        // 4. Filtro por Fecha de Inicio (Desde)
        // NOTA: Usamos 'search_fecha_inicio' para coincidir con tu vista
        if ($request->filled('search_fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->input('search_fecha_inicio'));
        }

        // 5. Filtro por Fecha de Fin (Hasta)
        // NOTA: Usamos 'search_fecha_fin' para coincidir con tu vista
        if ($request->filled('search_fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->input('search_fecha_fin'));
        }

        // 6. Ordenamiento (Sort)
        // Capturamos el parámetro 'sort' de la URL. Si no viene, usamos 'desc' (más reciente primero)
        $sort = $request->input('sort', 'desc');
        
        // Validación de seguridad para evitar inyección SQL en el orderBy
        if (!in_array(strtolower($sort), ['asc', 'desc'])) {
            $sort = 'desc';
        }

        // Aplicamos el ordenamiento
        $query->orderBy('created_at', $sort);

        // 7. Paginación y preservación de filtros
        // 'appends' asegura que si cambias de página, los filtros no se pierdan
        $casos = $query->paginate(10)->appends($request->all());

        // 8. Retornar la vista
        return view('asesoria.casos.index', compact('casos'));
    }

    /**
     * Muestra el formulario para crear un nuevo caso manualmente.
     */
    public function create(): View
    {
        return view('asesoria.casos.create');
    }

    /**
     * Almacena un nuevo caso en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validación de datos
        $validatedData = $request->validate([
            'rut_estudiante' => 'required|string|max:12',
            'nombre_estudiante' => 'required|string|max:255',
            'correo_estudiante' => 'required|email|max:255',
            'carrera' => 'required|string|max:255',
            'via_ingreso' => 'required|string',
            'tipo_discapacidad' => 'nullable|array',
            'origen_discapacidad' => 'nullable|string',
            'caracteristicas_intereses' => 'nullable|string',
            'ajustes_propuestos' => 'nullable|array',
            // Agrega aquí más validaciones según tus campos del formulario create
        ]);

        // 2. Buscar o Crear Estudiante (Opcional, si tienes lógica de sincronización)
        $estudiante = Estudiante::firstOrCreate(
            ['rut' => $validatedData['rut_estudiante']],
            [
                'nombre_completo' => $validatedData['nombre_estudiante'],
                'correo' => $validatedData['correo_estudiante'],
                'carrera' => $validatedData['carrera'],
                // Puedes agregar valores por defecto si el estudiante es nuevo
            ]
        );

        // 3. Crear el Caso
        Caso::create([
            'estudiante_id' => $estudiante->id,
            'asesoria_id' => Auth::id(), // El usuario logueado (Asesor) es el creador
            'rut_estudiante' => $validatedData['rut_estudiante'],
            'nombre_estudiante' => $validatedData['nombre_estudiante'],
            'correo_estudiante' => $validatedData['correo_estudiante'],
            'carrera' => $validatedData['carrera'],
            'estado' => 'En Gestion CTP', // Estado inicial
            'via_ingreso' => $request->input('via_ingreso'),
            'tipo_discapacidad' => $request->input('tipo_discapacidad'), // Array JSON
            'origen_discapacidad' => $request->input('origen_discapacidad'),
            'credencial_rnd' => $request->has('credencial_rnd'),
            'caracteristicas_intereses' => $request->input('caracteristicas_intereses'),
            'requiere_apoyos' => $request->has('requiere_apoyos'),
            'ajustes_propuestos' => $request->input('ajustes_propuestos'), // Array JSON
            // Mapea el resto de campos necesarios aquí
        ]);

        return redirect()->route('casos.index')
                         ->with('success', 'Caso creado exitosamente y enviado a gestión.');
    }

    /**
     * Muestra el detalle completo de un caso específico.
     */
    public function show(Caso $caso): View
    {
        // Cargamos relaciones para optimizar y mostrar nombres en la vista
        $caso->load('estudiante', 'asesor', 'ctp', 'director');
        
        return view('asesoria.casos.show', compact('caso'));
    }

    /**
     * Muestra el formulario para editar un caso existente.
     */
    public function edit(Caso $caso): View
    {
        // Restricción: No editar si ya está finalizado
        if ($caso->estado === 'Finalizado') {
            return redirect()->route('casos.index')
                             ->with('error', 'No se puede editar un caso que ya está finalizado.');
        }
        
        return view('asesoria.casos.edit', compact('caso'));
    }

    /**
     * Actualiza la información del caso en la base de datos.
     */
    public function update(Request $request, Caso $caso): RedirectResponse
    {
        // Validación básica
        $request->validate([
            'nombre_estudiante' => 'required|string',
            // Agrega las reglas que necesites actualizar
        ]);

        // Actualización
        $caso->update([
            'nombre_estudiante' => $request->input('nombre_estudiante'),
            'correo_estudiante' => $request->input('correo_estudiante'),
            'carrera' => $request->input('carrera'),
            'tipo_discapacidad' => $request->input('tipo_discapacidad'),
            'ajustes_propuestos' => $request->input('ajustes_propuestos'),
            // Agrega el resto de campos actualizables
        ]);

        return redirect()->route('casos.index')
                         ->with('success', 'Caso actualizado correctamente.');
    }

    /**
     * Elimina un caso de la base de datos.
     */
    public function destroy(Caso $caso): RedirectResponse
    {
        $caso->delete();
        
        return redirect()->route('casos.index')
                         ->with('success', 'Caso eliminado correctamente.');
    }
}