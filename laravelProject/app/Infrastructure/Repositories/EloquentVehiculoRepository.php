<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Vehiculo;
use App\Domain\Repositories\VehiculoRepositoryInterface;
use App\Infrastructure\Eloquent\Models\Vehiculo as VehiculoModel;

/**
 * Implementación del repositorio de Vehículo usando Eloquent.
 *
 * Esta clase implementa la interfaz del dominio usando Eloquent ORM.
 * Separa la lógica de persistencia del dominio.
 */
class EloquentVehiculoRepository implements VehiculoRepositoryInterface
{
    public function findById(int $id): ?Vehiculo
    {
        $model = VehiculoModel::with(['marca', 'propietarios'])->find($id);
        
        return $model ? $this->toEntity($model) : null;
    }

    public function findAll(int $skip = 0, int $limit = 100): array
    {
        $models = VehiculoModel::with(['marca', 'propietarios'])
            ->skip($skip)
            ->limit($limit)
            ->get();
        
        return $models->map(fn ($model) => $this->toEntity($model))->toArray();
    }

    public function save(Vehiculo $vehiculo): Vehiculo
    {
        $model = $vehiculo->id 
            ? VehiculoModel::findOrFail($vehiculo->id)
            : new VehiculoModel();
        
        $model->modelo = $vehiculo->modelo;
        $model->marca_id = $vehiculo->marcaId;
        $model->numero_puertas = $vehiculo->numeroPuertas;
        $model->color = $vehiculo->color;
        $model->save();
        
        // Sincronizar propietarios si se proporcionaron
        if (!empty($vehiculo->propietariosIds)) {
            $model->propietarios()->sync($vehiculo->propietariosIds);
        }
        
        return $this->toEntity($model->fresh(['marca', 'propietarios']));
    }

    public function delete(int $id): bool
    {
        return VehiculoModel::destroy($id) > 0;
    }

    public function findByPersonaId(int $personaId): array
    {
        $models = VehiculoModel::whereHas('propietarios', function ($query) use ($personaId) {
            $query->where('personas.id', $personaId);
        })->with(['marca', 'propietarios'])->get();
        
        return $models->map(fn ($model) => $this->toEntity($model))->toArray();
    }

    public function addPropietario(int $vehiculoId, int $personaId): bool
    {
        $vehiculo = VehiculoModel::findOrFail($vehiculoId);
        
        // Verificar si ya existe la relación
        if ($vehiculo->propietarios()->where('personas.id', $personaId)->exists()) {
            return false;
        }
        
        $vehiculo->propietarios()->attach($personaId);
        
        return true;
    }

    /**
     * Convierte un modelo Eloquent a entidad de dominio.
     *
     * @param VehiculoModel $model
     * @return Vehiculo
     */
    private function toEntity(VehiculoModel $model): Vehiculo
    {
        return new Vehiculo(
            id: $model->id,
            modelo: $model->modelo,
            marcaId: $model->marca_id,
            numeroPuertas: $model->numero_puertas,
            color: $model->color,
            propietariosIds: $model->propietarios->pluck('id')->toArray(),
        );
    }
}


