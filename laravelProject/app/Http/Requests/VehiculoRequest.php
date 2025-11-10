<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request de validación para operaciones de Vehículo.
 */
class VehiculoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a hacer esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'modelo' => ['required', 'string', 'max:255'],
            'marca_id' => ['required', 'integer', 'exists:marcas_vehiculos,id'],
            'numero_puertas' => ['required', 'integer', 'min:2', 'max:10'],
            'color' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Mensajes de error personalizados.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'modelo.required' => 'El modelo es obligatorio.',
            'marca_id.required' => 'La marca es obligatoria.',
            'marca_id.exists' => 'La marca seleccionada no existe.',
            'numero_puertas.required' => 'El número de puertas es obligatorio.',
            'numero_puertas.min' => 'El número de puertas debe ser al menos 2.',
            'numero_puertas.max' => 'El número de puertas no puede ser mayor a 10.',
            'color.required' => 'El color es obligatorio.',
        ];
    }
}


