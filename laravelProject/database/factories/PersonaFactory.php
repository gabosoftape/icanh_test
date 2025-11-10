<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para Persona.
 */
class PersonaFactory extends Factory
{
    protected $model = Persona::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'cedula' => $this->faker->unique()->numerify('##########'),
        ];
    }
}


