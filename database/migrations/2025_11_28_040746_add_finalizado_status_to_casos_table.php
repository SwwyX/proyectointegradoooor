<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Modificamos la columna ENUM para agregar 'Finalizado' sin borrar los otros
        DB::statement("ALTER TABLE casos MODIFY COLUMN estado ENUM('Sin Revision', 'En Revision', 'Aceptado', 'Rechazado', 'Reevaluacion', 'Finalizado') DEFAULT 'Sin Revision'");
    }

    public function down()
    {
        // Revertir (Opcional, por seguridad a veces no se revierte enum para no perder datos)
        // DB::statement("ALTER TABLE casos MODIFY COLUMN estado ENUM('Sin Revision', 'En Revision', 'Aceptado', 'Rechazado', 'Reevaluacion') DEFAULT 'Sin Revision'");
    }
};