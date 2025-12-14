<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. ELIMINAR LA TABLA PIVOTE ANTIGUA (Si existe)
        // Esto soluciona el problema de tener los ajustes "por separado" en otra tabla.
        Schema::dropIfExists('caso_ajuste'); 
        Schema::dropIfExists('catalogo_ajustes');

        Schema::table('casos', function (Blueprint $table) {
            
            // 2. ELIMINAR COLUMNAS BOOLEANAS ANTIGUAS (Si las tenías separadas)
            // Agrega aquí si tenías columnas como 'tiene_discapacidad_visual', etc.
            // $table->dropColumn(['discapacidad_visual', 'discapacidad_auditiva']); 

            // 3. ASEGURAR QUE LAS COLUMNAS JSON EXISTAN
            // Usamos 'longText' para máxima compatibilidad o 'json' si tu MySQL es moderno.
            // Si la columna ya existe, la modificamos. Si no, la creamos.
            
            if (!Schema::hasColumn('casos', 'tipo_discapacidad')) {
                $table->json('tipo_discapacidad')->nullable();
            } else {
                $table->json('tipo_discapacidad')->nullable()->change();
            }

            if (!Schema::hasColumn('casos', 'ajustes_propuestos')) {
                $table->json('ajustes_propuestos')->nullable(); // Anamnesis
            } else {
                $table->json('ajustes_propuestos')->nullable()->change();
            }

            if (!Schema::hasColumn('casos', 'ajustes_ctp')) {
                $table->json('ajustes_ctp')->nullable(); // Ajustes del CTP (Aquí se guardarán todos juntos)
            } else {
                $table->json('ajustes_ctp')->nullable()->change();
            }

            if (!Schema::hasColumn('casos', 'evaluacion_director')) {
                $table->json('evaluacion_director')->nullable(); // Decisiones del Director
            } else {
                $table->json('evaluacion_director')->nullable()->change();
            }
            
            if (!Schema::hasColumn('casos', 'informacion_familiar')) {
                $table->json('informacion_familiar')->nullable();
            } else {
                $table->json('informacion_familiar')->nullable()->change();
            }
        });
    }

    public function down()
    {
        // En caso de error, revertimos (básico)
        Schema::table('casos', function (Blueprint $table) {
            // No podemos recuperar datos borrados de tablas pivote fácilmente, 
            // pero podemos revertir el tipo de dato.
        });
    }
};