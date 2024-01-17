@extends('layouts.app')

@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Modificar Restaurante</h1>

            <form action="{{ route('restaurante.mis-restaurantes.guardar-modificacion', ['slug' => $restaurante->slug]) }}" method="post">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $restaurante->nombre) }}" class="form-control" required>
                    @error('nombre')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $restaurante->direccion) }}" class="form-control" required>
                    @error('direccion')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sitio_web">Sitio web:</label>
                    <input type="text" name="sitio_web" value="{{ old('sitio_web', $restaurante->sitio_web) }}" class="form-control">
                    @error('sitio_web')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $restaurante->telefono) }}" class="form-control">
                    @error('telefono')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="aforo_maximo">Aforo Máximo:</label>
                    <input type="number" name="aforo_maximo" value="{{ old('aforo_maximo', $restaurante->aforo_maximo) }}" class="form-control" required>
                    @error('aforo_maximo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="{{ route('perfil.mis-restaurantes', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger">Volver atrás</a>
                <a href="{{ route('restaurantes.gestionar_carta', ['slug' => $restaurante->slug]) }}" class="btn btn-secondary">Gestionar Carta</a>
            </form>
        </div>
    </div>
</div>

@endsection
