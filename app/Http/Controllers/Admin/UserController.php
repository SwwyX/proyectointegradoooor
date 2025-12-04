<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // 1. Capturar parámetros de ordenamiento de la URL
        // Si no vienen, usamos 'id' y 'asc' por defecto.
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        // 2. Seguridad: Validar que solo se pueda ordenar por columnas permitidas
        $allowedSorts = ['id', 'name', 'email', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        // Si intentan inyectar una columna rara, volvemos al defecto
        if (!in_array($sort, $allowedSorts)) $sort = 'id';
        if (!in_array($direction, $allowedDirections)) $direction = 'asc';

        // 3. Consulta a la base de datos
        $users = User::with('rol') // Traemos el rol para evitar consultas N+1
                    ->orderBy($sort, $direction) // Aplicamos el ordenamiento
                    ->paginate(50); // <-- ¡AQUÍ ESTÁ EL CAMBIO! 50 usuarios por página

        // 4. Retornamos la vista con los usuarios
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = Rol::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'rol_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $roles = Rol::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'rol_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|confirmed|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->rol_id = $request->rol_id;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevenir que el admin se borre a sí mismo
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado con éxito.');
    }
}