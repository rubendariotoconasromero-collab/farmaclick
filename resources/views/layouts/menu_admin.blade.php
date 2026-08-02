<aside class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
  @php
    $user = Auth::user();
  @endphp

  <div class="sidebar-brand d-none d-md-flex">
    <img
      class="sidebar-brand-logo"
      src="{{ asset('img/FarmaClick_logo_horizontal.png') }}"
      alt="FarmaClick"
    >
  </div>

  <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    @if (permiso(1))
      <li class="nav-item">
        <router-link class="nav-link" :to="{ name: 'dashboard' }" data-route-name="dashboard">
          <img class="sidebar-menu-icon" src="{{ asset('icons/speedometer.svg') }}" alt="" aria-hidden="true">
          <span>Datos gráficos</span>
        </router-link>
      </li>
    @endif

    @if (permiso(2))
      <li class="nav-item">
        <router-link class="nav-link" :to="{ name: 'arqueo-caja' }" data-route-name="arqueo-caja">
          <img class="sidebar-menu-icon" src="{{ asset('icons/calculator.svg') }}" alt="" aria-hidden="true">
          <span>Arqueo de caja</span>
        </router-link>
      </li>
    @endif

    @if (permiso(3) || permiso(4) || permiso(5))
      <li class="nav-group" aria-expanded="false">
        <a class="nav-link nav-group-toggle" href="#">
          <img class="sidebar-menu-icon" src="{{ asset('icons/truck.svg') }}" alt="" aria-hidden="true">
          <span>Compras</span>
        </a>
        <ul class="nav-group-items">
          @if (permiso(3))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'compra-nueva' }" data-route-name="compra-nueva"><span class="nav-icon"></span>Nueva compra</router-link></li>
          @endif
          @if (permiso(4))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'compras-historial' }" data-route-name="compras-historial"><span class="nav-icon"></span>Historial de compras</router-link></li>
          @endif
          @if (permiso(5))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'compras-pagos' }" data-route-name="compras-pagos"><span class="nav-icon"></span>Pagos de compras</router-link></li>
          @endif
        </ul>
      </li>
    @endif

    @if (permiso(6) || permiso(7) || permiso(8))
      <li class="nav-group" aria-expanded="false">
        <a class="nav-link nav-group-toggle" href="#">
          <img class="sidebar-menu-icon" src="{{ asset('icons/cart.svg') }}" alt="" aria-hidden="true">
          <span>Ventas</span>
        </a>
        <ul class="nav-group-items">
          @if (permiso(6))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'ventas-nueva' }" data-route-name="ventas-nueva"><span class="nav-icon"></span>Nueva venta</router-link></li>
          @endif
          @if (permiso(7))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'ventas-historial' }" data-route-name="ventas-historial"><span class="nav-icon"></span>Historial de ventas</router-link></li>
          @endif
          @if (permiso(8))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'ventas-pagos' }" data-route-name="ventas-pagos"><span class="nav-icon"></span>Pagos de ventas</router-link></li>
          @endif
          @if ($user->id_grupo == 1)
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'historial-arqueos' }" data-route-name="historial-arqueos"><span class="nav-icon"></span>Historial de arqueos</router-link></li>
          @endif
          <li class="nav-item"><router-link class="nav-link" :to="{ name: 'ventas-clientes' }" data-route-name="ventas-clientes"><span class="nav-icon"></span>Ventas por cliente</router-link></li>
          <li class="nav-item"><router-link class="nav-link" :to="{ name: 'ventas-productos' }" data-route-name="ventas-productos"><span class="nav-icon"></span>Ventas por producto</router-link></li>
        </ul>
      </li>
    @endif

    @if (permiso(9) || permiso(10) || permiso(11) || permiso(12) || permiso(13) || permiso(14) || permiso(15))
      <li class="nav-group" aria-expanded="false">
        <a class="nav-link nav-group-toggle" href="#">
          <img class="sidebar-menu-icon" src="{{ asset('icons/basket.svg') }}" alt="" aria-hidden="true">
          <span>Almacén</span>
        </a>
        <ul class="nav-group-items">
          @if (permiso(9))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'inventario' }" data-route-name="inventario"><span class="nav-icon"></span>Inventario</router-link></li>
          @endif
          @if (permiso(10))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'productos' }" data-route-name="productos"><span class="nav-icon"></span>Productos</router-link></li>
          @endif
          @if (permiso(11))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'categorias' }" data-route-name="categorias"><span class="nav-icon"></span>Categorías</router-link></li>
          @endif
          @if (permiso(12))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'ajustes' }" data-route-name="ajustes"><span class="nav-icon"></span>Ajustes</router-link></li>
          @endif
          @if (permiso(13))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'presentaciones' }" data-route-name="presentaciones"><span class="nav-icon"></span>Presentaciones</router-link></li>
          @endif
          @if (permiso(14))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'lineas' }" data-route-name="lineas"><span class="nav-icon"></span>Líneas</router-link></li>
          @endif
          @if (permiso(15))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'lotes' }" data-route-name="lotes"><span class="nav-icon"></span>Lotes</router-link></li>
          @endif
        </ul>
      </li>
    @endif

    @if (permiso(16) || permiso(17))
      <li class="nav-group" aria-expanded="false">
        <a class="nav-link nav-group-toggle" href="#">
          <img class="sidebar-menu-icon" src="{{ asset('icons/money.svg') }}" alt="" aria-hidden="true">
          <span>Gastos</span>
        </a>
        <ul class="nav-group-items">
          @if (permiso(16))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'motivos-gasto' }" data-route-name="motivos-gasto"><span class="nav-icon"></span>Motivos de gasto</router-link></li>
          @endif
          @if (permiso(17))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'gastos' }" data-route-name="gastos"><span class="nav-icon"></span>Registro de gastos</router-link></li>
          @endif
        </ul>
      </li>
    @endif

    @if (permiso(18) || permiso(19) || permiso(20) || permiso(21) || permiso(22))
      <li class="nav-group" aria-expanded="false">
        <a class="nav-link nav-group-toggle" href="#">
          <img class="sidebar-menu-icon" src="{{ asset('icons/storage.svg') }}" alt="" aria-hidden="true">
          <span>Datos maestros</span>
        </a>
        <ul class="nav-group-items">
          @if (permiso(18))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'clientes' }" data-route-name="clientes"><span class="nav-icon"></span>Clientes</router-link></li>
          @endif
          @if (permiso(19))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'laboratorios' }" data-route-name="laboratorios"><span class="nav-icon"></span>Laboratorios</router-link></li>
          @endif
          @if (permiso(20))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'personal' }" data-route-name="personal"><span class="nav-icon"></span>Personal</router-link></li>
          @endif
          @if (permiso(21))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'cargos' }" data-route-name="cargos"><span class="nav-icon"></span>Cargos</router-link></li>
          @endif
          @if (permiso(22))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'mi-empresa' }" data-route-name="mi-empresa"><span class="nav-icon"></span>Mi empresa</router-link></li>
          @endif
        </ul>
      </li>
    @endif

    @if (permiso(23) || permiso(24) || permiso(25))
      <li class="nav-group" aria-expanded="false">
        <a class="nav-link nav-group-toggle" href="#">
          <img class="sidebar-menu-icon" src="{{ asset('icons/people.svg') }}" alt="" aria-hidden="true">
          <span>Usuarios</span>
        </a>
        <ul class="nav-group-items">
          @if (permiso(23))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'grupos-usuarios' }" data-route-name="grupos-usuarios"><span class="nav-icon"></span>Grupos de usuarios</router-link></li>
          @endif
          @if (permiso(24))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'usuarios' }" data-route-name="usuarios"><span class="nav-icon"></span>Usuarios</router-link></li>
          @endif
          @if (permiso(25))
            <li class="nav-item"><router-link class="nav-link" :to="{ name: 'permisos' }" data-route-name="permisos"><span class="nav-icon"></span>Permisos</router-link></li>
          @endif
        </ul>
      </li>
    @endif

    @if (permiso(26))
      <li class="nav-item">
        <router-link class="nav-link" :to="{ name: 'reportes' }" data-route-name="reportes">
          <img class="sidebar-menu-icon" src="{{ asset('icons/chart-line.svg') }}" alt="" aria-hidden="true">
          <span>Reportes</span>
        </router-link>
      </li>
    @endif
  </ul>

  <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable" aria-label="Contraer menú"></button>
</aside>
