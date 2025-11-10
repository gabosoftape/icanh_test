<?php

namespace App\Http\Controllers\Web;

use App\Application\Services\PersonaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonaController extends Controller
{
    public function __construct(
        private PersonaService $service
    ) {
    }

    public function index(Request $request): Response
    {
        $skip = (int) $request->get('skip', 0);
        $limit = (int) $request->get('limit', 100);
        
        $personas = $this->service->list($skip, $limit);
        
        return Inertia::render('personas/index', [
            'personas' => collect($personas)->map(function ($persona) {
                return [
                    'id' => $persona->id,
                    'nombre' => $persona->nombre,
                    'cedula' => $persona->cedula,
                ];
            })->values()->all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('personas/create');
    }

    public function store(PersonaRequest $request): RedirectResponse|Response
    {
        try {
            $this->service->create($request->validated());
            
            // Mantener al usuario en la página de creación y mostrar el mensaje
            return redirect()->back()
                ->with('success', 'Persona creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(int $id): Response
    {
        try {
            $persona = $this->service->get($id);
            
            return Inertia::render('personas/show', [
                'persona' => [
                    'id' => $persona->id,
                    'nombre' => $persona->nombre,
                    'cedula' => $persona->cedula,
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Persona no encontrada');
        }
    }

    public function edit(int $id): Response
    {
        try {
            $persona = $this->service->get($id);
            
            return Inertia::render('personas/edit', [
                'persona' => [
                    'id' => $persona->id,
                    'nombre' => $persona->nombre,
                    'cedula' => $persona->cedula,
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Persona no encontrada');
        }
    }

    public function update(PersonaRequest $request, int $id): RedirectResponse
    {
        try {
            $this->service->update($id, $request->validated());
            
            return redirect()->route('personas.index')
                ->with('success', 'Persona actualizada exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Persona no encontrada');
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
            
            return redirect()->route('personas.index')
                ->with('success', 'Persona eliminada exitosamente');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Persona no encontrada');
        }
    }
}

