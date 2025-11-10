<?php

namespace App\Application\Services;

use App\Domain\Entities\Persona;
use App\Domain\Repositories\PersonaRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Servicio de aplicación para Persona.
 *
 * Contiene la lógica de negocio para operaciones con personas.
 * Sigue el principio de responsabilidad única (SOLID).
 */
class PersonaService
{
    public function __construct(
        private PersonaRepositoryInterface $repository
    ) {
    }

    /**
     * Crea una nueva persona.
     *
     * @param array<string, mixed> $data
     * @return Persona
     * @throws \Exception Si la cédula ya existe
     */
    public function create(array $data): Persona
    {
        // Validar que no exista una persona con la misma cédula
        $existing = $this->repository->findByCedula($data['cedula']);
        if ($existing) {
            throw new \Exception('Ya existe una persona con esa cédula.');
        }
        
        $persona = Persona::fromArray($data);
        
        return $this->repository->save($persona);
    }

    /**
     * Obtiene todas las personas.
     *
     * @param int $skip
     * @param int $limit
     * @return array<Persona>
     */
    public function list(int $skip = 0, int $limit = 100): array
    {
        return $this->repository->findAll($skip, $limit);
    }

    /**
     * Obtiene una persona por ID.
     *
     * @param int $id
     * @return Persona
     * @throws ModelNotFoundException
     */
    public function get(int $id): Persona
    {
        $persona = $this->repository->findById($id);
        
        if (!$persona) {
            throw new ModelNotFoundException('Persona no encontrada.');
        }
        
        return $persona;
    }

    /**
     * Actualiza una persona.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Persona
     * @throws ModelNotFoundException
     * @throws \Exception Si la cédula ya existe en otra persona
     */
    public function update(int $id, array $data): Persona
    {
        $persona = $this->get($id);
        
        // Si se está cambiando la cédula, validar que no exista
        if (isset($data['cedula']) && $data['cedula'] !== $persona->cedula) {
            $existing = $this->repository->findByCedula($data['cedula']);
            if ($existing && $existing->id !== $id) {
                throw new \Exception('Ya existe una persona con esa cédula.');
            }
        }
        
        // Actualizar campos
        foreach ($data as $key => $value) {
            if ($key === 'nombre') {
                $persona->nombre = $value;
            } elseif ($key === 'cedula') {
                $persona->cedula = $value;
            }
        }
        
        return $this->repository->save($persona);
    }

    /**
     * Elimina una persona.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $this->get($id); // Valida que exista
        
        return $this->repository->delete($id);
    }
}


