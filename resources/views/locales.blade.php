@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/locales.css') }}">

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

            <div class="scroll-container">
                @if(count($restaurantes) > 0)
                    <ul class="list-group">
                        @foreach($restaurantes as $restaurante)
                            <li class="list-group-item">
                                <h5>{{ $restaurante->nombre }}</h5>
                                <p>Dirección: {{ $restaurante->direccion }}</p>
                                <p>Sitio web: {{ $restaurante->sitio_web ?? 'No disponible' }}</p>
                                <p>Teléfono: {{ $restaurante->telefono ?? 'No disponible' }}</p>
                                <a href="{{ route('restaurante.mis-restaurantes.modificar', ['slug' => $restaurante->slug]) }}" class="btn btn-info btn-sm">Modificar Restaurante</a>
                                <a href="{{ route('restaurantes.verReservas', ['slug' => $restaurante->slug]) }}" class="btn btn-primary btn-sm">Ver Reservas</a>
                                <a href="{{ route('restaurantes.verComentarios', ['slug' => $restaurante->slug]) }}" class="btn btn-secondary btn-sm">Ver Comentarios</a>
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
    
    <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-primary">Volver al perfil</a>

</div>

@endsection
