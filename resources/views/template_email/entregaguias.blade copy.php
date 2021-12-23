<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Estimados </p>
    <table>
        <thead style="background-color:rgb(30, 30, 163); color:white; padding:5px 0px;">
            <tr>
                <th>Guia de remision remitente GRR</th>
                <th>Hora de salida</th>
                <th>Estado tracking</th>
                <th>Hora de llegada cliente</th>
                <th>Orden de transporte</th>
                <th>Cliente</th>
                <th>Destino</th>
                <th>Placa tracto</th>
                <th>Transportista</th>
                <th>Chofer</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $registro->guias_de_remision }}</td>
                <td>{{ date('d/m/Y H:i:s', strtotime($registro->hora_de_salida)) }}</td>
                <td>{{ $registro->estado_tracking }}</td>
                <td>{{ strtotime($registro->hora_de_llegada_cliente) ? date('d/m/Y H:i:s', strtotime($registro->hora_de_llegada_cliente)) : 'Pendiente' }}
                </td>
                <td>{{ $registro->orden_de_transporte }}</td>
                <td>{{ $registro->cliente }}</td>
                <td>{{ $registro->destino }}</td>
                <td>{{ $registro->placa_tracto }}</td>
                <td>{{ $registro->transportista }}</td>
                <td>{{ $registro->chofer }}</td>
            </tr>
        </tbody>
    </table>
    <p>
        Adjuntamos las guias de cobranza y/o ventas
        <br><br>
        <small>
            {{ $registro->transportista }}
        </small>
    </p>

</body>

</html>
