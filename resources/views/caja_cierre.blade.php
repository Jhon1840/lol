<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Cierre de Caja</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .header-section {
            margin-bottom: 20px;
        }

        .totals-section {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-section">
            <h1>Reporte de Cierre de Caja</h1>
            <p><strong>Caja ID:</strong> {{ $caja->id }}</p>
            <p><strong>Estado:</strong> {{ $caja->estado }}</p>
            <p><strong>Total Billetes y Monedas:</strong> ${{ number_format($totalBilletesMonedas, 2) }}</p>
            <p><strong>Observaciones:</strong> {{ $caja->observaciones }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Denominación</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($billetes as $denominacion => $cantidad)
                    <tr>
                        <td>${{ $denominacion }}</td>
                        <td>{{ $cantidad }}</td>
                        <td class="text-right">${{ number_format($denominacion * $cantidad, 2) }}</td>
                    </tr>
                @endforeach
                @foreach ($monedas as $denominacion => $cantidad)
                    <tr>
                        <td>${{ $denominacion }}</td>
                        <td>{{ $cantidad }}</td>
                        <td class="text-right">${{ number_format($denominacion * $cantidad, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals-section">
            <p class="text-right"><strong>Total General:</strong> ${{ number_format($totalBilletesMonedas, 2) }}</p>
        </div>
    </div>
</body>

</html>
