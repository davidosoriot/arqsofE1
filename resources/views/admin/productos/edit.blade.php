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

    <form method="POST"
          action="{{ route('admin.productos.update', $producto) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>{{ __('mensajes.categoria') }}</label>
        <select name="categoria_id" required>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}"
                    @selected(old('categoria_id', $producto->categoria_id) == $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>

        <label>{{ __('mensajes.nombre') }}</label>
        <input type="text" name="nombre"
               value="{{ old('nombre', $producto->nombre) }}" required>

        <label>{{ __('mensajes.tipo_carne') }}</label>
        <input type="text" name="tipo_carne"
               value="{{ old('tipo_carne', $producto->tipo_carne) }}" required>

        <label>{{ __('mensajes.precio') }} (por kilogramo)</label>
        <input type="number" step="0.01" min="0" name="precio"
               value="{{ old('precio', $producto->precio) }}" required>

        <label>Fotografía actual</label>

        @if ($producto->imagen)
            <div>
                <img src="{{ asset('storage/' . $producto->imagen) }}"
                     alt="{{ $producto->nombre }}"
                     style="width: 180px; height: 140px; object-fit: cover; border-radius: 10px;">
            </div>
        @else
            <p>Este producto todavía no tiene fotografía.</p>
        @endif

        <label for="imagen">Cambiar fotografía</label>
        <input type="file" id="imagen" name="imagen"
               accept=".jpg,.jpeg,.png,.webp">

        <small>Formatos permitidos: JPG, JPEG, PNG y WEBP. Máximo 2 MB.</small>

        <label>
            <input type="checkbox" name="disponible" value="1"
                   @checked(old('disponible', $producto->disponible ? '1' : '0') == '1')>
            {{ __('mensajes.disponible') }}
        </label>

        <button type="submit">{{ __('mensajes.guardar') }}</button>
    </form>
@endsection
