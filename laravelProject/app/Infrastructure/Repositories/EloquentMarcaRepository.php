<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Marca;
use App\Domain\Repositories\MarcaRepositoryInterface;
use App\Infrastructure\Eloquent\Models\MarcaVehiculo;

/**
 * Implementación del repositorio de Marca usando Eloquent.
 *
 * Esta clase implementa la interfaz del dominio usando Eloquent ORM.
 * Separa la lógica de persistencia del dominio.
 */
class EloquentMarcaRepository implements MarcaRepositoryInterface
{
    public function findById(int $id): ?Marca
    {
        $model = MarcaVehiculo::find($id);
        
        return $model ? $this->toEntity($model) : null;
    }

    public function findAll(int $skip = 0, int $limit = 100): array
    {
        $models = MarcaVehiculo::skip($skip)->limit($limit)->get();
        
        return $models->map(fn ($model) => $this->toEntity($model))->toArray();
    }

    public function findByNombre(string $nombreMarca): ?Marca
    {
        $model = MarcaVehiculo::where('nombre_marca', $nombreMarca)->first();
        
        return $model ? $this->toEntity($model) : null;
    }

    public function save(Marca $marca): Marca
    {
        $model = $marca->id 
            ? MarcaVehiculo::findOrFail($marca->id)
            : new MarcaVehiculo();
        
        $model->nombre_marca = $marca->nombreMarca;
        $model->pais = $marca->pais;
        $model->save();
        
        return $this->toEntity($model);
    }

    public function delete(int $id): bool
    {
        return MarcaVehiculo::destroy($id) > 0;
    }

    /**
     * Convierte un modelo Eloquent a entidad de dominio.
     *
     * @param MarcaVehiculo $model
     * @return Marca
     */
    private function toEntity(MarcaVehiculo $model): Marca
    {
        return new Marca(
            id: $model->id,
            nombreMarca: $model->nombre_marca,
            pais: $model->pais,
        );
    }
}


