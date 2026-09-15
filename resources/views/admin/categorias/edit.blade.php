{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', __('mensajes.editar_categoria'))

@section('contenido')
    <h1>{{ __('mensajes.editar_categoria') }}</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}">
        @csrf
        @method('PUT')

        <label>{{ __('mensajes.nombre') }}</label>
        <input type="text" name="nombre" value="{{ old('nombre', $categoria->nombre) }}">

        <button type="submit">{{ __('mensajes.guardar') }}</button>
    </form>
@endsection
