<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol; // Usamos el modelo Eloquent para mejor práctica
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Desactivamos las restricciones de claves foráneas temporalmente para limpiar sin errores
        Schema::disableForeignKeyConstraints();
        
        // Limpiamos la tabla
        DB::table('roles')->truncate();
        
        // Reactivamos las restricciones
        Schema::enableForeignKeyConstraints();
        
        // Definimos los roles exactos que necesitas
        $roles = [
            'Administrador',
            'Asesoría Pedagógica',
            'Director de Carrera',
            'Docente',
            'Estudiante',
            'Coordinador Técnico Pedagógico',
            'Encargada Inclusión',
        ];

        // Insertamos uno por uno
        foreach ($roles as $nombre) {
            Rol::create(['nombre_rol' => $nombre]);
        }
    }
}