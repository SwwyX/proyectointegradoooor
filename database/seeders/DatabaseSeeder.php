<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolSeeder::class,             // 1. Roles Base (Indispensable primero)
            
            MainUsersSeeder::class,       // 2. NUEVO: Usuarios Fijos (Admin, Encargada, etc.)
            
            // EstudianteSeeder::class,   // (Comentado como lo tenías)
            // DemoDocenteSeeder::class,  // (Comentado como lo tenías)
            
            EscenarioDePruebaSeeder::class // 3. Datos de prueba masivos
        ]);
    }
}