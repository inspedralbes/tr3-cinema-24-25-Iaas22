<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Reserva</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Playfair+Display:wght@400;700&display=swap');
        
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 40px;
            color: #333;
            background-color: #f9f9f9;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45aaf2);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 20px;
        }
        
        .header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #4ecdc4, transparent);
        }
        
        h1 {
            font-family: 'Playfair Display', serif;
            color: #2c3e50;
            font-size: 2.2em;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .subtitle {
            font-size: 1.1em;
            color: #7f8c8d;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 300;
        }
        
        .movie-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        
        .info-item {
            flex: 1 1 200px;
            margin: 10px 0;
        }
        
        .info-item strong {
            display: block;
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 5px;
        }
        
        .info-item span {
            font-size: 1.1em;
            color: #2c3e50;
            font-weight: 500;
        }
        
        h2 {
            font-family: 'Playfair Display', serif;
            color: #2c3e50;
            font-size: 1.5em;
            margin: 30px 0 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        h2::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: #4ecdc4;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0 30px;
            font-size: 0.95em;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .table th {
            background: #2c3e50;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
            padding: 12px 15px;
            text-align: left;
        }
        
        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #555;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .table tr:hover td {
            background: #f8f9fa;
        }
        
        .total {
            text-align: right;
            font-size: 1.3em;
            font-weight: 700;
            color: #2c3e50;
            margin: 30px 0;
            padding: 15px;
            background: linear-gradient(to right, transparent, #f8f9fa);
            border-radius: 5px;
        }
        
        .total span {
            color: #ff6b6b;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.85em;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        
        .footer p:first-child {
            margin-bottom: 10px;
            font-weight: 600;
            color: #7f8c8d;
        }
        
        .qr-code {
            text-align: center;
            margin: 30px 0;
        }
        
        .qr-code img {
            width: 120px;
            height: 120px;
            border: 1px solid #eee;
            padding: 10px;
            background: white;
        }
        
        .qr-code p {
            font-size: 0.8em;
            color: #95a5a6;
            margin-top: 5px;
        }
        
        @media print {
            body {
                background: none;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                padding: 20px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Confirmación de Reserva</h1>
            <p class="subtitle">CineYa - Entradas de Cine</p>
        </div>

        <div class="movie-info">
            <div class="info-item">
                <strong>Película</strong>
                <span>{{ $movie_title }}</span>
            </div>
            <div class="info-item">
                <strong>Fecha</strong>
                <span>{{ $session_date }}</span>
            </div>
            <div class="info-item">
                <strong>Hora</strong>
                <span>{{ $session_time }}</span>
            </div>
            <div class="info-item">
                <strong>Cliente</strong>
                <span>{{ $name }} {{ $apellidos }}</span>
            </div>
            <div class="info-item">
                <strong>Email</strong>
                <span>{{ $email }}</span>
            </div>
        </div>
        
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

        <div class="total">
            Total: <span>${{ number_format($total, 2) }}</span>
        </div>
        
        <div class="qr-code no-print">
            <!-- Espacio para código QR (puedes generarlo dinámicamente) -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode('Reserva para ' . $name . ' - ' . $movie_title) }}" alt="Código QR">
            <p>Presente este código en taquilla</p>
        </div>

        <div class="footer">
            <p>Gracias por su compra. Presente este documento en taquilla.</p>
            <p>CineYa - Todos los derechos reservados © {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>