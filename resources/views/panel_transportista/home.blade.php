@extends('layouts.app_transportista')
@section('content')
    <div class="container-fluid my-4">


        <div class="row container d-flex justify-content-center">
            <div class="col-xl-12 col-md-12">
                <div class="card user-card-full py-5">
                    <div class="row m-l-0 m-r-0">
                        <div class="col-sm-4 bg-c-lite-green user-profile">
                            <div class="card-block text-center text-white">
                                <div class="m-b-25"> <img src="{{ asset('assets/img/transportista.png')}}"
                                        class="img-radius w-75"  alt="User-Profile-Image"> </div>
                                <h6 class="f-w-600 text-dark">{{ auth('transportista')->user()->sap }}</h6>
                                <p class="text-dark">{{ auth('transportista')->user()->nombre }}</p> <i
                                    class=" mdi mdi-square-edit-outline feather icon-edit m-t-10 f-16"></i>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="card-block">
                                <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Datos de la empresa</h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="m-b-10 f-w-600">Nombre</p>
                                        <h6 class="text-muted f-w-400">
                                            {{ auth('transportista')->user()->nombre }}</h6>

                                    </div>
                                    <div class="col-sm-6">
                                        <p class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600">Sap</p>
                                        <h6 class="text-muted f-w-400">
                                            {{ auth('transportista')->user()->sap }}</h6>
                                    </div>
                                </div>
                                <h6 class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600"><br></h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600">Usuario</p>

                                        <h6 class="text-muted f-w-400">
                                            {{ auth('transportista')->user()->usuario }}</h6>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600">Correo(s)</p>
                                        <div class="text-muted f-w-400">
                                            <ul>
                                                @if (auth('transportista')->user()->correo)
                                                    @foreach (explode(';', auth('transportista')->user()->correo) as $correo)
                                                        <li>
                                                            {{ $correo }}
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <p>Pongase en contacto con los administradores para actualizar sus datos
                                                    </p>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                </div>

                                <h6 class="m-b-20 m-t-40 p-b-5 b-b-default mt-5 f-w-600">Cambiar contraseña</h6>
                                <div class="w-100">
                                    <div class="w-100">
                                        @if (session()->has('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        @if (session()->has('error'))
                                            <div class="alert alert-danger">
                                                {{ session('error') }}
                                            </div>
                                        @endif
                                    </div>
                                    <form action="{{ route('changepassword.transportista') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="">Contraseña(actual)</label>
                                                    <input type="password" required name="password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="">Contraseña nueva</label>
                                                    <input type="password" required name="password_new"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="">Repetir contraseña</label>
                                                    <input type="password" required name="password_verify"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" class="w-100">.</label>
                                                    <button class="btn btn-primary" type="submit"> <i
                                                            class="fa fa-save"></i> Guardar</button>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <h6 class="m-b-20 m-t-40 p-b-5 b-b-default mt-5 f-w-600">Actualizar correo</h6>
                                <div class="w-100">
                                    <form action="{{ route('changecorreo.transportista') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" required name="correo" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    
                                                    <button class="btn btn-primary" type="submit"> <i
                                                            class="fa fa-save"></i> Guardar</button>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <ul class="social-link list-unstyled m-t-40 m-b-10">
                                    <li><a href="#!" data-toggle="tooltip" data-placement="bottom" title=""
                                            data-original-title="facebook" data-abc="true"><i
                                                class="mdi mdi-facebook feather icon-facebook facebook"
                                                aria-hidden="true"></i></a></li>
                                    <li><a href="#!" data-toggle="tooltip" data-placement="bottom" title=""
                                            data-original-title="twitter" data-abc="true"><i
                                                class="mdi mdi-twitter feather icon-twitter twitter"
                                                aria-hidden="true"></i></a></li>
                                    <li><a href="#!" data-toggle="tooltip" data-placement="bottom" title=""
                                            data-original-title="instagram" data-abc="true"><i
                                                class="mdi mdi-instagram feather icon-instagram instagram"
                                                aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/datatable.js') }}"></script>


@endsection
