@extends('layouts.app')

@section('contenido')

<div class="container mt-5">
    <h2 class="my-4">Modificar Horas de {{ $restaurante->nombre }}</h2>

    {{-- Almacenar las horas anteriores en un array asociativo --}}
    @php
        $horasAnteriores = [];
        foreach ($horarios as $horario) {
            $horasAnteriores[$horario->id] = [
                'hora_apertura' => $horario->hora_apertura,
                'hora_cierre' => $horario->hora_cierre,
            ];
        }
    @endphp

    <form action="{{ route('restaurantes.guardar-horas', ['slug' => $restaurante->slug]) }}" method="post">
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
                @foreach ($horarios as $horario)
                <tr>
                    <td>{{ $horario->dia_semana }}</td>
                    <td>
                        <select name="hora_apertura[{{ $horario->id }}]">
                            @foreach (range(0, 47) as $intervalo)
                                @php
                                    $hora = floor($intervalo / 2);
                                    $minuto = ($intervalo % 2) * 30;
                                    $horaFormato = sprintf('%02d:%02d', $hora, $minuto);
                                @endphp
                                <option value="{{ $horaFormato }}" {{ $horasAnteriores[$horario->id]['hora_apertura'] == $horaFormato ? 'selected' : '' }}>
                                    {{ $horaFormato }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="hora_cierre[{{ $horario->id }}]">
                            @foreach (range(0, 47) as $intervalo)
                                @php
                                    $hora = floor($intervalo / 2);
                                    $minuto = ($intervalo % 2) * 30;
                                    $horaFormato = sprintf('%02d:%02d', $hora, $minuto);
                                @endphp
                                <option value="{{ $horaFormato }}" {{ $horasAnteriores[$horario->id]['hora_cierre'] == $horaFormato ? 'selected' : '' }}>
                                    {{ $horaFormato }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" class="btn btn-success btn-sm" onclick="agregarFila()">Agregar Tramo Horario</button>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

<script>
    function agregarFila() {
        var tabla = document.getElementById('tabla-horarios').getElementsByTagName('tbody')[0];
        var nuevaFila = tabla.insertRow(tabla.rows.length);
        var dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];

        for (var i = 0; i < 3; i++) {
            var celda = nuevaFila.insertCell(i);
            if (i === 0) {
                var selectDia = document.createElement('select');
                selectDia.name = 'nuevo_dia[]';
                dias.forEach(function (dia) {
                    var option = document.createElement('option');
                    option.value = dia.toLowerCase();
                    option.text = dia;
                    selectDia.appendChild(option);
                });
                celda.appendChild(selectDia);
            } else {
                var selectHora = document.createElement('select');
                selectHora.name = 'nueva_hora[]';
                for (var j = 0; j <= 47; j++) {
                    var hora = Math.floor(j / 2);
                    var minuto = (j % 2) * 30;
                    var horaFormato = sprintf('%02d:%02d', hora, minuto);
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
            eliminarFila(this);
        };
        celdaEliminar.appendChild(botonEliminar);
    }

    function eliminarFila(boton) {
        var fila = boton.parentNode.parentNode;
        fila.parentNode.removeChild(fila);
    }
</script>

@endsection
