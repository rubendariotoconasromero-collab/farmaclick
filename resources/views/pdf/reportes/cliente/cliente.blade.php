@include('pdf.reportes.partials.system-theme')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 0.8cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .logo {
            width: 15%;
        }
        .logo img {
            max-height: 70px;
            width: auto;
        }
        .logo-placeholder {
            width: 70px;
            height: 70px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }
        .info-empresa {
            width: 70%;
            text-align: center;
        }
        .info-empresa h1 {
            margin: 0;
            font-size: 18px;
            color: #001843;
            font-weight: bold;
        }
        .info-empresa p {
            margin: 4px 0;
            font-size: 10px;
            color: #555;
        }
        .resumen {
            text-align: right;
            font-size: 10px;
            margin-bottom: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #001843;
            color: white;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #000;
        }
        td {
            padding: 5px 4px;
            border: 1px solid #000;
            vertical-align: top;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #777;
            background-color: #f9f9f9;
        }
        .footer-note {
            margin-top: 15px;
            font-size: 9px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>

<header>
    <table class="header-table">
        <tr>
            <td style="width: 22%; text-align: left;">
                @if($logo_sistema)
                    <img src="{{ public_path('img/logo/' . $logo_sistema) }}" alt="FarmaClick">
                @endif
            </td>
            <td class="header-title">{{ $title }}</td>
            <td style="width: 22%; text-align: right;">
                <strong>{{ $nombre_empresa }}</strong><br>
                <small>{{ $direccion_empresa }}<br>{{ $telefono_empresa }}</small>
            </td>
        </tr>
    </table>
</header>

<!-- Resumen -->
<div class="resumen">
    Total de clientes: <strong>{{ number_format($total_clientes) }}</strong> | Fecha: {{ now()->format('d/m/Y') }}
</div>

<!-- Tabla de clientes -->
@if($clientes->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Nombre del Cliente</th>
                <th>Documento</th>
                <th>Teléfono</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $index => $cliente)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $cliente->cliente ?? '—' }}</td>
                    <td class="text-center">{{ $cliente->matricula ?? '—' }}</td>
                    <td class="text-left">{{ $cliente->telefono ?? '—' }}</td>
                    <td class="text-left">{{ $cliente->direccion ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <table>
        <tr>
            <td class="no-data" colspan="5">
                No hay clientes registrados.
            </td>
        </tr>
    </table>
@endif



</body>
</html>
