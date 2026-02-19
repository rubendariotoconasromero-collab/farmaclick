<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar" style="background-color: {{$mi_empresa[0]->color_menu}}">
    @php
    $user = Auth::user();
    @endphp
    <div class="sidebar-brand d-none d-md-flex">
        @if($mi_empresa == [])
            <img src="{{ asset('img/logo_default/logo_sistema_default.png') }}" alt="logo" width=100px>
        @else
            @if(isset($mi_empresa[0]->logo_sistema))
                @if(file_exists('img/logo/'.$mi_empresa[0]->logo_sistema))
                    <img src="{{ asset('img/logo/'.$mi_empresa[0]->logo_sistema) }}" width=250px>
                @else
                    <img src="{{ asset('img/logo_default/logo_sistema_default.png') }}" width=250px>
                @endif
            @else
                <img src="{{ asset('img/logo_default/logo_sistema_default.png') }}" width=250px>
            @endif
        @endif
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="init">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask" style="background-color: {{$mi_empresa[0]->color_menu}}">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 0px;">
                            @if (permiso(1))
                            <li class="nav-item" @click="menu=0"  style="">
                                <a class="nav-link"  href="#">&nbsp;<img src="img/menu/grafico2.gif" width=30px>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Datos Gráficos<span class="badge badge-sm bg-info ms-auto">NEW</span></a>
                            </li>
                            @endif
                            @if (permiso(2))
                            <li class="nav-item" @click="menu=26"><a class="nav-link" ><img src="img/menu/dinero.png" width=35px>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Arqueo Caja</a></li>
                            @endif

                            @if(permiso(3) || permiso(4) || permiso(5))
                            <li class="nav-group show" style="" aria-expanded="true" ><a class="nav-link nav-group-toggle" href="#">&nbsp;<img src="img/menu/compra.png" width=30px>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>Compra</a>
                                <ul class="nav-group-items">
                                    @if (permiso(3))
                                    <li class="nav-item" @click="menu=10"><a class="nav-link" href="#"><span class="nav-icon"></span> Compra</a></li>
                                    @endif
                                    @if (permiso(4))
                                    <li class="nav-item" @click="menu=17"><a class="nav-link" href="#"><span class="nav-icon"></span> Historial Compra</a></li>
                                    @endif
                                    @if (permiso(5))
                                    <li class="nav-item" @click="menu=70"><a class="nav-link" href="#"><span class="nav-icon"></span> Pagos Compra</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if (permiso(6) || permiso(7) || permiso(8))
                            <li class="nav-group show" style="" aria-expanded="false"><a class="nav-link nav-group-toggle" href="#">&nbsp;<img src="img/menu/tienda.png" width=30px>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>Venta</a>
                                <ul class="nav-group-items">
                                    @if (permiso(6))
                                    <li class="nav-item" @click="menu=33"><a class="nav-link" href="#"><span class="nav-icon"></span> Venta</a></li>
                                    @endif
                                    @if (permiso(7))
                                    <li class="nav-item" @click="menu=34"><a class="nav-link" href="#"><span class="nav-icon"></span> Historial Venta</a></li>
                                    @endif
                                    @if (permiso(8))
                                    <li class="nav-item" @click="menu=37"><a class="nav-link" href="#"><span class="nav-icon"></span> Pagos Venta</a></li>
                                    @endif
                                    @if($user->id_grupo == 1)
                                    <li class="nav-item" @click="menu=27"><a class="nav-link" href="#"><span class="nav-icon"></span> Historial Arqueo</a></li>
                                    @endif
                                    <li class="nav-item" @click="menu=91"><a class="nav-link" href="#"><span class="nav-icon"></span> Historial Cliente</a></li>

                                    <li class="nav-item" @click="menu=92"><a class="nav-link" href="#"><span class="nav-icon"></span> Historial Producto</a></li>
  

                                </ul>
                            </li>
                            @endif
                            @if (permiso(9) || permiso(10)|| permiso(11) || permiso(12) || permiso(13) || permiso(14) || permiso(15) )
                            <li class="nav-group show" style="" aria-expanded="true"><a class="nav-link nav-group-toggle" href="#">&nbsp;<img src="img/menu/almacen.png" width=30px>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>Almacen</a>
                                <ul class="nav-group-items">
                                    @if (permiso(9))
                                    <li class="nav-item" @click="menu=9"><a class="nav-link" href="#"><span class="nav-icon"></span> Inventario</a></li>
                                    @endif
                                    @if (permiso(10))
                                    <li class="nav-item" @click="menu=8"><a class="nav-link" href="#"><span class="nav-icon"></span> Productos</a></li>
                                    @endif
                                    @if (permiso(11))
                                    <li class="nav-item" @click="menu=7"><a class="nav-link" href="#"><span class="nav-icon"></span> Categoria</a></li>
                                    @endif
                                    @if (permiso(12))
                                    <li class="nav-item" @click="menu=14"><a class="nav-link" href="#"><span class="nav-icon"></span> Ajuste</a></li>
                                    @endif
                                    @if (permiso(13))
                                    <li class="nav-item" @click="menu=50"><a class="nav-link" href="#"><span class="nav-icon"></span> Presentación</a></li>
                                    @endif
                                    @if (permiso(14))
                                    <li class="nav-item" @click="menu=90"><a class="nav-link" href="#"><span class="nav-icon"></span> Linea</a></li>
                                    @endif
                                    @if (permiso(15))
                                    <li class="nav-item" @click="menu=83"><a class="nav-link" href="#"><span class="nav-icon"></span> Lotes</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if (permiso(16) || permiso(17))
                            <li class="nav-group show" style="" aria-expanded="true"><a class="nav-link nav-group-toggle" href="#">&nbsp;<img src="img/menu/gasto.png" width=23px>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gastos</a>
                                <ul class="nav-group-items">
                                    @if (permiso(16))
                                    <li class="nav-item" @click="menu=11"><a class="nav-link" href="#"><span class="nav-icon"></span> Motivo Gastos</a></li>
                                    @endif
                                    @if (permiso(17))
                                    <li class="nav-item" @click="menu=12"><a class="nav-link" href="#"><span class="nav-icon"></span> Gastos</a></li>
                                    @endif
                                    <!-- <li class="nav-item" @click="menu=13"><a class="nav-link" href="#"><span class="nav-icon"></span> GP0</a></li> -->
                                </ul>
                            </li>
                            @endif
                            @if (permiso(18) || permiso(19) || permiso(20) || permiso(21)  || permiso(22))
                            <li class="nav-group show" style="" aria-expanded="true"><a class="nav-link nav-group-toggle" href="#">&nbsp;<img src="img/menu/configuracion.png" width=30px>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Datos</a>
                                <ul class="nav-group-items">
                                    @if (permiso(18))
                                    <li class="nav-item" @click="menu=1"><a class="nav-link" href="#"><span class="nav-icon"></span> Cliente</a></li>
                                    @endif
                                    @if (permiso(19))
                                    <li class="nav-item" @click="menu=3"><a class="nav-link" href="#"><span class="nav-icon"></span> Laboratorio</a></li>
                                    @endif
                                    @if (permiso(20))
                                    <li class="nav-item" @click="menu=2"><a class="nav-link" href="#"><span class="nav-icon"></span> Personal</a></li>
                                    @endif
                                    @if (permiso(21))
                                    <li class="nav-item" @click="menu=4"><a class="nav-link" href="#"><span class="nav-icon"></span> Cargo</a></li>
                                    @endif
                                    @if (permiso(22))
                                    <li class="nav-item" @click="menu=20"><a class="nav-link" href="#"><span class="nav-icon"></span> Mi Empresa</a></li>
                                    @endif
                                    <!-- <li class="nav-item" @click="menu=13"><a class="nav-link" href="#"><span class="nav-icon"></span> GP0</a></li> -->
                                </ul>
                            </li>
                            @endif
                            @if (permiso(23) || permiso(24) || permiso(25))
                            <li class="nav-group show" style="" aria-expanded="true"><a class="nav-link nav-group-toggle" href="#">&nbsp;<img src="img/menu/usuario.png" width=30px>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Usuario</a>
                                <ul class="nav-group-items">
                                    @if (permiso(23))
                                    <li class="nav-item" @click="menu=22"><a class="nav-link" href="#"><span class="nav-icon"></span> Grupos de Usuarios</a></li>
                                    @endif
                                    @if (permiso(24))
                                    <li class="nav-item" @click="menu=21"><a class="nav-link" href="#"><span class="nav-icon"></span> Usuarios</a></li>
                                    @endif
                                    @if (permiso(25))
                                    <li class="nav-item" @click="menu=6"><a class="nav-link" href="#"><span class="nav-icon"></span> Permisos</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif
                            @if (permiso(26))
                            <li class="nav-group show" style="" aria-expanded="true"><a class="nav-link nav-group-toggle" href="#">&nbsp;<img src="img/menu/reportes.png" width=30px>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reportes</a>
                                <ul class="nav-group-items">
                                    <li class="nav-item" @click="menu=28"><a class="nav-link" href="#"><span class="nav-icon"></span> Reportes</a></li>
                                </ul>
                            </li>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="simplebar-placeholder" style="width: 256px; height: 1606px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar simplebar-visible" style="width: 0px; display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
            <div class="simplebar-scrollbar simplebar-visible" style="height: 367px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
        </div>
    </ul>
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>
