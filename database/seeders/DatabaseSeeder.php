<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolSeeder::class,             // 1. Roles Base
            // EstudianteSeeder::class,   // Comentamos los antiguos para no generar basura
            // DemoDocenteSeeder::class,  // Comentamos el demo de Roberto Alveal
            
            EscenarioDePruebaSeeder::class // 2. EL NUEVO ESCENARIO COMPLETO
        ]);
    }
}