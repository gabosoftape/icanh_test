<?php

namespace App\Application\Services;

use App\Domain\Entities\Marca;
use App\Domain\Repositories\MarcaRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Servicio de aplicación para Marca.
 *
 * Contiene la lógica de negocio para operaciones con marcas.
 * Sigue el principio de responsabilidad única (SOLID).
 */
class MarcaService
{
    public function __construct(
        private MarcaRepositoryInterface $repository
    ) {
    }

    /**
     * Crea una nueva marca.
     *
     * @param array<string, mixed> $data
     * @return Marca
     * @throws \Exception Si la marca ya existe
     */
    public function create(array $data): Marca
    {
        // Validar que no exista una marca con el mismo nombre
        $existing = $this->repository->findByNombre($data['nombre_marca']);
        if ($existing) {
            throw new \Exception('Ya existe una marca con ese nombre.');
        }
        
        $marca = Marca::fromArray($data);
        
        return $this->repository->save($marca);
    }

    /**
     * Obtiene todas las marcas.
     *
     * @param int $skip
     * @param int $limit
     * @return array<Marca>
     */
    public function list(int $skip = 0, int $limit = 100): array
    {
        return $this->repository->findAll($skip, $limit);
    }

    /**
     * Obtiene una marca por ID.
     *
     * @param int $id
     * @return Marca
     * @throws ModelNotFoundException
     */
    public function get(int $id): Marca
    {
        $marca = $this->repository->findById($id);
        
        if (!$marca) {
            throw new ModelNotFoundException('Marca no encontrada.');
        }
        
        return $marca;
    }

    /**
     * Actualiza una marca.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Marca
     * @throws ModelNotFoundException
     * @throws \Exception Si el nombre ya existe en otra marca
     */
    public function update(int $id, array $data): Marca
    {
        $marca = $this->get($id);
        
        // Si se está cambiando el nombre, validar que no exista
        if (isset($data['nombre_marca']) && $data['nombre_marca'] !== $marca->nombreMarca) {
            $existing = $this->repository->findByNombre($data['nombre_marca']);
            if ($existing && $existing->id !== $id) {
                throw new \Exception('Ya existe una marca con ese nombre.');
            }
        }
        
        // Actualizar campos
        foreach ($data as $key => $value) {
            if ($key === 'nombre_marca') {
                $marca->nombreMarca = $value;
            } elseif ($key === 'pais') {
                $marca->pais = $value;
            }
        }
        
        return $this->repository->save($marca);
    }

    /**
     * Elimina una marca.
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


