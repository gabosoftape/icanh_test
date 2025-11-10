<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Marca;

/**
 * Interfaz del repositorio para Marca.
 *
 * Define el contrato que deben cumplir las implementaciones de repositorio.
 * Sigue el principio de inversión de dependencias (SOLID).
 */
interface MarcaRepositoryInterface
{
    /**
     * Encuentra una marca por ID.
     *
     * @param int $id
     * @return Marca|null
     */
    public function findById(int $id): ?Marca;

    /**
     * Encuentra todas las marcas.
     *
     * @param int $skip
     * @param int $limit
     * @return array<Marca>
     */
    public function findAll(int $skip = 0, int $limit = 100): array;

    /**
     * Encuentra una marca por nombre.
     *
     * @param string $nombreMarca
     * @return Marca|null
     */
    public function findByNombre(string $nombreMarca): ?Marca;

    /**
     * Guarda una marca (crea o actualiza).
     *
     * @param Marca $marca
     * @return Marca
     */
    public function save(Marca $marca): Marca;

    /**
     * Elimina una marca por ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}


