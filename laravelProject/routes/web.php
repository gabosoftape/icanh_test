<?php

use App\Http\Controllers\Web\MarcaController;
use App\Http\Controllers\Web\PersonaController;
use App\Http\Controllers\Web\VehiculoController;
use App\Infrastructure\Eloquent\Models\MarcaVehiculo;
use App\Infrastructure\Eloquent\Models\Persona;
use App\Infrastructure\Eloquent\Models\Vehiculo;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard', [
            'counts' => [
                'marcas' => MarcaVehiculo::count(),
                'personas' => Persona::count(),
                'vehiculos' => Vehiculo::count(),
                'usuarios' => User::count(),
            ],
        ]);
    })->name('dashboard');

    // CRUD de Marcas
    Route::resource('marcas', MarcaController::class);

    // CRUD de Personas
    Route::resource('personas', PersonaController::class);

    // CRUD de Vehículos
    Route::resource('vehiculos', VehiculoController::class);
});

// Ruta para servir el JSON de Swagger directamente
Route::get('docs/api-docs.json', function () {
    $jsonPath = storage_path('api-docs/api-docs.json');
    
    if (!file_exists($jsonPath)) {
        return response()->json(['error' => 'Documentación no encontrada. Ejecuta: php artisan l5-swagger:generate'], 404);
    }
    
    return response()->file($jsonPath, [
        'Content-Type' => 'application/json',
    ]);
})->name('swagger.json');

require __DIR__.'/settings.php';
