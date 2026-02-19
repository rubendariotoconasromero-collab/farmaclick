<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>Veterinaria Santander</title>
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
    <link rel="manifest" href="assets/favicon/manifest.json">
    <meta name="theme-color" content="#ffffff">
    <link href="css/plantilla.css" rel="stylesheet">
  </head>
  <body>
    <div class=" min-vh-100 d-flex flex-row align-items-center">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card-group d-block d-md-flex row">
              <div class="card col-md-7 p-4 mb-0" style="background: url('img/sit_norte/blanco.png');" >
                <div class="card-body">
                  <form role="form" method="POST" action="{{ route('usuario') }}">
                    @csrf
                    <h1 style="color:#5A007F">INICIO DE SESION</h1>
                    <h6 style="color:#C74C35">Ingrese sus Datos</h6>
                    <div class="mb-3">
                    <div class="input-group ">
                        <input style="background-color: transparent;border: none;border-bottom: #5A007F thin solid;color:#5A007F" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Usuario') }}" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                      @error('name')
                      <div class="text-danger">
                         {{$message}}
                      </div>
                      @enderror
                    </div>

                    <div class="mb-3">
                    <div class="input-group ">
                        <input style="background-color: transparent;border: none;border-bottom: #5A007F thin solid;color:#5A007F;" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Contraseña') }}" type="password" required>
                    </div>
                    @error('password')
                      <div class="text-danger">
                         {{$message}}
                      </div>
                      @enderror
                    </div>
                    <div class="row">
                      <div class="col-6">
                        <button class="btn bg-blue px-4" style="color:#5A007F" type="submit">Acceder</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="card col-md-5 text-white bg-blue py-5">
                <div class="card-body text-center">
                  <div>
                    <img src="img/logo.png" style="width: 105%;">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="js/plantilla.js"></script>
  </body>
</html>

<style>

body{
    margin: 0;
    padding: 0;
    background: url("img/principal.jpeg");
    background-size: 100% 100%;
    background-position: center;
    font-family: sans-serif;
    background-attachment: relative;
}

.bg-blue{
     background-color: 	#CD9933 !important;
    }

.formulario input {
    width: 70%;
    display: block;
    margin: auto;
    margin-bottom: 2rem;
    background-color: transparent;
    border: none;
    border-bottom: white thin solid;
    text-align: center;
    outline: none;
    padding: .2rem 0;
    font-size: .9rem;
    color: white;

}

</style>
