<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Reserva</title>
</head>
<body>
    <h1>Hola {{ $name }} {{ $apellidos }},</h1>
    <p>Tu reserva ha sido confirmada con éxito. Aquí están los detalles:</p>

    <h3>Detalles de la reserva:</h3>
    <ul>
        @foreach ($seats as $seat)
            <li>Asiento: {{ $seat['seat_id'] }} - Precio: ${{ $seat['precio'] }}</li>
        @endforeach
    </ul>

    <p><strong>Total: ${{ $total }}</strong></p>
    <p>Sesión ID: {{ $session_id }}</p>

    <p>¡Gracias por elegirnos!</p>
    <p>Atentamente,</p>
    <p>CineYa</p>
</body>
</html>
