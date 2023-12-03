@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1>Perfil de {{ $usuario->usuario }}</h1>

            @if($usuario->imagen)
                <img src="{{ '/storage/' . $usuario->imagen }}" alt="{{ $usuario->usuario }}" class="img-fluid mt-3 img-perfil">
            @endif
            
            <p><strong>Correo Electrónico:</strong> {{ $usuario->email }}</p>
            <p><strong>Teléfono:</strong> {{ $usuario->telefono }}</p>
            <p><strong>Biografía:</strong> {{ $usuario->biografia }}</p>
            
            @auth
                @if(Auth::user()->id === $usuario->id)
                    <p><a href="{{ route('perfil.modificar', ['nombreUsuario' => $usuario->usuario]) }}" class="btn btn-primary btn-modificar">Modificar Perfil</a></p>
                @endif
            @endauth

            @if(Auth::check())
                <div class="btn btn-cerrarsesion mt-3">
                    <a href="{{ route('logout') }}" class="btn btn-danger btn-cerrar-sesion">Cerrar Sesión</a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
