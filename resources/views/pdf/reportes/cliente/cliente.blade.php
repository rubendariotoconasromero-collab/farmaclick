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

<!-- Encabezado -->
<div class="header">
    <div class="logo">
        @if($foto_empresa)
            <img src="{{ public_path('img/logo/' . $foto_empresa) }}" alt="Logo">
        @else
            <div class="logo-placeholder">LOGO</div>
        @endif
    </div>
    {{-- <div class="info-empresa">
        <h1>{{ $title }}</h1>
        <p>{{ $nombre_empresa }}</p>
        <p>{{ $direccion_empresa }} | Tel: {{ $telefono_empresa }}</p>
    </div> --}}
</div>

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
                <th>Teléfono</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $index => $cliente)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $cliente->cliente ?? '—' }}</td>
                    <td class="text-left">{{ $cliente->telefono ?? '—' }}</td>
                    <td class="text-left">{{ $cliente->direccion ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <table>
        <tr>
            <td class="no-data" colspan="4">
                No hay clientes registrados.
            </td>
        </tr>
    </table>
@endif



</body>
</html>