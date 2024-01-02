@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/nueva_reserva.css') }}">

<div class="container mt-5">
    <div id="alerts-container"></div>
    <div class="card">
        <div class="card-body">
            <h1>Modificar Reserva</h1>
            <form action="{{ route('admin.reservas.guardar-modificacion', ['reservaId' => $reserva->id]) }}" method="POST" onsubmit="return validarReserva()">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nueva_fecha">Nueva Fecha de la Reserva:</label>
                    <input type="date" class="form-control" id="nueva_fecha" name="nueva_fecha"
                        onchange="cargarHorasDisponibles()" required value="{{ $reserva->fecha->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label for="nueva_hora">Nueva Hora de la Reserva:</label>
                    <select class="form-control" id="nueva_hora" name="nueva_hora" required>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad_personas">Cantidad de personas:</label>
                    <input type="number" class="form-control" id="cantidad_personas" name="cantidad_personas" required
                        value="{{ $reserva->cantidad_personas }}">
                </div>
                <button type="submit" class="btn btn-success">Confirmar Modificación</button>
                <a href="javascript:history.back()" class="btn btn-secondary">Volver Atrás</a>
            </form>
        </div>
    </div>
</div>

<script>
    var reservasPorFecha = {};
    reservasPorFecha["{{ $reserva->fecha }}"] = [{
        hora: "{{ $reserva->hora }}",
        personas: "{{ $reserva->cantidad_personas }}"
    }];

    var horariosRestaurante = {!! json_encode($reserva->restaurante->horarios) !!};

    function formatHora(hora) {
        var horas = hora.getHours().toString().padStart(2, '0');
        var minutos = hora.getMinutes().toString().padStart(2, '0');
        return horas + ':' + minutos;
    }

    function obtenerHorasReservadas(fechaSeleccionada) {
        var reservasParaFecha = reservasPorFecha[fechaSeleccionada] || [];
        return reservasParaFecha.map(function (reserva) {
            return reserva.hora;
        }); 
    }

    function cargarHorasDisponibles() {
        var fechaSeleccionada = document.getElementById('nueva_fecha').value;
        var reservasParaFecha = obtenerHorasReservadas(fechaSeleccionada);

        var diaSemana = new Date(fechaSeleccionada).toLocaleDateString('es', { weekday: 'long' });
        var horarioParaDia = horariosRestaurante.find(function (horario) {
            return horario.dia_semana.toLowerCase() === diaSemana.toLowerCase();
        });

        var horasDisponibles = obtenerHorasDisponibles(horarioParaDia.hora_apertura, horarioParaDia.hora_cierre, reservasParaFecha);

        var selectHora = document.getElementById('nueva_hora');
        selectHora.innerHTML = '';

        horasDisponibles.forEach(function (hora) {
            var option = document.createElement('option');
            option.value = hora;
            option.text = hora;
            selectHora.appendChild(option);
        });
    }

    function obtenerHorasDisponibles(horaApertura, horaCierre, reservas) {
        var horasDisponibles = [];
        var horaActual = parseHora(horaApertura);

        while (horaActual <= parseHora(horaCierre)) {
            var horaActualString = formatHora(horaActual);
            if (!reservas.includes(horaActualString)) {
                horasDisponibles.push(horaActualString);
            }
            horaActual.setMinutes(horaActual.getMinutes() + 30);
        }

        return horasDisponibles;
    }

    function mostrarAlerta(mensaje, tipo) {
        var alertsContainer = document.getElementById('alerts-container');

        var alertElement = document.createElement('div');
        alertElement.className = 'alert alert-' + tipo + ' alert-dismissible fade show';
        alertElement.role = 'alert';
        alertElement.innerHTML = `
            ${mensaje}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;

        alertsContainer.appendChild(alertElement);

        setTimeout(function() {
            alertElement.remove();
        }, 20000);
    }

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

    window.onload = function() {
        cargarHorasDisponibles();
    };

    function validarReserva() {
        var fechaInput = document.getElementById('nueva_fecha');
        var horaInput = document.getElementById('nueva_hora');
        var cantidadPersonasInput = document.getElementById('cantidad_personas');

        var fechaActual = new Date();
        var fechaSeleccionada = new Date(fechaInput.value + 'T' + horaInput.value);

        if (fechaSeleccionada < fechaActual) {
            mostrarAlerta('La fecha de la reserva no puede ser anterior a la fecha actual.', 'danger');
            return false;
        }

        var diaSemana = fechaSeleccionada.toLocaleDateString('es', { weekday: 'long' });

        var horarioParaDia = horariosRestaurante.find(function (horario) {
            return horario.dia_semana.toLowerCase() === diaSemana.toLowerCase();
        });

        if (!horarioParaDia) {
            mostrarAlerta('El restaurante no está abierto los ' + diaSemana + 's.', 'danger');
            return false;
        }

        var horaApertura = parseHora(horarioParaDia.hora_apertura);
        var horaCierre = parseHora(horarioParaDia.hora_cierre);

        var horaSeleccionada = parseHora(horaInput.value);

        if (horaSeleccionada < horaApertura || horaSeleccionada > horaCierre) {
            mostrarAlerta('La reserva debe estar dentro del horario de apertura (' + horarioParaDia.hora_apertura + ' - ' + horarioParaDia.hora_cierre + ').', 'danger');
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

        if (reservasEnIntervalo + parseInt(cantidadPersonasInput.value) > {{ $restaurante->aforo_maximo }}) {
            mostrarAlerta('Aforo completo en esos momentos. Por favor, reserva más tarde.', 'danger');
            return false;
        }
        var mediaHora = 30 * 60 * 1000; 

        if (horaSeleccionada >= horaCierre - mediaHora || horaSeleccionada <= horaApertura + mediaHora) {
            mostrarAlerta('No puede hacer la reserva porque está cerrando o a punto de cerrar. Por favor, elija otro horario.', 'danger');
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
