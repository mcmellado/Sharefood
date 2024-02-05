@extends('layouts.app')

<title> Modificar perfil </title>

@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/perfil-modificar.css') }}">

@if ($errors->has('email'))
    <div class="alert alert-danger" role="alert">
        {{ $errors->first('email') }} <span onclick="this.parentElement.style.display='none'" style="float:right;cursor:pointer;">&times;</span>
    </div>
@endif


<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1>Modificar Perfil de {{ $usuario->usuario }}</h1>

            <form action="{{ route('perfil.modificar.guardar') }}" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario()">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" name="email" value="{{ $usuario->email }}" class="form-control" id="email">
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" value="{{ $usuario->telefono }}" class="form-control" id="telefono" pattern="[0-9]{9}" title="Teléfono debe contener solo 9 números">
                </div>

                <div class="form-group">
                    <label for="biografia">Biografía:</label>
                    <textarea name="biografia" rows="4" class="form-control">{{ $usuario->biografia }}</textarea>
                </div>

                <div class="form-group">
                    <label for="imagen">Imagen de Perfil:</label>
                    <input type="file" name="imagen" accept="image/*" class="form-control-file">
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Guardar Cambios
                </button>
                <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </form>
        </div>
    </div>
</div>

<script>
    function validarFormulario() {
        var telefonoInput = document.getElementById('telefono');
        var telefonoRegex = /^[0-9]{9}$/;

        if (!telefonoRegex.test(telefonoInput.value)) {
            alert('Teléfono debe contener solo 9 números');
            return false;
        }

        return true; 
    }
</script>

@endsection
