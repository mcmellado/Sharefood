@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/nueva_reserva.css') }}">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1>Hacer Reserva</h1>
            <form action="{{ route('restaurantes.guardarReserva', ['slug' => $restaurante->slug]) }}" method="POST" onsubmit="return validarReserva()">
                @csrf
                <div class="form-group">
                    <label for="fecha">Fecha de la Reserva:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required onclick="mostrarAforo()">
                </div>
                <div id="aforo-info" style="display: none;">
                    <p id="aforo-actual">Aforo actual: {{ $aforoRestante }}</p>
                    <p id="aforo-restante">Aforo restante: {{ $aforoDiario - $aforoRestante }}</p>
                </div>
                <div class="form-group">
                    <label for="hora">Hora de la Reserva:</label>
                    <input type="time" class="form-control" id="hora" name="hora" required>
                </div>
                <div class="form-group">
                    <label for="cantidad_personas">Cantidad de Personas:</label>
                    <input type="number" class="form-control" id="cantidad_personas" name="cantidad_personas" required>
                </div>
                <button type="submit" class="btn btn-success">Confirmar Reserva</button>
            </form>
        </div>
    </div>
</div>

<script>
    var horariosRestaurante = {!! json_encode($restaurante->horarios) !!};

    function validarReserva() {
        var fechaInput = document.getElementById('fecha');
        var horaInput = document.getElementById('hora');
        var cantidadPersonasInput = document.getElementById('cantidad_personas');
        var fechaSeleccionada = new Date(fechaInput.value + 'T' + horaInput.value);
        var diaSemana = fechaSeleccionada.toLocaleDateString('es', { weekday: 'long' });

        var horarioParaDia = horariosRestaurante.find(function (horario) {
            return horario.dia_semana.toLowerCase() === diaSemana.toLowerCase();
        });

        if (!horarioParaDia) {
            alert('El restaurante no está abierto los ' + diaSemana + 's.');
            return false;
        }

        var horaApertura = parseHora(horarioParaDia.hora_apertura);
        var horaCierre = parseHora(horarioParaDia.hora_cierre);

        var horaSeleccionada = parseHora(horaInput.value);

        if (horaSeleccionada < horaApertura || horaSeleccionada > horaCierre) {
            alert('La reserva debe estar dentro del horario de apertura (' + horarioParaDia.hora_apertura + ' - ' + horarioParaDia.hora_cierre + ').');
            return false;
        }

        return true;
    } 

    function parseHora(horaString) {
        var partes = horaString.split(':');
        return new Date(1970, 0, 1, partes[0], partes[1]);
    }

    function mostrarAforo() {
        var fechaInput = document.getElementById('fecha');
        var aforoInfo = document.getElementById('aforo-info');
        var aforoActual = document.getElementById('aforo-actual');
        var aforoRestante = document.getElementById('aforo-restante');

        var aforoPorDia = {!! json_encode($aforoPorDia) !!};
        var fechaSeleccionada = fechaInput.value;
        var aforoDiaSeleccionado = aforoPorDia[fechaSeleccionada] || 0;

        aforoActual.innerText = 'Aforo actual: ' + aforoDiaSeleccionado;
        aforoRestante.innerText = 'Aforo restante: ' + ({{ $aforoDiario }} - aforoDiaSeleccionado);

        aforoInfo.style.display = 'block';
    }
</script>

@endsection
