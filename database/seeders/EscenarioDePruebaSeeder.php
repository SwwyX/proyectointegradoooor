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
use Carbon\Carbon;
use Faker\Factory as Faker;

class EscenarioDePruebaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_CL');
        $this->command->info('--- Iniciando creación del Escenario de Defensa (Datos Ricos) ---');

        // 1. OBTENER O CREAR ROLES
        $rolDocente = Rol::firstOrCreate(['nombre_rol' => 'Docente']);
        $rolEstudiante = Rol::firstOrCreate(['nombre_rol' => 'Estudiante']);
        // Asegúrate de tener estos roles en tu BD o créalos aquí si no existen
        $rolEncargada = Rol::firstOrCreate(['nombre_rol' => 'Encargada Inclusión']);
        $rolCTP = Rol::firstOrCreate(['nombre_rol' => 'Coordinador CTP']);
        $rolDirector = Rol::firstOrCreate(['nombre_rol' => 'Director']);

        // 2. CREAR USUARIOS RESPONSABLES (Para que los nombres en el reporte sean reales)
        
        // Encargada (Quien crea el caso)
        $userEncargada = User::firstOrCreate(
            ['email' => 'maria.encargada@inacapmail.cl'],
            ['name' => 'Maria Encargada', 'password' => Hash::make('password'), 'rol_id' => $rolEncargada->id]
        );

        // CTP (Quien propone ajustes)
        $userCTP = User::firstOrCreate(
            ['email' => 'carlos.ctp@inacapmail.cl'],
            ['name' => 'Carlos Coordinador', 'password' => Hash::make('password'), 'rol_id' => $rolCTP->id]
        );

        // Directora (Quien valida)
        $userDirectora = User::firstOrCreate(
            ['email' => 'ana.directora@inacapmail.cl'],
            ['name' => 'Ana Directora', 'password' => Hash::make('password'), 'rol_id' => $rolDirector->id]
        );

        // 3. DEFINIR CARRERAS (Lista completa para gráficos)
        $carreras = [
            'Ingeniería en Informática', 'Analista Programador',
            'Enfermería', 'Técnico en Odontología',
            'Mecánica Automotriz', 'Ingeniería en Mecánica',
            'Gastronomía Internacional', 'Administración Gastronómica',
            'Ingeniería en Administración de Empresas', 'Ingeniería en Logística',
            'Diseño Gráfico', 'Trabajo Social',
            'Construcción Civil', 'Ingeniería Eléctrica',
            'Técnico en Automatización y Robótica'
        ];

        // 4. BUCLE MAESTRO: Crear ecosistema por cada carrera
        foreach ($carreras as $index => $nombreCarrera) {
            
            $prefix = strtoupper(substr(str_replace(' ', '', $nombreCarrera), 0, 6)); 
            
            // --- A. CREAR PROFESOR ---
            $profe = User::firstOrCreate(
                ['email' => 'profe.' . strtolower(substr($prefix, 0, 3)) . $index . '@inacapmail.cl'],
                [
                    'name' => 'Profesor ' . $nombreCarrera,
                    'password' => Hash::make('password'),
                    'rol_id' => $rolDocente->id,
                ]
            );

            // --- B. CREAR ASIGNATURA ---
            $ramo = Asignatura::firstOrCreate(
                ['codigo' => $prefix . '-101'],
                ['nombre' => 'Introducción a ' . $nombreCarrera]
            );

            // --- C. CREAR SECCIÓN ---
            $seccion = Seccion::firstOrCreate(
                ['nombre_seccion' => $prefix . '-SEC1'], 
                [
                    'asignatura_id' => $ramo->id,
                    'docente_id' => $profe->id
                ]
            );

            // --- D. CREAR 4 ESTUDIANTES POR CARRERA ---
            for ($i = 1; $i <= 4; $i++) {
                
                // Generar datos enriquecidos para el estudiante
                $trabaja = $faker->boolean(40); // 40% de probabilidad de trabajar
                $pie = $faker->boolean(30); // 30% de probabilidad de haber estado en PIE
                $repitencia = $faker->boolean(20);
                $estudiosPrevios = $faker->boolean(25);

                // Crear Usuario Estudiante
                $userEst = User::firstOrCreate(
                    ['email' => 'estudiante' . $i . '.' . strtolower($prefix) . '@inacapmail.cl'],
                    [
                        'name' => $faker->firstName . ' ' . $faker->lastName,
                        'password' => Hash::make('password'),
                        'rol_id' => $rolEstudiante->id,
                    ]
                );

                // Crear Ficha Estudiante (Datos Académicos)
                $rutFicticio = ($index + 10) . $i . rand(100,999) . '-' . rand(0,9);
                
                $perfilEst = Estudiante::firstOrCreate(
                    ['rut' => $rutFicticio],
                    [
                        'user_id' => $userEst->id,
                        'nombre_completo' => $userEst->name,
                        'correo' => $userEst->email,
                        'carrera' => $nombreCarrera,
                        'jornada' => $faker->randomElement(['Diurno', 'Vespertino']),
                        'sede' => 'Sede Central',
                        'area_academica' => 'Área Técnica',
                        'fecha_nacimiento' => $faker->date('Y-m-d', '2005-01-01'),
                        'telefono' => '+569' . rand(10000000, 99999999),
                        'edad' => rand(19, 35)
                    ]
                );

                // Matricular en sección
                if(method_exists($seccion, 'estudiantes')) {
                    $seccion->estudiantes()->syncWithoutDetaching([$perfilEst->id]);
                }

                // --- F. CREAR CASO (80% de probabilidad para tener hartos datos) ---
                if (rand(1, 100) <= 80) {
                    $this->crearCasoRico(
                        $perfilEst, 
                        $faker, 
                        $userEncargada->id, 
                        $userCTP->id, 
                        $userDirectora->id,
                        $trabaja, $pie, $repitencia, $estudiosPrevios // Pasamos los datos booleanos
                    );
                }
            }
        }

        $this->command->info('¡Escenario de Pruebas "Rico en Datos" creado exitosamente!');
    }

    /**
     * Función auxiliar para generar un caso con datos COMPLETOS y REALISTAS
     */
    private function crearCasoRico($estudiante, $faker, $encargadaId, $ctpId, $directoraId, $trabaja, $pie, $repitencia, $estudiosPrevios)
    {
        // 1. ESTADOS PERMITIDOS (Según tu flujo estricto)
        // Damos más peso a 'Finalizado' para que se vean bien los gráficos de éxito
        $estado = $faker->randomElement(['Finalizado', 'Finalizado', 'Finalizado', 'Pendiente de Validacion', 'En Gestion CTP']);
        
        $created_at = Carbon::now()->subDays(rand(10, 150));
        $updated_at = (clone $created_at)->addDays(rand(2, 20));

        // 2. GENERACIÓN DE JSONs COMPLETOS
        $tiposDiscapacidad = $faker->randomElements(['Visual', 'Auditiva', 'Física', 'TEA', 'Mental', 'Intelectual'], rand(1, 2));
        
        $textosSolicitudes = [
            'Presenta dificultades para tomar apuntes rápidos debido a su condición motora. Solicita permiso para grabar audio.',
            'Requiere ubicación preferencial en primera fila por baja visión y material en formato ampliado (Arial 14).',
            'Solicita tiempo adicional (50%) en evaluaciones escritas por diagnóstico de TDAH y ansiedad.',
            'Necesita salir del aula brevemente en caso de crisis sensorial o desregulación.',
            'Requiere uso de computador para pruebas de desarrollo extenso por disgrafía.'
        ];
        $ajustesPropuestos = $faker->randomElements($textosSolicitudes, rand(1, 2));

        $ajustesCTP = null;
        $evaluacionDirector = null;
        $motivoDecision = null;

        // LÓGICA DE ESTADOS
        // Si no está en gestión inicial, ya pasó por el CTP
        if ($estado != 'En Gestion CTP') {
            $ajustesCTP = [
                'Uso de audífonos con cancelación de ruido durante evaluaciones.',
                'Entrega de instrucciones segmentadas y por escrito.',
                'Tiempo adicional del 50% en evaluaciones sumativas teóricas.',
                'Ubicación lejos de fuentes de ruido (ventanas, pasillos).'
            ];
            // Seleccionamos algunos aleatorios
            $ajustesCTP = $faker->randomElements($ajustesCTP, rand(2, 3));
        }

        // Si está finalizado, tiene la firma del Director
        if ($estado == 'Finalizado') {
            $evaluacionDirector = [];
            
            // Simulamos que el director aprueba casi todo, pero a veces rechaza algo
            foreach ($ajustesCTP as $ajuste) {
                $decision = ($faker->boolean(90)) ? 'Aceptado' : 'Rechazado'; // 90% aprobación
                $evaluacionDirector[] = [
                    'decision' => $decision,
                    'comentario' => ($decision == 'Aceptado') 
                        ? 'Se valida el recurso por pertinencia pedagógica.' 
                        : 'No viable administrativamente en este semestre.'
                ];
            }
            
            $motivoDecision = 'Habiendo revisado los antecedentes médicos y la propuesta del CTP, se aprueba la implementación de los ajustes razonables para el presente periodo académico.';
        }

        // 3. DATOS ACADÉMICOS Y LABORALES (RELLENAR N/A)
        $modalidadMedia = $faker->randomElement(['Científico Humanista', 'Técnico Profesional', 'Artístico']);
        $detallePie = $pie ? 'Programa de Integración Escolar en enseñanza básica y media por TDAH.' : null;
        $motivoRepitencia = $repitencia ? 'Repitencia de 2do medio por problemas de salud no diagnosticados a tiempo.' : null;
        
        $institucionPrev = null;
        $carreraPrev = null;
        if ($estudiosPrevios) {
            $institucionPrev = $faker->randomElement(['Universidad de Chile', 'DUOC UC', 'Santo Tomás', 'U. Mayor']);
            $carreraPrev = $faker->randomElement(['Arquitectura', 'Derecho', 'Kinesiología', 'Diseño']);
        }

        $empresa = null;
        $cargo = null;
        if ($trabaja) {
            $empresa = $faker->company;
            $cargo = $faker->jobTitle;
        }

        // 4. CREAR CASO EN BD
        Caso::create([
            'estudiante_id' => $estudiante->id,
            'asesoria_id' => $encargadaId, // La Encargada creó el caso

            // Snapshots
            'rut_estudiante' => $estudiante->rut,
            'nombre_estudiante' => $estudiante->nombre_completo,
            'correo_estudiante' => $estudiante->correo,
            'carrera' => $estudiante->carrera,
            
            // Estado y Flujo
            'estado' => $estado,
            'via_ingreso' => $faker->randomElement(['FUP (Formulario Único)', 'Derivación Docente', 'Solicitud Espontánea']),
            
            // Responsables (Lógica de nulidad según estado)
            'ctp_id' => ($estado != 'En Gestion CTP') ? $ctpId : null,
            'director_id' => ($estado == 'Finalizado') ? $directoraId : null,

            // Datos JSON
            'tipo_discapacidad' => $tiposDiscapacidad,
            'ajustes_propuestos' => $ajustesPropuestos,
            'ajustes_ctp' => $ajustesCTP,
            'evaluacion_director' => $evaluacionDirector,
            
            // Datos Ricos (Adiós N/A)
            'origen_discapacidad' => $faker->randomElement(['Nacimiento', 'Adquirida en la adolescencia', 'Adquirida en la adultez']),
            'credencial_rnd' => $faker->boolean(60), // 60% tiene credencial
            'pension_invalidez' => $faker->boolean(20),
            'certificado_medico' => true, // Casi siempre traen certificado
            
            'tratamiento_farmacologico' => $faker->boolean(50) ? 'Uso de Ritalin 10mg am, Sertralina 50mg pm.' : 'No indica tratamiento actual.',
            'acompanamiento_especialista' => $faker->boolean(40) ? 'Psicólogo particular (sesiones quincenales).' : 'Control médico anual en CESFAM.',
            'redes_apoyo' => 'Vive con sus padres y hermano menor. Cuenta con apoyo familiar constante.',
            
            // Académico Rico
            'enseñanza_media_modalidad' => $modalidadMedia,
            'recibio_apoyos_pie' => $pie,
            'detalle_apoyos_pie' => $detallePie,
            'repitio_curso' => $repitencia,
            'motivo_repeticion' => $motivoRepitencia,
            
            // Superior Rico
            'estudio_previo_superior' => $estudiosPrevios,
            'nombre_institucion_anterior' => $institucionPrev,
            'carrera_anterior' => $carreraPrev,
            
            // Laboral Rico
            'trabaja' => $trabaja,
            'empresa' => $empresa,
            'cargo' => $cargo,

            'caracteristicas_intereses' => 'Estudiante demuestra gran compromiso con su aprendizaje. Manifiesta ansiedad ante evaluaciones orales pero destaca en trabajos prácticos. Le interesa el área de desarrollo de software y videojuegos.',
            'motivo_decision' => $motivoDecision,
            
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);
    }
}