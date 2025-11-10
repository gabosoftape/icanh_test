<?php

namespace App\Domain\Entities;

/**
 * Entidad de dominio para Vehículo.
 *
 * Representa un vehículo en el dominio del negocio.
 * Esta es una entidad pura sin dependencias de infraestructura.
 */
class Vehiculo
{
    /**
     * @param array<int> $propietariosIds Lista de IDs de propietarios
     */
    public function __construct(
        public ?int $id = null,
        public string $modelo = '',
        public int $marcaId = 0,
        public int $numeroPuertas = 0,
        public string $color = '',
        public array $propietariosIds = [],
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
            modelo: $data['modelo'] ?? '',
            marcaId: $data['marca_id'] ?? $data['marcaId'] ?? 0,
            numeroPuertas: $data['numero_puertas'] ?? $data['numeroPuertas'] ?? 0,
            color: $data['color'] ?? '',
            propietariosIds: $data['propietarios_ids'] ?? $data['propietariosIds'] ?? [],
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
            'modelo' => $this->modelo,
            'marca_id' => $this->marcaId,
            'numero_puertas' => $this->numeroPuertas,
            'color' => $this->color,
            'propietarios_ids' => $this->propietariosIds,
        ];
    }
}


