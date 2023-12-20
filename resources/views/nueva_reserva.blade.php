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
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
                <div class="form-group">
                    <label for="hora">Hora de la Reserva:</label>
                    <input type="time" class="form-control" id="hora" name="hora" required>
                </div>
                <div class="form-group">
                    <label for="cantidad_personas">Cantidad de personas:</label>
                    <input type="number" class="form-control" id="cantidad_personas" name="cantidad_personas" required>
                </div>
                <button type="submit" class="btn btn-success">Confirmar Reserva</button>
                <a href="javascript:history.back()" class="btn btn-secondary">Volver Atrás</a>
            </form>
        </div>
    </div>
</div>

<script>
    var reservasPorFecha = {};

    @foreach($restaurante->reservas as $reserva)
        var fecha = "{{ $reserva->fecha }}";
        if (!reservasPorFecha[fecha]) {
            reservasPorFecha[fecha] = [];
        }
        reservasPorFecha[fecha].push({
            hora: "{{ $reserva->hora }}",
            personas: "{{ $reserva->cantidad_personas }}"
        });
    @endforeach

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

        var intervaloInicio = new Date(fechaSeleccionada);
        intervaloInicio.setHours(intervaloInicio.getHours() - 1);

        var intervaloFin = new Date(fechaSeleccionada);
        intervaloFin.setHours(intervaloFin.getHours() + 1);

        var reservasEnIntervalo = 1;

        Object.keys(reservasPorFecha).forEach(function (fecha) {
            reservasPorFecha[fecha].forEach(function (reserva) {
                var fechaReserva = new Date(fecha + 'T' + reserva.hora);
                if (fechaReserva >= intervaloInicio && fechaReserva <= intervaloFin) {
                    reservasEnIntervalo += parseInt(reserva.personas);
                }
            });
        });

        if (reservasEnIntervalo + parseInt(cantidadPersonasInput.value) > 150) {
            alert('Aforo completo en esos momentos. Por favor, reserva más tarde.');
            return false;
        }
        var mediaHora = 30 * 60 * 1000; 

        if (horaSeleccionada >= horaCierre - mediaHora || horaSeleccionada <= horaApertura + mediaHora) {
            alert('No puede hacer la reserva porque está cerrando o a punto de cerrar. Por favor, elija otro horario.');
            return false;
        }

        return true;
    } 

    function parseHora(horaString) {
        var partes = horaString.split(':');
        return new Date(1970, 0, 1, partes[0], partes[1]);
    }
</script>

@endsection
