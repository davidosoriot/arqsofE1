{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', __('mensajes.crear_sede'))

@section('contenido')
    <h1>{{ __('mensajes.crear_sede') }}</h1>

    <form method="POST" action="{{ route('admin.sedes.store') }}">
        @include('admin.sedes._form')
    </form>
@endsection
