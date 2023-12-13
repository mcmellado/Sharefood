@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/nueva_reserva.css') }}">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Modificar Reserva</h1>
            <form action="{{ route('admin.reservas.guardar-modificacion', ['reservaId' => $reserva->id]) }}" method="POST" onsubmit="return validarReserva()">
                @csrf
                @method('put') 
                <div class="form-group">
                    <label for="nueva_fecha">Nueva Fecha de Reserva:</label>
                    <input type="date" class="form-control" id="nueva_fecha" name="nueva_fecha" value="{{ optional($reserva->fecha_reserva)->format('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label for="nueva_hora">Nueva Hora de Reserva:</label>
                    <input type="time" class="form-control" id="nueva_hora" name="nueva_hora" value="{{ $reserva->hora }}" required>
                </div>

                <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>

<script>
    function validarReserva() {
        var fechaInput = document.getElementById('nueva_fecha');
        var horaInput = document.getElementById('nueva_hora');
        var fechaSeleccionada = new Date(fechaInput.value + 'T' + horaInput.value);
        var diaSemana = fechaSeleccionada.toLocaleDateString('es', { weekday: 'long' });

        // Asegúrate de que los datos se estén pasando correctamente
        var horariosRestaurante = {!! json_encode($reserva->restaurante->horarios ?? []) !!};

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

        var fechaActual = new Date();

        if (fechaSeleccionada < fechaActual) {
            alert('La nueva fecha de reserva no puede ser en el pasado.');
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
