@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container mt-3">
    @auth
    @if(Auth::user()->id === $usuario->id)

    <div class="mt-3">
        <h3>Amigos</h3>
        <ul class="list-group">
            @forelse($amigos as $amigo)
                <li class="list-group-item">
                    {{ $amigo->usuario }}
                    <a href="{{ route('perfil.mensajes', ['amigoId' => $amigo->id]) }}" class="btn btn-primary">Mensajes</a>
                </li>
            @empty
                <li class="list-group-item">No tienes amigos.</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-3">
        <h3>Solicitudes Pendientes</h3>
        <ul class="list-group">
            @forelse($solicitudesPendientes as $solicitud)
                <li class="list-group-item">
                    {{ $solicitud->usuario->usuario }} te ha enviado una solicitud de amistad.
                    <form action="{{ route('perfil.aceptarSolicitud', ['id' => $solicitud->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Aceptar</button>
                    </form> 
                </li>
            @empty
                <li class="list-group-item">No tienes solicitudes pendientes.</li>
            @endforelse
        </ul>
    </div>

    @endif
    @endauth
</div>

@endsection
