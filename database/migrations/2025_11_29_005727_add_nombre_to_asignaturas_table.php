<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('asignaturas', function (Blueprint $table) {
            // Agregamos la columna 'nombre' después de 'id' (o donde prefieras)
            $table->string('nombre')->after('id');
        });
    }

    public function down()
    {
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->dropColumn('nombre');
        });
    }
};