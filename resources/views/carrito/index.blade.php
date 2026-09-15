{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.tu_carrito'))

@section('contenido')
    <h1>{{ __('mensajes.tu_carrito') }}</h1>

    <table>
        <thead>
            <tr>
                <th>{{ __('mensajes.nombre') }}</th>
                <th>{{ __('mensajes.cantidad') }}</th>
                <th>{{ __('mensajes.subtotal') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($carrito->productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->pivot->cantidad }}</td>
                    <td>${{ $producto->precio * $producto->pivot->cantidad }}</td>
                    <td>
                        <form method="POST" action="{{ route('carrito.quitar', $producto) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">{{ __('mensajes.quitar') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">{{ __('mensajes.carrito_vacio') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3>{{ __('mensajes.total') }}: ${{ $total }}</h3>

    @if ($carrito->productos->isNotEmpty())
        <form method="POST" action="{{ route('carrito.checkout') }}">
            @csrf
            <button type="submit">{{ __('mensajes.finalizar_compra') }}</button>
        </form>
    @endif

    <a href="{{ route('catalogo.index') }}">{{ __('mensajes.catalogo') }}</a>
@endsection
