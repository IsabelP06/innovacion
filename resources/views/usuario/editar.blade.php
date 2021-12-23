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
                                        @if (session()->has('error'))
                                            <div class="alert alert-warning">
                                                <p>{{ session('error') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <form class="user"
                                        action="{{ route('usuario.update', ['usuario' => $usuario->id]) }}" method="post">
                                        @csrf
                                        @method('put')

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Nombres</label>
                                                    <input type="text" name="name" value="{{ $usuario->name }}"
                                                        class="form-control form-control-user">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Correo</label>
                                                    <input type="email" readonly name="email" value="{{ $usuario->email }}"
                                                        class="form-control form-control-user">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">Contraseña</label>
                                                    <input type="password" name="password"
                                                        class="form-control form-control-user" placeholder="(Opcional )">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="rol">Estado</label>
                                                    <select name="estado" required value="{{ $usuario->estado }}"
                                                        class="form-control">
                                                        <option value="activo">Activo</option>
                                                        <option value="baja">Baja</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex mt-4 justify-content-end">
                                            <input type="submit" class="btn btn-primary mx-2 btn-user" value="Guardar">
                                            <a href="/dashboard/usuario" class="btn btn-danger btn-user"> Volver</a>
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
