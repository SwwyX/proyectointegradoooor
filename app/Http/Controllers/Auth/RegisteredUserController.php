<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol; // <-- Importamos el modelo Rol
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Buscamos el rol Estudiante
        // Si no existe, lanzamos un error claro en vez de un fallo genérico
        $rolEstudiante = Rol::where('nombre_rol', 'Estudiante')->first();

        if (!$rolEstudiante) {
            // Esto solo pasa si no corriste el RoleSeeder
            return back()->withErrors(['email' => 'Error del sistema: El rol "Estudiante" no existe en la base de datos. Contacte al administrador.']);
        }

        // 2. Creamos el usuario con el rol asignado
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => $rolEstudiante->id, // <-- Asignación automática
        ]);

        event(new Registered($user));

        Auth::login($user);

        // 3. Redirección Inteligente
        // Enviamos al usuario a la raíz '/'. 
        // Tu archivo web.php recibirá la petición, verá que es "Estudiante" y lo mandará a su dashboard.
        return redirect('/');
    }
}