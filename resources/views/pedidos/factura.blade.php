
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>Factura del pedido #{{ $pedido->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 12px;
        }

        h1 {
            color: #8b0000;
            text-align: center;
        }

        h2 {
            color: #600018;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #700018;
            color: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        .ahorro {
            color: #16803c;
        }

        .total {
            text-align: right;
            color: #700018;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>Los del Corte</h1>
    <h2>Factura del pedido #{{ $pedido->id }}</h2>

    <p><strong>Cliente:</strong> {{ $pedido->usuario->name }}</p>
    <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y') }}</p>
    <p><strong>Estado:</strong> {{ $pedido->estado }}</p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio original</th>
                <th>Descuento</th>
                <th>Precio final</th>
                <th>Subtotal</th>
                <th>Ahorro</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($pedido->productos as $producto)
                @php
                    $cantidad = $producto->pivot->cantidad;
                    $precioOriginal = (float) $producto->pivot->precio_original;
                    $precioFinal = (float) $producto->pivot->precio_unitario;
                    $descuento = (float) $producto->pivot->descuento_porcentaje;

                    $subtotal = $cantidad * $precioFinal;
                    $ahorro = $cantidad * ($precioOriginal - $precioFinal);
                @endphp

                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $cantidad }}</td>
                    <td>${{ number_format($precioOriginal, 2, ',', '.') }}</td>
                    <td>{{ number_format($descuento, 0) }}%</td>
                    <td>${{ number_format($precioFinal, 2, ',', '.') }}</td>
                    <td>${{ number_format($subtotal, 2, ',', '.') }}</td>
                    <td class="ahorro">
                        ${{ number_format($ahorro, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="total">
        Total pagado: ${{ number_format((float) $pedido->total, 2, ',', '.') }}
    </h3>

</body>
</html>
