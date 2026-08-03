<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Sistema de gestión farmacéutica FarmaClick">
    <meta name="author" content="FarmaClick">
    <meta name="keyword" content="Farmacia,Ventas,Inventario,Administración">
    <title>FarmaClick</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('img/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('img/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('img/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('img/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('img/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('img/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('img/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('img/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#1f8a4c">
    <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/farmaclick-theme.css') }}" rel="stylesheet">
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
<div class="wrapper d-flex flex-column min-vh-100">
    <header class="header header-sticky mb-12">
        <div class="container-fluid">
            <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector(&#39;#sidebar&#39;)).toggle()">
                <img class="header-action-icon" src="{{ asset('icons/menu.svg') }}" alt="" aria-hidden="true">
            </button>
          
            <ul class="header-nav d-none d-md-flex">
          
            </ul>
            <ul class="header-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#" aria-label="Notificaciones"><img class="header-action-icon" src="{{ asset('icons/bell.svg') }}" alt="" aria-hidden="true"></a></li>
                <li class="nav-item"><a class="nav-link" href="#" aria-label="Actividad"><img class="header-action-icon" src="{{ asset('icons/list-rich.svg') }}" alt="" aria-hidden="true"></a></li>
                <li class="nav-item"><a class="nav-link" href="#" aria-label="Mensajes"><img class="header-action-icon" src="{{ asset('icons/envelope-open.svg') }}" alt="" aria-hidden="true"></a></li>
            </ul>

            <ul class="header-nav ms-3">
                <li class="nav-item dropdown">
                        <a class="nav-link py-0 text-center header-user-trigger" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-md"><img class="avatar-img" src="{{ asset('img/FarmaClick_logo_cuadrado.png') }}" alt="FarmaClick"></div>
                            <span class="header-user-name">{{$user->name}}</span>
                        </a>
                    <div class="text-center pt-1 text-uppercase text-medium-emphasis small header-user-name-mobile">{{$user->name}}</div>
                    <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-light py-1 text-uppercase text-center">
                        <strong>{{Auth::user()->name}}</strong>
                    </div>

                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <img class="dropdown-action-icon" src="{{ asset('icons/account-logout.svg') }}" alt="" aria-hidden="true"> Salir</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>

    </header>

    <!-- <div class="body flex-grow-1 px-3"> -->
        <div class="container-fluid app-content">
            @yield('content')
        </div>
    <!-- </div> -->
</div>
</div>
    @include('layouts.footers')
</div>
</div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/plantilla.js') }}"></script>
    <script src="{{ asset('js/coreui.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"></script>
</body>
</html>
