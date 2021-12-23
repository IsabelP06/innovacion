@extends('layouts.app_transportista')
@section('content')
    <div class="container-fluid my-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="" class="fw-bold">Codigo de transporte</label>
                        <p class="w-100">{{ $registro->orden_de_transporte }}</p>
                    </div>
                    <div class="col-md-4">

                        <label for="" class="fw-bold">Cliente</label>
                        <p class="w-100">{{ $registro->cliente }}</p>
                    </div>
                    <div class="col-md-4">

                        <label for="" class="fw-bold">Destino</label>
                        <p class="w-100">{{ $registro->destino }}</p>
                    </div>
                    <div class="col-md-4">
                        <label for="" class="fw-bold">Conductor</label>
                        <p class="w-100">{{ $registro->chofer }}</p>
                    </div>
                </div>
                <div class="row">
                    <h5 class="fw-bold text-muted ">Observaciones</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Observaciones</th>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($registro->observaciones))
                                @foreach ($registro->observaciones as $observacion)
                                    <tr>
                                        <td>{{ $observacion->nombre }}  &nbsp;&nbsp; {{$observacion->pivot->cantidad}}</td>
                                        <td><i class="fa fa-trash text-danger" data-bs-toggle="modal" data-bs-target="#id{{$observacion->id}}" role="button"></i></td>
                                        <div class="modal fade" id="id{{$observacion->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{route('eliminarobservacion.transportista',['registro_conformidad_id'=>$observacion->pivot->registro_conformidad_id,"observacion_id"=>$observacion->pivot->observacion_id])}}" method="post">
                                                    @csrf
                                                    @method('delete') 
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Eliminar observacion</h5>
                                                            <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Deseas eliminar la observacion  {{$observacion->nombre}}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary">Eliminar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="text-center">No existen observaciones</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @if(session()->has("error"))
                        <div class="w-100">
                            <div class="alert alert-danger">
                                {{session("error")}}
                            </div>
                        </div>
                    @endif
                    @if(session()->has("success"))
                    <div class="w-100">
                        <div class="alert alert-success">
                            {{session("success")}}
                        </div>
                    </div>
                @endif
                    <form action="{{route('observacion_store.transportista')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="hidden" name="registro_id" value="{{$registro->id}}"">
                                    <input type="hidden" name="newobservacion" id="newobservacion">
                                    <label for="">Observacion</label>
                                    <select name="observacion" id="observacionselect" required id="" class="form-control">
                                        @foreach($observaciones as $obs)
                                            <option value="{{$obs->id}}">{{$obs->nombre}}</option>
                                        @endforeach
                                        <option value="">Otro (especificar)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Cantidad</label>
                                    <input type="text" required name="cantidad" class="form-control" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <br>
                                <button class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $("#observacionselect").change(function(self) {
                const value = self.target.value
                if (value == "") {
                    var valor = prompt("Ingrese su observacion");
                    if (valor) {
                        $("#observacionselect").append(`<option value="new">${valor}</option>`);
                        $("#observacionselect").val("new");
                        $("#newobservacion").val(valor);
                    } else {
                        $("#observacionselect").val("");
                    }
                }
            })
        })

    </script>
@endsection
