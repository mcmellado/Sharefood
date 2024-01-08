<?php

    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
    use App\Models\Reserva;
    use App\Models\Contacto;
    use App\Models\Mensaje;
    use App\Models\Restaurante;
    use Illuminate\Support\Facades\DB;
    use App\Models\User;



    class PerfilController extends Controller
    {
        
        public function show($nombreUsuario)
        {
            $usuario = Auth::user();


            return view('perfil', compact('usuario'));
        }

        public function mostrarFormularioModificar()
        {
            $usuario = Auth::user();
            return view('perfil-modificar', compact('usuario'));
        }

        public function modificarPerfil(Request $request)
        {
            $user = User::find(Auth::id());
        
            $request->validate([
                'email' => 'required|email|unique:users,email,' . $user->id,
                'telefono' => 'nullable|string|max:255',
                'biografia' => 'nullable|string',
            ]);
        
            $user->email = $request->email;
            $user->telefono = $request->telefono;
            $user->biografia = $request->biografia;
        
            if ($request->hasFile('imagen')) {
                if ($user->imagen) {
                    Storage::delete($user->imagen);
                }
        
                $rutaImagen = $request->file('imagen')->store('perfiles', 'public');
                $user->imagen = $rutaImagen;
        
                info('Ruta de la imagen guardada: ' . $rutaImagen);
            }
        
            $user->save();
        
            return redirect()->route('perfil', ['nombreUsuario' => $user->usuario])->withSuccess('Perfil modificado correctamente');
        }

        public function verReservas($nombreUsuario)
        {

            $usuario = Auth::user();

            $reservas = $usuario->reservas;

            return view('ver-reservas', compact('usuario', 'reservas'));
        }

        public function misRestaurantes()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            abort(404);
        }

        $restaurantes = $usuario->misRestaurantes;

        return view('locales', compact('usuario', 'restaurantes'));
    }

    public function ver($nombreUsuario)
    {
        $usuario = User::where('usuario', $nombreUsuario)->firstOrFail();
        return view('perfil', compact('usuario'));
    }

    public function mostrar($nombreUsuario)
        {
            $usuario = User::where('usuario', $nombreUsuario)->firstOrFail();
            return view('perfil.mostrar', ['usuario' => $usuario]);
        }

        public function enviarSolicitudAmistad($nombreUsuario)
        {
            $usuario = Auth::user();
            $otroUsuario = User::where('usuario', $nombreUsuario)->firstOrFail();
        
            $contactoExistente = Contacto::where(function ($query) use ($usuario, $otroUsuario) {
                $query->where('usuario_id', $usuario->id)
                    ->where('otro_usuario_id', $otroUsuario->id)
                    ->where('estado', 'pendiente');
            })->orWhere(function ($query) use ($usuario, $otroUsuario) {
                $query->where('usuario_id', $otroUsuario->id)
                    ->where('otro_usuario_id', $usuario->id)
                    ->where('estado', 'pendiente');
            })->first();
        
            if ($contactoExistente) {
                return redirect()->route('perfil.ver', ['nombreUsuario' => $otroUsuario->usuario])->with('warning', 'Ya existe una solicitud de amistad o son amigos.');
            }
        
            $contacto = new Contacto([
                'usuario_id' => $usuario->id,
                'otro_usuario_id' => $otroUsuario->id,
                'mensaje' => 'Hola, ¿te gustaría ser mi amigo?',
                'estado' => 'pendiente',
            ]);
        
            $contacto->save();
        
            return redirect()->route('perfil.ver', ['nombreUsuario' => $otroUsuario->usuario])->with('success', 'Solicitud de amistad enviada correctamente.');
        }
        

        public function obtenerSolicitudesPendientes()
        {
            $solicitudesPendientes = Contacto::where('otro_usuario_id', auth()->user()->id)
                ->where('estado', 'pendiente') // Cambiado de 'aceptada' a 'estado'
                ->get();
        
            return $solicitudesPendientes;
        }
        

        public function mostrarSocial($nombreUsuario)
    {
        $usuario = User::where('usuario', $nombreUsuario)->firstOrFail();
        
        $solicitudesPendientes = $this->obtenerSolicitudesPendientes();

        // Obtener la lista de amigos del usuario actual
        $amigos = Auth::user()->amigos;

        return view('perfil-social', [
            'usuario' => $usuario,
            'amigos' => $amigos,
            'solicitudesPendientes' => $solicitudesPendientes,
        ]);
    }

    public function aceptarSolicitud($id)
    {
        $solicitud = Contacto::findOrFail($id);

        // Verificar si la solicitud está pendiente
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('perfil.social', ['nombreUsuario' => Auth::user()->usuario])
                ->with('warning', 'La solicitud de amistad ya ha sido aceptada o rechazada.');
        }

        // Marcar la solicitud como aceptada
        $solicitud->estado = 'aceptada';
        $solicitud->save();

        DB::table('amigos_user')->insert([
            ['usuario_id' => $solicitud->usuario_id, 'amigo_id' => $solicitud->otro_usuario_id],
            ['usuario_id' => $solicitud->otro_usuario_id, 'amigo_id' => $solicitud->usuario_id],
        ]);

        return redirect()->route('perfil.social', ['nombreUsuario' => Auth::user()->usuario])
            ->with('success', 'Solicitud de amistad aceptada correctamente.');
    }

    public function mostrarMensajes($amigoId)
    {
        $amigo = User::find($amigoId);
        $sonAmigos = Auth::user()->amigos->contains($amigo);

        if (!$sonAmigos) {
            $solicitudAceptada = Contacto::where(function ($query) use ($amigoId) {
                $query->where('usuario_id', Auth::id())
                    ->where('otro_usuario_id', $amigoId)
                    ->where('estado', 'aceptada');
            })->orWhere(function ($query) use ($amigoId) {
                $query->where('usuario_id', $amigoId)
                    ->where('otro_usuario_id', Auth::id())
                    ->where('estado', 'aceptada');
            })->exists();

            if (!$solicitudAceptada) {
                return redirect()->back()->with('warning', 'No puedes ver los mensajes con alguien que no es tu amigo.');
            }
        }

        $mensajes = Contacto::where(function ($query) use ($amigoId) {
            $query->where('usuario_id', Auth::id())
                ->where('otro_usuario_id', $amigoId);
        })->orWhere(function ($query) use ($amigoId) {
            $query->where('usuario_id', $amigoId)
                ->where('otro_usuario_id', Auth::id());
        })->where('estado', 'aceptada')->orderBy('created_at', 'asc')->get();

        return view('mensajes', ['amigo' => $amigo, 'mensajes' => $mensajes]);
    }

    public function enviarMensaje(Request $request, $amigoId)
    {
        $request->validate([
            'mensaje' => 'required|string',
        ]);

        Contacto::create([
            'usuario_id' => Auth::id(),
            'otro_usuario_id' => $amigoId,
            'mensaje' => $request->mensaje,
            'estado' => 'aceptada',
        ]);

        return redirect()->back()->with('success', 'Mensaje enviado correctamente.');
    }

    public function eliminarAmigo($amigoId)
{
    $usuario = Auth::user();

    $amigoExistente = DB::table('amigos_user')
        ->whereIn('usuario_id', [$usuario->id, $amigoId])
        ->whereIn('amigo_id', [$usuario->id, $amigoId])
        ->count();

    if ($amigoExistente) {
        $this->eliminarMensajes($usuario->id, $amigoId);
        DB::table('contactos')
            ->where(function ($query) use ($usuario, $amigoId) {
                $query->where('usuario_id', $usuario->id)
                    ->where('otro_usuario_id', $amigoId);
            })
            ->orWhere(function ($query) use ($usuario, $amigoId) {
                $query->where('usuario_id', $amigoId)
                    ->where('otro_usuario_id', $usuario->id);
            })
            ->update(['estado' => 'pendiente']);

        DB::table('amigos_user')
            ->whereIn('usuario_id', [$usuario->id, $amigoId])
            ->whereIn('amigo_id', [$usuario->id, $amigoId])
            ->delete();

        return redirect()->back()->with('success', 'Amigo eliminado correctamente.');
    } else {
        return redirect()->back()->with('error', 'No se pudo eliminar al amigo. La relación no existe.');
    }
}

    private function eliminarMensajes($usuarioId, $amigoId)
    {
        DB::table('contactos')
            ->where(function ($query) use ($usuarioId, $amigoId) {
                $query->where('usuario_id', $usuarioId)
                    ->where('otro_usuario_id', $amigoId);
            })
            ->orWhere(function ($query) use ($usuarioId, $amigoId) {
                $query->where('usuario_id', $amigoId)
                    ->where('otro_usuario_id', $usuarioId);
            })
            ->delete();
    }

    }
