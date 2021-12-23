@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <div class="card  my-4">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Editar usuario</h1>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <form class="user">
                                        @csrf
                                        @method('put')
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold" for="name">Nombres</label>
                                                    <p class="w-100">{{ $usuario->name }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold" for="email">Correo</label>
                                                    <p>{{ $usuario->email}} </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold" for="fichasap">Ficha sap</label>
                                                    <p>{{ $usuario->fichasap }}</p>
                                                </div>
                                            </div>
                                           

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold" for="rol">Estado</label>
                                                   <p class="w-100">{{ $usuario->estado }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold" for="rol">Rol</label>
                                                   <p class="w-100">{{$usuario->rol}}</p>
                                                </div>
                                            </div>
                                           
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <a href="/dashboard/usuario" class="btn btn-danger btn-user"
                                            > Volver</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>
    </div>
@endsection
