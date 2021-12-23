@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class=" offset-1 col-md-10">
                <div class="card my-4">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4 text-muted">Crear transportista</h1>
                                    </div>
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if (session()->has('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <form class="user" action="{{ route('transportista.store') }}" method="post">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Sap</label>
                                                    <input type="text" name="sap" require
                                                        class="form-control form-control-user">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Nombre</label>
                                                    <input type="text" name="nombre" aria-required=""
                                                        class="form-control form-control-user">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Correo(s) </label>
                                                    <input type="text" name="correo" require
                                                        class="form-control form-control-user">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">Contraseña</label>
                                                    <input type="password" require name="password"
                                                        class="form-control form-control-user">
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row my-auto text-right mt-4">
                                            <div class="col-md-8"></div>
                                            <div class="col-md-4"> <input type="submit"
                                                    class="btn btn-primary btn-user" value="Agregar transportista"></div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
