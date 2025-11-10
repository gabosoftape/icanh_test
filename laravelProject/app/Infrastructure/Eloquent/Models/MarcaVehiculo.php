<?php

namespace App\Infrastructure\Eloquent\Models;

use Database\Factories\MarcaVehiculoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Eloquent para Marca de Vehículo.
 *
 * Representa la tabla marcas_vehiculos en la base de datos.
 * Esta es la implementación de infraestructura.
 */
class MarcaVehiculo extends Model
{
    use HasFactory;
    
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return MarcaVehiculoFactory::new();
    }

    protected $table = 'marcas_vehiculos';

    protected $fillable = [
        'nombre_marca',
        'pais',
    ];

    /**
     * Relación con vehículos.
     *
     * @return HasMany
     */
    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class, 'marca_id');
    }
}

