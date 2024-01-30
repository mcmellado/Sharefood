@extends('layouts.app')

<title> Crear Restaurante </title>


@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/crear-restaurante.css') }}">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Crear Nuevo Restaurante:</h1>

            <div class="scroll-container">
                <form action="{{ route('registrar-nuevo-restaurante') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="nombre">Nombre del Restaurante:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="gastronomia">Gastronomía:</label>
                        <input type="text" name="gastronomia" id="gastronomia" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="tel" name="telefono" id="telefono" class="form-control" pattern="[0-9]{9}" placeholder="Ejemplo: 123456789" title="Introduce un número de teléfono válido de 9 dígitos" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sitio_web">Sitio Web:</label>
                        <input type="url" name="sitio_web" id="sitio_web" class="form-control" placeholder="Ejemplo: https://www.ejemplo.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="aforo_maximo">Aforo Máximo:</label>
                        <input type="number" name="aforo_maximo" id="aforo_maximo" class="form-control" min="1" value="150" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="imagen">Imagen:</label>
                        <input type="file" name="imagen" id="imagen" class="form-control">
                    </div>

                    <h2>Horarios</h2>

                    @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                        <div class="form-group">
                            <label>{{ $dia }}:</label>
                            <div class="horarios-container">
                                <div class="horario">
                                    <select name="estado_{{ strtolower($dia) }}[]" class="form-control" required>
                                        <option value="abierto">Abierto</option>
                                        <option value="cerrado">Cerrado</option>
                                    </select>

                                    <select name="hora_apertura_{{ strtolower($dia) }}[]" class="form-control mr-2" required>
                                        @for ($hora = 0; $hora < 24; $hora++)
                                            @for ($minuto = 0; $minuto < 60; $minuto += 30)
                                                <option value="{{ sprintf('%02d:%02d', $hora, $minuto) }}">{{ sprintf('%02d:%02d', $hora, $minuto) }}</option>
                                            @endfor
                                        @endfor
                                    </select>

                                    <select name="hora_cierre_{{ strtolower($dia) }}[]" class="form-control" required>
                                        @for ($hora = 0; $hora < 24; $hora++)
                                            @for ($minuto = 0; $minuto < 60; $minuto += 30)
                                                <option value="{{ sprintf('%02d:%02d', $hora, $minuto) }}">{{ sprintf('%02d:%02d', $hora, $minuto) }}</option>
                                            @endfor
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary btn-agregar-horario" data-dia="{{ strtolower($dia) }}">Agregar Horario</button>
                            <button type="button" class="btn btn-danger btn-quitar-horario">Quitar Horario</button>
                        </div>
                    @endforeach
                    <div class="form-group">
                        <label for="intervalo">Intervalo:</label>
                        <input type="number" name="intervalo" class="form-control" min="1" value="60">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Restaurante
                    </button>
                    
                </form>
            </div>
        </div>
    </div>

    <br>

    <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger">
        <i class="fas fa-arrow-left"></i> 
    </a>
    
</div>

<script src="{{ asset('js/crear_restaurante.js') }}" defer></script>


@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-agregar-horario').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const dia = this.getAttribute('data-dia');
                const container = this.closest('.form-group').querySelector('.horarios-container');

                const nuevoHorario = container.querySelector('.horario').cloneNode(true);
                // container.appendChild(nuevoHorario);

                // nuevoHorario.querySelector('select[name^="hora_apertura"]').value = '';
                // nuevoHorario.querySelector('select[name^="hora_cierre"]').value = '';
            });
        });

        document.querySelectorAll('.btn-quitar-horario').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const horario = this.parentNode.querySelector('.horario');
                const container = this.closest('.form-group').querySelector('.horarios-container');

                if (container.querySelectorAll('.horario').length > 1) {
                    container.removeChild(horario);
                // } else {
                //     horario.querySelector('select[name^="estado"]').value = 'abierto';
                //     horario.querySelector('select[name^="hora_apertura"]').value = '';
                //     horario.querySelector('select[name^="hora_cierre"]').value = '';
                // } 
        }});
        });
    });
</script>