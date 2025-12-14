<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Actualizamos la columna ENUM. 
        // Mantenemos los antiguos por seguridad, pero agregamos "Pendiente de Validacion".
        // NOTA: Si ya no quieres usar 'En Revision', lo podrías quitar, pero mejor dejarlo por si hay datos viejos.
        DB::statement("ALTER TABLE casos MODIFY COLUMN estado ENUM('Sin Revision', 'En Revision', 'Pendiente de Validacion', 'Aceptado', 'Rechazado', 'Reevaluacion', 'Finalizado') DEFAULT 'Sin Revision'");
        
        // Opcional: Migrar los datos viejos que decían 'En Revision' al nuevo nombre
        DB::table('casos')->where('estado', 'En Revision')->update(['estado' => 'Pendiente de Validacion']);
    }

    public function down()
    {
        // Revertir es complejo con enums, generalmente no se borran opciones para no perder datos.
    }
};