<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 150px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            margin-top: 20px;
            font-size: 1.2em;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.8em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Confirmación de Reserva</h1>
        <p>CineYa - Entradas de Cine</p>
    </div>

    <p><strong>Película:</strong> {{ $movie_title }}</p>
    <p><strong>Fecha:</strong> {{ $session_date }}</p>
    <p><strong>Hora:</strong> {{ $session_time }}</p>
    <p><strong>Nombre:</strong> {{ $name }} {{ $apellidos }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    
    <h2>Detalles de los Asientos</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Fila</th>
                <th>Asiento</th>
                <th>Tipo</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($seats as $seat)
                <tr>
                    <td>{{ $seat['row'] }}</td>
                    <td>{{ $seat['seat_num'] }}</td>
                    <td>{{ ucfirst($seat['type']) }}</td>
                    <td>${{ number_format($seat['precio'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Total: ${{ number_format($total, 2) }}</p>

    <div class="footer">
        <p>Gracias por su compra. Presente este documento en taquilla.</p>
        <p>CineYa - Todos los derechos reservados</p>
    </div>
</body>
</html>