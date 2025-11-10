<?php

namespace Tests\Feature\Api;

use App\Infrastructure\Eloquent\Models\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de integración para el API de Personas.
 */
class PersonaTest extends TestCase
{
    use RefreshDatabase;

    public function test_listar_personas(): void
    {
        Persona::factory()->count(2)->create();
        
        $response = $this->getJson('/api/personas');
        
        $response->assertStatus(200)->assertJsonCount(2, 'data');
    }

    public function test_crear_persona(): void
    {
        $data = ['nombre' => 'Juan Pérez', 'cedula' => '123456789'];
        
        $response = $this->postJson('/api/personas', $data);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('personas', $data);
    }

    public function test_no_permite_cedulas_duplicadas(): void
    {
        Persona::factory()->create(['cedula' => '123456789']);
        
        $response = $this->postJson('/api/personas', [
            'nombre' => 'Otro',
            'cedula' => '123456789',
        ]);
        
        $response->assertStatus(400);
    }
}


