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
        var nuevaFechaInput = document.getElementById('nueva_fecha');
        var nuevaHoraInput = document.getElementById('nueva_hora');
        
        var nuevaFecha = new Date(nuevaFechaInput.value + 'T' + nuevaHoraInput.value);
        var diaSemana = nuevaFecha.toLocaleDateString('es', { weekday: 'long' });

        // Obtener el restaurante asociado a la reserva
        var restauranteId = "{{ $reserva->restaurante_id }}";
        var horariosRestaurante = {!! json_encode(App\Models\Restaurante::find($reserva->restaurante_id)->horarios) !!};

        var horarioParaDia = horariosRestaurante.find(function (horario) {
            return horario.dia_semana.toLowerCase() === diaSemana.toLowerCase();
        });

        if (!horarioParaDia) {
            alert('El restaurante no está abierto los ' + diaSemana + 's.');
            return false;
        }

        var horaApertura = new Date('1970-01-01T' + horarioParaDia.hora_apertura);
        var horaCierre = new Date('1970-01-01T' + horarioParaDia.hora_cierre);

        if (nuevaFecha < horaApertura || nuevaFecha > horaCierre) {
            alert('La reserva debe estar dentro del horario de apertura (' + horarioParaDia.hora_apertura + ' - ' + horarioParaDia.hora_cierre + ').');
            return false;
        }

        var fechaActual = new Date();

        if (nuevaFecha < fechaActual) {
            alert('La nueva fecha de reserva no puede ser en el pasado.');
            return false;
        }

        return true;
    }
</script>

@endsection
