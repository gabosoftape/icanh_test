<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\PersonaService;
use App\Application\Services\VehiculoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonaRequest;
use App\Http\Resources\PersonaResource;
use App\Http\Resources\VehiculoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

/**
 * Controlador API para gestión de Personas.
 *
 * @OA\Tag(
 *     name="Personas",
 *     description="Operaciones CRUD para gestionar personas"
 * )
 */
class PersonaController extends Controller
{
    public function __construct(
        private PersonaService $personaService,
        private VehiculoService $vehiculoService
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/personas",
     *     tags={"Personas"},
     *     summary="Lista todas las personas",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de personas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Persona")
     *         )
     *     )
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $skip = (int) $request->get('skip', 0);
        $limit = (int) $request->get('limit', 100);
        
        return PersonaResource::collection($this->personaService->list($skip, $limit));
    }

    /**
     * @OA\Post(
     *     path="/api/personas",
     *     tags={"Personas"},
     *     summary="Crea una nueva persona",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PersonaCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Persona creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(PersonaRequest $request): JsonResponse
    {
        try {
            $persona = $this->personaService->create($request->validated());
            
            return (new PersonaResource($persona))
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            Log::error('Error al crear persona', ['error' => $e->getMessage()]);
            
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/personas/{id}",
     *     tags={"Personas"},
     *     summary="Obtiene una persona por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada"
     *     )
     * )
     */
    public function show(int $id): JsonResponse|PersonaResource
    {
        try {
            return new PersonaResource($this->personaService->get($id));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/personas/{id}",
     *     tags={"Personas"},
     *     summary="Actualiza una persona",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PersonaUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Persona actualizada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Persona")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada"
     *     )
     * )
     */
    public function update(PersonaRequest $request, int $id): JsonResponse|PersonaResource
    {
        try {
            $persona = $this->personaService->update($id, $request->validated());
            
            return new PersonaResource($persona);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        } catch (\Exception $e) {
            Log::error('Error al actualizar persona', ['error' => $e->getMessage()]);
            
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/personas/{id}",
     *     tags={"Personas"},
     *     summary="Elimina una persona",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Persona eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->personaService->delete($id);
            
            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/personas/{id}/vehiculos",
     *     tags={"Personas"},
     *     summary="Obtiene los vehículos de una persona",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de vehículos de la persona",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Vehiculo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Persona no encontrada"
     *     )
     * )
     */
    public function vehiculos(int $id): JsonResponse|AnonymousResourceCollection
    {
        try {
            $vehiculos = $this->vehiculoService->getVehiculosByPersona($id);
            
            return VehiculoResource::collection($vehiculos);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Persona no encontrada'], 404);
        }
    }
}


