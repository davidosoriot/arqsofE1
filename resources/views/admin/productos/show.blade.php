{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', $producto->nombre)

@section('contenido')
    <h1>{{ $producto->nombre }}</h1>

    <p><strong>{{ __('mensajes.id') }}:</strong> {{ $producto->id }}</p>
    <p><strong>{{ __('mensajes.categoria') }}:</strong> {{ $producto->categoria->nombre }}</p>
    <p><strong>{{ __('mensajes.tipo_carne') }}:</strong> {{ $producto->tipo_carne }}</p>
    <p><strong>{{ __('mensajes.precio') }}:</strong> ${{ $producto->precio }}</p>
    <p><strong>{{ __('mensajes.disponible') }}:</strong> {{ $producto->disponible ? __('mensajes.si') : __('mensajes.no') }}</p>

    <a href="{{ route('admin.productos.edit', $producto) }}">{{ __('mensajes.editar') }}</a>

    <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}" onsubmit="return confirm('{{ __('mensajes.confirmar_eliminar') }}');">
        @csrf
        @method('DELETE')
        <button type="submit">{{ __('mensajes.eliminar') }}</button>
    </form>

    <a href="{{ route('admin.productos.index') }}">{{ __('mensajes.volver_al_listado') }}</a>
@endsection
