<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Reserva</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600&display=swap');
        
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 30px;
            color: #444;
            background-color: #f5f7fa;
            line-height: 1.5;
            font-size: 14px;
        }
        
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 30%;
            width: 40%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
        }
        
        h1 {
            font-family: 'Playfair Display', serif;
            color: #2d3748;
            font-size: 1.8em;
            margin-bottom: 8px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        
        .subtitle {
            font-size: 0.95em;
            color: #718096;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 400;
        }
        
        .movie-info {
            background: #f8fafc;
            padding: 18px;
            border-radius: 6px;
            margin-bottom: 25px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
        }
        
        .info-item {
            flex: 1 1 180px;
            margin: 8px 0;
        }
        
        .info-item strong {
            display: block;
            color: #718096;
            font-size: 0.85em;
            margin-bottom: 4px;
            font-weight: 500;
        }
        
        .info-item span {
            font-size: 1em;
            color: #2d3748;
            font-weight: 500;
        }
        
        h2 {
            font-family: 'Playfair Display', serif;
            color: #2d3748;
            font-size: 1.3em;
            margin: 25px 0 15px;
            position: relative;
            padding-bottom: 8px;
            font-weight: 500;
        }
        
        h2::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: #667eea;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 25px;
            font-size: 0.9em;
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.03);
        }
        
        .table th {
            background: #4a5568;
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.8em;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            text-align: left;
        }
        
        .table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f4f8;
            color: #4a5568;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .table tr:hover td {
            background: #f8fafc;
        }
        
        .total {
            text-align: right;
            font-size: 1.2em;
            font-weight: 600;
            color: #2d3748;
            margin: 25px 0;
            padding: 12px;
            background: linear-gradient(to right, transparent, #f8fafc);
            border-radius: 4px;
        }
        
        .total span {
            color: #764ba2;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.8em;
            color: #a0aec0;
            border-top: 1px solid #f0f4f8;
            padding-top: 15px;
        }
        
        .footer p:first-child {
            margin-bottom: 8px;
            font-weight: 500;
            color: #718096;
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
        
        <div class="footer">
            <p>Gracias por su compra. Presente este documento en taquilla.</p>
            <p>CineYa - Todos los derechos reservados © {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>