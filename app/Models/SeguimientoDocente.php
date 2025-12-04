<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoDocente extends Model
{
    protected $table = 'seguimiento_docentes';
    
    protected $fillable = ['caso_id', 'user_id', 'comentario'];

    public function docente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}