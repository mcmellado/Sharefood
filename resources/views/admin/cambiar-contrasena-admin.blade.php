@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">


<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1>Cambiar contraseña de {{ $usuario->usuario }}:</h1>

            <form action="{{ route('admin.usuarios.cambiar-contrasena-admin.guardar', ['usuarioId' => $usuario->id]) }}" method="POST" 
                onsubmit="return validarContrasena(event)">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="password">Nueva Contraseña:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <small id="passwordHelp" class="form-text text-muted">
                        La contraseña debe tener al menos una letra y un número, no puede contener caracteres especiales y debe tener al menos 6 caracteres.
                    </small>
                    <div class="invalid-feedback" id="errorPasswordLength">
                        La contraseña debe tener al menos 6 caracteres.
                    </div>
                    <div class="invalid-feedback" id="errorPasswordPattern">
                        La contraseña debe tener al menos una letra y un número, y no puede contener caracteres especiales.
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña:</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Cambiar Contraseña</button>

                <a href="{{ route('admin.panel_admin') }}" class="btn btn-danger">Cancelar</a>

                @if(session('contrasena-cambiada'))
                    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                        {{ session('contrasena-cambiada') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/cambiar_contrasena_admin.js') }}" defer></script>

@endsection
