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
                    @if ($producto['imagen'])
                        <img src="{{ $producto['imagen'] }}"
                             alt="{{ $producto['titulo'] }}"
                             loading="lazy"
                             style="width:100%;aspect-ratio:2/3;object-fit:cover;border-radius:4px;">
                    @endif

                    <h3>{{ $producto['titulo'] }}</h3>

                    @if ($producto['plataforma'])
                        <p><strong>{{ __('mensajes.aliado_plataforma') }}:</strong> {{ $producto['plataforma'] }}</p>
                    @endif
                    @if ($producto['categoria'])
                        <p><strong>{{ __('mensajes.categoria') }}:</strong> {{ $producto['categoria'] }}</p>
                    @endif

                    <p class="precio">
                        US$ {{ number_format($producto['precio'], 2) }}
                        <small>USD</small>
                    </p>

                    @if ($producto['stock'] !== null)
                        <p>
                            @if ($producto['stock'] > 0)
                                {{ __('mensajes.aliado_en_stock', ['cantidad' => $producto['stock']]) }}
                            @else
                                <strong>{{ __('mensajes.aliado_agotado') }}</strong>
                            @endif
                        </p>
                    @endif

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
