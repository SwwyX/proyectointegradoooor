<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            // Dejamos nullable para permitir bloqueos administrativos (sin estudiante)
            $table->foreignId('estudiante_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('encargada_id')->nullable()->constrained('users'); 

            // Datos de la Cita
            $table->date('fecha'); 
            $table->time('hora_inicio'); 
            $table->time('hora_fin'); 
            
            // Estado y Motivo
            $table->string('estado')->default('Pendiente'); 
            $table->string('motivo')->nullable(); 
            $table->text('comentario_encargada')->nullable(); 

            $table->timestamps();

            // --- CAMBIO IMPORTANTE ---
            // Eliminamos las líneas $table->unique(...)
            // Ahora la Base de Datos permitirá guardar múltiples filas con la misma hora
            // (por ejemplo: 1 cancelada y 1 nueva confirmada).
            // La lógica de "no topar horarios activos" la controlará el Controlador.
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas');
    }
};