<?php

namespace Tests\Feature\Api;

use App\Infrastructure\Eloquent\Models\MarcaVehiculo;
use App\Infrastructure\Eloquent\Models\Persona;
use App\Infrastructure\Eloquent\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de integración para el API de Vehículos.
 */
class VehiculoTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_vehiculo(): void
    {
        $marca = MarcaVehiculo::factory()->create();
        
        $response = $this->postJson('/api/vehiculos', [
            'modelo' => 'Corolla',
            'marca_id' => $marca->id,
            'numero_puertas' => 4,
            'color' => 'Rojo',
        ]);
        
        $response->assertStatus(201);
    }

    public function test_asignar_propietario(): void
    {
        $marca = MarcaVehiculo::factory()->create();
        $vehiculo = Vehiculo::factory()->create(['marca_id' => $marca->id]);
        $persona = Persona::factory()->create();
        
        $response = $this->postJson("/api/vehiculos/{$vehiculo->id}/propietarios", [
            'persona_id' => $persona->id,
        ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('vehiculo_propietario', [
            'vehiculo_id' => $vehiculo->id,
            'persona_id' => $persona->id,
        ]);
    }

    public function test_obtener_vehiculos_de_persona(): void
    {
        $marca = MarcaVehiculo::factory()->create();
        $persona = Persona::factory()->create();
        $vehiculo = Vehiculo::factory()->create(['marca_id' => $marca->id]);
        $vehiculo->propietarios()->attach($persona->id);
        
        $response = $this->getJson("/api/personas/{$persona->id}/vehiculos");
        
        $response->assertStatus(200)->assertJsonCount(1, 'data');
    }
}


