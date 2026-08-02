<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta name="author" content="FarmaClick">
  <meta name="description" content="Acceso al sistema de gestión FarmaClick">
  <title>Acceso | FarmaClick</title>
  <link rel="icon" type="image/png" href="{{ asset('img/FarmaClick_logo_cuadrado.png') }}">
  <meta name="theme-color" content="#1f8a4c">
  <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet">
  <link href="{{ asset('css/farmaclick-theme.css') }}" rel="stylesheet">
</head>
<body class="login-page">
  <main class="login-layout">
    <section class="login-shell" aria-label="Acceso a FarmaClick">
      <div class="login-brand-panel">
        <div class="login-brand-content">
          <img
            class="login-brand-logo"
            src="{{ asset('img/FarmaClick_logo_cuadrado.png') }}"
            alt="FarmaClick"
          >
          <div class="login-brand-copy">
            <span class="login-brand-kicker">Gestión farmacéutica</span>
            <h2>Tu farmacia, siempre conectada.</h2>
            <p>Ventas, inventario y administración en un solo lugar.</p>
          </div>
        </div>
        <span class="login-brand-status">
          <i class="fa fa-circle" aria-hidden="true"></i>
          Acceso seguro
        </span>
      </div>

      <div class="login-form-panel">
        <div class="login-form-header">
          <span class="login-form-eyebrow">Bienvenido</span>
          <h1 class="login-title">Iniciar sesión</h1>
          <p class="login-subtitle">Ingresa tus credenciales para continuar.</p>
        </div>

        <form class="login-form" role="form" method="POST" action="{{ route('usuario') }}">
          @csrf
          <div class="login-field">
            <label for="login-name">Usuario</label>
            <div class="login-control{{ $errors->has('name') ? ' is-invalid' : '' }}">
              <i class="fa fa-user-o login-control-icon" aria-hidden="true"></i>
              <input
                id="login-name"
                class="login-input"
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Nombre de usuario"
                autocomplete="username"
                required
                autofocus
              >
            </div>
            @error('name')
              <p class="login-error" role="alert">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                {{ $message }}
              </p>
            @enderror
          </div>

          <div class="login-field">
            <label for="login-password">Contraseña</label>
            <div class="login-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
              <i class="fa fa-lock login-control-icon" aria-hidden="true"></i>
              <input
                id="login-password"
                class="login-input"
                name="password"
                placeholder="Ingresa tu contraseña"
                type="password"
                autocomplete="current-password"
                required
              >
              <button
                id="toggle-password"
                class="login-password-toggle"
                type="button"
                aria-label="Mostrar contraseña"
                aria-pressed="false"
                title="Mostrar contraseña"
              >
                <i class="fa fa-eye" aria-hidden="true"></i>
              </button>
            </div>
            @error('password')
              <p class="login-error" role="alert">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                {{ $message }}
              </p>
            @enderror
          </div>

          <button class="login-submit" type="submit">
            <span>Acceder al sistema</span>
            <i class="fa fa-arrow-right" aria-hidden="true"></i>
          </button>
        </form>

        <p class="login-footer-copy">&copy; {{ date('Y') }} FarmaClick</p>
      </div>
    </section>
  </main>

  <script>
    (function () {
      var toggle = document.getElementById('toggle-password');
      var password = document.getElementById('login-password');

      if (!toggle || !password) {
        return;
      }

      toggle.addEventListener('click', function () {
        var isVisible = password.type === 'text';
        password.type = isVisible ? 'password' : 'text';
        toggle.setAttribute('aria-pressed', isVisible ? 'false' : 'true');
        toggle.setAttribute('aria-label', isVisible ? 'Mostrar contraseña' : 'Ocultar contraseña');
        toggle.setAttribute('title', isVisible ? 'Mostrar contraseña' : 'Ocultar contraseña');
        toggle.querySelector('i').className = isVisible ? 'fa fa-eye' : 'fa fa-eye-slash';
        password.focus();
      });
    }());
  </script>
</body>
</html>
