@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/locales.css') }}">

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
                                {{-- Agrega aquí cualquier otra información que desees mostrar --}}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No tienes restaurantes registrados.</p>
                @endif
            </div>
        </div>
    </div>

    <button class="go-back btn btn-primary mt-3" onclick="goBack()">Volver atrás</button>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>

@endsection
