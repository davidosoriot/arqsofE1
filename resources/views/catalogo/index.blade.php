
{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.catalogo'))

@section('contenido')
    <section class="portada">
        <p>BIENVENIDO A LOS DEL CORTE</p>

        <h1>El sabor de los mejores cortes</h1>

        <p>
            Encuentra carnes frescas y de calidad.
            Compra la cantidad que necesitas, por gramos o kilogramos.
        </p>

        <form method="GET" action="{{ route('catalogo.index') }}" class="buscador">
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="{{ __('mensajes.buscar_producto') }}"
            >

            <button type="submit" class="btn btn-claro">
                {{ __('mensajes.buscar') }}
            </button>

            @if(request()->filled('q'))
                <a href="{{ route('catalogo.index') }}" class="btn btn-claro">
                    Limpiar búsqueda
                </a>
            @endif
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
        <div class="vacio">
            <h2>No encontramos productos</h2>
            <p>Intenta buscar con otro nombre o vuelve a consultar nuestro catálogo.</p>

            <a href="{{ route('catalogo.index') }}" class="btn">
                Ver todos los productos
            </a>
        </div>
    @else
        @foreach (
            $productos->groupBy(fn ($producto) =>
                $producto->categoria?->nombre ?? $producto->tipo_carne
            ) as $nombreCategoria => $productosCategoria
        )
            <section class="seccion-carne">
                <h2>{{ $nombreCategoria }}</h2>

                <div class="cortes">
                    @foreach ($productosCategoria as $producto)
                        <article class="corte">
                            <div style="width: 100%; height: 210px; overflow: hidden; border-radius: 12px; margin-bottom: 15px; background: #f5e9e6; display: flex; align-items: center; justify-content: center;">
                                @if ($producto->imagen)
                                    <img
                                        src="{{ asset('storage/' . $producto->imagen) }}"
                                        alt="{{ $producto->nombre }}"
                                        style="width: 100%; height: 100%; object-fit: cover;"
                                        loading="lazy"
                                    >
                                @else
                                    <div style="text-align: center; color: #8b2635;">
                                        <span style="font-size: 55px;">🥩</span>
                                        <p style="margin: 5px;">Imagen próximamente</p>
                                    </div>
                                @endif
                            </div>

                            <p>{{ $producto->tipo_carne }}</p>

                            <h3>
                                <a href="{{ route('catalogo.show', $producto) }}">
                                    {{ $producto->nombre }}
                                </a>
                            </h3>

                            <p class="precio">
                                ${{ number_format((float) $producto->precio, 0, ',', '.') }}
                                <small>/ kg</small>
                            </p>

                            <p>Disponible para comprar por gramos o kilogramos.</p>

                            <a href="{{ route('catalogo.show', $producto) }}" class="btn">
                                {{ __('mensajes.ver_detalle') }}
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach
    @endif
@endsection
