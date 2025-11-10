<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\Models\MarcaVehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para MarcaVehiculo.
 */
class MarcaVehiculoFactory extends Factory
{
    protected $model = MarcaVehiculo::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_marca' => $this->faker->unique()->company(),
            'pais' => $this->faker->country(),
        ];
    }
}


