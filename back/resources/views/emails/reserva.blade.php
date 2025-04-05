<!DOCTYPE html>
<html>
<head>
    <title>Confirmació d' Entrada</title>
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
        <p> La terva reserva per <strong>{{ $movieTitle }}</strong> ha sigut confirmada amb éxit!.</p>
        
        <h3>Detalls de la funció:</h3>
        <p><strong>Data:</strong> {{ $sessionDate }}</p>
        <p><strong>Hora:</strong> {{ $sessionTime }}</p>

        <h3>Detalles de les butaques:</h3>
        <ul>
            @foreach ($seats as $seat)
                <li>
                    <strong>Butaca:</strong> Fila {{ $seat['row'] }}, Numero {{ $seat['seat_num'] }} 
                    ({{ ucfirst($seat['type']) }}) - <strong>Preu:</strong> ${{ number_format($seat['precio'], 2) }}
                </li>
            @endforeach
        </ul>

        <p class="total"><strong>Total: ${{ number_format($total, 2) }}</strong></p>

        <p>Trobaràs el teu comprovant adjunt, preseta-ho a la taquilla.</p>

        <div class="footer">
            <p>Atentament,</p>
            <p><strong>CineYa</strong></p>
        </div>
    </div>
</body>
</html>