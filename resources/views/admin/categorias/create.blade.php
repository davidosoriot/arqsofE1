{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', __('mensajes.crear_categoria'))

@section('contenido')
    <h1>{{ __('mensajes.crear_categoria') }}</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.categorias.store') }}">
        @csrf

        <label>{{ __('mensajes.nombre') }}</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}">

        <button type="submit">{{ __('mensajes.guardar') }}</button>
    </form>
@endsection
