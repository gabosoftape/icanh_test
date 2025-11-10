<?php

namespace App\Http\Controllers\Web;

use App\Application\Services\MarcaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\MarcaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MarcaController extends Controller
{
    public function __construct(
        private MarcaService $service
    ) {
    }

    public function index(Request $request): Response
    {
        $skip = (int) $request->get('skip', 0);
        $limit = (int) $request->get('limit', 100);
        
        $marcas = $this->service->list($skip, $limit);
        
        return Inertia::render('marcas/index', [
            'marcas' => collect($marcas)->map(function ($marca) {
                return [
                    'id' => $marca->id,
                    'nombre_marca' => $marca->nombreMarca,
                    'pais' => $marca->pais,
                ];
            })->values()->all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('marcas/create');
    }

    public function store(MarcaRequest $request): RedirectResponse|Response
    {
        try {
            $this->service->create($request->validated());
            
            // Mantener al usuario en la página de creación y mostrar el mensaje
            return redirect()->back()
                ->with('success', 'Marca creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(int $id): Response
    {
        try {
            $marca = $this->service->get($id);
            
            return Inertia::render('marcas/show', [
                'marca' => [
                    'id' => $marca->id,
                    'nombre_marca' => $marca->nombreMarca,
                    'pais' => $marca->pais,
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Marca no encontrada');
        }
    }

    public function edit(int $id): Response
    {
        try {
            $marca = $this->service->get($id);
            
            return Inertia::render('marcas/edit', [
                'marca' => [
                    'id' => $marca->id,
                    'nombre_marca' => $marca->nombreMarca,
                    'pais' => $marca->pais,
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Marca no encontrada');
        }
    }

    public function update(MarcaRequest $request, int $id): RedirectResponse
    {
        try {
            $this->service->update($id, $request->validated());
            
            return redirect()->route('marcas.index')
                ->with('success', 'Marca actualizada exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Marca no encontrada');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->service->delete($id);
            
            return redirect()->route('marcas.index')
                ->with('success', 'Marca eliminada exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Marca no encontrada');
        }
    }
}

