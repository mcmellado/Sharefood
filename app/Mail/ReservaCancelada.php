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

    /**
     * Create a new message instance.
     *
     * @param string $usuarioNombre El nombre del usuario afectado
     * @param string $nombreRestaurante El nombre del restaurante
     */
    public function __construct($usuarioNombre, $nombreRestaurante)
    {
        $this->usuario = $usuarioNombre;
        $this->nombreRestaurante = $nombreRestaurante;
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
                    ]);
    }
}
