@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container mt-5">
    <form action="{{ route('restaurantes.buscar') }}" method="GET">
        <div class="input-group mb-3 position-relative"> 
            <input type="text" class="form-control" placeholder="Buscar restaurantes..." name="q" id="buscar-input">
            <div class="sugerencias-desplegable position-absolute" id="sugerencias-desplegable"></div>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>
</div>
<br>
<br>

<div class="mejores-locales mt-5">
    <h2 class="mb-4">Los 5 Locales con Mejor Puntuación</h2>
    <ul class="list-group">
        @php
            $mejoresLocales = \App\Models\Restaurante::orderByDesc('puntuacion')->take(5)->get();
        @endphp
        @forelse($mejoresLocales as $local)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('restaurantes.perfil', $local->slug) }}">
                    <span>{{ $local->nombre }}</span>
                </a>
                <span class="badge badge-warning badge-pill">{{ $local->puntuacion }} &#9733;</span>
            </li>
        @empty
            <p>No hay locales disponibles.</p>
        @endforelse
    </ul>
</div>

<div id="carruselPrincipal" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('imagenes/imagen1.png') }}" class="d-block w-100" alt="Imagen 1">
        </div>
        <div class="carousel-item">
            <img src="{{ asset('imagenes/imagen2.jpg') }}" class="d-block w-100" alt="Imagen 2">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carruselPrincipal" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next" href="#carruselPrincipal" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Siguiente</span>
    </a>
</div>

<script>
    var desplegable = document.getElementById('sugerencias-desplegable');
    var inputBuscar = document.getElementById('buscar-input');

    inputBuscar.addEventListener('input', function () {
        var query = this.value;

        if (query.length >= 3) {
            fetch(`/restaurantes/buscar-sugerencias?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    actualizarDesplegableSugerencias(data);
                })
                .catch(error => {
                    console.error('Error al obtener sugerencias:', error);
                });
        } else {
            desplegable.innerHTML = ''; 
        }
    });

    function actualizarDesplegableSugerencias(sugerencias) {
        desplegable.innerHTML = '';

        if (sugerencias.length === 0) {
            desplegable.style.display = 'none';
            return;
        }

        sugerencias.forEach(function (sugerencia) {
            var sugerenciaItem = document.createElement('div');
            sugerenciaItem.className = 'sugerencia-item';

            // Crear un enlace al perfil del restaurante
            var enlacePerfil = document.createElement('a');
            enlacePerfil.href = `/restaurantes/${sugerencia.slug}`;
            enlacePerfil.textContent = sugerencia.nombre;

            sugerenciaItem.appendChild(enlacePerfil);
            desplegable.appendChild(sugerenciaItem);
        });

        desplegable.style.display = 'block';
    }

    document.addEventListener('click', function (event) {
        if (!desplegable.contains(event.target)) {
            desplegable.style.display = 'none';
        }
    });
</script>

@endsection 