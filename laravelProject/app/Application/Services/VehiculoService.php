<?php

namespace App\Application\Services;

use App\Domain\Entities\Vehiculo;
use App\Domain\Repositories\MarcaRepositoryInterface;
use App\Domain\Repositories\PersonaRepositoryInterface;
use App\Domain\Repositories\VehiculoRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Servicio de aplicación para Vehículo.
 *
 * Contiene la lógica de negocio para operaciones con vehículos.
 * Sigue el principio de responsabilidad única (SOLID).
 */
class VehiculoService
{
    public function __construct(
        private VehiculoRepositoryInterface $vehiculoRepository,
        private MarcaRepositoryInterface $marcaRepository,
        private PersonaRepositoryInterface $personaRepository
    ) {
    }

    /**
     * Crea un nuevo vehículo.
     *
     * @param array<string, mixed> $data
     * @return Vehiculo
     * @throws ModelNotFoundException Si la marca no existe
     */
    public function create(array $data): Vehiculo
    {
        // Validar que la marca exista
        $marca = $this->marcaRepository->findById($data['marca_id']);
        if (!$marca) {
            throw new ModelNotFoundException('Marca no encontrada.');
        }
        
        $vehiculo = Vehiculo::fromArray($data);
        
        return $this->vehiculoRepository->save($vehiculo);
    }

    /**
     * Obtiene todos los vehículos.
     *
     * @param int $skip
     * @param int $limit
     * @return array<Vehiculo>
     */
    public function list(int $skip = 0, int $limit = 100): array
    {
        return $this->vehiculoRepository->findAll($skip, $limit);
    }

    /**
     * Obtiene un vehículo por ID.
     *
     * @param int $id
     * @return Vehiculo
     * @throws ModelNotFoundException
     */
    public function get(int $id): Vehiculo
    {
        $vehiculo = $this->vehiculoRepository->findById($id);
        
        if (!$vehiculo) {
            throw new ModelNotFoundException('Vehículo no encontrado.');
        }
        
        return $vehiculo;
    }

    /**
     * Actualiza un vehículo.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return Vehiculo
     * @throws ModelNotFoundException
     */
    public function update(int $id, array $data): Vehiculo
    {
        $vehiculo = $this->get($id);
        
        // Si se está cambiando la marca, validar que exista
        if (isset($data['marca_id']) && $data['marca_id'] !== $vehiculo->marcaId) {
            $marca = $this->marcaRepository->findById($data['marca_id']);
            if (!$marca) {
                throw new ModelNotFoundException('Marca no encontrada.');
            }
        }
        
        // Actualizar campos
        foreach ($data as $key => $value) {
            if ($key === 'modelo') {
                $vehiculo->modelo = $value;
            } elseif ($key === 'marca_id') {
                $vehiculo->marcaId = $value;
            } elseif ($key === 'numero_puertas') {
                $vehiculo->numeroPuertas = $value;
            } elseif ($key === 'color') {
                $vehiculo->color = $value;
            }
        }
        
        return $this->vehiculoRepository->save($vehiculo);
    }

    /**
     * Elimina un vehículo.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $this->get($id); // Valida que exista
        
        return $this->vehiculoRepository->delete($id);
    }

    /**
     * Obtiene los vehículos de una persona.
     *
     * @param int $personaId
     * @return array<Vehiculo>
     * @throws ModelNotFoundException
     */
    public function getVehiculosByPersona(int $personaId): array
    {
        // Validar que la persona exista
        $persona = $this->personaRepository->findById($personaId);
        if (!$persona) {
            throw new ModelNotFoundException('Persona no encontrada.');
        }
        
        return $this->vehiculoRepository->findByPersonaId($personaId);
    }

    /**
     * Asigna un propietario a un vehículo.
     *
     * @param int $vehiculoId
     * @param int $personaId
     * @return Vehiculo
     * @throws ModelNotFoundException
     * @throws \Exception Si el propietario ya está asignado
     */
    public function addPropietario(int $vehiculoId, int $personaId): Vehiculo
    {
        // Validar que el vehículo exista
        $vehiculo = $this->get($vehiculoId);
        
        // Validar que la persona exista
        $persona = $this->personaRepository->findById($personaId);
        if (!$persona) {
            throw new ModelNotFoundException('Persona no encontrada.');
        }
        
        // Intentar agregar el propietario
        $added = $this->vehiculoRepository->addPropietario($vehiculoId, $personaId);
        if (!$added) {
            throw new \Exception('El propietario ya está asociado a este vehículo.');
        }
        
        return $this->get($vehiculoId);
    }
}


