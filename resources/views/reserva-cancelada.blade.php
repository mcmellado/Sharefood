<!DOCTYPE html>
<html>
<head>
    <title>Reserva Cancelada</title>
</head>
<body>
    <p>Hola {{ $usuario }},</p>

    @if(isset($justificacion) && !empty($justificacion))
        <p> {{ $justificacion }}</p>
    @else
        <p>Lamentamos informarte que tu reserva en el restaurante Comida Mexicana Tradicional ha sido cancelada. Sentimos mucho los inconvenientes que esto pueda causar.</p>
    @endif

    <p>Gracias por elegir nuestro servicio.</p>

    <p>Saludos,</p>
    <p>Equipo Sharefood</p>

    <img src="{{ $message->embed(public_path('/imagenes/imagen_login.png')) }}" alt="Logo de Sharefood">

</body>
</html>
