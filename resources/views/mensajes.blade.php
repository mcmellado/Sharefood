@extends('layouts.app')

@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/mensajes.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <div class="container mt-3">
        <h3>Conversación con {{ $amigo->usuario }}:</h3>
        
        <div class="messages-container">
            @forelse($mensajes as $mensaje)
                <div class="message">
                    <p><strong>{{ $mensaje->usuario->usuario }}:</strong> {{ $mensaje->mensaje }}</p>
                    <small>{{ $mensaje->created_at->diffForHumans() }}</small>
                </div>
            @empty
                <p>No hay mensajes.</p>
            @endforelse
        </div>

        <form action="{{ route('enviarMensaje', ['amigoId' => $amigo->id]) }}" method="POST">
            @csrf
            <div class="form-group">
                <textarea class="form-control" name="mensaje" rows="3" placeholder="Escribe tu mensaje..."></textarea>
            </div>
            <a href="{{ route('perfil.social', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger">
                <i class="fas fa-arrow-left"></i>
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Enviar mensaje
            </button>
        </form>
    </div>
@endsection
