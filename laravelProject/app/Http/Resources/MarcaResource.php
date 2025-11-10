<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para serializar Marca.
 */
class MarcaResource extends JsonResource
{
    /**
     * Transforma el resource en un array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Domain\Entities\Marca $marca */
        $marca = $this->resource;
        
        return [
            'id' => $marca->id,
            'nombre_marca' => $marca->nombreMarca,
            'pais' => $marca->pais,
        ];
    }
}

