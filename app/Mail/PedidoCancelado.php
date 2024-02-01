<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pedido;

class PedidoCancelado extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;
    public $justificacion;

    public function __construct(Pedido $pedido, $justificacion)
    {
        $this->pedido = $pedido;
        $this->justificacion = $justificacion;
    }

    public function build()
    {
        return $this->view('pedido-cancelado')
                    ->subject('Pedido Cancelado');
    }
}
