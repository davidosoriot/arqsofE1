
@extends('layouts.app')

@section('titulo', __('mensajes.tu_carrito'))

@section('contenido')
    <div class="container py-4">
        <h1 class="mb-4">{{ __('mensajes.tu_carrito') }}</h1>

        @if (session('mensaje'))
            <div class="alert alert-success">
                {{ session('mensaje') }}
            </div>
        @endif

        @if (count($detalle) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio original / kg</th>
                            <th>Descuento</th>
                            <th>Precio final / kg</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($detalle as $item)
                            <tr>
                                <td>{{ $item['producto']->nombre }}</td>

                                <td>
                                    <strong>
                                        {{ number_format($item['cantidad'], 3, ',', '.') }} kg
                                    </strong>
                                    <small class="text-muted d-block">
                                        {{ number_format($item['cantidad'] * 1000, 0, ',', '.') }} gramos
                                    </small>
                                </td>

                                <td>
                                    ${{ number_format($item['precio_original'], 0, ',', '.') }}
                                </td>

                                <td>
                                    @if ($item['descuento_porcentaje'] > 0)
                                        <span class="badge bg-success">
                                            {{ number_format($item['descuento_porcentaje'], 0) }}% OFF
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Sin promoción
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <strong class="text-success">
                                        ${{ number_format($item['precio_unitario'], 0, ',', '.') }}
                                    </strong>
                                </td>

                                <td>
                                    <strong>
                                        ${{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </strong>
                                </td>

                                <td>
                                    <form method="POST"
                                          action="{{ route('carrito.quitar', $item['producto']) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            {{ __('mensajes.quitar') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <h5>
                        Total sin descuentos:
                        ${{ number_format(collect($detalle)->sum('subtotal_original'), 0, ',', '.') }}
                    </h5>

                    <h5 class="text-success">
                        Ahorro total:
                        ${{ number_format($ahorroTotal, 0, ',', '.') }}
                    </h5>

                    <hr>

                    <h3 class="mt-3">
                        Total a pagar:
                        ${{ number_format($total, 0, ',', '.') }}
                    </h3>

                    <form method="POST" action="{{ route('carrito.checkout') }}">
                        @csrf

                        <button type="submit" class="btn btn-success mt-3">
                            {{ __('mensajes.finalizar_compra') }}
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                {{ __('mensajes.carrito_vacio') }}
            </div>
        @endif

        <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary mt-3">
            {{ __('mensajes.catalogo') }}
        </a>
    </div>
@endsection
