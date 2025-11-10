<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para serializar Persona.
 */
class PersonaResource extends JsonResource
{
    /**
     * Transforma el resource en un array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Domain\Entities\Persona $persona */
        $persona = $this->resource;
        
        return [
            'id' => $persona->id,
            'nombre' => $persona->nombre,
            'cedula' => $persona->cedula,
        ];
    }
}

