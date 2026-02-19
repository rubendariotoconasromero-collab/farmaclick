<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>Farmacia Suarez</title>
    <link rel="apple-touch-icon" sizes="57x57" href="img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link href="css/plantilla.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
    integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<div id="app">
@php
    $user = Auth::user();
    $tienda = DB::select("SELECT id, nombre, foto FROM tienda");

    $mi_empresa = DB::select("SELECT id, nombre, foto, logo_login, logo_sistema, logo_usuario,color_login,color_menu FROM mi_empresa WHERE id=1");
@endphp
@include('layouts.menu_admin')
<div class="wrapper d-flex flex-column min-vh-100 bg-light">
    <header class="header header-sticky mb-12">
        <div class="container-fluid">
            <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector(&#39;#sidebar&#39;)).toggle()">
                <i class="fa fa-list"></i><svg class="icon icon-lg"><use xlink:href="icons/svg/free.svg#cil-menu"></use></svg>
            </button>
          
            <ul class="header-nav d-none d-md-flex">
          
            </ul>
            <ul class="header-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#"><svg class="icon icon-lg"><use xlink:href="icons/svg/free.svg#cil-bell"></use></svg></a></li>
                <li class="nav-item"><a class="nav-link" href="#"><svg class="icon icon-lg"><use xlink:href="icons/svg/free.svg#cil-list-rich"></use></svg></a></li>
                <li class="nav-item"><a class="nav-link" href="#"><svg class="icon icon-lg"><use xlink:href="icons/svg/free.svg#cil-envelope-open"></use></svg></a></li>
            </ul>

            <ul class="header-nav ms-3">
                <li class="nav-item dropdown">
                        <a class="nav-link py-0 text-center" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">

                            @if(file_exists('img/logo/'.$mi_empresa[0]->logo_sistema))
                                <div class="avatar avatar-md"><img class="avatar-img" src="{{ asset('img/logo/'.$mi_empresa[0]->logo_usuario) }}" alt="tienda"></div>
                            @else
                            <div class="avatar avatar-md"><img class="avatar-img" src="{{ asset('img/logo_default/logo_usuario_default.png') }}" alt="tienda"></div>
                            @endif



                        </a>
                    <div class="text-center pt-1 text-uppercase text-medium-emphasis small ">{{$user->name}}</div>
                    <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-light py-1 text-uppercase text-center">
                        <strong>{{Auth::user()->name}}</strong>
                    </div>

                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-lock"></i><svg class="icon me-2"><use xlink:href="icons/svg/free.svg#cil-account-logout"></use></svg> Salir</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>

    </header>

    <!-- <div class="body flex-grow-1 px-3"> -->
        <div class="container-fluid">
            @yield('content')
        </div>
    <!-- </div> -->
</div>
</div>
    @include('layouts.footers')
</div>
</div>
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
    <script src="js/coreui.bundle.min.js">
        rel="stylesheet" integrity="sha384-UkVD+zxJKGsZP3s/JuRzapi4dQrDDuEf/kHphzg8P3v8wuQ6m9RLjTkPGeFcglQU" crossorigin="anonymous"
    </script>
    <script src="https://coreui.io/demo/free/3.4.0/js/main.js" async defer></script>
    <script src="https://kit.fontawesome.com/96cea3baee.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>
</body>
</html>
