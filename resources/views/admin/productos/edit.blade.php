{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', __('mensajes.editar_producto'))

@section('contenido')
    <h1>{{ __('mensajes.editar_producto') }}</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.productos.update', $producto) }}">
        @csrf
        @method('PUT')

        <label>{{ __('mensajes.categoria') }}</label>
        <select name="categoria_id">
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected($categoria->id === $producto->categoria_id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>

        <label>{{ __('mensajes.nombre') }}</label>
        <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}">

        <label>{{ __('mensajes.tipo_carne') }}</label>
        <input type="text" name="tipo_carne" value="{{ old('tipo_carne', $producto->tipo_carne) }}">

        <label>{{ __('mensajes.precio') }}</label>
        <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}">

        <label>
            <input type="checkbox" name="disponible" value="1" @checked($producto->disponible)>
            {{ __('mensajes.disponible') }}
        </label>

        <button type="submit">{{ __('mensajes.guardar') }}</button>
    </form>
@endsection
