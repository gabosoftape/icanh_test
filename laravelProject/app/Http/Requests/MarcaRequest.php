<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request de validación para operaciones de Marca.
 */
class MarcaRequest extends FormRequest
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
        $marcaId = $this->route('marca');
        
        return [
            'nombre_marca' => [
                'required',
                'string',
                'max:255',
                Rule::unique('marcas_vehiculos', 'nombre_marca')->ignore($marcaId),
            ],
            'pais' => ['required', 'string', 'max:255'],
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
            'nombre_marca.required' => 'El nombre de la marca es obligatorio.',
            'nombre_marca.unique' => 'Ya existe una marca con ese nombre.',
            'pais.required' => 'El país es obligatorio.',
        ];
    }
}


