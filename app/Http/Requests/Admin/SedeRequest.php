<?php
// Autor: David Osorio

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SedeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdministrador() ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'ciudad' => ['required', 'string', 'max:100'],
            'horario' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => __('mensajes.nombre'),
            'direccion' => __('mensajes.sede_direccion'),
            'ciudad' => __('mensajes.sede_ciudad'),
            'horario' => __('mensajes.sede_horario'),
            'telefono' => __('mensajes.sede_telefono'),
            'latitud' => __('mensajes.sede_latitud'),
            'longitud' => __('mensajes.sede_longitud'),
        ];
    }
}
