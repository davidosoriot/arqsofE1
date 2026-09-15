{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', __('mensajes.crear_producto'))

@section('contenido')
    <h1>{{ __('mensajes.crear_producto') }}</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.productos.store') }}">
        @csrf

        <label>{{ __('mensajes.categoria') }}</label>
        <select name="categoria_id">
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
        </select>

        <label>{{ __('mensajes.nombre') }}</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}">

        <label>{{ __('mensajes.tipo_carne') }}</label>
        <input type="text" name="tipo_carne" value="{{ old('tipo_carne') }}">

        <label>{{ __('mensajes.precio') }}</label>
        <input type="number" step="0.01" name="precio" value="{{ old('precio') }}">

        <label>
            <input type="checkbox" name="disponible" value="1" checked>
            {{ __('mensajes.disponible') }}
        </label>

        <button type="submit">{{ __('mensajes.guardar') }}</button>
    </form>
@endsection
