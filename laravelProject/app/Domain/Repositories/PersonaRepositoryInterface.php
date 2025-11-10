<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Persona;

/**
 * Interfaz del repositorio para Persona.
 *
 * Define el contrato que deben cumplir las implementaciones de repositorio.
 * Sigue el principio de inversión de dependencias (SOLID).
 */
interface PersonaRepositoryInterface
{
    /**
     * Encuentra una persona por ID.
     *
     * @param int $id
     * @return Persona|null
     */
    public function findById(int $id): ?Persona;

    /**
     * Encuentra todas las personas.
     *
     * @param int $skip
     * @param int $limit
     * @return array<Persona>
     */
    public function findAll(int $skip = 0, int $limit = 100): array;

    /**
     * Encuentra una persona por cédula.
     *
     * @param string $cedula
     * @return Persona|null
     */
    public function findByCedula(string $cedula): ?Persona;

    /**
     * Guarda una persona (crea o actualiza).
     *
     * @param Persona $persona
     * @return Persona
     */
    public function save(Persona $persona): Persona;

    /**
     * Elimina una persona por ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}


