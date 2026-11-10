<?php
// Autor: David Osorio

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'tipo_carne' => $this->tipo_carne,
            'categoria' => $this->categoria?->nombre,
            'precio' => (float) $this->precio,
            'descuento_hoy' => $this->descuentoVigente(),
            'precio_hoy' => $this->precioHoy(),
            'enlace' => route('catalogo.show', $this->id),
        ];
    }
}
