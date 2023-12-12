<!-- resources/views/admin/ver-comentarios.blade.php -->

@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/ver-comentarios.css') }}">

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

                        <form method="post" action="{{ route('admin.comentarios.eliminar', $comentario->id) }}" style="display:inline">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este comentario?')">Eliminar</button>
                        </form>

                        @if(session('comentario-eliminado-' . $comentario->id))
                            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                {{ session('comentario-eliminado-' . $comentario->id) }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p>No hay comentarios.</p>
            @endforelse
        </div>
        <a href="{{ route('admin.panel_admin') }}" class="btn btn-primary btn-return-admin mt-3">Volver al Panel de Administrador</a>
    </div>
@endsection
