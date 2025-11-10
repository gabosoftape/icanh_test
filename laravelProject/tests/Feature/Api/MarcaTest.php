<?php

namespace Tests\Feature\Api;

use App\Infrastructure\Eloquent\Models\MarcaVehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de integración para el API de Marcas.
 */
class MarcaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Listar marcas.
     */
    public function test_listar_marcas(): void
    {
        MarcaVehiculo::factory()->create(['nombre_marca' => 'Toyota', 'pais' => 'Japón']);
        MarcaVehiculo::factory()->create(['nombre_marca' => 'Ford', 'pais' => 'EE.UU.']);
        
        $response = $this->getJson('/api/marcas');
        
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'nombre_marca', 'pais']
                ]
            ]);
    }

    /**
     * Test: Crear marca.
     */
    public function test_crear_marca(): void
    {
        $data = [
            'nombre_marca' => 'Toyota',
            'pais' => 'Japón',
        ];
        
        $response = $this->postJson('/api/marcas', $data);
        
        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'nombre_marca', 'pais']])
            ->assertJson(['data' => $data]);
        
        $this->assertDatabaseHas('marcas_vehiculos', $data);
    }

    /**
     * Test: No permite marcas duplicadas.
     */
    public function test_no_permite_marcas_duplicadas(): void
    {
        MarcaVehiculo::factory()->create(['nombre_marca' => 'Toyota']);
        
        $response = $this->postJson('/api/marcas', [
            'nombre_marca' => 'Toyota',
            'pais' => 'Japón',
        ]);
        
        $response->assertStatus(400);
    }

    /**
     * Test: Obtener marca por ID.
     */
    public function test_obtener_marca(): void
    {
        $marca = MarcaVehiculo::factory()->create();
        
        $response = $this->getJson("/api/marcas/{$marca->id}");
        
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'nombre_marca', 'pais']]);
    }

    /**
     * Test: Actualizar marca.
     */
    public function test_actualizar_marca(): void
    {
        $marca = MarcaVehiculo::factory()->create();
        
        $response = $this->putJson("/api/marcas/{$marca->id}", [
            'nombre_marca' => 'Toyota Motor',
            'pais' => 'Japón',
        ]);
        
        $response->assertStatus(200)
            ->assertJson(['data' => ['nombre_marca' => 'Toyota Motor']]);
    }

    /**
     * Test: Eliminar marca.
     */
    public function test_eliminar_marca(): void
    {
        $marca = MarcaVehiculo::factory()->create();
        
        $response = $this->deleteJson("/api/marcas/{$marca->id}");
        
        $response->assertStatus(204);
        $this->assertDatabaseMissing('marcas_vehiculos', ['id' => $marca->id]);
    }
}


