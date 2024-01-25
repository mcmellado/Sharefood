<?

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TuNotificacion extends Notification
{
    public function toMail($notifiable)
    {
        $restaurante = $notifiable; 

        $reservas = $restaurante->reservas()->count();
        $pedidos = $restaurante->pedidos()->count();

        $message = (new MailMessage)
            ->line('Tu mensaje principal aquí.');

        if ($reservas > 0) {
            $message->action('Ver Reservas', route('restaurantes.verReservas', ['slug' => $restaurante->slug]));
        }

        if ($pedidos > 0) {
            $message->action('Ver Pedidos', route('restaurantes.ver_pedidos', ['slug' => $restaurante->slug]));
        }

        return $message;
    }
}