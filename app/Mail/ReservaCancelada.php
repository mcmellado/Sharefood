<?php

// app/Mail/ReservaCancelada.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaCancelada extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $nombreRestaurante;
    public $justificacion;

    /**
     * Create a new message instance.
     *
     * @param string $usuarioNombre El nombre del usuario afectado
     * @param string $nombreRestaurante El nombre del restaurante
     * @param string
     */
    public function __construct($usuarioNombre, $nombreRestaurante, $justificacion)
    {
        $this->usuario = $usuarioNombre;
        $this->nombreRestaurante = $nombreRestaurante;
        $this->justificacion = $justificacion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('reserva-cancelada')
                    ->subject('Reserva Cancelada')
                    ->with([
                        'usuario' => $this->usuario,
                        'nombreRestaurante' => $this->nombreRestaurante,
                        'justificacion' => $this->justificacion
                    ]);
    }
}
