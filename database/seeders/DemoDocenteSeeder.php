<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use App\Models\Estudiante;
use App\Models\Asignatura;
use App\Models\Seccion;
use App\Models\Caso;
use Illuminate\Support\Facades\Hash;

class DemoDocenteSeeder extends Seeder
{
    public function run()
    {
        // 1. Roles
        $rolDocente = Rol::firstOrCreate(['nombre_rol' => 'Docente']);
        $rolEstudiante = Rol::firstOrCreate(['nombre_rol' => 'Estudiante']);

        // ---------------------------------------------------------
        // 2. CREAR DOS PROFESORES
        // ---------------------------------------------------------
        // Profe 1: Roberto (El que usaremos nosotros)
        $profeRoberto = User::firstOrCreate(
            ['email' => 'roberto.alveal@example.com'], 
            ['name' => 'Roberto Alveal', 'password' => Hash::make('password'), 'rol_id' => $rolDocente->id]
        );

        // Profe 2: Maria (De otra asignatura, misma sección)
        $profeMaria = User::firstOrCreate(
            ['email' => 'maria.ingles@example.com'], 
            ['name' => 'Maria English', 'password' => Hash::make('password'), 'rol_id' => $rolDocente->id]
        );

        // ---------------------------------------------------------
        // 3. CREAR ASIGNATURAS
        // ---------------------------------------------------------
        $ramoSeguridad = Asignatura::firstOrCreate(['codigo' => 'INFO-SEG'], ['nombre' => 'Seguridad Info']);
        $ramoIngles = Asignatura::firstOrCreate(['codigo' => 'ING-IV'], ['nombre' => 'Inglés IV']);

        // ---------------------------------------------------------
        // 4. CREAR LAS SECCIONES (AQUÍ ESTÁ LA LÓGICA QUE PIDES)
        // ---------------------------------------------------------
        // Ambas se llaman "IEI-170", pero son instancias diferentes
        
        $seccionRoberto = Seccion::firstOrCreate(
            ['asignatura_id' => $ramoSeguridad->id, 'docente_id' => $profeRoberto->id],
            ['nombre_seccion' => 'IEI-170'] // Misma etiqueta
        );

        $seccionMaria = Seccion::firstOrCreate(
            ['asignatura_id' => $ramoIngles->id, 'docente_id' => $profeMaria->id],
            ['nombre_seccion' => 'IEI-170'] // Misma etiqueta
        );

        // ---------------------------------------------------------
        // 5. CREAR AL ESTUDIANTE: JUAN PÉREZ
        // ---------------------------------------------------------
        $userJuan = User::firstOrCreate(
            ['email' => 'juan.perez@example.com'],
            ['name' => 'Juan Pérez', 'password' => Hash::make('password'), 'rol_id' => $rolEstudiante->id]
        );

        $perfilJuan = Estudiante::firstOrCreate(
            ['rut' => '12345678-9'],
            [
                'user_id' => $userJuan->id, 
                'nombre_completo' => $userJuan->name,
                'correo' => $userJuan->email,
                'carrera' => 'Ingeniería en Informática',
                'jornada' => 'Diurno',
                'sede' => 'Campus Central',
                'area_academica' => 'Tecnología',
                'fecha_nacimiento' => '2000-01-01',
                'telefono' => '912345678',
                'edad' => 24
            ]
        );

        // ---------------------------------------------------------
        // 6. MATRICULAR A JUAN EN AMBAS (Misma sección, distintos ramos)
        // ---------------------------------------------------------
        // Juan está en la IEI-170 de Seguridad Y en la IEI-170 de Inglés
        $seccionRoberto->estudiantes()->syncWithoutDetaching([$perfilJuan->id]);
        $seccionMaria->estudiantes()->syncWithoutDetaching([$perfilJuan->id]);

        // ---------------------------------------------------------
        // 7. CREAR CASO FINALIZADO
        // ---------------------------------------------------------
        if (!$perfilJuan->casos()->exists()) {
            Caso::create([
                'estudiante_id' => $perfilJuan->id,
                'asesoria_id' => 1, 
                'director_id' => 1, 
                'rut_estudiante' => '12345678-9',
                'nombre_estudiante' => 'Juan Pérez',
                'correo_estudiante' => 'juan.perez@example.com',
                'carrera' => 'Ingeniería en Informática',
                'estado' => 'Finalizado', 
                'ajustes_propuestos' => ['Uso de grabadora.', 'Tiempo extra.'],
                'motivo_decision' => 'Validado.',
                'via_ingreso' => 'Admisión',
                'requiere_apoyos' => true,
            ]);
        }
    }
}