<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol; // Asegúrate de importar tu modelo Rol
use Illuminate\Support\Facades\Hash;

class MainUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Definimos los usuarios que queremos crear
        $usuarios = [
            [
                'name' => 'admin1',
                'email' => 'admin1@gmail.com',
                'password' => '12345678',
                'rol_nombre' => 'Administrador' // Asegúrate que coincida con tu DB
            ],
            [
                'name' => 'asesoria1',
                'email' => 'asesoria1@gmail.com',
                'password' => '12345678',
                'rol_nombre' => 'Asesoría Pedagógica'
            ],
            [
                'name' => 'director1',
                'email' => 'director1@gmail.com',
                'password' => '12345678',
                'rol_nombre' => 'Director de Carrera'
            ],
            [
                'name' => 'ctp1',
                'email' => 'ctp1@gmail.com',
                'password' => '12345678',
                'rol_nombre' => 'Coordinador Técnico Pedagógico'
            ],
            [
                'name' => 'encargada1',
                'email' => 'encargada1@gmail.com',
                'password' => '12345678',
                'rol_nombre' => 'Encargada Inclusión'
            ],
        ];

        foreach ($usuarios as $userData) {
            // 2. Buscamos el ID del rol en la base de datos
            // Usamos firstOrCreate para que si el rol no existe, lo cree y no falle
            $rol = Rol::firstOrCreate(
                ['nombre_rol' => $userData['rol_nombre']], // Condición de búsqueda
                ['descripcion' => 'Rol generado automáticamente'] // Valores si se crea nuevo
            );

            // 3. Creamos el usuario si no existe (buscando por email)
            User::firstOrCreate(
                ['email' => $userData['email']], // Busca por email para no duplicar
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'rol_id' => $rol->id,
                    'email_verified_at' => now(), // Para que no pida verificar correo
                ]
            );
        }
        $this->command->info('Usuarios principales creados exitosamente.');
    }
}