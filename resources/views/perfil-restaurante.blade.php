@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="{{ asset('css/restaurante-perfil.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="container mt-5 scrollable-container">
        @if(session('reserva-confirmada'))
            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                {{ session('reserva-confirmada') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <script>
                $(document).ready(function(){
                    $('#confirmacionReserva').modal('show');
                });
            </script>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h1>{{ $restaurante->nombre }}</h1>
                        <p class="text-muted">{{ $restaurante->gastronomia }}</p>
                        <p>{{ $restaurante->direccion }}</p>
                        <p>Teléfono: {{ $restaurante->telefono }}</p>
                        <p>Sitio web: <a href="{{ $restaurante->sitio_web }}" target="_blank">{{ $restaurante->sitio_web }}</a></p>
                        <div class="mb-3">
                            <p class="mb-0">Puntuación:</p>
                            <div class="custom-star-rating">
                                @php
                                    $puntuacion = $restaurante->puntuaciones->avg('puntuacion') ?: 0;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $puntuacion)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        {{-- <img src="{{ asset($restaurante->imagen) }}" alt="{{ $restaurante->nombre }}" class="img-fluid"> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Mostrar horarios si existen --}}
        @if ($restaurante->horarios)
            <div class="card mt-4">
                <div class="card-body">
                    <h3>Horarios:</h3>
                    @foreach ($restaurante->horarios as $horario)
                        <p>{{ $horario->dia_semana }}: {{ \Carbon\Carbon::parse($horario->hora_apertura)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->hora_cierre)->format('H:i') }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="card mt-4">
            <div class="card-body">
                <h3>Comentarios:</h3>
                <div id="comentarios-existentes" class="scrollable-content">
                    @forelse ($restaurante->comentarios as $comentario)
                        @php
                            $usuarioId = auth()->user()->id;
                            $usuarioBloqueado = DB::table('bloqueados')
                                ->where('usuario_id', $usuarioId)
                                ->where('usuario_bloqueado_id', $comentario->usuario_id)
                                ->exists();
        
                            $usuarioDelComentarioBloqueado = DB::table('bloqueados')
                                ->where('usuario_id', $comentario->usuario_id)
                                ->where('usuario_bloqueado_id', $usuarioId)
                                ->exists();
                        @endphp
        
        <div class="media mt-3">
            <div class="media-body">
                <h5 class="mt-0">
                    @if(Auth::check() && auth()->user()->id !== $comentario->usuario_id)
                        @if ($usuarioBloqueado)
                            No puedes ver este comentario porque el usuario ha sido bloqueado.
                        @elseif ($usuarioDelComentarioBloqueado)
                            No puedes ver este comentario porque el usuario te ha bloqueado.
                        @else
                            <a href="{{ route('perfil.ver', ['nombreUsuario' => $comentario->usuario->usuario]) }}" target="_blank">
                                {{ $comentario->usuario->usuario }}
                            </a>:
                        @endif
                    @else
                        {{ $comentario->usuario->usuario }}:
                    @endif
        
                    @if ($comentario->modificado)
                        <span class="text-muted">(Modificado)</span>
                    @endif
                </h5>
        
                @if (!$usuarioBloqueado && !$usuarioDelComentarioBloqueado)
                    {{ $comentario->contenido }}
                    @auth
                        @if(auth()->user()->id == $comentario->usuario_id)
                            <div class="d-flex align-items-center mt-2">
                                <form action="{{ route('restaurantes.eliminarComentario', ['comentarioId' => $comentario->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar Comentario</button>
                                </form>
        
                                <button class="btn btn-sm btn-warning ml-2" onclick="activarEdicion({{ $comentario->id }})">
                                    <i class="fas fa-edit"></i> Editar Comentario
                                </button>
        
                                <button type="button" class="btn btn-sm btn-primary compartir-facebook ml-2"
                                data-comentario="{{ $comentario->contenido }}"
                                data-usuario="{{ $comentario->usuario->usuario }}">
                                <i class="fab fa-facebook"></i> Compartir en Facebook
                            </button>
                            
                            <button type="button" class="btn btn-sm btn-info compartir-twitter ml-2"
                            data-comentario="{{ $comentario->contenido }}"
                            data-usuario="{{ $comentario->usuario->usuario }}">
                            <i class="fab fa-twitter"></i> Compartir en Twitter
                        </button>
                        
                    </div>
                    <br>
                    <div id="areaEdicion{{ $comentario->id }}" style="display: none;">
                        <textarea class="form-control" id="nuevoContenido{{ $comentario->id }}" rows="3" required>{{ $comentario->contenido }}</textarea>
                        <a class="btn btn-primary mt-2" onclick="guardarEdicion({{ $comentario->id }})">Guardar Cambios</a>
                    </div>
                        @endif
                    @endauth
                @else
                    No puedes ver este comentario.
                @endif
            </div>
        </div>
        @empty
            <p>No hay comentarios aún.</p>
        @endforelse
                </div>
            </div>
        </div>


                @auth
                <div id="agregar-comentario" class="card mt-4">
                    <div class="card-body">
                        <form action="{{ route('restaurantes.comentar', ['restauranteId' => $restaurante->slug]) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="contenido">Agregar Comentario:</label>
                                <textarea class="form-control" id="contenido" name="contenido" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Comentar</button>
                        </form>
                    </div>
                </div>
        
                <!-- Enlaces para compartir en redes sociales -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h4>Compartir restaurante en redes sociales:</h4>
                
                        <div class="social-share-links mt-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=http://sharefood.local/restaurantes/{{ $restaurante->slug }}" target="_blank" class="btn btn-primary">
                                <i class="fab fa-facebook"></i> Compartir en Facebook
                            </a>
                            
                            <a href="https://twitter.com/intent/tweet?url=http://sharefood.local/restaurantes/{{ $restaurante->slug }}&text=¡Echa un vistazo a este restaurante increíble!" target="_blank" class="btn btn-info ml-2">
                                <i class="fab fa-twitter"></i> Compartir en Twitter
                            </a>
                        </div>
                    </div>
                </div>
                    
                @if ($usuarioReserva)
                    @php
                        $fechaReserva = $usuarioReserva->fecha;
                        $haPasadoDeFecha = $usuarioReserva->haPasadoDeFecha();
                    @endphp
        
        <div>
            @if ($haPasadoDeFecha)
                @if ($usuarioHaVotado)
                    @php
                        $puntuacionesUsuario = DB::table('puntuaciones')
                            ->where('usuario_id', auth()->user()->id)
                            ->where('restaurante_id', $restaurante->id)
                            ->get();
                    @endphp
        
                    @if ($puntuacionesUsuario->isNotEmpty())
                        @php
                            $promedioPuntuaciones = $puntuacionesUsuario->avg('puntuacion');
                            $puntuacionRedondeada = round($promedioPuntuaciones);
                        @endphp
        
        
                        <div class="card mt-4">
                            <div class="alert alert-success" role="alert">
                                ¡Ya votaste este restaurante con una puntuación de {{ $puntuacionRedondeada }} estrella{{ $puntuacionRedondeada != 1 ? 's' : '' }}, gracias!
                            </div>
                            <div class="card-body">
                                <h4 class="mb-3">Compartir puntuación del restaurante en redes sociales: </h4>
                                <div class="social-share-links">
                                    <button class="btn btn-primary compartir-facebook"
                                            data-comentario="¡He votado este restaurante en ShareFood con una puntuación de {{ $puntuacionRedondeada }} estrella{{ $puntuacionRedondeada != 1 ? 's' : '' }}! ¡Ven a echar un vistazo!"
                                            data-usuario="{{ auth()->user()->usuario }}">
                                        <i class="fab fa-facebook-f"></i> Compartir en Facebook
                                    </button>
                                    <button class="btn btn-info compartir-twitter"
                                            data-comentario="¡He votado este restaurante en ShareFood con una puntuación de {{ $puntuacionRedondeada }} estrella{{ $puntuacionRedondeada != 1 ? 's' : '' }}! ¡Ven a echar un vistazo!"
                                            data-usuario="{{ auth()->user()->usuario }}">
                                        <i class="fab fa-twitter"></i> Compartir en Twitter
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            Aún no has dado ninguna puntuación a este restaurante.
                        </div>
                    @endif
                @else
                    <h3 class="mb-3">Puntuar Restaurante</h3>
                    <form action="{{ route('restaurantes.puntuar', ['slug' => $restaurante->slug]) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="puntuacion">Puntuación (1-5):</label>
                            <select class="form-control" name="puntuacion" id="puntuacion" required>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Puntuar</button>
                    </form>
                @endif
            @endif
        </div>
        
        
                @endif
                <div id="hacer-reserva" class="card mt-4">
                    <div class="card-body">
                        <a href="{{ route('restaurantes.nuevaReserva', ['slug' => $restaurante->slug]) }}" class="btn btn-success">Hacer Reserva</a>
                    </div>
                </div>
            @else
                <p class="mt-4">Inicia sesión para dejar un comentario o realizar una reserva.</p>
            @endauth
        </div>
        
        <script>
            $(document).ready(function() {

                $('.compartir-comentario').click(function() {
                    var comentario = $(this).data('comentario'); 
                    var usuario = $(this).data('usuario'); 
                    var url = 'http://sharefood.local/restaurantes/{{ $restaurante->slug }}'; 
                    
                    console.log('Comentario:', comentario);
                    console.log('Usuario:', usuario);
        
                    var textoCompartir = encodeURIComponent(usuario + ' ha comentado en sharefood: ' + comentario);
                    console.log('Texto a compartir:', textoCompartir);
        
                    window.open('https://www.facebook.com/sharer/sharer.php?u=' + url + '&quote=' + textoCompartir, 'Compartir en Facebook', 'width=600,height=400');
                });
        
                function compartirEnRedSocial(redSocial, comentario, usuario) {
                    var url = 'http://sharefood.local/restaurantes/{{ $restaurante->slug }}';
                    var textoCompartir = encodeURIComponent(usuario + ' ha comentado en sharefood: ' + comentario);
        
                    console.log('Comentario:', comentario);
                    console.log('Usuario:', usuario);
                    console.log('Texto a compartir:', textoCompartir);
        
                    var shareUrl = '';
                    if (redSocial === 'facebook') {
                        shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + url + '&quote=' + textoCompartir;
                    } else if (redSocial === 'twitter') {
                        shareUrl = 'https://twitter.com/intent/tweet?url=' + url + '&text=' + textoCompartir;
                    }
        
                    console.log('URL de compartir:', shareUrl);
                    window.open(shareUrl, 'Compartir en ' + redSocial, 'width=600,height=400');
                }
        
                $('.compartir-facebook').click(function() {
                    compartirEnRedSocial('facebook', $(this).data('comentario'), $(this).data('usuario'));
                });
        
                $('.compartir-twitter').click(function() {
                    compartirEnRedSocial('twitter', $(this).data('comentario'), $(this).data('usuario'));
                });
            });

            function activarEdicion(comentarioId) {
        $('[id^=areaEdicion]').hide();
        $('#areaEdicion' + comentarioId).show();
    }

    function guardarEdicion(comentarioId) {
    var nuevoContenido = $('#nuevoContenido' + comentarioId).val();

    fetch('{{ route('restaurantes.actualizarComentario') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({
            comentarioId: comentarioId,
            nuevoContenido: nuevoContenido
        })
    })
    .then(response => response.json())
    .then(data => {

        console.log(data.mensaje);


        $('#areaEdicion' + comentarioId).hide();

        $('#comentario' + comentarioId).html(data.nuevoContenido);
        location.reload();
    })
    .catch(error => {
        console.error('Error al actualizar el comentario:', error);
    });
}
        </script>
        @endsection
        