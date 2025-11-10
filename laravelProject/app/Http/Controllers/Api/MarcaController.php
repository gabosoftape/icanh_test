<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\MarcaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\MarcaRequest;
use App\Http\Resources\MarcaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

/**
 * Controlador API para gestión de Marcas.
 *
 * @OA\Tag(
 *     name="Marcas",
 *     description="Operaciones CRUD para gestionar marcas de vehículos"
 * )
 */
class MarcaController extends Controller
{
    public function __construct(
        private MarcaService $service
    ) {
    }

    /**
     * Lista todas las marcas.
     *
     * @OA\Get(
     *     path="/api/marcas",
     *     tags={"Marcas"},
     *     summary="Lista todas las marcas",
     *     @OA\Parameter(
     *         name="skip",
     *         in="query",
     *         description="Número de registros a saltar",
     *         required=false,
     *         @OA\Schema(type="integer", default=0)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número máximo de registros a retornar",
     *         required=false,
     *         @OA\Schema(type="integer", default=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de marcas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Marca")
     *         )
     *     )
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $skip = (int) $request->get('skip', 0);
        $limit = (int) $request->get('limit', 100);
        
        $marcas = $this->service->list($skip, $limit);
        
        return MarcaResource::collection($marcas);
    }

    /**
     * Crea una nueva marca.
     *
     * @OA\Post(
     *     path="/api/marcas",
     *     tags={"Marcas"},
     *     summary="Crea una nueva marca",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MarcaCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Marca creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Marca")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(MarcaRequest $request): JsonResponse
    {
        try {
            $marca = $this->service->create($request->validated());
            
            return (new MarcaResource($marca))
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            Log::error('Error al crear marca', ['error' => $e->getMessage()]);
            
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Muestra una marca específica.
     *
     * @OA\Get(
     *     path="/api/marcas/{id}",
     *     tags={"Marcas"},
     *     summary="Obtiene una marca por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marca encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Marca")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Marca no encontrada"
     *     )
     * )
     */
    public function show(int $id): JsonResponse|MarcaResource
    {
        try {
            $marca = $this->service->get($id);
            
            return new MarcaResource($marca);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Marca no encontrada',
            ], 404);
        }
    }

    /**
     * Actualiza una marca.
     *
     * @OA\Put(
     *     path="/api/marcas/{id}",
     *     tags={"Marcas"},
     *     summary="Actualiza una marca",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MarcaUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marca actualizada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Marca")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Marca no encontrada"
     *     )
     * )
     */
    public function update(MarcaRequest $request, int $id): JsonResponse|MarcaResource
    {
        try {
            $marca = $this->service->update($id, $request->validated());
            
            return new MarcaResource($marca);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Marca no encontrada',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error al actualizar marca', ['error' => $e->getMessage()]);
            
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Elimina una marca.
     *
     * @OA\Delete(
     *     path="/api/marcas/{id}",
     *     tags={"Marcas"},
     *     summary="Elimina una marca",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Marca eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Marca no encontrada"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);
            
            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Marca no encontrada',
            ], 404);
        }
    }
}


