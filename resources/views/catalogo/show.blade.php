
@extends('layouts.app')

@section('titulo', $producto->nombre)

@section('contenido')
    <div class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="row g-0">

                {{-- Fotografía del producto --}}
                <div class="col-md-6">
                    @if ($producto->imagen)
                        <img
                            src="{{ asset('storage/' . $producto->imagen) }}"
                            alt="{{ $producto->nombre }}"
                            style="width: 100%; height: 100%; min-height: 350px; max-height: 500px; object-fit: cover; border-radius: 12px 0 0 12px;"
                        >
                    @else
                        <div style="min-height: 350px; height: 100%; background: #f5e9e6; display: flex; flex-direction: column; justify-content: center; align-items: center; color: #8b2635;">
                            <span style="font-size: 100px;">🥩</span>
                            <p>Fotografía próximamente</p>
                        </div>
                    @endif
                </div>

                {{-- Información del producto --}}
                <div class="col-md-6">
                    <div class="card-body p-4">

                        <h1 class="mb-3">{{ $producto->nombre }}</h1>

                        <p>
                            <strong>{{ __('mensajes.categoria') }}:</strong>
                            {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                        </p>

                        <p>
                            <strong>{{ __('mensajes.tipo_carne') }}:</strong>
                            {{ $producto->tipo_carne }}
                        </p>

                        <p>
                            <strong>Precio por kilogramo:</strong>
                            <span class="precio">
                                ${{ number_format((float) $producto->precio, 0, ',', '.') }} / kg
                            </span>
                        </p>

                        <p>
                            <strong>{{ __('mensajes.disponible') }}:</strong>
                            {{ $producto->disponible ? __('mensajes.si') : __('mensajes.no') }}
                        </p>

                        @if ($producto->disponible)
                            @auth
                                <form method="POST"
                                      action="{{ route('carrito.agregar', $producto) }}"
                                      class="mt-4">

                                    @csrf

                                    <label for="cantidad" class="form-label">
                                        Cantidad que deseas comprar
                                    </label>

                                    <div class="input-group mb-3" style="max-width: 300px;">
                                        <input
                                            type="number"
                                            id="cantidad"
                                            name="cantidad"
                                            class="form-control"
                                            value="1.000"
                                            min="0.001"
                                            max="1000"
                                            step="0.001"
                                            required
                                        >
                                        <span class="input-group-text">kg</span>
                                    </div>

                                    <small class="text-muted d-block mb-3">
                                        Ejemplos: 0.250 kg = 250 gramos,
                                        0.500 kg = 500 gramos,
                                        0.750 kg = 750 gramos.
                                    </small>

                                    @error('cantidad')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <button type="submit" class="btn btn-danger">
                                        {{ __('mensajes.agregar_al_carrito') }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-danger">
                                    {{ __('mensajes.iniciar_sesion') }}
                                </a>
                            @endauth
                        @else
                            <div class="alert alert-warning">
                                Este producto no está disponible actualmente.
                            </div>
                        @endif

                        <a href="{{ route('catalogo.index') }}"
                           class="btn btn-outline-secondary mt-3">
                            {{ __('mensajes.volver_al_listado') }}
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
