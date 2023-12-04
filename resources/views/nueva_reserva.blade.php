@extends('layouts.app')

@section('contenido')

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
        var fechaSeleccionada = new Date(fechaInput.value);
        var fechaActual = new Date();
        if (fechaSeleccionada < fechaActual) {
            alert('La fecha de la reserva no puede ser anterior a la fecha actual.');
            return false; 
        }

        return true; 
    }
</script>

@endsection
