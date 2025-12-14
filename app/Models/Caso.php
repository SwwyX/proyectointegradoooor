<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caso extends Model
{
    use HasFactory;

    protected $table = 'casos';

    protected $fillable = [
        'estudiante_id', 'rut_estudiante', 'nombre_estudiante', 'correo_estudiante', 'carrera',
        'asesoria_id', 'ctp_id', 'director_id', 'estado', 'motivo_decision',
        'via_ingreso',
        'tipo_discapacidad', 'origen_discapacidad', 'credencial_rnd', 'pension_invalidez',
        'certificado_medico', 'tratamiento_farmacologico', 'acompanamiento_especialista', 'redes_apoyo',
        'informacion_familiar',
        'enseñanza_media_modalidad', 'recibio_apoyos_pie', 'detalle_apoyos_pie',
        'repitio_curso', 'motivo_repeticion',
        'estudio_previo_superior', 'nombre_institucion_anterior', 'tipo_institucion_anterior',
        'carrera_anterior', 'motivo_no_termino',
        'trabaja', 'empresa', 'cargo',
        'caracteristicas_intereses',
        'requiere_apoyos', 
        'ajustes_propuestos', 
        'ajustes_ctp',
        'evaluacion_director',
    ];

    protected $casts = [
        'tipo_discapacidad' => 'array',
        'informacion_familiar' => 'array',
        'ajustes_propuestos' => 'array', 
        'ajustes_ctp' => 'array', 
        'evaluacion_director' => 'array',
        
        'credencial_rnd' => 'boolean',
        'pension_invalidez' => 'boolean',
        'certificado_medico' => 'boolean',
        'recibio_apoyos_pie' => 'boolean',
        'repitio_curso' => 'boolean',
        'estudio_previo_superior' => 'boolean',
        'trabaja' => 'boolean',
        'requiere_apoyos' => 'boolean',
    ];

    public function estudiante() { return $this->belongsTo(Estudiante::class); }
    public function asesor() { return $this->belongsTo(User::class, 'asesoria_id'); }
    public function director() { return $this->belongsTo(User::class, 'director_id'); }
    public function ctp() { return $this->belongsTo(User::class, 'ctp_id'); }
    public function documentos() { return $this->hasMany(Documento::class); }

    /**
     * Relación Muchos a Muchos para confirmar lecturas.
     * Conecta Caso <-> User (Docente) mediante la tabla 'confirmacion_lecturas'.
     */
    public function docentesQueConfirmaron()
    {
        return $this->belongsToMany(User::class, 'confirmacion_lecturas', 'caso_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * NUEVA RELACIÓN: Bitácora de Seguimiento Docente
     * Permite acceder a los comentarios que los profesores han dejado sobre este caso.
     */
    public function seguimientos()
    {
        return $this->hasMany(SeguimientoDocente::class)->orderBy('created_at', 'desc');
    }
}