<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('casos', function (Blueprint $table) {
            $table->id();
            
            // VINCULACIÓN
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            
            // SNAPSHOTS
            $table->string('rut_estudiante')->nullable();
            $table->string('nombre_estudiante')->nullable();
            $table->string('correo_estudiante')->nullable();
            $table->string('carrera')->nullable();

            // USUARIOS
            $table->unsignedBigInteger('asesoria_id'); 
            $table->unsignedBigInteger('ctp_id')->nullable(); 
            $table->unsignedBigInteger('director_id')->nullable(); 
            
            // VÍA DE INGRESO
            $table->string('via_ingreso')->nullable(); 

            // SALUD
            $table->json('tipo_discapacidad')->nullable();
            $table->string('origen_discapacidad')->nullable();
            $table->boolean('credencial_rnd')->default(false);
            $table->boolean('pension_invalidez')->default(false);
            $table->boolean('certificado_medico')->default(false);
            $table->text('tratamiento_farmacologico')->nullable();
            $table->text('acompanamiento_especialista')->nullable();
            $table->text('redes_apoyo')->nullable();
            
            // FAMILIA
            $table->json('informacion_familiar')->nullable();
            
            // ACADÉMICO
            $table->string('enseñanza_media_modalidad')->nullable();
            $table->boolean('recibio_apoyos_pie')->default(false);
            $table->text('detalle_apoyos_pie')->nullable();
            $table->boolean('repitio_curso')->default(false);
            $table->text('motivo_repeticion')->nullable();
            
            // SUPERIOR
            $table->boolean('estudio_previo_superior')->default(false);
            $table->string('nombre_institucion_anterior')->nullable();
            $table->string('tipo_institucion_anterior')->nullable();
            $table->string('carrera_anterior')->nullable();
            $table->text('motivo_no_termino')->nullable();
            
            // LABORAL
            $table->boolean('trabaja')->default(false);
            $table->string('empresa')->nullable();
            $table->string('cargo')->nullable();
            
            // INTERESES
            $table->text('caracteristicas_intereses')->nullable();

            // SOLICITUDES Y RESPUESTAS (FLUJO TÉCNICO)
            $table->boolean('requiere_apoyos')->default(false);
            $table->json('ajustes_propuestos')->nullable(); // Lista del Estudiante
            $table->json('ajustes_ctp')->nullable();        // Lista del CTP
            
            // --- NUEVO: EVALUACIÓN DETALLADA DEL DIRECTOR ---
            $table->json('evaluacion_director')->nullable(); // Lista de decisiones y comentarios por ítem
            
            $table->enum('estado', ['Sin Revision', 'En Revision', 'Aceptado', 'Rechazado', 'Reevaluacion'])->default('Sin Revision');
            $table->text('motivo_decision')->nullable(); // Comentario global (opcional ahora)

            $table->timestamps();

            $table->foreign('asesoria_id')->references('id')->on('users');
            $table->foreign('ctp_id')->references('id')->on('users');
            $table->foreign('director_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('casos');
    }
};