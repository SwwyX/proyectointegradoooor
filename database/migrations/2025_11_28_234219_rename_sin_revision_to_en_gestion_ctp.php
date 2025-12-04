<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Modificar ENUM para aceptar 'En Gestion CTP'
        // Mantenemos los otros estados ('Pendiente de Validacion', 'Finalizado', etc.)
        DB::statement("ALTER TABLE casos MODIFY COLUMN estado ENUM('Sin Revision', 'En Gestion CTP', 'Pendiente de Validacion', 'En Revision', 'Aceptado', 'Rechazado', 'Reevaluacion', 'Finalizado') DEFAULT 'En Gestion CTP'");

        // 2. Migrar los datos antiguos: Todo lo que era 'Sin Revision' ahora es 'En Gestion CTP'
        DB::table('casos')
            ->where('estado', 'Sin Revision')
            ->update(['estado' => 'En Gestion CTP']);
    }

    public function down()
    {
        // No revertimos para proteger la integridad de los datos
    }
};