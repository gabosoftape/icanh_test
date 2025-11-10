<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\Models\MarcaVehiculo;
use App\Infrastructure\Eloquent\Models\Vehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para Vehiculo.
 */
class VehiculoFactory extends Factory
{
    protected $model = Vehiculo::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'modelo' => $this->faker->word(),
            'marca_id' => MarcaVehiculo::factory(),
            'numero_puertas' => $this->faker->numberBetween(2, 5),
            'color' => $this->faker->colorName(),
        ];
    }
}


