<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('casos', function (Blueprint $table) {
            // Modificamos las columnas para que sean de tipo JSON (y permitan nulos)
            // Si ya existen, las cambiamos. Si no, habría que crearlas (pero asumo que existen por tu código anterior)
            
            // 1. Para la lista de discapacidades (Checkboxes del inicio)
            $table->json('tipo_discapacidad')->nullable()->change();

            // 2. Para la lista de solicitudes del estudiante (Anamnesis)
            $table->json('ajustes_propuestos')->nullable()->change();

            // 3. Para la lista de ajustes técnicos seleccionados por el CTP
            $table->json('ajustes_ctp')->nullable()->change();

            // 4. Para la evaluación detallada del Director (Decisión + Comentario por ítem)
            $table->json('evaluacion_director')->nullable()->change();
            
            // 5. Para la familia (si la usas)
            $table->json('informacion_familiar')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('casos', function (Blueprint $table) {
            // En caso de revertir, las volvemos a texto simple (LONGTEXT para que quepan datos)
            $table->text('tipo_discapacidad')->nullable()->change();
            $table->text('ajustes_propuestos')->nullable()->change();
            $table->text('ajustes_ctp')->nullable()->change();
            $table->text('evaluacion_director')->nullable()->change();
            $table->text('informacion_familiar')->nullable()->change();
        });
    }
};