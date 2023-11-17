<!-- resources/views/restaurantes/index.blade.php -->

@extends('layouts.app')

@section('contenido')

<div class="container mt-5">
    <form action="{{ route('restaurantes.buscar') }}" method="GET">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Buscar restaurantes..." name="q" id="buscar-input">
            <!-- Desplegable para las sugerencias -->
            <ul class="nav" id="sugerencias-desplegable"></ul>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>
</div>

<script>
    var desplegable = document.getElementById('sugerencias-desplegable');
    var inputBuscar = document.getElementById('buscar-input');

    inputBuscar.addEventListener('input', function () {
        var query = this.value;

        if (query.length >= 3) {
            // Supongamos que tienes la ruta correcta en tu aplicación
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/restaurantes/buscar-sugerencias?q=' + query, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var sugerencias = JSON.parse(xhr.responseText);
                    actualizarDesplegableSugerencias(sugerencias);
                }
            };
            xhr.send();
        } else {
            desplegable.style.display = 'none';
        }
    });

    function actualizarDesplegableSugerencias(sugerencias) {
        desplegable.innerHTML = '';

        if (sugerencias.length === 0) {
            desplegable.style.display = 'none';
            return;
        }

        sugerencias.forEach(function (sugerencia) {
            var listItem = document.createElement('li');
            listItem.innerHTML = '<a href="#">' + sugerencia + '</a>';
            desplegable.appendChild(listItem);
        });

        desplegable.style.display = 'block';
    }
</script>

@endsection
