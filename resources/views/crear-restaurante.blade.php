@extends('layouts.app')

@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/modi.css') }}">

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
                            <div class="d-flex">
                                <select name="estado_{{ strtolower($dia) }}" class="form-control" required>
                                    <option value="abierto">Abierto</option>
                                    <option value="cerrado">Cerrado</option>
                                </select>

                                <input type="time" name="hora_apertura_{{ strtolower($dia) }}" class="form-control mr-2" required>
                                <input type="time" name="hora_cierre_{{ strtolower($dia) }}" class="form-control" required>
                            </div>
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const selectsEstado = document.querySelectorAll('[name^="estado_"]');

        selectsEstado.forEach(selectEstado => {
            const inputApertura = selectEstado.parentElement.querySelector('[name^="hora_apertura"]');
            const inputCierre = selectEstado.parentElement.querySelector('[name^="hora_cierre"]');

            selectEstado.addEventListener("change", function () {
                const cerrado = this.value === 'cerrado';
                inputApertura.disabled = cerrado;
                inputCierre.disabled = cerrado;
            });

            if (selectEstado.value === 'cerrado') {
                inputApertura.disabled = true;
                inputCierre.disabled = true;
            }
        });
    });
</script>

@endsection
