@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-1  my-4">
        <div class="w-100 px-4 py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-muted fs-5">Control de sedes</h6>
            <a href="{{route('sedes.create')}}" class="btn btn-sm  btn-primary">Agregar</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead>
                        <!--      id	titulo	nro_sesiones	precio -->
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sedes as $sede)
                        <tr>
                            <td>{{$sede->id}}</td>
                            <td>{{$sede->nombre}}</td>
                            <td>&nbsp;&nbsp;
                                <a href="{{route('sedes.edit',['sede'=>$sede->id])}}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                <a href="#" data-bs-toggle="modal" data-bs-target="#id{{$sede->id}}"><i class="fa fa-times"></i></a>
                            </td>
                            <div class="modal fade" id="id{{$sede->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{route('sedes.destroy',['sede'=>$sede->id])}}" method="post">
                                        @csrf
                                        @method('delete') 
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Dar de baja sede</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Deseas eliminar definitivamente la sede  {{$sede->nombre}}
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('js/datatable.js')}}"></script>


@endsection