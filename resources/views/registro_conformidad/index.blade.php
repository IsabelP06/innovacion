@extends('layouts.app')
@section('content')
<style>
    .guias_pendientes{
        list-style: none;
        padding: .2em;
       
    }
    .guias_pendientes li{
        padding: .2em;
        border:0;
        background-color: #fff;
        filter: saturate(2);
    }
    .guias_pendientes li:hover{
        background-color: #f5f5f5;
    }
</style>
    <div class="container-fluid">

        <div class="card border-0 shadow-1  my-4">
            <div class="w-100 px-4 py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-muted fs-5">Registro conformidad</h6>
                <a href="{{ route('registro_conformidad.create') }}" class="btn btn-sm  btn-primary">Agregar</a>
            </div>
            <div class="card-body">
                <div class="row mb-5">
                    <form method="get" id="formfilter">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fechainicio">Fecha inicio</label>
                                        <input type="date" id="fechainicioid"  required
                                            name="inicio" class="form-control-sm  border_controls_filter">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fechainicio">Fecha fin</label>
                                        <input type="date" id="fechafinid" required  name="fin"
                                            class="form-control-sm  border_controls_filter">
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <select name="estado" id="estado" class="form-control-sm w-100 border_controls_filter">
                                            <option value="" selected>Todas</option>
                                            <option value="OK" >OK</option>
                                            <option value="NO OK">NO OK</option>
                                            <option value="PENDIENTE" >PENDIENTE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fechainicio">Sede</label>
                                        <select name="sede"  class="form-control-sm w-100 border_controls_filter" id="sede">
                                            <option value="" selected>Todas</option>
                                            @foreach ($sedes as $sede)
                                                <option value="{{ $sede->nombre }}">
                                                    {{ $sede->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <br>
                                    <button type="submit" id="btnconsultar" class="btn btn-sm btn-primary">Consultar <i
                                            class="fa fa-search"></i></button>
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
                    <table class="table table-bordered" id="datatableregistros" style="font-size:.7em !important" width="100%"
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
                          
                        </tbody>
                    </table>
   

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modaldetalle" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalles de
                            registro</h5>
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modaldetallecontent">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cerrar</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalNotificacion" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <form action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Correo Electronico</h5>
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="w-100" id="loadingsms"
                            style="display: none">


                            <div class="d-flex w-100 my-2 justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-100">
                            <h5 class="mx-2 text-muted">Guias sin entregar</h5>
                                   
                            <ul class="guias_pendientes" id="sendNotificacionRegistro"></ul>
                        </div>
                        <p class="mx-2"><i>¿Estas segur@ de continuar con el envio del correo de petición de estas guias de remisión?</i></p>
                    </div>
                    <div class="modal-footer" id="footermodalnotification">
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="modal fade" id="modaldocumentos" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <form action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Documentos</h5>
                        <button type="button" class="btn"
                            data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-6">
                            <div class="row" id="contentguiastransportista">
                                    
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row" id="contentguiascobranza">
                                   
                            </div>
                        </div>
                        <div class="col-md-12" id="contentdocumentsempty" style="display: none">
                            <div class="alert alert-info">
                                No se han subido archivos
                            
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="w-100">
                                <h5 class="mx-2 text-muted">Guias sin entregar</h5>
                                <ul class="guias_pendientes" id="registrosguiaspendientes">
                                </ul>
                            </div>
                        </div>
                       </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalobservaciones" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <form action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Observaciones
                        </h5>
                        <button type="button" class="btn"
                            data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="w-100" id="loadingobservaciones"
                            style="display: none">


                            <div class="d-flex w-100 my-2 justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="contentobservaciones">
                                
                        </div>
                        <div class="w-100 text-center" id="contentobservacionesempty">
                           
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
   
@endsection
@section('scripts')
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script>
      
        async function submitRequestGuia(message) {
            $(`#loadingsms`).show();
            $.ajax({
                url: '/dashboard/message_request_guias',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: message
                },
                type: 'POST',
                success: function(json) {
                    console.log(json);

                    $(`#loadingsms`).hide();
                    if (json.success) {
                        Swal.fire({
                            title: 'Envio exitoso!',
                            text: json.message,
                            icon: 'success',
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: json.message,
                            icon: 'error'
                        });
                    }
                },
                error: function(jqXHR, status, error) {
                    $(`#loadingsms`).hide();
                    Swal.fire({
                        title: "Excepcion",
                        text: "Se a producido un error intentelo mas tarde",
                        icon: 'error'
                    });
                },

            });
        }
    </script>
    <script>
        $(document).ready(function() {
            
            if (!$("#fechainicioid").val() && !$("#fechafinid").val()) {
                $("#fechafinid").val(moment().format('YYYY-MM-DD'));
                $("#fechainicioid").val(moment().subtract(1, "month").format('YYYY-MM-DD'));
            }
        })
        function documentosPendientes(ev) {
            var guias_entregadas = [];
            var guias_pendientes = [];
            var registro = $(ev).attr('registro');
            var guias_de_remision = $(ev).attr("guias");
            var pdf_guia_transportista = $(ev).attr("guias_entregadas");
            if (pdf_guia_transportista) {
                var linksarchivos = pdf_guia_transportista.split(';');
                var entregados = linksarchivos.reduce((a, b) => {
                    var value = b.split('_')[3];
                    if (value) {
                        a.push(value);
                    }
                    return a;
                }, []);
                guias_entregadas = entregados;
                var all_guias = guias_de_remision.split('/');
                var pendientes = all_guias.reduce((a, b) => {
                    var entregado = guias_entregadas.find((x) => {
                        return x == b || x + "*" == b;
                    });
                    if (!entregado) {
                        a.push(b);
                    }
                    return a;
                }, []);
                guias_pendientes = pendientes;
            } else {
                guias_pendientes = guias_de_remision.split('/');
            }
            renderPendientes(guias_pendientes, `#registrospendientes${registro}`);
        
        }
        function modalNotification(ev){
           

        }
       
    </script>
    <script src="{{ asset('/js/registroconformidadtableadmin.js')}}"></script>
@endsection
