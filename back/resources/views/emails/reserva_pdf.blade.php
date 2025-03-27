<!DOCTYPE html>
<html>
<head>
    <title>Detalles de la Reserva</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; font-size: 18px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Comprobante de Reserva</h1>
        <p>CineYa - {{ date('d/m/Y') }}</p>
    </div>
    
    <div class="info">
        <p><strong>Cliente:</strong> {{ $name }} {{ $apellidos }}</p>
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Película:</strong> {{ $movie_title }}</p>
        <p><strong>Fecha:</strong> {{ $session_date }}</p>
        <p><strong>Hora:</strong> {{ $session_time }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Butaca</th>
                <th>Fila</th>
                <th>Número</th>
                <th>Tipo</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($seats as $seat)
            <tr>
                <td>{{ $seat['seat_id'] }}</td>
                <td>{{ $seat['row'] }}</td>
                <td>{{ $seat['seat_num'] }}</td>
                <td>{{ ucfirst($seat['type']) }}</td>
                <td>${{ $seat['precio'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="total">
        <p>Total: ${{ $total }}</p>
    </div>
    
    <div style="margin-top: 50px; text-align: center; font-size: 12px;">
        <p>Gracias por su compra. Presente este comprobante al ingresar al cine.</p>
    </div>
</body>
</html>