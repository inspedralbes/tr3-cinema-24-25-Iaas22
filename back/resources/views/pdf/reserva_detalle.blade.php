<!DOCTYPE html>
<html>
<head>
    <title>Detalls de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        h1 {
            font-size: 24px;
            color: #2c3e50;
        }

        p {
            font-size: 16px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            font-size: 16px;
            padding: 8px 0;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
        }
    </style>
</head>
<body>

    <h1>Detalls de la Reserva</h1>

    <p><strong>Nom:</strong> {{ $name }} {{ $apellidos }}</p>
    <p><strong>Sesión ID:</strong> {{ $session_id }}</p>

    <h3>Detalls de les butaques:</h3>
    <ul>
        @foreach ($seats as $seat)
            <li><strong>Asiento:</strong> {{ $seat['seat_id'] }} - <strong>Preu:</strong> ${{ $seat['precio'] }}</li>
        @endforeach
    </ul>

    <p class="total"><strong>Total: ${{ $total }}</strong></p>

    <p>¡Gràcies por elegirnos!</p>

</body>
</html>
