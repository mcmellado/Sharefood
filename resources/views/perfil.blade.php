@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">

<div class="container mt-5">
    <div class="card">
        <div class="card-body text-center">
            @if($usuario->imagen)
                <img src="{{ '/storage/' . $usuario->imagen }}" alt="{{ $usuario->usuario }}" class="img-fluid mt-3 img-perfil rounded-circle">
            @endif

            <h1 class="mt-3">{{ $usuario->usuario }}</h1>
            
            <table class="table mt-3">
                <tbody>
                    <tr>
                        <th scope="row">Correo Electrónico</th>
                        <td>{{ $usuario->email }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Teléfono</th>
                        <td>{{ $usuario->telefono }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Biografía</th>
                        <td>{{ $usuario->biografia }}</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="mt-3">
                @auth
                @if(Auth::check())
                    <a href="{{ route('logout') }}" class="btn btn-danger btn-cerrar-sesion mr-2">Cerrar Sesión</a>
                @endif
            
                    @if(Auth::user()->id === $usuario->id)
                        <a href="{{ route('perfil.modificar', ['nombreUsuario' => $usuario->usuario]) }}" class="btn btn-primary btn-modificar mr-2">Modificar Perfil</a>
                    @endif
                @endauth
            
                {{-- Botón Ver Reservas con un margen superior ajustado --}}
                <a href="{{ route('perfil.reservas', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-info btn-ver-reservas mr-2">Ver Reservas</a>
            </div>
            
        </div>
    </div>
</div>

@endsection
