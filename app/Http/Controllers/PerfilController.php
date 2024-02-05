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
    use App\Models\Bloqueado;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Redirect;
    use App\Models\Pedido;
    use Illuminate\Validation\Rule;



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
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'telefono' => 'nullable|string|max:255',
                'biografia' => 'nullable|string',
            ], [
                'email.unique' => 'Este correo electrónico ya está en uso por otro usuario.',
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

    $puedeEnviarSolicitud = $this->puedeEnviarSolicitud(Auth::user()->id, $usuario->id);

    return view('perfil.mostrar', compact('usuario', 'puedeEnviarSolicitud'));
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
                ->where('estado', 'pendiente') 
                ->get();
        
            return $solicitudesPendientes;
        }
        

        public function mostrarSocial($nombreUsuario)
    {
        $usuario = User::where('usuario', $nombreUsuario)->firstOrFail();
        
        $solicitudesPendientes = $this->obtenerSolicitudesPendientes();

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

        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('perfil.social', ['nombreUsuario' => Auth::user()->usuario])
                ->with('warning', 'La solicitud de amistad ya ha sido aceptada o rechazada.');
        }

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


    public function eliminarAmigo($amigoId)
{
    $usuario = Auth::user();

    $bloqueadoPorUsuario = DB::table('bloqueados')
        ->where('usuario_id', $usuario->id)
        ->where('usuario_bloqueado_id', $amigoId)
        ->exists();

    $amigo = User::find($amigoId);
    $bloqueadoPorAmigo = DB::table('bloqueados')
        ->where('usuario_id', $amigoId)
        ->where('usuario_bloqueado_id', $usuario->id)
        ->exists();

    if ($bloqueadoPorUsuario || $bloqueadoPorAmigo) {
        return redirect()->back()->with('warning', 'No puedes eliminar al amigo debido al bloqueo.');
    }

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

public function bloquearAmigo($amigoId)
{
    $usuario = Auth::user();

    $bloqueadoPorUsuario = DB::table('bloqueados')
        ->where('usuario_id', $usuario->id)
        ->where('usuario_bloqueado_id', $amigoId)
        ->exists();

    $amigo = User::find($amigoId);
    $bloqueadoPorAmigo = DB::table('bloqueados')
        ->where('usuario_id', $amigoId)
        ->where('usuario_bloqueado_id', $usuario->id)
        ->exists();

    if ($bloqueadoPorUsuario || $bloqueadoPorAmigo) {
        return redirect()->back()->with('warning', 'Ya está bloqueado.');
    }

    $amigoExistente = DB::table('amigos_user')
        ->whereIn('usuario_id', [$usuario->id, $amigoId])
        ->whereIn('amigo_id', [$usuario->id, $amigoId])
        ->count();

    if ($amigoExistente) {
        DB::table('amigos_user')
            ->whereIn('usuario_id', [$usuario->id, $amigoId])
            ->whereIn('amigo_id', [$usuario->id, $amigoId])
            ->delete();
    }

    DB::table('bloqueados')->insert([
        'usuario_id' => $usuario->id,
        'usuario_bloqueado_id' => $amigoId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('contactos')
        ->where(function ($query) use ($usuario, $amigoId) {
            $query->where('usuario_id', $usuario->id)
                ->where('otro_usuario_id', $amigoId);
        })
        ->orWhere(function ($query) use ($usuario, $amigoId) {
            $query->where('usuario_id', $amigoId)
                ->where('otro_usuario_id', $usuario->id);
        })
        ->update(['estado' => 'bloqueada']);

    return redirect()->back()->with('success', 'Amigo bloqueado correctamente.');
}

public function rechazarSolicitud($id)
{
    $solicitud = Contacto::findOrFail($id);

    if ($solicitud->estado !== 'pendiente') {
        return redirect()->route('perfil.social', ['nombreUsuario' => Auth::user()->usuario])
            ->with('warning', 'La solicitud de amistad ya ha sido aceptada o rechazada.');
    }

    $solicitud->estado = 'rechazada';
    $solicitud->save();

    return redirect()->route('perfil.social', ['nombreUsuario' => Auth::user()->usuario])
        ->with('success', 'Solicitud de amistad rechazada correctamente.');
}

public function verBloqueos()
{
    $usuario = Auth::user();
    $bloqueos = DB::table('bloqueados')
                    ->where('usuario_id', $usuario->id)
                    ->get();

    return view('ver-bloqueos', compact('usuario', 'bloqueos'));
}

public function desbloquearUsuario($usuarioId)
{
    $usuario = Auth::user();
    $bloqueos = DB::table('bloqueados')
        ->where('usuario_id', $usuario->id)
        ->get();

    $bloqueoExistente = DB::table('bloqueados')
        ->where('usuario_id', $usuario->id)
        ->where('usuario_bloqueado_id', $usuarioId)
        ->first();

    if ($bloqueoExistente) {
        DB::table('bloqueados')
            ->where('usuario_id', $usuario->id)
            ->where('usuario_bloqueado_id', $usuarioId)
            ->delete();

        DB::table('contactos')
            ->where('usuario_id', $usuario->id)
            ->where('otro_usuario_id', $usuarioId)
            ->delete();

            return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => true]);
        
    }

}

public function puedeEnviarSolicitud($usuarioId, $otroUsuarioId)
{

    $bloqueadoPorOtro = Bloqueado::where('usuario_id', $otroUsuarioId)
        ->where('usuario_bloqueado_id', $usuarioId)
        ->exists();

    $bloqueadoPorUsuario = Bloqueado::where('usuario_id', $usuarioId)
        ->where('usuario_bloqueado_id', $otroUsuarioId)
        ->exists();

    return !($bloqueadoPorUsuario || $bloqueadoPorOtro);
}

public function verPedidos()
{
    $usuarioId = Auth::id();
    $pedidos = Pedido::where('usuario_id', $usuarioId)->get();

    return view('ver-pedidos', compact('pedidos'));
}

 }