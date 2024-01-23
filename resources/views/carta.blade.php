@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="{{ asset('css/carta.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <div class="main-container">
        <div class="container mt-5">
            @if(Session::has('success_message'))
                <div class="alert alert-success">
                    <span>{{ Session::get('success_message') }}</span>
                    <button class="close" aria-label="Close" onclick="this.parentElement.style.display='none';">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(Session::has('error_message'))
                <div class="alert alert-danger">
                    <span>{{ Session::get('error_message') }}</span>
                    <button class="close" aria-label="Close" onclick="this.parentElement.style.display='none';">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="my-custom-heading">Carta de "{{ $restaurante->nombre }}":</h3>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </div>

            <form id="pedidoForm" action="{{ route('realizar-pedido') }}" method="POST" onsubmit="return submitForm()">
                @csrf
                <input type="hidden" name="restaurante_id" value="{{ $restaurante->id }}">

                @if ($productos->isNotEmpty())
                    <div class="card mt-4">
                        <div class="card-body">
                            @foreach ($productos as $producto)
                                <div class="mb-4 border-bottom">
                                    <h4 class="font-weight-bold">{{ $producto->nombre }}</h4>
                                    <p class="mb-2">{{ $producto->descripcion }}</p>
                                    <p class="font-weight-bold">Precio: {{ number_format($producto->precio, 2) }} €</p>
                                    <label>
                                        Seleccionar plato: <input type="checkbox" onchange="toggleCantidadInput(event, {{ $producto->id }})" name="productos_checkbox[]"> 
                                    </label>
                                    <input type="number" class="cantidad form-control" name="productos[{{ $producto->id }}]" style="display: none;" disabled min="1" placeholder="Cantidad:" oninput="validarCantidad(event)" pattern="[0-9]+" required>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    @auth
                                    <h3>Dirección de Entrega:</h3>
                                    <div class="input-container">
                                        <input id="direccion" type="text" name="direccion" class="form-control input-corto" placeholder="Dirección" required>
                                        <ul id="direccionesGuardadas" class="list-group mt-2">
                                        </ul>
                                    </div>
                                    
                                    
                                    <ul id="direccionesGuardadas" class="list-group mt-2">
                                    </ul>
                                    @endauth
                                </div>

                                <div class="col-md-12 mt-2">
                                    @auth
                                        <button type="submit" class="btn btn-success">Realizar Pedido</button>
                                    @endauth
                                    <a href="{{ route('restaurantes.perfil', ['slug' => $restaurante->slug ]) }}" class="btn btn-secondary ml-2">Volver al Perfil</a>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <p class="mt-4">No hay productos disponibles en la carta.</p>
                @endif
            </form>
        </div>
    </div>

    <script>

        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
        document.addEventListener('DOMContentLoaded', function () {
    var direccionInput = document.getElementById('direccion');
    var direccionesGuardadas = document.getElementById('direccionesGuardadas');

    direccionInput.addEventListener('focus', function () {
        mostrarSugerencias();
    });

    direccionInput.addEventListener('blur', function () {
        setTimeout(function () {
            direccionesGuardadas.style.display = 'none';
        }, 200); 
    });

    function mostrarSugerencias() {
        var direcciones = obtenerDireccionesGuardadas();
        actualizarSugerencias(direcciones);
    }

    function obtenerDireccionesGuardadas() {
        var direcciones = [];
        var direccionCookie = getCookie("direccion_entrega");
        if (direccionCookie) {
            direcciones.push(decodeURIComponent(direccionCookie));
        }

        return direcciones;
    }

    function actualizarSugerencias(direcciones) {
        direccionesGuardadas.innerHTML = '';

        direcciones.forEach(function (direccion) {
            var li = document.createElement('li');
            li.className = 'list-group-item';
            li.innerText = direccion;

            li.addEventListener('click', function () {
                direccionInput.value = direccion;
                direccionesGuardadas.style.display = 'none';
            });

            direccionesGuardadas.appendChild(li);
        });

        if (direcciones.length > 0) {
            direccionesGuardadas.style.display = 'block';
        }
    }
});


    function submitForm() {
    var direccion = document.getElementById('direccion').value;
    if (direccion.trim() !== '') {
        setCookie('direccion_entrega', encodeURIComponent(direccion), 30);
    }
    return true;
}

        function toggleCantidadInput(event, productId) {
            var cantidadInput = event.target.parentNode.nextElementSibling;
            cantidadInput.style.display = event.target.checked ? 'block' : 'none';
            cantidadInput.disabled = !event.target.checked;
            cantidadInput.value = 1;
        }

        function validarCantidad(event) {
            if (parseFloat(event.target.value) === 0) {
                event.target.value = 1;
            }
        }
    </script>
@endsection
