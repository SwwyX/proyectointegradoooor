<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $table = 'secciones';
    protected $fillable = ['nombre_seccion', 'asignatura_id', 'docente_id'];

    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class);
    }

    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'estudiante_seccion', 'seccion_id', 'estudiante_id');
    }
}