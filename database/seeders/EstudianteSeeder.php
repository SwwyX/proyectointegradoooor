<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estudiante;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class EstudianteSeeder extends Seeder
{
    public function run()
    {
        $rolEstudiante = Rol::where('nombre_rol', 'Estudiante')->first();

        // ---------------------------------------------------------------
        // 1. Usuario de prueba manual (Juan Pérez Demo) CON LOGIN
        // ---------------------------------------------------------------
        // Primero creamos su usuario
        $userDemo = User::firstOrCreate(
            ['email' => 'juan.perez@inacapmail.cl'],
            [
                'name' => 'Juan Pérez Demo',
                'password' => Hash::make('password'),
                'rol_id' => $rolEstudiante->id,
            ]
        );

        // Luego su perfil académico enlazado
        Estudiante::firstOrCreate(
            ['rut' => '11111111-1'],
            [
                'user_id' => $userDemo->id, // <--- AQUÍ LO VINCULAMOS
                'nombre_completo' => $userDemo->name,
                'correo' => $userDemo->email,
                'carrera' => 'Ingeniería en Informática',
                'area_academica' => 'Tecnología',
                'fecha_nacimiento' => '2002-05-15',
                'telefono' => '+56 9 1234 5678',
                'sede' => 'Sede Santiago Sur',
                'jornada' => 'Diurna',
                'edad' => 23,
            ]
        );

        // ---------------------------------------------------------------
        // 2. Resto de usuarios random CON LOGIN
        // ---------------------------------------------------------------
        if (Estudiante::count() < 10) {
            // Creamos 10 usuarios y por cada uno, un perfil de estudiante
            User::factory(10)->create([
                'rol_id' => $rolEstudiante->id,
                'password' => Hash::make('password')
            ])->each(function ($user) {
                
                // Por cada usuario creado, creamos su ficha de estudiante
                Estudiante::factory()->create([
                    'user_id' => $user->id, // Vinculamos
                    'nombre_completo' => $user->name,
                    'correo' => $user->email,
                ]);
            });
        }
    }
}