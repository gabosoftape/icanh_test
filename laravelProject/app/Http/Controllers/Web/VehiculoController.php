<?php

namespace App\Http\Controllers\Web;

use App\Application\Services\MarcaService;
use App\Application\Services\PersonaService;
use App\Application\Services\VehiculoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\VehiculoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VehiculoController extends Controller
{
    public function __construct(
        private VehiculoService $vehiculoService,
        private MarcaService $marcaService,
        private PersonaService $personaService
    ) {
    }

    public function index(Request $request): Response
    {
        $skip = (int) $request->get('skip', 0);
        $limit = (int) $request->get('limit', 100);
        
        $vehiculos = $this->vehiculoService->list($skip, $limit);
        $marcas = $this->marcaService->list(0, 1000);
        $marcasCollection = collect($marcas);
        
        return Inertia::render('vehiculos/index', [
            'vehiculos' => collect($vehiculos)->map(function ($vehiculo) use ($marcasCollection) {
                $marca = $marcasCollection->firstWhere('id', $vehiculo->marcaId);
                return [
                    'id' => $vehiculo->id,
                    'modelo' => $vehiculo->modelo,
                    'marca' => $marca ? [
                        'id' => $marca->id,
                        'nombre_marca' => $marca->nombreMarca,
                    ] : null,
                    'numero_puertas' => $vehiculo->numeroPuertas,
                    'color' => $vehiculo->color,
                ];
            })->values()->all(),
        ]);
    }

    public function create(): Response
    {
        $marcas = $this->marcaService->list(0, 1000);
        $personas = $this->personaService->list(0, 1000);
        
        return Inertia::render('vehiculos/create', [
            'marcas' => collect($marcas)->map(function ($marca) {
                return [
                    'id' => $marca->id,
                    'nombre_marca' => $marca->nombreMarca,
                ];
            })->values()->all(),
            'personas' => collect($personas)->map(function ($persona) {
                return [
                    'id' => $persona->id,
                    'nombre' => $persona->nombre,
                    'cedula' => $persona->cedula,
                ];
            })->values()->all(),
        ]);
    }

    public function store(VehiculoRequest $request): RedirectResponse|Response
    {
        try {
            $this->vehiculoService->create($request->validated());
            
            // Mantener al usuario en la página de creación y mostrar el mensaje
            return redirect()->back()
                ->with('success', 'Vehículo creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(int $id): Response
    {
        try {
            $vehiculo = $this->vehiculoService->get($id);
            $marcas = $this->marcaService->list(0, 1000);
            $personas = $this->personaService->list(0, 1000);
            
            $marcasCollection = collect($marcas);
            $personasCollection = collect($personas);
            
            $marca = $marcasCollection->firstWhere('id', $vehiculo->marcaId);
            $propietarios = collect($vehiculo->propietariosIds)
                ->map(function ($personaId) use ($personasCollection) {
                    return $personasCollection->firstWhere('id', $personaId);
                })
                ->filter()
                ->map(function ($persona) {
                    return [
                        'id' => $persona->id,
                        'nombre' => $persona->nombre,
                        'cedula' => $persona->cedula,
                    ];
                })
                ->values()
                ->all();
            
            return Inertia::render('vehiculos/show', [
                'vehiculo' => [
                    'id' => $vehiculo->id,
                    'modelo' => $vehiculo->modelo,
                    'marca' => $marca ? [
                        'id' => $marca->id,
                        'nombre_marca' => $marca->nombreMarca,
                        'pais' => $marca->pais,
                    ] : null,
                    'numero_puertas' => $vehiculo->numeroPuertas,
                    'color' => $vehiculo->color,
                    'propietarios' => $propietarios,
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Vehículo no encontrado');
        }
    }

    public function edit(int $id): Response
    {
        try {
            $vehiculo = $this->vehiculoService->get($id);
            $marcas = $this->marcaService->list(0, 1000);
            $personas = $this->personaService->list(0, 1000);
            
            return Inertia::render('vehiculos/edit', [
                'vehiculo' => [
                    'id' => $vehiculo->id,
                    'modelo' => $vehiculo->modelo,
                    'marca_id' => $vehiculo->marcaId,
                    'numero_puertas' => $vehiculo->numeroPuertas,
                    'color' => $vehiculo->color,
                    'propietarios_ids' => $vehiculo->propietariosIds,
                ],
                'marcas' => collect($marcas)->map(function ($marca) {
                    return [
                        'id' => $marca->id,
                        'nombre_marca' => $marca->nombreMarca,
                    ];
                })->values()->all(),
                'personas' => collect($personas)->map(function ($persona) {
                    return [
                        'id' => $persona->id,
                        'nombre' => $persona->nombre,
                        'cedula' => $persona->cedula,
                    ];
                })->values()->all(),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Vehículo no encontrado');
        }
    }

    public function update(VehiculoRequest $request, int $id): RedirectResponse
    {
        try {
            $this->vehiculoService->update($id, $request->validated());
            
            return redirect()->route('vehiculos.index')
                ->with('success', 'Vehículo actualizado exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Vehículo no encontrado');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->vehiculoService->delete($id);
            
            return redirect()->route('vehiculos.index')
                ->with('success', 'Vehículo eliminado exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Vehículo no encontrado');
        }
    }
}

