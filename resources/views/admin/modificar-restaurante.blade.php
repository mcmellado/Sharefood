@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <div class="container">
        <h2 class="mt-4 mb-4">Modificar Restaurante</h2>

        <form method="post" action="{{ route('admin.restaurantes.actualizar', $restaurante->id) }}" enctype="multipart/form-data" onsubmit="return validarRestaurante()">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="nombre">Nombre del Restaurante:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $restaurante->nombre }}" required>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $restaurante->direccion }}" required>
            </div>

            <div class="form-group">
                <label for="sitio_web">Sitio Web:</label>
                <input type="text" class="form-control" id="sitio_web" name="sitio_web" value="{{ $restaurante->sitio_web }}">
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $restaurante->telefono }}">
            </div>

            <div class="form-group">
                <label for="gastronomia">Gastronomía:</label>
                <input type="text" class="form-control" id="gastronomia" name="gastronomia" value="{{ $restaurante->gastronomia }}">
            </div>

            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <input type="file" class="form-control-file" id="imagen" name="imagen">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Restaurante</button>

            <a href="{{ route('admin.panel-admin-restaurante') }}" class="btn btn-secondary">Volver al Panel de Administración</a>

        </form>
    </div>

    <script src="{{ asset('js/modificar_restaurante_admin.js') }}" defer></script>
@endsection
