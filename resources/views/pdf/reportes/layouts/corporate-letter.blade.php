<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    @include('pdf.reportes.partials.corporate-letter-theme')
    @stack('styles')
</head>
<body>
    @include('pdf.reportes.partials.corporate-letter-header')
    @include('pdf.reportes.partials.corporate-letter-footer')
    @yield('content')
</body>
</html>
