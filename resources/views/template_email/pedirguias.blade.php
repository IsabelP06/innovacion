<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Estimado transportista : {{ $registro->transportista }}</p>
    <table>
        <thead style="background-color:rgb(30, 30, 163); color:white; padding:5px 0px;">
            <tr>
                <th>Guia de remisión remitente GRR</th>
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
                <td>
                    @foreach($guiaspendientes as $gp)
                     {{ $gp }}<br> 
                    @endforeach
                </td>
                <td>{{ date('d/m/Y H:i:s', strtotime($registro->hora_de_salida)) }}</td>
                <td>{{ $registro->estado_tracking }}</td>
                <td>{{ strtotime($registro->hora_de_llegada_cliente) ? date('d/m/Y H:i:s', strtotime($registro->hora_de_llegada_cliente)) : 'Pendiente' }}</td>
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
        Esperamos su respuesta en las proximas 24 horas, caso contrario se considerara una amonestacion<br>
        escrita, que suma como falta dentro de su evaluación como transportista.<br><br>
        Considerar que cada 3 faltas, se suspende de la emprea de transportes 15 dias de toda actividad con<br>
        SIDERPERU.<br>
        <br><br>

        Considerar las GRR deber tener el sello y firma de la recepcion del material.<br><br><br>

        <small>Por favor, no responda este correo. Cualquier duda o consulta, favor comunicarse con Jimy Alva <br>
            Movil 94 37 477199 / Freddy Loayza -Movil 943529378</small>
    </p>

</body>

</html>
