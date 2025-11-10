<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Persona;
use App\Domain\Repositories\PersonaRepositoryInterface;
use App\Infrastructure\Eloquent\Models\Persona as PersonaModel;

/**
 * Implementación del repositorio de Persona usando Eloquent.
 *
 * Esta clase implementa la interfaz del dominio usando Eloquent ORM.
 * Separa la lógica de persistencia del dominio.
 */
class EloquentPersonaRepository implements PersonaRepositoryInterface
{
    public function findById(int $id): ?Persona
    {
        $model = PersonaModel::find($id);
        
        return $model ? $this->toEntity($model) : null;
    }

    public function findAll(int $skip = 0, int $limit = 100): array
    {
        $models = PersonaModel::skip($skip)->limit($limit)->get();
        
        return $models->map(fn ($model) => $this->toEntity($model))->toArray();
    }

    public function findByCedula(string $cedula): ?Persona
    {
        $model = PersonaModel::where('cedula', $cedula)->first();
        
        return $model ? $this->toEntity($model) : null;
    }

    public function save(Persona $persona): Persona
    {
        $model = $persona->id 
            ? PersonaModel::findOrFail($persona->id)
            : new PersonaModel();
        
        $model->nombre = $persona->nombre;
        $model->cedula = $persona->cedula;
        $model->save();
        
        return $this->toEntity($model);
    }

    public function delete(int $id): bool
    {
        return PersonaModel::destroy($id) > 0;
    }

    /**
     * Convierte un modelo Eloquent a entidad de dominio.
     *
     * @param PersonaModel $model
     * @return Persona
     */
    private function toEntity(PersonaModel $model): Persona
    {
        return new Persona(
            id: $model->id,
            nombre: $model->nombre,
            cedula: $model->cedula,
        );
    }
}


