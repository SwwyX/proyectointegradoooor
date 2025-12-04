<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('estudiante_seccion', function (Blueprint $table) {
            // 1. Conexión con el Estudiante
            // onDelete('cascade') significa que si borras al estudiante, se borra su inscripción
            $table->foreignId('estudiante_id')->after('id')->constrained('estudiantes')->onDelete('cascade');

            // 2. Conexión con la Sección
            $table->foreignId('seccion_id')->after('estudiante_id')->constrained('secciones')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('estudiante_seccion', function (Blueprint $table) {
            $table->dropForeign(['estudiante_id']);
            $table->dropForeign(['seccion_id']);
            $table->dropColumn(['estudiante_id', 'seccion_id']);
        });
    }
};