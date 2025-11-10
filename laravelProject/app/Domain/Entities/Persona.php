<?php

namespace App\Domain\Entities;

/**
 * Entidad de dominio para Persona.
 *
 * Representa una persona en el dominio del negocio.
 * Esta es una entidad pura sin dependencias de infraestructura.
 */
class Persona
{
    public function __construct(
        public ?int $id = null,
        public string $nombre = '',
        public string $cedula = '',
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
            nombre: $data['nombre'] ?? '',
            cedula: $data['cedula'] ?? '',
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
            'nombre' => $this->nombre,
            'cedula' => $this->cedula,
        ];
    }
}


