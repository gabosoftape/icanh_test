<?php

namespace App\Infrastructure\Eloquent\Models;

use Database\Factories\PersonaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Modelo Eloquent para Persona.
 *
 * Representa la tabla personas en la base de datos.
 * Esta es la implementación de infraestructura.
 */
class Persona extends Model
{
    use HasFactory;
    
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return PersonaFactory::new();
    }

    protected $table = 'personas';

    protected $fillable = [
        'nombre',
        'cedula',
    ];

    /**
     * Relación many-to-many con vehículos (propietarios).
     *
     * @return BelongsToMany
     */
    public function vehiculos(): BelongsToMany
    {
        return $this->belongsToMany(
            Vehiculo::class,
            'vehiculo_propietario',
            'persona_id',
            'vehiculo_id'
        )->withTimestamps();
    }
}

