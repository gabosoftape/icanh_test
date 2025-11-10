<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="API Gestión de Vehículos",
 *     version="1.0.0",
 *     description="API RESTful para gestionar marcas de vehículos, personas y su relación de propiedad con vehículos. Incluye operaciones CRUD completas y endpoints de relaciones.",
 *     @OA\Contact(
 *         email="dumar.pabon@gmail.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor de la API"
 * )
 *
 * @OA\Schema(
 *     schema="Marca",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre_marca", type="string", example="Toyota"),
 *     @OA\Property(property="pais", type="string", example="Japón")
 * )
 *
 * @OA\Schema(
 *     schema="MarcaCreate",
 *     type="object",
 *     required={"nombre_marca", "pais"},
 *     @OA\Property(property="nombre_marca", type="string", example="Toyota"),
 *     @OA\Property(property="pais", type="string", example="Japón")
 * )
 *
 * @OA\Schema(
 *     schema="MarcaUpdate",
 *     type="object",
 *     @OA\Property(property="nombre_marca", type="string", example="Toyota"),
 *     @OA\Property(property="pais", type="string", example="Japón")
 * )
 *
 * @OA\Schema(
 *     schema="Persona",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *     @OA\Property(property="cedula", type="string", example="1234567890")
 * )
 *
 * @OA\Schema(
 *     schema="PersonaCreate",
 *     type="object",
 *     required={"nombre", "cedula"},
 *     @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *     @OA\Property(property="cedula", type="string", example="1234567890")
 * )
 *
 * @OA\Schema(
 *     schema="PersonaUpdate",
 *     type="object",
 *     @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *     @OA\Property(property="cedula", type="string", example="1234567890")
 * )
 *
 * @OA\Schema(
 *     schema="Vehiculo",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="modelo", type="string", example="Corolla"),
 *     @OA\Property(property="marca_id", type="integer", example=1),
 *     @OA\Property(property="numero_puertas", type="integer", example=4),
 *     @OA\Property(property="color", type="string", example="Rojo"),
 *     @OA\Property(property="propietarios_ids", type="array", @OA\Items(type="integer"), example={1, 2})
 * )
 *
 * @OA\Schema(
 *     schema="VehiculoCreate",
 *     type="object",
 *     required={"modelo", "marca_id", "numero_puertas", "color"},
 *     @OA\Property(property="modelo", type="string", example="Corolla"),
 *     @OA\Property(property="marca_id", type="integer", example=1),
 *     @OA\Property(property="numero_puertas", type="integer", example=4),
 *     @OA\Property(property="color", type="string", example="Rojo"),
 *     @OA\Property(property="propietarios_ids", type="array", @OA\Items(type="integer"), example={1, 2})
 * )
 *
 * @OA\Schema(
 *     schema="VehiculoUpdate",
 *     type="object",
 *     @OA\Property(property="modelo", type="string", example="Corolla"),
 *     @OA\Property(property="marca_id", type="integer", example=1),
 *     @OA\Property(property="numero_puertas", type="integer", example=4),
 *     @OA\Property(property="color", type="string", example="Rojo"),
 *     @OA\Property(property="propietarios_ids", type="array", @OA\Items(type="integer"), example={1, 2})
 * )
 */
abstract class Controller
{
    //
}
