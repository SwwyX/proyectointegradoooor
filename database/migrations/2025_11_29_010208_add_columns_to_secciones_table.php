<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('secciones', function (Blueprint $table) {
            // 1. Nombre de la sección (Ej: IEI-170)
            $table->string('nombre_seccion')->after('id');

            // 2. Relación con Asignatura (FK)
            // constrained('asignaturas') le dice a Laravel que mire la tabla asignaturas
            $table->foreignId('asignatura_id')->after('nombre_seccion')->constrained('asignaturas');

            // 3. Relación con Docente (FK hacia la tabla users)
            // Como el docente es un 'User', referenciamos a la tabla 'users'
            $table->foreignId('docente_id')->after('asignatura_id')->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('secciones', function (Blueprint $table) {
            // Eliminamos las columnas y las llaves foráneas si revertimos
            $table->dropForeign(['asignatura_id']);
            $table->dropForeign(['docente_id']);
            $table->dropColumn(['nombre_seccion', 'asignatura_id', 'docente_id']);
        });
    }
};