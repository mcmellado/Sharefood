@extends('layouts.app')

@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/agregar_producto.css') }}">

    
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Editar Producto:</h1>

            <form action="{{ route('restaurantes.actualizar_producto', ['slug' => $restaurante->slug, 'id' => $producto->id]) }}" method="post" onsubmit="return validarFormulario()" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" value="{{ $producto->nombre }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" class="form-control">{{ $producto->descripcion }}</textarea>
                </div>

                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="text" name="precio" value="{{ $producto->precio }}" class="form-control" required>
                    <small id="precioHelp" class="form-text text-muted">Formato válido: 123.45</small>
                </div>

                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" name="imagen" accept="image/*">
                </div>

                <div class="btn-group mt-3">
                    <a href="{{ route('restaurantes.gestionar_carta', ['slug' => $restaurante->slug]) }}" class="btn btn-danger">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus"></i> Editar producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validarFormulario() {
        var precioRegex = /^\d+(\.\d{1,2})?$/;

        var precioInput = document.getElementsByName('precio')[0];
        var precioHelp = document.getElementById('precioHelp');

        if (!precioRegex.test(precioInput.value)) {
            precioHelp.innerHTML = 'Formato de precio no válido. Utiliza números con hasta dos decimales.';
            precioHelp.style.color = 'red';
            return false;
        } else {
            precioHelp.innerHTML = 'Formato válido: 123.45';
            precioHelp.style.color = 'black';
            return true;
        }
    }
</script>

@endsection
