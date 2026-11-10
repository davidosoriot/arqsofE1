{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.productos_aliados'))

@section('contenido')
    <h1>{{ __('mensajes.productos_aliados') }}</h1>
    <p>{{ __('mensajes.productos_aliados_desc') }}</p>

    @if ($productos->isEmpty())
        <p class="vacio">{{ __('mensajes.aliado_no_disponible') }}</p>
    @else
        <div class="cortes">
            @foreach ($productos as $producto)
                <article class="corte">
                    <h3>{{ $producto['nombre'] }}</h3>
                    @if ($producto['categoria'])
                        <p><strong>{{ __('mensajes.categoria') }}:</strong> {{ $producto['categoria'] }}</p>
                    @endif
                    <p class="precio">${{ number_format($producto['precio'], 0, ',', '.') }}</p>
                    @if ($producto['enlace'])
                        <a class="btn" href="{{ $producto['enlace'] }}" target="_blank" rel="noopener">
                            {{ __('mensajes.ver_detalle') }}
                        </a>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
@endsection
