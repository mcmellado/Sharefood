@extends('layouts.app')

@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show alert-short" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Mis Restaurantes</h1>

            <div class="scroll-container" style="max-height: 500px; overflow-y: auto;">
                @if(count($restaurantes) > 0)
                    <ul class="list-group">
                        @foreach($restaurantes as $restaurante)
                            <li class="list-group-item">
                                <h5>{{ $restaurante->nombre }}</h5>
                                <p>Dirección: {{ $restaurante->direccion }}</p>
                                <p>Sitio web: {{ $restaurante->sitio_web ?? 'No disponible' }}</p>
                                <p>Teléfono: {{ $restaurante->telefono ?? 'No disponible' }}</p>
                                <a href="{{ route('restaurante.mis-restaurantes.modificar', ['slug' => $restaurante->slug]) }}" class="btn btn-info btn-sm">Modificar Restaurante</a>
                                <a href="{{ route('restaurantes.verReservas', ['slug' => $restaurante->slug]) }}" class="btn btn-primary btn-sm">Ver Reservas y Pedidos</a>
                                <a href="{{ route('restaurantes.verComentarios', ['slug' => $restaurante->slug]) }}" class="btn btn-secondary btn-sm">Ver Comentarios</a>
                                <button class="btn btn-danger btn-sm" data-toggle="collapse" data-target="#borrarCollapse{{ $restaurante->id }}" aria-expanded="false" aria-controls="borrarCollapse{{ $restaurante->id }}">
                                    Borrar Restaurante
                                </button>

                                <div class="float-right">

                                    <!-- Contenido de borrado colapsable -->
                                    <div class="collapse mt-2" id="borrarCollapse{{ $restaurante->id }}">
                                        <p class="mb-3">¿Estás seguro de que quieres borrar este restaurante?</p>
                                        <!-- Formulario para enviar la solicitud de borrado -->
                                        <form action="{{ route('restaurante.borrar', ['slug' => $restaurante->slug]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mr-2">Borrar</button>
                                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="collapse" data-target="#borrarCollapse{{ $restaurante->id }}" aria-expanded="false" aria-controls="borrarCollapse{{ $restaurante->id }}">
                                                Cancelar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No tienes restaurantes registrados.</p>
                @endif
            </div>
        </div>
    </div>

    <br>
    <div class="btn-group">
        <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-primary mr-2">Volver al perfil</a>

        <form action="{{ route('crear-nuevo-restaurante.formulario') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-success mr-2">Crear Nuevo Restaurante</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

@endsection
