{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', $categoria->nombre)

@section('contenido')
    <h1>{{ $categoria->nombre }}</h1>

    <p><strong>{{ __('mensajes.id') }}:</strong> {{ $categoria->id }}</p>
    <p><strong>{{ __('mensajes.productos_en_categoria') }}:</strong> {{ $categoria->productos_count }}</p>

    <a href="{{ route('admin.categorias.edit', $categoria) }}">{{ __('mensajes.editar') }}</a>

    <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}" onsubmit="return confirm('{{ __('mensajes.confirmar_eliminar') }}');">
        @csrf
        @method('DELETE')
        <button type="submit">{{ __('mensajes.eliminar') }}</button>
    </form>

    <a href="{{ route('admin.categorias.index') }}">{{ __('mensajes.volver_al_listado') }}</a>
@endsection
