<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('asignaturas', function (Blueprint $table) {
            // Agregamos la columna codigo después del id
            $table->string('codigo')->unique()->after('id');
        });
    }

    public function down()
    {
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->dropColumn('codigo');
        });
    }
};