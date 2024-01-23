@extends('layouts.app')
@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/ver-comentarios.css') }}">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Comentarios del Restaurante {{ $restaurante->nombre }}:</h1>

            @if(count($comentarios) > 0)
                <ul class="list-group">
                    @foreach($comentarios as $comentario)
                        <li class="comment-container">
                            <h5 class="mt-0">{{ $comentario->usuario->usuario }}:</h5>
                            <p>{{ $comentario->contenido }}</p>
                            @if($comentario->imagen)
                                <img src="{{ asset('storage/' . $comentario->imagen) }}" alt="Imagen del comentario" class="img-thumbnail">
                            @endif
                            @if($comentario->usuario->puntuaciones->where('restaurante_id', $restaurante->id)->count() > 0)
                                @php
                                    $puntuacion = $comentario->usuario->puntuaciones->where('restaurante_id', $restaurante->id)->first()->puntuacion;
                                @endphp
                                <p>Este usuario ha calificado el restaurante con {{ number_format($puntuacion, ($puntuacion == intval($puntuacion) ? 0 : 2)) }} estrella(s) ⭐️</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Este restaurante aún no tiene comentarios.</p>
            @endif
        </div>
    </div>
     
        <div class="btn-group">
            <a href="{{ route('perfil.mis-restaurantes', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger ">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
</div>

@endsection
