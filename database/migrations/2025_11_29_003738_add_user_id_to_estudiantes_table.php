<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            // Agregamos la columna user_id para vincular al estudiante con la tabla users (Login)
            // Lo dejamos 'nullable' por si tienes estudiantes antiguos sin usuario
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('id') // La ponemos al principio
                  ->constrained('users')
                  ->onDelete('set null'); // Si se borra el usuario, no borramos el historial académico
        });
    }

    public function down()
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};