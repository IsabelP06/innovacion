@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="w-100">
                        <form action="{{ route('registro_conformidad.store') }}" method="post" enctype="multipart/form-data">
                            @if ($errors->any())
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            @if (session()->has('error'))
                                <div class="alert alert-warning alert-dismiss">
                                    @if(session('error.row')=="2")
                                        <p>Compruebe su archivo e intentelo de nuevo</p>
                                        @else
                                        <p>Se importo correctamente hasta la fila {{session('error.row')}}</p>
                                    @endif
                                    <p>Los siguientes campos son requeridos {!! implode(' ', explode('_', session('error.columns'))) !!}</p>
                                </div>
                            @endif
                            @if (session()->has('exception'))
                                <div class="alert alert-danger alert-dismiss">
                                    <p>A ocurrido un error verifique que el archivo sea valido</p>
                                    <p>{{session("exception.message")}}</p>
                                </div>
                            @endif
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Archivo</label>
                                        <input type="file" required name="registro_conformidad" class="form-control ">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Sede</label>
                                        <select name="sede" required class="form-control" required class="form-control">
                                            <option value="">Seleccione</option>
                                            @foreach($sedes as $sede)
                                            <option value="{{$sede->nombre}}">{{$sede->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <br>
                                    <button class="btn btn-primary" type="submit">Subir</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
