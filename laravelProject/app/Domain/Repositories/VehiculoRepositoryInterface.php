<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Vehiculo;

/**
 * Interfaz del repositorio para Vehículo.
 *
 * Define el contrato que deben cumplir las implementaciones de repositorio.
 * Sigue el principio de inversión de dependencias (SOLID).
 */
interface VehiculoRepositoryInterface
{
    /**
     * Encuentra un vehículo por ID.
     *
     * @param int $id
     * @return Vehiculo|null
     */
    public function findById(int $id): ?Vehiculo;

    /**
     * Encuentra todos los vehículos.
     *
     * @param int $skip
     * @param int $limit
     * @return array<Vehiculo>
     */
    public function findAll(int $skip = 0, int $limit = 100): array;

    /**
     * Guarda un vehículo (crea o actualiza).
     *
     * @param Vehiculo $vehiculo
     * @return Vehiculo
     */
    public function save(Vehiculo $vehiculo): Vehiculo;

    /**
     * Elimina un vehículo por ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Obtiene los vehículos de una persona.
     *
     * @param int $personaId
     * @return array<Vehiculo>
     */
    public function findByPersonaId(int $personaId): array;

    /**
     * Asigna un propietario a un vehículo.
     *
     * @param int $vehiculoId
     * @param int $personaId
     * @return bool
     */
    public function addPropietario(int $vehiculoId, int $personaId): bool;
}


