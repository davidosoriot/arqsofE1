

{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.pedido') . " #{$pedido->id}")

@section('contenido')
    <div class="container py-4">

        <h1>{{ __('mensajes.pedido') }} #{{ $pedido->id }}</h1>

        <div class="card mt-3">
            <div class="card-body">
                <p>
                    <strong>{{ __('mensajes.estado') }}:</strong>
                    {{ $pedido->estado }}
                </p>

                <p>
                    <strong>Fecha:</strong>
                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <h3 class="mt-4">Detalle de tu compra</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad (kg)</th>
                        <th>Precio original/kg</th>
                        <th>Descuento</th>
                        <th>Precio final/kg</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pedido->productos as $producto)
                        @php
                            $cantidad = (float) $producto->pivot->cantidad;
                            $precioOriginal = (float) $producto->pivot->precio_original;
                            $precioFinal = (float) $producto->pivot->precio_unitario;
                            $descuento = (float) $producto->pivot->descuento_porcentaje;
                            $subtotal = $cantidad * $precioFinal;
                        @endphp

                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ number_format($cantidad, 3, ',', '.') }}</td>

                            <td>
                                ${{ number_format($precioOriginal, 2, ',', '.') }}
                            </td>

                            <td>
                                @if ($descuento > 0)
                                    <span class="badge bg-success">
                                        {{ number_format($descuento, 0) }}% OFF
                                    </span>
                                @else
                                    Sin promoción
                                @endif
                            </td>

                            <td>
                                ${{ number_format($precioFinal, 2, ',', '.') }}
                            </td>

                            <td>
                                ${{ number_format($subtotal, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5>
                    Ahorro total:
                    <span class="text-success">
                        ${{ number_format(
                            $pedido->productos->sum(function ($producto) {
                                return $producto->pivot->cantidad *
                                    ($producto->pivot->precio_original - $producto->pivot->precio_unitario);
                            }),
                            2,
                            ',',
                            '.'
                        ) }}
                    </span>
                </h5>

                <h3 class="mt-3">
                    {{ __('mensajes.total') }}:
                    ${{ number_format((float) $pedido->total, 2, ',', '.') }}
                </h3>
            </div>
        </div>

        <div class="mt-4">
            <a class="btn" href="{{ route('pedidos.factura', ['pedido' => $pedido, 'formato' => 'pdf']) }}">
                {{ __('mensajes.descargar_factura_pdf') }}
            </a>

            <a class="btn btn-claro" href="{{ route('pedidos.factura', ['pedido' => $pedido, 'formato' => 'excel']) }}">
                {{ __('mensajes.descargar_factura_excel') }}
            </a>
        </div>

        <a href="{{ route('catalogo.index') }}" class="btn btn-claro mt-3">
            Volver al catálogo
        </a>

    </div>
@endsection
