<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield("title") | Siderperu</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html"></a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars text-light"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group d-none">
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <p class="text-white my-auto"> {{ auth('transportista')->user()->nombre }}</p>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <div class="dropdown-item" href="#!">

                            <form method="POST" action="{{ route('logouttransportista') }}" class="d-flex">
                                @csrf
                                <input type="submit" value="Salir" style="background:transparent;border:none"
                                    class="d-flex text-muted w-100">
                            </form>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="javascript:void(0)">
                            <div class="w-100 text-center">
                                <i class="fa fa-user fs-1"></i>
                            </div>
                        </a>
                        <a class="nav-link" href="javascript:void(0)">
                            <div class="w-100 text-center ">
                                <i class="fa fa-user"></i> &nbsp;&nbsp; SIGN UP
                            </div>
                        </a>
                        <div class="sb-sidenav-menu-heading">Panel</div>
                       <a class="nav-link" href="{{URL::to('/panel')}}">
                        <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Inicio
                        </a>
                        <a class="nav-link" href="{{ URL::to('/panel/registro_conformidad') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-pdf"></i></div>
                            Guias de remisión
                        </a>
                        <a class="nav-link">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            <form method="POST" action="{{ route('logouttransportista') }}" class="d-flex">
                                @csrf
                                <input type="submit" value="Salir" style="background:transparent;border:none;color:rgba(255, 255, 255, 0.774)"
                                    class="d-flex  w-100">
                            </form>
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="d-flex justify-content-between" style="color:rgba(255,255,255,0.80)">
                        <i class="fab fa-twitter-square fs-4"></i>
                        <i class="fab fa-facebook fs-4"></i>
                        <i class="far fa-check-circle fs-4"></i>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    @yield('content')
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2021</div>
                        <div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/angular.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    @yield("scripts");
</body>

</html>
