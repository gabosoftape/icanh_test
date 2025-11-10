<?php

namespace App\Providers;

use App\Application\Services\MarcaService;
use App\Application\Services\PersonaService;
use App\Application\Services\VehiculoService;
use App\Domain\Repositories\MarcaRepositoryInterface;
use App\Domain\Repositories\PersonaRepositoryInterface;
use App\Domain\Repositories\VehiculoRepositoryInterface;
use App\Infrastructure\Repositories\EloquentMarcaRepository;
use App\Infrastructure\Repositories\EloquentPersonaRepository;
use App\Infrastructure\Repositories\EloquentVehiculoRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider para registrar los repositorios y servicios.
 *
 * Implementa la inversión de dependencias (SOLID).
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Registra los servicios en el contenedor.
     */
    public function register(): void
    {
        // Registrar interfaces de repositorios con sus implementaciones
        $this->app->bind(MarcaRepositoryInterface::class, EloquentMarcaRepository::class);
        $this->app->bind(PersonaRepositoryInterface::class, EloquentPersonaRepository::class);
        $this->app->bind(VehiculoRepositoryInterface::class, EloquentVehiculoRepository::class);
        
        // Registrar servicios
        $this->app->singleton(MarcaService::class, function ($app) {
            return new MarcaService($app->make(MarcaRepositoryInterface::class));
        });
        
        $this->app->singleton(PersonaService::class, function ($app) {
            return new PersonaService($app->make(PersonaRepositoryInterface::class));
        });
        
        $this->app->singleton(VehiculoService::class, function ($app) {
            return new VehiculoService(
                $app->make(VehiculoRepositoryInterface::class),
                $app->make(MarcaRepositoryInterface::class),
                $app->make(PersonaRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}


