{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.catalogo'))

@section('contenido')
    <section class="portada">
        <h1>{{ __('mensajes.catalogo') }}</h1>

        <form method="GET" action="{{ route('catalogo.index') }}" class="buscador">
            <input type="search" name="q" value="{{ request('q') }}"
                   placeholder="{{ __('mensajes.buscar_producto') }}">
            <button type="submit" class="btn btn-claro">{{ __('mensajes.buscar') }}</button>
        </form>

        <div class="accesos">
            <a href="{{ route('catalogo.promociones') }}" class="btn btn-claro">
                {{ __('mensajes.promociones_vigentes') }}
            </a>
            <a href="{{ route('catalogo.mas-vendidos') }}" class="btn btn-claro">
                {{ __('mensajes.mas_vendidos') }}
            </a>
        </div>
    </section>

    @if ($productos->isEmpty())
        <p class="vacio">{{ __('mensajes.no_hay_registros') }}</p>
    @else
        @foreach ($productos->groupBy('tipo_carne') as $tipo => $items)
            <section class="seccion-carne">
                <h2>{{ $tipo }}</h2>
                <div class="cortes">
                    @foreach ($items as $producto)
                        <article class="corte">
                            <h3>
                                <a href="{{ route('catalogo.show', $producto) }}">{{ $producto->nombre }}</a>
                            </h3>
                            <p class="precio">
                                ${{ number_format($producto->precio, 0, ',', '.') }}
                                <small>/ {{ $producto->categoria->nombre }}</small>
                            </p>
                            <a class="btn" href="{{ route('catalogo.show', $producto) }}">
                                {{ __('mensajes.ver_detalle') }}
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach
    @endif
@endsection
