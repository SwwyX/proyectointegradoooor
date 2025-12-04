<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    protected $fillable = ['nombre', 'codigo'];

    public function secciones()
    {
        return $this->hasMany(Seccion::class);
    }
}