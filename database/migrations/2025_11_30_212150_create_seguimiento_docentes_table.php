<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seguimiento_docentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caso_id')->constrained('casos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // El profesor que comenta
            $table->text('comentario');
            $table->timestamps(); // Esto guarda la fecha y hora automáticamente
        });
    }

    public function down()
    {
        Schema::dropIfExists('seguimiento_docentes');
    }
};