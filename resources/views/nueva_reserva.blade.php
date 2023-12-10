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
                <button type="submit" class="btn btn-success">Confirmar Reserva</button>
            </form>
        </div>
    </div>
</div>

<script>
    function validarReserva() {
        var fechaInput = document.getElementById('fecha');
        var horaInput = document.getElementById('hora');
        var fechaSeleccionada = new Date(fechaInput.value + 'T' + horaInput.value);
        var diaSemana = fechaSeleccionada.toLocaleDateString('es', { weekday: 'long' });

        // json_encode es una función de php que convierte una estructura de datos de PHP en una cadena json, lo que hace que pueda obtener los horarios del restaurante
        // y me los incorpora en javascript :)
        var horariosRestaurante = { json_encode($restaurante->horarios) };

        var horarioParaDia = horariosRestaurante.find(function (horario) {
            return horario.dia_semana.toLowerCase() === diaSemana.toLowerCase();
        });

        if (!horarioParaDia) {
            alert('El restaurante no está abierto los ' + diaSemana + 's.');
            return false;
        }

        var horaApertura = new Date('1970-01-01T' + horarioParaDia.hora_apertura);
        var horaCierre = new Date('1970-01-01T' + horarioParaDia.hora_cierre);

        if (fechaSeleccionada < horaApertura || fechaSeleccionada > horaCierre) {
            alert('La reserva debe estar dentro del horario de apertura (' + horarioParaDia.hora_apertura + ' - ' + horarioParaDia.hora_cierre + ').');
            return false;
        }

        return true;
    } 
</script>

@endsection
