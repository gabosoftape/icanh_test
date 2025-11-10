<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\VehiculoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PropietarioRequest;
use App\Http\Requests\VehiculoRequest;
use App\Http\Resources\VehiculoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

/**
 * Controlador API para gestión de Vehículos.
 *
 * @OA\Tag(
 *     name="Vehículos",
 *     description="Operaciones CRUD para gestionar vehículos"
 * )
 */
class VehiculoController extends Controller
{
    public function __construct(
        private VehiculoService $service
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/vehiculos",
     *     tags={"Vehículos"},
     *     summary="Lista todos los vehículos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de vehículos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Vehiculo")
     *         )
     *     )
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $skip = (int) $request->get('skip', 0);
        $limit = (int) $request->get('limit', 100);
        
        return VehiculoResource::collection($this->service->list($skip, $limit));
    }

    /**
     * @OA\Post(
     *     path="/api/vehiculos",
     *     tags={"Vehículos"},
     *     summary="Crea un nuevo vehículo",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/VehiculoCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vehículo creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Vehiculo")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(VehiculoRequest $request): JsonResponse
    {
        try {
            $vehiculo = $this->service->create($request->validated());
            
            return (new VehiculoResource($vehiculo))
                ->response()
                ->setStatusCode(201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/vehiculos/{id}",
     *     tags={"Vehículos"},
     *     summary="Obtiene un vehículo por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehículo encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Vehiculo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehículo no encontrado"
     *     )
     * )
     */
    public function show(int $id): JsonResponse|VehiculoResource
    {
        try {
            return new VehiculoResource($this->service->get($id));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Vehículo no encontrado'], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/vehiculos/{id}",
     *     tags={"Vehículos"},
     *     summary="Actualiza un vehículo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/VehiculoUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehículo actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Vehiculo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehículo no encontrado"
     *     )
     * )
     */
    public function update(VehiculoRequest $request, int $id): JsonResponse|VehiculoResource
    {
        try {
            $vehiculo = $this->service->update($id, $request->validated());
            
            return new VehiculoResource($vehiculo);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/vehiculos/{id}",
     *     tags={"Vehículos"},
     *     summary="Elimina un vehículo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Vehículo eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehículo no encontrado"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);
            
            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Vehículo no encontrado'], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/vehiculos/{id}/propietarios",
     *     tags={"Vehículos"},
     *     summary="Asigna un propietario a un vehículo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"persona_id"},
     *             @OA\Property(property="persona_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Propietario asignado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Vehiculo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehículo o persona no encontrada"
     *     )
     * )
     */
    public function addPropietario(PropietarioRequest $request, int $id): JsonResponse|VehiculoResource
    {
        try {
            $vehiculo = $this->service->addPropietario($id, $request->validated()['persona_id']);
            
            return (new VehiculoResource($vehiculo))
                ->response()
                ->setStatusCode(201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            Log::error('Error al asignar propietario', ['error' => $e->getMessage()]);
            
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}


