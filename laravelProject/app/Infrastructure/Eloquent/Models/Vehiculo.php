<?php

namespace App\Infrastructure\Eloquent\Models;

use Database\Factories\VehiculoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Modelo Eloquent para Vehículo.
 *
 * Representa la tabla vehiculos en la base de datos.
 * Esta es la implementación de infraestructura.
 */
class Vehiculo extends Model
{
    use HasFactory;
    
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return VehiculoFactory::new();
    }

    protected $table = 'vehiculos';

    protected $fillable = [
        'modelo',
        'marca_id',
        'numero_puertas',
        'color',
    ];

    /**
     * Relación con marca.
     *
     * @return BelongsTo
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(MarcaVehiculo::class, 'marca_id');
    }

    /**
     * Relación many-to-many con personas (propietarios).
     *
     * @return BelongsToMany
     */
    public function propietarios(): BelongsToMany
    {
        return $this->belongsToMany(
            Persona::class,
            'vehiculo_propietario',
            'vehiculo_id',
            'persona_id'
        )->withTimestamps();
    }
}

