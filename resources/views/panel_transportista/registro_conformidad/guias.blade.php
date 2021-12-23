@extends('layouts.app_transportista')
@section('content')
    <div class="container-fluid">

        <div class="card border-0 shadow-1  my-4">
            <div class="w-100 px-4 py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-muted fs-5">Registro conformidad</h6>
            </div>
            <div class="card-body my-5">
                <div class="row mb-5">
                    <form method="get" action="{{ route('registro_conformidad_index.transportista') }}">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fechainicio">Fecha inicio</label>
                                        <input type="date" id="fechainicioidd" @if ($filtro)value="{{ $inicio }}"@endif required
                                            name="inicio" class="form-control-sm  border_controls_filter">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fechainicio">Fecha fin</label>
                                        <input type="date" id="fechafinid" required @if ($filtro)value="{{ $fin }}"@endif name="fin"
                                            class="form-control-sm  border_controls_filter">
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-control-sm w-100 border_controls_filter">
                                            <option value="" selected>Todas</option>
                                            <option value="OK" @if ($filtro) @if ($estado == 'OK') selected @endif @endif>OK</option>
                                            <option value="NO OK" @if ($filtro) @if ($estado == 'NO OK') selected @endif @endif>NO OK</option>
                                            <option value="PENDIENTE" @if ($filtro) @if ($estado == 'PENDIENTE') selected @endif @endif>PENDIENTE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <br>
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        id="btnconsultar">Consultar</button>
                                </div>
                                <div class="col-md-2" id="buscandoarchivos" style="display: none">
                                    <br>
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatables" style="font-size:.7em !important" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:30px">Codigo OT</th>
                                <th>Guias de remisión</th>
                                <th style="width:100px">transportista</th>
                                <th>Fecha ingreso</th>
                                <th>Fecha llegada</th>
                                <th>Llegada cliente</th>
                                <th>Descarga cliente</th>
                                <th>Sede</th>
                                <th>Destino</th>
                                <th>Estado</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registros as $registro)
                                <tr>
                                    <td style="width:30px"> <strong>{{ $registro->orden_de_transporte }}</strong></td>
                                    <td>{{ $registro->guias_de_remision }}</td>
                                    <td style="width:100px">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <strong> {{ $registro->sap_transportista }}</strong>
                                            </div>
                                            <div class="col-md-12">
                                                {{ $registro->transportista }}
                                            </div>
                                        </div>
                                    </td>
                                    <td style="max-width:200px;overflow:hidden">
                                        {{ date('Y-m-d H:i:s', strtotime($registro->hora_de_ingreso)) }}</td>
                                    <td>{{ date('Y-m-d H:i:s', strtotime($registro->hora_de_salida)) }}</td>
                                    <td style="max-width:200px;overflow:hidden">
                                        {{ date('Y-m-d H:i:s', strtotime($registro->hora_de_ingreso)) }}</td>
                                    <td>{{ date('Y-m-d H:i:s', strtotime($registro->hora_de_salida)) }}</td>
                                    <td>{{ $registro->sede }}</td>
                                    <td>{{ $registro->destino }}</td>
                                    <td>{{ $registro->estado_tracking }}</td>

                                    <td style="width:30px;" class="text-center"> &nbsp;&nbsp;
                                        <div class="dropdown">
                                            <i class="fa fa-ellipsis-v" type="button" id="dropdownMenuButton1"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            </i>
                                            <ul class="dropdown-menu" style="font-size:.9em"
                                                aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item"
                                                        href="/panel/registro_conformidad/{{ $registro->id }}/archivos"><i
                                                            class="fa fa-file"></i> Documentos</a></li>
                                                <li><a class="dropdown-item"
                                                        href="/panel/registro_conformidad/{{ $registro->id }}/observaciones"><i
                                                            class="fa fa-edit"></i> Observaciones</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>




                </div>
            </div>
        </div>


    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#btnconsultar").on("click", function() {
                var inicio = $("#fechainicioidd").val();
                var fin = $("#fechafinid").val();
                if (inicio && fin) {
                    $("#buscandoarchivos").css("display", "block");
                }
            });
            if(!$("#fechainicioidd").val() && !$("#fechafinid").val()){
                $("#fechafinid").val(moment().format('YYYY-MM-DD'));
                $("#fechainicioidd").val(moment().subtract(1,"month").format('YYYY-MM-DD'));
            }
        })
    </script>
@endsection
