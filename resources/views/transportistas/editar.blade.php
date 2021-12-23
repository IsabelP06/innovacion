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
                                        <h1 class="h4 text-gray-900 mb-4">Editar transportista</h1>
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
                                            {{session('error') }}
                                        </div>
                                        @endif
                                    </div>
                                    <form class="user"
                                        action="{{ route('transportista.update', ['transportistum' => $transportista->id]) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Sap </label>
                                                    <input type="text" name="sap" required value="{{ $transportista->sap }}"
                                                        class="form-control form-control-user">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Nombre</label>
                                                    <input type="text" required name="nombre" value="{{ $transportista->nombre }}"
                                                        class="form-control form-control-user">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="correo">Correo</label>
                                                    <input type="text"  name="correo" value="{{ $transportista->correo }}"
                                                        class="form-control form-control-user" required>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">Contraseña</label>
                                                    <input type="password" name="password"
                                                        class="form-control form-control-user" placeholder="(Opcional )">
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="d-flex justify-content-end mt-4">
                                            <input type="submit" class="btn btn-primary mx-2 btn-user"
                                            value="Guardar">
                                            <a href="/dashboard/transportista" class="btn btn-danger btn-user"
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
