
<link rel="stylesheet" href="{{ asset('css/ver-comentarios.css') }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">


@extends('layouts.app')

@section('contenido')
    <div class="container">
        <h2 class="mt-4 mb-4">Comentarios de {{ $usuario->usuario }}</h2>

        <div class="comment-container">
            @forelse ($comentarios as $comentario)
                <div class="card">
                    <div class="card-body">
                        <p>{{ $comentario->contenido }}</p>

                        @if ($comentario->fecha_publicacion)
                            <p>Fecha de publicación: {{ $comentario->fecha_publicacion->format('Y-m-d H:i:s') }}</p>
                        @else
                            <p>Fecha de publicación no disponible</p>
                        @endif

                        @if ($comentario->restaurante)
                            <p>Sitio del restaurante: {{ $comentario->restaurante->nombre }}</p>
                        @else
                            <p>Sitio del restaurante no disponible</p>
                        @endif

                        <!-- Agregar un formulario para permitir al admin eliminar el comentario -->
                        <form method="post" action="{{ route('admin.comentarios.eliminar', $comentario->id) }}" style="display:inline">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </div>
                </div>
            @empty
                <p>No hay comentarios.</p>
            @endforelse
        </div>

        <!-- Agregar un enlace de regreso al panel del admin al final de la página -->
        <a href="{{ route('admin.panel_admin') }}" class="btn btn-primary btn-return-admin">Volver al Panel de Administrador</a>
    </div>
@endsection



