<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('rut')->unique();
            $table->string('nombre_completo');
            $table->string('correo');
            $table->string('carrera');
            
            // --- NUEVOS CAMPOS BASADOS EN DOCUMENTO "PRIMERA ENTREVISTA" ---
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono')->nullable(); // Datos de contacto
            $table->string('telefono_emergencia')->nullable(); // Contacto de emergencia
            $table->string('sede')->default('Sede Central');
            $table->string('jornada')->nullable(); // Diurna / Vespertina
            $table->string('area_academica')->nullable(); 
            // --------------------------------------------------------------

            $table->integer('edad')->nullable();
            $table->string('foto_perfil')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estudiantes');
    }
};