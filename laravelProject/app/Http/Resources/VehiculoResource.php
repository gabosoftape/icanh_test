<?php

namespace App\Http\Resources;

use App\Domain\Entities\Marca;
use App\Domain\Entities\Persona;
use App\Domain\Entities\Vehiculo;
use App\Domain\Repositories\MarcaRepositoryInterface;
use App\Domain\Repositories\PersonaRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para serializar Vehículo.
 */
class VehiculoResource extends JsonResource
{
    /**
     * Transforma el resource en un array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Vehiculo $vehiculo */
        $vehiculo = $this->resource;
        
        $marca = app(MarcaRepositoryInterface::class)->findById($vehiculo->marcaId);
        $propietarios = [];
        
        foreach ($vehiculo->propietariosIds as $personaId) {
            $persona = app(PersonaRepositoryInterface::class)->findById($personaId);
            if ($persona) {
                $propietarios[] = [
                    'id' => $persona->id,
                    'nombre' => $persona->nombre,
                    'cedula' => $persona->cedula,
                ];
            }
        }
        
        return [
            'id' => $vehiculo->id,
            'modelo' => $vehiculo->modelo,
            'marca' => $marca ? [
                'id' => $marca->id,
                'nombre_marca' => $marca->nombreMarca,
                'pais' => $marca->pais,
            ] : null,
            'numero_puertas' => $vehiculo->numeroPuertas,
            'color' => $vehiculo->color,
            'propietarios' => $propietarios,
        ];
    }
}


