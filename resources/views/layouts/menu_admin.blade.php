@php($navigation = \App\Support\Navigation::forUser(Auth::user()))
<aside class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
  <button class="sidebar-close-mobile d-md-none" type="button" aria-label="Cerrar menú" onclick="coreui.Sidebar.getOrCreateInstance(document.querySelector('#sidebar')).hide()">
    <img src="{{ asset('icons/x.svg') }}" alt="" aria-hidden="true">
  </button>
  <div class="sidebar-brand d-none d-md-flex">
    <img class="sidebar-brand-logo" src="{{ asset('img/FarmaClick_logo_horizontal.png') }}" alt="FarmaClick">
  </div>

  <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    @foreach ($navigation as $item)
      @if (isset($item['children']))
        <li class="nav-group" aria-expanded="false">
          <a class="nav-link nav-group-toggle" href="#">
            <img class="sidebar-menu-icon" src="{{ asset($item['icon']) }}" alt="" aria-hidden="true">
            <span>{{ $item['label'] }}</span>
          </a>
          <ul class="nav-group-items">
            @foreach ($item['children'] as $child)
              <li class="nav-item">
                <router-link class="nav-link" :to="{ name: '{{ $child['route'] }}' }" data-route-name="{{ $child['route'] }}">
                  <span class="nav-icon"></span>{{ $child['label'] }}
                </router-link>
              </li>
            @endforeach
          </ul>
        </li>
      @else
        <li class="nav-item">
          <router-link class="nav-link" :to="{ name: '{{ $item['route'] }}' }" data-route-name="{{ $item['route'] }}">
            <img class="sidebar-menu-icon" src="{{ asset($item['icon']) }}" alt="" aria-hidden="true">
            <span>{{ $item['label'] }}</span>
          </router-link>
        </li>
      @endif
    @endforeach
  </ul>

  <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable" aria-label="Contraer menú"></button>
</aside>
