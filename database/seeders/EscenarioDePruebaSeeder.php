<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use App\Models\Estudiante;
use App\Models\Asignatura;
use App\Models\Seccion;
use Illuminate\Support\Facades\Hash;

class EscenarioDePruebaSeeder extends Seeder
{
    public function run()
    {
        // 1. Obtener Roles
        $rolDocente = Rol::firstOrCreate(['nombre_rol' => 'Docente']);
        $rolEstudiante = Rol::firstOrCreate(['nombre_rol' => 'Estudiante']);

        // 2. Definir las Carreras para la prueba
        $carreras = [
            'Ingeniería en Informática',
            'Enfermería',
            'Mecánica Automotriz',
            'Gastronomía Internacional'
        ];

        // 3. Bucle Maestro: Crear ecosistema por cada carrera
        foreach ($carreras as $index => $nombreCarrera) {
            
            $prefix = substr($nombreCarrera, 0, 3); // Ej: Ing, Enf, Mec...
            
            // --- A. CREAR PROFESOR ---
            $profe = User::firstOrCreate(
                ['email' => 'profe.' . strtolower($prefix) . '@inacapmail.cl'],
                [
                    'name' => 'Profesor ' . $nombreCarrera,
                    'password' => Hash::make('password'),
                    'rol_id' => $rolDocente->id,
                ]
            );

            // --- B. CREAR ASIGNATURA ---
            $ramo = Asignatura::firstOrCreate(
                ['codigo' => strtoupper($prefix) . '-101'],
                ['nombre' => 'Introducción a ' . $nombreCarrera]
            );

            // --- C. CREAR SECCIÓN ---
            $seccion = Seccion::firstOrCreate(
                ['nombre_seccion' => strtoupper($prefix) . '-SEC1'],
                [
                    'asignatura_id' => $ramo->id,
                    'docente_id' => $profe->id
                ]
            );

            // --- D. CREAR 2 ESTUDIANTES ---
            for ($i = 1; $i <= 2; $i++) {
                // Crear Usuario (Login)
                $userEst = User::firstOrCreate(
                    ['email' => 'estudiante' . $i . '.' . strtolower($prefix) . '@inacapmail.cl'],
                    [
                        'name' => 'Estudiante ' . $i . ' de ' . $prefix,
                        'password' => Hash::make('password'),
                        'rol_id' => $rolEstudiante->id,
                    ]
                );

                // Crear Perfil Académico
                $perfilEst = Estudiante::firstOrCreate(
                    ['rut' => ($index + 1) . $i . '111111-1'], // RUT Ficticio único
                    [
                        'user_id' => $userEst->id,
                        'nombre_completo' => $userEst->name,
                        'correo' => $userEst->email,
                        'carrera' => $nombreCarrera,
                        'jornada' => 'Diurno',
                        'sede' => 'Sede Central',
                        'area_academica' => 'Área Técnica',
                        'fecha_nacimiento' => '2000-01-01',
                        'telefono' => '912345678',
                        'edad' => 20 + $i
                    ]
                );

                // --- E. MATRICULAR EN LA SECCIÓN (Conexión) ---
                // Esto permite que el profesor vea a este alumno
                $seccion->estudiantes()->syncWithoutDetaching([$perfilEst->id]);
            }
        }

        $this->command->info('¡Escenario de Pruebas creado!');
        $this->command->info('Se crearon 4 Profesores, 4 Asignaturas y 8 Estudiantes matriculados.');
    }
}