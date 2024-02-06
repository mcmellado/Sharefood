@extends('layouts.app')

@section('contenido')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">

<div class="container mt-5">
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
</div>


<div class="espaciado-superior">
<div class="container mt-5">
    <h2 class="my-4">Modificar Horas de {{ $restaurante->nombre }}:</h2>

    @php
        $horasAnteriores = [];
        foreach ($horarios as $horario) {
            $horasAnteriores[$horario->id] = [
                'hora_apertura' => $horario->hora_apertura,
                'hora_cierre' => $horario->hora_cierre,
            ];
        }
    @endphp

    <form action="{{ route('restaurantes.horarios.guardar', ['slug' => $restaurante->slug]) }}" method="post">
        @csrf
        <table class="table" id="tabla-horarios">
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Hora de Apertura</th>
                    <th>Hora de Cierre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @if(count($horarios) > 0)
                @foreach ($horarios as $horario)
                <tr id="horario_{{ $horario->id }}">
                    <input type="hidden" name="nuevo_dia[{{ $horario->id }}]" value="{{ $horario->dia_semana }}">
                    <td>{{ $horario->dia_semana }}</td>
                    <td>
                        <select name="hora_apertura[{{ $horario->id }}]">
                            @for ($intervalo = 0; $intervalo <= 47; $intervalo++)
                                @php
                                    $hora = floor($intervalo / 2);
                                    $minuto = ($intervalo % 2) * 30;
                                    $horaFormato = sprintf('%02d:%02d', $hora, $minuto);
                                    $horaAnterior = substr($horasAnteriores[$horario->id]['hora_apertura'], 0, 5); // Extrae HH:mm
                                @endphp
                                <option value="{{ $horaFormato }}" {{ $horaAnterior == $horaFormato ? 'selected' : '' }}>
                                    {{ $horaFormato }}
                                </option>
                            @endfor
                        </select>
                    </td>

                    <!-- Hora de Cierre -->
                    <td>
                        <select name="hora_cierre[{{ $horario->id }}]">
                            @for ($intervalo = 0; $intervalo <= 47; $intervalo++)
                                @php
                                    $hora = floor($intervalo / 2);
                                    $minuto = ($intervalo % 2) * 30;
                                    $horaFormato = sprintf('%02d:%02d', $hora, $minuto);
                                    $horaAnterior = substr($horasAnteriores[$horario->id]['hora_cierre'], 0, 5); // Extrae HH:mm
                                @endphp
                                <option value="{{ $horaFormato }}" {{ $horaAnterior == $horaFormato ? 'selected' : '' }}>
                                    {{ $horaFormato }}
                                </option>
                            @endfor
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila({{ $horario->id }})">Eliminar</button>
                    </td>
                </tr>
                @endforeach
                @else
                        <p>No hay horarios disponibles.</p>
                @endif
            </tbody>
        </table>

        <a href="{{ route('perfil.mis-restaurantes', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger" style="width: 100px;">
            <i class="fas fa-arrow-left"></i>     
        </a>
        <button type="button" class="btn btn-success" onclick="agregarFila()">Agregar Tramo Horario</button>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
</div>
<script>

function agregarFila() {
var tabla = document.getElementById('tabla-horarios').getElementsByTagName('tbody')[0];
var nuevaFila = tabla.insertRow(tabla.rows.length);
var dias = ["lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo"];

for (var i = 0; i < 3; i++) {
    var celda = nuevaFila.insertCell(i);
    if (i === 0) {
        var selectDia = document.createElement('select');
        selectDia.name = 'nuevo_dia[]';


        for (var j = 0; j < dias.length; j++) {
            var optionDia = document.createElement('option');
            optionDia.value = dias[j].toLowerCase();
            optionDia.text = dias[j];
            selectDia.appendChild(optionDia);
        }

        celda.appendChild(selectDia);
    } else {
        var selectHora = document.createElement('select');
        selectHora.name = i === 1 ? 'hora_apertura[]' : (i === 2 ? 'hora_cierre[]' : '');

        for (var k = 0; k <= 47; k++) {
            var hora = Math.floor(k / 2);
            var minuto = (k % 2) * 30;
            var horaFormato = ('0' + hora).slice(-2) + ':' + ('0' + minuto).slice(-2);
            var option = document.createElement('option');
            option.value = horaFormato;
            option.text = horaFormato;
            selectHora.appendChild(option);
        }
        celda.appendChild(selectHora);
    }
}

var celdaEliminar = nuevaFila.insertCell(3);
var botonEliminar = document.createElement('button');
botonEliminar.type = 'button';
botonEliminar.className = 'btn btn-danger btn-sm';
botonEliminar.textContent = 'Eliminar';
botonEliminar.onclick = function() {
    eliminarFila(-1);
};
celdaEliminar.appendChild(botonEliminar);
}


    function eliminarFila(horarioId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'No podrás revertir esto.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo'
    }).then((result) => {
        if (result.isConfirmed) {
            if (horarioId > 0) {
                fetch("{{ url('/eliminar-horario') }}/" + horarioId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var fila = document.getElementById('horario_' + horarioId);
                        fila.parentNode.removeChild(fila);
                        Swal.fire('¡Eliminado!', 'El tramo horario ha sido eliminado.', 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar el tramo horario.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Ocurrió un error al procesar la solicitud.', 'error');
                });
            } else {
                var filaNueva = document.getElementById('tabla-horarios').getElementsByTagName('tbody')[0].lastChild;
                filaNueva.parentNode.removeChild(filaNueva);
            }
        }
    });
}
</script>

@endsection