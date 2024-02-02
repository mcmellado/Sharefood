@extends('layouts.app')

@section('contenido')
    <h1>Modificar Horarios</h1>

    <form action="{{ route('restaurantes.guardar-horas', ['slug' => $restaurante->slug]) }}" method="post">
        @csrf

        @foreach ($diasDeLaSemana as $dia)
            <div>
                <label for="hora_apertura_{{ $dia }}">{{ $dia }} - Hora Apertura:</label>
                <input type="time" name="hora_apertura_{{ $dia }}" value="{{ optional($restaurante->horarios->where('dia_semana', $dia)->first())->hora_apertura }}">

                <label for="hora_cierre_{{ $dia }}">Hora Cierre:</label>
                <input type="time" name="hora_cierre_{{ $dia }}" value="{{ optional($restaurante->horarios->where('dia_semana', $dia)->first())->hora_cierre }}">

                <label for="intervalo_{{ $dia }}">Intervalo:</label>
                <input type="number" name="intervalo_{{ $dia }}" value="{{ optional($restaurante->horarios->where('dia_semana', $dia)->first())->intervalo }}">
            </div>
        @endforeach

        <button type="submit">Guardar Horarios</button>
    </form>
@endsection
