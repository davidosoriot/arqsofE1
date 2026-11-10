{{-- Autor: David Osorio --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('mensajes.factura_de_pedido') }} #{{ $pedido->id }}</title>
</head>
<body>
    <h1>Carnicería Virtual</h1>
    <h2>{{ __('mensajes.factura_de_pedido') }} #{{ $pedido->id }}</h2>

    <p><strong>{{ __('mensajes.cliente') }}:</strong> {{ $pedido->usuario->name }}</p>
    <p><strong>{{ __('mensajes.fecha') }}:</strong> {{ $pedido->created_at->format('d/m/Y') }}</p>

    <table border="1" cellpadding="4">
        <thead>
            <tr>
                <th>{{ __('mensajes.producto') }}</th>
                <th>{{ __('mensajes.cantidad') }}</th>
                <th>{{ __('mensajes.precio_unitario') }}</th>
                <th>{{ __('mensajes.subtotal') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedido->productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->pivot->cantidad }}</td>
                    <td>${{ $producto->pivot->precio_unitario }}</td>
                    <td>${{ $producto->pivot->cantidad * $producto->pivot->precio_unitario }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>{{ __('mensajes.total') }}: ${{ $pedido->total }}</h3>
</body>
</html>
