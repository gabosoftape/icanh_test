<?php

namespace App\Domain\Entities;

/**
 * Entidad de dominio para Marca de Vehículo.
 *
 * Representa una marca de vehículo en el dominio del negocio.
 * Esta es una entidad pura sin dependencias de infraestructura.
 */
class Marca
{
    public function __construct(
        public ?int $id = null,
        public string $nombreMarca = '',
        public string $pais = '',
    ) {
    }

    /**
     * Crea una nueva instancia desde un array.
     *
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            nombreMarca: $data['nombre_marca'] ?? $data['nombreMarca'] ?? '',
            pais: $data['pais'] ?? '',
        );
    }

    /**
     * Convierte la entidad a array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre_marca' => $this->nombreMarca,
            'pais' => $this->pais,
        ];
    }
}


