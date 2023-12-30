@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Comentarios del Restaurante {{ $restaurante->nombre }}</h1>

            <ul class="list-group">
                @forelse($comentarios as $comentario)
                    <li class="list-group-item">
                        <p>Usuario: {{ $comentario->usuario->usuario }}</p>
                        <p>Contenido: {{ $comentario->contenido }}</p>
                        @if($comentario->imagen)
                            <img src="{{ asset('storage/' . $comentario->imagen) }}" alt="Imagen del comentario" class="img-thumbnail">
                        @endif
                    </li>
                @empty
                    <p>No hay comentarios para este restaurante.</p>
                @endforelse
            </ul>
        </div>
    </div>
    
    <br>
    
    <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-primary">Volver al perfil</a>
    <a href="{{ route('restaurantes.verReservas', ['slug' => $restaurante->slug]) }}" class="btn btn-primary">Ver Reservas</a>
    <!-- Agrega el botón para volver a la página anterior -->
    <a href="javascript:history.back()" class="btn btn-secondary">Volver atrás</a>
</div>

@endsection
