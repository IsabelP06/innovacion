@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-1  my-4">
            <div class="w-100 px-4 py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-muted fs-5">Control de transportistas</h6>
                <a href="{{ route('transportista.create') }}" class="btn btn-sm  btn-primary">Agregar</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size:.8em" id="datatables" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Correo</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transportistas as $transportista)
                                <tr>

                                    <td>{{ $transportista->id }}</td>
                                    <td>
                                        <div class="w-100">
                                            <div class="w-100">
                                                <strong>{{ $transportista->sap }}</strong>
                                            </div>
                                            <div class="w-100">
                                                {{ $transportista->nombre }}
                                            </div>
                                            
                                        </div>
                                    </td>
                                    <td>{{$transportista->usuario}}</td>
                                    <td style="max-width:200px;overflow:hidden">{{ $transportista->correo }}</td>
                                    <td>&nbsp;&nbsp;
                                        <a href="{{ route('transportista.edit', ['transportistum' => $transportista->id]) }}"><i
                                                class="fa fa-edit"></i></a>&nbsp;&nbsp;
                                        
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
@endsection
