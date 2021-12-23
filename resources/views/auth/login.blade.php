<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">


</head>

<body class="wrap-content-login">
    <div class="container">
        <div class="row">

            <img src="{{ asset('assets/img/logo.png') }}" class="p-absolute logos" alt="">
        </div>
        <div class="row">
            <div class="col-md-7 img-login">
                <div class="circle circle-4">
                    <div class="d-flex justify-content-center text-white welcome-text align-items-center">
                        BIENVENID@
                    </div>
                </div>
            </div>
            <div class="col-md-4 vh-100 p-relative d-flex justify-content-start align-items-center">
                <div class="card border-0 p-relative bg-login w-100">
                    <div class="circle circle-1">
                    </div>
                    <div class="circle circle-2">
                    </div>
                    <div class="card-body">
                        <form method="POST" class="mt-3" action="{{ route('login') }}">
                            @csrf
                            <div class="input-group my-4">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent" id="usuario"><i
                                            class="fa fa-user-circle-o fs-12"></i>&nbsp;</span>
                                </div>
                                <input type="email" class="form-control" name="email" placeholder="USUARIO"
                                    aria-label="usuario" aria-describedby="usuario">
                            </div>
                            <div class="input-group my-4">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent" id="password"><i
                                            class="fa fa-lock fs-12"></i>&nbsp;&nbsp;</span>
                                </div>
                                <input type="password" class="form-control" name="password" placeholder="CONTRASEÑA"
                                    aria-label="password" aria-describedby="password">
                            </div>

                            @if ($errors->any())

                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="w-100 mt-4">
                                <button type="submit"
                                    class="btn button-login w-100 d-flex justify-content-center align-items-center"><i
                                        class="fa fa-user mx-4 text-white"></i> <span class="mx-4">Iniciar
                                        Sesión</span></button>
                            </div>
                            <div class="w-100 text-center my-3">
                                @if (Route::has('password.request'))
                                    <a class="underline text-sm text-gray-600 hover:text-gray-900"
                                        href="{{ route('password.request') }}">
                                        Olvidaste tu contraseña?
                                    </a>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
