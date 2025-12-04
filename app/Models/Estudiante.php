<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable = [
        'rut',
        'nombre_completo',
        'correo',
        'carrera',
        
        // Nuevos campos agregados
        'area_academica',
        'fecha_nacimiento',
        'telefono',
        'telefono_emergencia',
        'sede',
        'jornada',
        
        'edad',
        'foto_perfil',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function secciones()
    {
        // Un estudiante pertenece a muchas secciones (clases)
        return $this->belongsToMany(Seccion::class, 'estudiante_seccion', 'estudiante_id', 'seccion_id');

    }

        // Relación: Un estudiante puede tener muchos casos
    public function casos()
    {
        return $this->hasMany(Caso::class);
    }

}