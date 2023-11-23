@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">


<title> ShareFood </title>

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

<div class="mejores-locales mt-5">
    <h2 class="mb-4">Los 5 Locales con Mejor Puntuación</h2>
    <ul class="list-group">
        @php
            $mejoresLocales = \App\Models\Restaurante::orderByDesc('puntuacion')->take(5)->get();
        @endphp
        @foreach($mejoresLocales as $local)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $local->nombre }}</span>
                <span class="badge badge-warning badge-pill">{{ $local->puntuacion }} &#9733;</span>
            </li>
        @endforeach
    </ul>
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
            sugerenciaItem.textContent = sugerencia.nombre;
            sugerenciaItem.addEventListener('click', function () {
                inputBuscar.value = sugerencia.nombre;
                desplegable.style.display = 'none';
            });

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
