{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', __('mensajes.editar_sede'))

@section('contenido')
    <h1>{{ __('mensajes.editar_sede') }}</h1>

    <form method="POST" action="{{ route('admin.sedes.update', $sede) }}">
        @method('PUT')
        @include('admin.sedes._form')
    </form>
@endsection
