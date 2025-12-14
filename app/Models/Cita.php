<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudiante_id', 'encargada_id', 'fecha', 'hora_inicio', 'hora_fin', 'estado', 'motivo', 'comentario_encargada'
    ];

    // Relaciones
    public function estudiante() { return $this->belongsTo(Estudiante::class); }
    public function encargada() { return $this->belongsTo(User::class, 'encargada_id'); }
}