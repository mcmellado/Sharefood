@extends('layouts.app')

@section('contenido')
    <div class="container">
        <h2 class="mt-4 mb-4">Comentarios de {{ $usuario->usuario }}</h2>

        @forelse ($comentarios as $comentario)
            <div class="card mb-3">
                <div class="card-body">
                    <p>{{ $comentario->contenido }}</p>
                    <p>Sitio: {{ $comentario->sitio }}</p>
                    {{-- <p>Fecha de publicación: {{ $comentario->hora_publicacion->format('Y-m-d H:i:s') }}</p> --}}

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
@endsection
