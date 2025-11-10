<?php

use App\Http\Controllers\Api\MarcaController;
use App\Http\Controllers\Api\PersonaController;
use App\Http\Controllers\Api\VehiculoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se definen las rutas de la API. Estas rutas están cargadas por
| el RouteServiceProvider dentro de un grupo que tiene asignado el
| middleware "api".
|
*/

// Rutas API públicas (sin autenticación)
// Nota: En Laravel 11, las rutas en api.php ya tienen el prefijo 'api' automáticamente

// Marcas
Route::apiResource('marcas', MarcaController::class);

// Personas
Route::apiResource('personas', PersonaController::class);
Route::get('personas/{persona}/vehiculos', [PersonaController::class, 'vehiculos']);

// Vehículos
Route::apiResource('vehiculos', VehiculoController::class);
Route::post('vehiculos/{vehiculo}/propietarios', [VehiculoController::class, 'addPropietario']);


