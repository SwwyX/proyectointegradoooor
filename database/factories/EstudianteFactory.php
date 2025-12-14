<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EstudianteFactory extends Factory
{
    public function definition()
    {
        $num = $this->faker->unique()->numberBetween(1000000, 25000000);
        $dv = $this->faker->randomElement(['0','1','2','3','4','5','6','7','8','9','K']);
        
        // Carreras y Áreas simuladas
        $carreras = [
            'Ingeniería en Informática' => 'Tecnología',
            'Diseño Gráfico' => 'Diseño',
            'Mecánica Automotriz' => 'Mecánica',
            'Enfermería' => 'Salud'
        ];
        
        // Seleccionamos una carrera al azar y obtenemos su área correspondiente
        $carreraRandom = $this->faker->randomElement(array_keys($carreras));

        return [
            'rut' => $num . '-' . $dv,
            'nombre_completo' => $this->faker->name(),
            'correo' => $this->faker->unique()->safeEmail(),
            'carrera' => $carreraRandom,
            'area_academica' => $carreras[$carreraRandom],
            
            // Datos nuevos
            'fecha_nacimiento' => $this->faker->date('Y-m-d', '-18 years'),
            'telefono' => '+56 9 ' . $this->faker->numberBetween(10000000, 99999999),
            'telefono_emergencia' => '+56 9 ' . $this->faker->numberBetween(10000000, 99999999),
            'sede' => 'Sede Santiago Centro',
            'jornada' => $this->faker->randomElement(['Diurna', 'Vespertina']),
            
            'edad' => $this->faker->numberBetween(18, 35),
            'foto_perfil' => null,
        ];
    }
}