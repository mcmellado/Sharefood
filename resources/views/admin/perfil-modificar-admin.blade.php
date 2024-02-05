<!-- resources/views/admin/perfil-modificar-admin.blade.php -->
@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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

            <form action="{{ route('admin.usuarios.modificar.guardar', ['usuarioId' => $usuario->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" name="email" value="{{ $usuario->email }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" value="{{ $usuario->telefono }}" class="form-control" pattern="[0-9]{9}" title="Teléfono debe contener solo 9 números">
                </div>

                <div class="form-group">
                    <label for="biografia">Biografía:</label>
                    <textarea name="biografia" rows="4" class="form-control">{{ $usuario->biografia }}</textarea>
                </div>

                <div class="form-group">
                    <label for="imagen">Imagen de Perfil:</label>
                    <input type="file" name="imagen" accept="image/*" class="form-control-file">
                </div>

                <button type="submit" class="btn btn-success">Guardar Cambios</button>
               
                <a href="{{ route('admin.panel_admin') }}" class="btn btn-danger">Cancelar</a>
            </form>
        </div>
    </div>
</div>

@endsection
