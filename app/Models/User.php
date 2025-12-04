<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id', // <-- VITAL para que funcione el registro
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación: Un Usuario pertenece a un Rol.
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    // Relación: Un Usuario (Asesor) puede crear muchos Casos.
    public function casosCreados()
    {
        return $this->hasMany(Caso::class, 'asesoria_id');
    }

    // Relación: Un Usuario (CTP) puede redactar muchos Casos.
    public function casosRedactados()
    {
        return $this->hasMany(Caso::class, 'ctp_id');
    }

    public function casosConfirmados()
    {
        return $this->belongsToMany(Caso::class, 'confirmacion_lecturas');
    }

    public function seccionesDictadas()
    {
        // Un usuario (Docente) tiene muchas secciones a su cargo
        return $this->hasMany(Seccion::class, 'docente_id');
    }

    public function estudiante()
    {
        // Un usuario (Estudiante) tiene un perfil de estudiante asociado
        return $this->hasOne(Estudiante::class, 'user_id');
        
    }
}