<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Restaurante;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Str;


class PedidoController extends Controller
{
    public function realizarPedido(Request $request)
    {
        $restauranteId = $request->input('restaurante_id');
        $productos = $request->input('productos');
        $precioTotalPedido = 0;

        $pedido = new Pedido();
        $pedido->usuario_id = auth()->id();
        $pedido->restaurante_id = $restauranteId;
        $pedido->estado = 'pendiente';
        $pedido->save();

        $productoIds = [];
        foreach ($productos as $productoId => $cantidad) {
            $producto = Producto::find($productoId);

            if ($producto && $producto->restaurante_id == $restauranteId) {
                $precioTotalProducto = $producto->precio * $cantidad;
                $precioTotalPedido += $precioTotalProducto;

                $productoIds[] = $productoId;
            }
        }

        $pedido->precio_total = $precioTotalPedido;
        $pedido->save();

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur', 
                        'product_data' => [
                            'name' => 'Pedido', 
                        ],
                        'unit_amount' => $precioTotalPedido * 100, 
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['restauranteId' => $restauranteId]),
            'cancel_url' => route('stripe.cancel', ['restauranteId' => $restauranteId]),
        ]);

        return redirect()->away($session->url);
    }

    public function stripeSuccess(Request $request)
{
    $restauranteId = $request->input('restauranteId');
    $pedido = Pedido::where('restaurante_id', $restauranteId)
        ->where('usuario_id', auth()->id())
        ->latest()
        ->first();

    return redirect()->route('restaurante.mostrar_carta', ['id' => $restauranteId])->with('success_message', '¡Pedido realizado con éxito, tu pedido llegará pronto a casa!');
}

public function stripeCancel(Request $request)
{
    $restauranteId = $request->input('restauranteId');
    $pedido = Pedido::where('restaurante_id', $restauranteId)
        ->where('usuario_id', auth()->id())
        ->latest()
        ->first();

    return redirect()->route('restaurante.mostrar_carta', ['id' => $restauranteId])->with('error_message', '¡El pago ha sido cancelado!');
}

public function getRestauranteSlug($restauranteId)
{
    $restaurante = Restaurante::find($restauranteId);

    if ($restaurante) {
        return $restaurante->slug; 
    }

    return null;
}

}
