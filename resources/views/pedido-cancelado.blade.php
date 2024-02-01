<!DOCTYPE html>
<html>
<p>Hola {{ $pedido->usuario->usuario }},</p>

@if(isset($justificacion) && !empty($justificacion))
    <p> {{ $justificacion }}</p>
@else
    <p>Desafortunadamente, tu pedido ha sido cancelado. Nos disculpamos por cualquier inconveniente.</p>
    <p>Te informamos que se realizará el reembolso del monto correspondiente a tu pedido.</p>
@endif

<p>Detalles del pedido cancelado:</p>
<ul>
    @foreach($pedido->platos as $plato)
        <li>
            Nombre del plato: {{ $plato->nombre }} - Cantidad: {{ $plato->cantidad }} - Precio: {{ $plato->precio }} €
        </li>
    @endforeach
   <p> Precio total del pedido: {{$pedido->precio_total}} </p> 
</ul>

<p>Gracias por elegir nuestro servicio.</p>

<img src="{{ $message->embed(public_path('/imagenes/imagen_login.png')) }}" alt="Logo de Sharefood">
<p>Saludos,</p>
<p>Equipo Sharefood</p>
</html>
