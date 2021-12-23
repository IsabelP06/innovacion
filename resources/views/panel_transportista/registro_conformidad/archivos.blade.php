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
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="row">
                    @if(count($guiaspendientes))
                    <div class="w-100">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            Asegurese de subir sus archivos a tiempo, para evitar amonestaciones.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                    @else
                    <div class="w-100">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Todas sus guias estan correctamente entregadas
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                    @endif
                    @foreach ($guiaspendientes as $guiapendiente)
                        <div class="col-md-12">
                            <h6 class="fw-bold py-2">Guia {{$guiapendiente}}</h6>
                            <form action="{{ route('uploadfiles.transportista', ['guia' => $guiapendiente]) }}"
                                class="w-100" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="hidden" name="id" value="{{ $registro->id }}">
                                        <label for="" class="fw-bold text-muted">Guia transportista</label>
                                        <input type="file" accept=".pdf" name="guia_transportista" required
                                            class="form-control" id="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="fw-bold text-muted">Guia de cobranza</label>
                                        <input type="file" accept=".pdf" name="guia_cobranza" class="form-control" id="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="w-100">.</label>
                                        <button class="btn btn-primary" type="submit">Subir archivos</button>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                    @endforeach
                   
                    <div class="col-md-12" style="display: none" id="modificarEntrega">
                        
                        <h6 class="fw-bold py-2" id="titlemodificarentrega">Guia </h6>
                        <form action="{{ route('updatefiles.transportista') }}" class="w-100" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="hidden" name="id" value="{{ $registro->id }}">
                                    <label for="" class="fw-bold">Guia transportista</label>
                                    <input type="file" accept=".pdf" name="guia_transportista" required
                                        class="form-control" id="">
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="fw-bold">Guia de cobranza</label>
                                    <input type="hidden" name="nroguiaeditar" id="nroguiaeditar">
                                    <input type="file" accept=".pdf" name="guia_cobranza" class="form-control" id="">
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="w-100">.</label>
                                    <button class="btn btn-primary" type="submit">Subir archivos</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                @if(count($guiasentregadas))
                <div class="container">
                    <h5>GUIAS ENTREGADAS</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table  table-bordered">
                                <thead class="bg-success text-white">
                               
                                    <tr>
                                        <th>Guia</th>
                                        <th>Editar entrega</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guiasentregadas as $guiaentregada)
                                        <tr>
                                            <td>{{ $guiaentregada }}</td>
                                            <td><i role="button" onclick="modificarEntrega(this)" guia="{{ $guiaentregada}}" class="fa fa-edit text-danger"></i></td>
                                        </tr>
                                    @endforeach
                                </tbody>
    
                            </table>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
    <br>
    <hr>
  
@endsection
@section('scripts')
    <script>
        function modificarEntrega(event) {
            console.log(event);
           var guia= $(event).attr("guia");
            $("#modificarEntrega").css("display", "block");
            $("#nroguiaeditar").val(guia);
            $('#titlemodificarentrega').html('Editar entrega de guia  ' +guia);
        }
     
       
    </script>
@endsection
