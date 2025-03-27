<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            font-size: 24px;
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
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Hola {{ $name }} {{ $apellidos }}!</h1>
        <p>Tu reserva para <strong>{{ $movieTitle }}</strong> ha sido confirmada con éxito.</p>
        
        <h3>Detalles de la función:</h3>
        <p><strong>Fecha:</strong> {{ $sessionDate }}</p>
        <p><strong>Hora:</strong> {{ $sessionTime }}</p>

        <h3>Detalles de los asientos:</h3>
        <ul>
            @foreach ($seats as $seat)
                <li>
                    <strong>Asiento:</strong> Fila {{ $seat['row'] }}, Número {{ $seat['seat_num'] }} 
                    ({{ ucfirst($seat['type']) }}) - <strong>Precio:</strong> ${{ number_format($seat['precio'], 2) }}
                </li>
            @endforeach
        </ul>

        <p class="total"><strong>Total: ${{ number_format($total, 2) }}</strong></p>

        <p>Adjunto encontrarás tu comprobante de reserva en formato PDF. Por favor, preséntalo en taquilla el día de la función.</p>

        <div class="footer">
            <p>Atentamente,</p>
            <p><strong>CineYa</strong></p>
        </div>
    </div>
</body>
</html>