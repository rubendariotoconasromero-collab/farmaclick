<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>Historial Clinico</title>
</head>
@php
    $color1 = "#203578";
    $color2 = "#E46C0A";
    $color3 = "#dedbb6";
    $color4 = "#a2d972";
    $color5 = "#000000";
    $color6 = "#E46C0A";
@endphp
<style>
    @page {
        margin: 0.7cm 0.7cm 0.7cm 0.7cm;
        font-size: 12px;
        font-family: Arial;
    }
    body {
        position: relative;
        color: #555555;
        background: #FFFFFF;
        font-family: Arial, sans-serif;
        font-size: 10px;
    }
    .table {
        display: table;
        width: 100%;
        max-width: 100%;
        background-color: transparent;
        border-collapse: collapse;
    }
    .table th {
        padding: 0.5rem;
        vertical-align: top;
    }
    .table td {
        padding: 0.5rem;
        vertical-align: top;
    }
    .table-pago {
        display: table;
        width: 80%;
        max-width: 80%;
        background-color: transparent;
        border-collapse: collapse;
    }
    .table-pago th {
        padding: 0.5rem;
        vertical-align: top;
    }
    .table-pago td {
        padding: 0.5rem;
        vertical-align: top;
    }
    .table-description{
        display: table;
        width: 95%;
        max-width: 95%;
        background-color: transparent;
        border-collapse: collapse;
    }
    .table-description th {
        padding: 0.5rem;
        vertical-align: top;
    }
    .table-description td {
        padding: 0.5rem;
        vertical-align: top;
    }
    /* .table tbody tr:nth-child(even) {
        background: #eff2d5;
    } */
    .table-head {
        width: 100%;
        max-width: 100%;
        border-collapse: separate;
        border-spacing: 1px;
    }
    .table-head-2 {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        border-spacing: 1px;
        padding: 0 0 0 0;
        margin: 0 0 0 0
    }
    .table-head th {
        vertical-align: center;
    }
    .table-body {
        display: table;
        width: 100%;
        max-width: 100%;
        background-color: transparent;
        border-collapse: collapse;
    }
    .table-body th {
        vertical-align: top;
    }
    .table-body td {
        vertical-align: top;
        padding-top: 0px;
        padding-bottom: 0px;
    }
    .table-footer{
        border-top: 1px solid <?php echo $color1; ?>;
        font-size: 10px; 
    }
    .table-saldo{
        text-align: right;
        vertical-align: top;
    }
    .footer-centro{
        position: absolute;
        bottom: 50%;
        left: 0;
        right: 0;
    }
    .footer-inferior{
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
    }
    .A{
        float: left;
        width: 20%; 
        height: 100px; 
        text-align:center;
    }
    .AA{
        float: left;
        text-align:center;
    }
    .BB{
        float: left;
        text-align:center;
    }
    .CC{
        float: left;
        text-align:center;
    }
    .DD{
        float: left;
        text-align:center;
    }
    .FF{
        float: left;
    }
    .container{
        height: 100px; 
    }
    .container2{
        height: 20px; 
    }
     #lateral { 
        width: 80px; 
    }
    #lateral { 
        height: 100px; 
    } 
    .page_break {
        page-break-before: always;
        @php $tamaño=0 @endphp
    }
    .mostrar {
        display: block;
    }
    .nomostrar {
        display: none;
    }
    .colocar_pie {
        page-break-before: always;

    }

    footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 1.5cm;
            }
    .stilo{
        height: 5px !important;
        margin: 0;
        padding: 0;
        vertical-align: top;
    }
    .columna1 {
    position:absolute;
    top:0px;
    left:0px;
    width:200px;
    margin-top:10px;
    background-color:#ffff55;
    }
    .columna2 {
    margin-left:220px;
    margin-right:20px;
    margin-top:10px;
    background-color:#ffffbb;
    }

</style>
<header>
    @php
    $nueva_fecha1 = date("d", strtotime($detalles[0]->fecha));
    $nueva_fecha2 = date("m", strtotime($detalles[0]->fecha));
    $nueva_fecha3 = date("Y", strtotime($detalles[0]->fecha));
    @endphp
<table class="table-head table-borderless" style="padding-bottom: 7px" width="100%">
        <tr class="set-center">
            <th rowspan="1"  width="40%">

            {{-- <img src=<?php echo $foto_empresa != null ? "../public_html/img/mi_empresa/".$foto_empresa : '""' ?> height="5%" width="100%"> --}}
            {{-- <img src=<?php echo $foto_empresa != null ? "../public/img/mi_empresa/".$foto_empresa : '""' ?> height="5%" width="100%">  --}}
            <img src=<?php echo  "../public/img/mi_empresa/logo_vet.png" ?> height="5%" width="100%"> 

            </th>
            <th rowspan="1"  width="60%" style="background-color: #69007F" >
                <div style="font-weight: center">
                    <strong ><FONT  FACE="COURIER" style=" color:white;text-align: right" SIZE="5">{{__('Historia Clinica')}}</FONT></strong>
                </div>
                <div style="font-weight: center">
                    <strong ><FONT  FACE="COURIER" style=" color:white;text-align: right" SIZE="3">{{__('Nro:')}}{{ $detalles[0]->nro_historia }}</FONT></strong>
                </div>

            </th>
        </tr>
    </table>
    <div style="font-weight: center">
        <table class="table-head table-borderless" style="padding-bottom: 7px" width="100%">

            <tr  style=" text-align:left" >   
                <td style="font-weight: normal;width:20%;vertical-align:top" >
                    {{-- <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>22</FONT> --}}
                </td>
                <td style="font-weight: normal;width:20%;vertical-align:top" >
                    {{-- <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>22</FONT> --}}
                </td>
                <td style="font-weight: normal;width:6%;vertical-align:top ; border : 1px solid #69007F; text-align:center"  >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $nueva_fecha1 }}</FONT>
                </td>
                <td style="font-weight: normal;width:6%;vertical-align:top ; border : 1px solid #69007F ; text-align:center" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $nueva_fecha2 }}</FONT>
                </td>
                <td style="font-weight: normal;width:6%;vertical-align:top ; border : 1px solid #69007F; text-align:center" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $nueva_fecha3 }}</FONT>
                </td>
                <td style="font-weight: normal;width:20%;vertical-align:top ; text-align:right">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=3>ATENDIDO POR:</FONT></strong>
                </td>
                <td style="font-weight: normal;width:20%;vertical-align:top" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->doctor }}</FONT>
                </td>
            </tr> 
        </table>
    </div>
    <div style="background-color: #69007F">
        <h2 style=" color:white; text-align:center" style="font-weight: normal;width:25%;vertical-align:top">Datos del Propietario</h2>
    </div>
    <div>
        <table class="table-head-2" width="100%">
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:25%;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Propietario:</FONT></strong>
                </td>
                <td style="font-weight: normal;width:75%;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->propietario }}</FONT>
                </td>
            </tr> 
            <tr>
                <td colspan=4>
                    <div style="border-top: 0.5px solid #69007F">
                    </div>
                </td>
            </tr>
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:25%;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Direccion:</FONT></strong>
                </td>
                <td style="font-weight: normal;width:75%;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->direccion }}</FONT>
                </td>
                <td style="font-weight: normal;width:25%;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Telefono:</FONT></strong>
                </td>
                <td style="font-weight: normal;width:75%;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->telefono }}</FONT>
                </td>
            </tr> 
            <tr>
                <td colspan=4>
                    <div style="border-top: 0.5px solid #69007F">
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div style="background-color: #69007F">
        <h2 style=" color:white; text-align:center" style="font-weight: normal;width:25%;vertical-align:top">Datos del Paciente</h2>
    </div>
    <div>
        <table class="table-head-2" width="100%">
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:10%;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Nombre:</FONT></strong>
                </td>
                <td  colspan="2" style="font-weight: normal;width:40%;vertical-align:0px; text-align:left" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->mascota }}</FONT>
                </td>

                <td style="font-weight: normal;width:10%;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Especie:</FONT></strong>
                </td>
                <td style="font-weight: normal;width:10%;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->animal }}</FONT>
                </td>
                <td style="font-weight: normal;width:10%;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Raza:</FONT></strong>
                </td>
                <td  colspan="3" style="font-weight: normal;width:20%;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->raza }}</FONT>
                </td>
            </tr> 
            <tr>
                <td colspan=9>
                    <div style="border-top: 0.5px solid #69007F">
                    </div>
                </td>
            </tr>
            <tr  style=" text-align:left" >    
                <td  style="font-weight: normal;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Sexo:</FONT></strong>
                </td>
                <td colspan="2" style="font-weight: normal;vertical-align:0px" >
                    @if($detalles[0]->sexo != 0)
                        <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Macho</FONT>
                    @else
                        <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Hembra</FONT>
                    @endif

                </td>
                <td style="font-weight: normal;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Peso:</FONT></strong>
                </td>
                <td style="font-weight: normal;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->peso }}</FONT>
                </td>
                <td style="font-weight: normal;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Edad:</FONT></strong>
                </td>
                <td style="font-weight: normal;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->edad }}</FONT>
                </td>
                {{-- <td style="font-weight: normal;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Meses:</FONT></strong>
                </td>
                <td style="font-weight: normal;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->meses }}</FONT>
                </td> --}}
            </tr> 
            <tr>
                <td colspan=9>
                    <div style="border-top: 0.5px solid #69007F">
                    </div>
                </td>
            </tr>
            <tr  style=" text-align:left" >    
                <td  colspan=2 style="font-weight: normal;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Cirugias Previas:</FONT></strong>
                </td>
                <td style="font-weight: normal;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->cirugias }}</FONT>
                </td>
            </tr> 
            <tr>
                <td colspan=9>
                    <div style="border-top: 0.5px solid #69007F">
                    </div>
                </td>
            </tr>
            <tr  style=" text-align:left" >    
                <td  colspan=2 style="font-weight: normal;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Enfer. Previas:</FONT></strong>
                </td>
                <td style="font-weight: normal;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->enferm }}</FONT>
                </td>
            </tr> 
            <tr>
                <td colspan=9>
                    <div style="border-top: 0.5px solid #69007F">
                    </div>
                </td>
            </tr>
            <tr  style=" text-align:left" >    
                <td  colspan=2 style="font-weight: normal;vertical-align:top">
                    <strong ><FONT FACE="COURIER" style=" color: #69007F" SIZE=2>Vacunas Previas:</FONT></strong>
                </td>
                <td style="font-weight: normal;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->vacunas }}</FONT>
                </td>
            </tr> 
            <tr>
                <td colspan=9>
                    <div style="border-top: 0.5px solid #69007F">
                    </div>
                </td>
            </tr>
        </table>
        </table>
    </div>
    <div style="background-color: #69007F">
        <h2 style=" color:white; text-align:center" style="font-weight: normal;width:25%;vertical-align:top">Motivo de Consulta</h2>
    </div>
    <div>
        <table class="table-head table-borderless" style="padding-bottom: 7px" width="100%">
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:100%;vertical-align:0px" >
                    <FONT FACE="COURIER" style=" color: #69007F" SIZE=2>{{ $detalles[0]->motivo }}</FONT>
                </td>
            </tr> 
            <tr>
                <td colspan=6>
                    <div style="border-top: 0.5px solid #69007F">
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div style="background-color: #69007F">
        <h2 style=" color:white; text-align:center" style="font-weight: normal;width:25%;vertical-align:top">Vacunaciones</h2>
    </div>
    <div>
        <table class="table-head-2" width="100%">

            <tr  style=" text-align:left" >    
                <td rowspan="8" style="font-weight: normal;width:5%;vertical-align:top; padding-top: 0px;">
                    <label for="" style=" color: #69007F">PERRO</label>
                </td>
                <td style="font-weight: normal;width:25%;vertical-align:top; padding-top: 0px; " class="stilo">
                    @if($detalles[0]->parvovirus !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">PARVOVIRUS</label>
                    @else
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" > <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">PARVOVIRUS</label>
                    @endif
                </td>
                <td rowspan="8" style="font-weight: normal;width:5%;vertical-align:top; padding-top: 0px;  ">
                    <label for="" style=" color: #69007F">GATO</label>
                </td>
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->triple_felina !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">TRIPLE FELINA</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">TRIPLE FELINA</label>
                    @endif
                </td>
                <td rowspan="2" style="font-weight: normal;width:10%;vertical-align:top; padding-top: 0px;  ">
                    <label for="" style=" color: #69007F">DESPARACITACIÓN</label>
                </td>
                <td style="font-weight: normal;width:20%;width:1%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->desparacitacion !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SI</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SI</label>
                    @endif
                </td>

            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->hexavalente !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">HEXAVALENTE</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> HEXAVALENTE</label>
                    @endif
                </td>
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->rabia_gato !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">RABIA</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> RABIA</label>
                    @endif
                </td>
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->desparacitacion !=1)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NO</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> NO</label>
                    @endif
                </td>
            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->octavalente !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">OCTAVALENTE</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> OCTAVALENTE</label>
                    @endif
 
                </td>
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->ninguna_gato !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NINGUNA</label>
                    @else
                    <label style=" color: #69007F ; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> NINGUNA</label>
                    @endif
                </td>
                <td rowspan="6" style="font-weight: normal;width:10%;vertical-align:top; padding-top: 0px;  ">
                    <label for="" style=" color: #69007F">CUANDO: </label>
                </td>
                <td  rowspan="6" style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->desparacitacion_cuando == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('....................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->desparacitacion_cuando}}</label>
                    @endif
                </td>
            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->rabia_perro !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">RABIA</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> RABIA</label>
                    @endif
                </td>
                <td  rowspan="4" style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->obs_g !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">OBS</label>
                    <label style=" color: #69007F; padding-top: 0;vertical-align: top">: {{ $detalles[0]->obs_gato }}</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> OBS</label>
                    @endif
                </td>
            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->tos_perrera !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">TOS DE PERRERA</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> TOS DE PERRERA</label>
                    @endif
                </td>
            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->ninguna_perro !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NINGUNA</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> NINGUNA</label>
                    @endif
                </td>
            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    @if($detalles[0]->obs_p !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">OBS</label>
                    <label style=" color: #69007F; padding-top: 0;vertical-align: top">: {{ $detalles[0]->obs_perro }}</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> OBS</label>
                    @endif

                </td>
            </tr> 
            {{-- <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px; " >
                    <label style=" color: #69007F"> OBS</label>
                </td>
            </tr>  --}}
        </table>
    </div>
    <div style="background-color: #69007F">
        <h2 style=" color:white; text-align:center" style="font-weight: normal;width:25%;vertical-align:top">Constantes Fisiológicas</h2>
    </div>
    <div>
        <table class="table-head-2" width="100%">

            <tr  style=" text-align:left" >    
                <td rowspan="4" style="font-weight: normal;width:10%;vertical-align:top; padding-top: 0px ">
                    <label for="" style=" color: #69007F">TEMPERATURA: </label>
                </td>
                <td rowspan="4"style="font-weight: normal;width:8%;vertical-align:top; padding-top: 0px  " class="stilo">
                    @if($detalles[0]->temperatura == '0.00')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.........................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->temperatura}}</label>
                    @endif
                </td>
                <td rowspan="4" style="font-weight: normal;width:5%;vertical-align:top; padding-top: 0px   ">
                    <label for="" style=" color: #69007F">F.C.</label>
                </td>
                <td rowspan="4" style="font-weight: normal;width:8%;vertical-align:top; padding-top: 0px  " >
                    @if($detalles[0]->fc == '0.00')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.........................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->fc}}</label>
                    @endif
                </td>
                <td style="font-weight: normal;width:20%;width:1%;vertical-align:top; padding-top: 0px  " >
                    @if($detalles[0]->taquicardia !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">TAQUICARDIA</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">TAQUICARDIA</label>
                    @endif
                </td>
                <td rowspan="4" style="font-weight: normal;width:5%;vertical-align:top; padding-top: 0px   ">
                    <label for="" style=" color: #69007F">F.R.</label>
                </td>
                <td rowspan="4" style="font-weight: normal;width:8%;vertical-align:top; padding-top: 0px  " >
                    @if($detalles[0]->fr == '0.00')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.........................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->fr}}</label>
                    @endif
                </td>
                <td style="font-weight: normal;width:20%;width:1%;vertical-align:top; padding-top: 0px  " >
                    @if($detalles[0]->bueno_fr !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NORMAL</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NORMAL</label>
                    @endif
                </td>

            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px  " >
                    @if($detalles[0]->arritmia !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">ARRITMIA</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> ARRITMIA</label>
                    @endif
                </td>
                <td rowspan="3" style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px  " >
                    @if($detalles[0]->disnea !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">DISNEA</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> DISNEA</label>
                    @endif
                </td>
            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px  " >
                    @if($detalles[0]->bradicardia !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">BRADICARDIA</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> BRADICARDIA</label>
                    @endif
                </td>
            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px  " >
                    @if($detalles[0]->sin_alteracion !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SIN ALTERACIÓN</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> SIN ALTERACIÓN</label>
                    @endif
                </td>
            </tr> 
        </table>
    </div>
    {{-- MUCOSA --}}
    <div style="border-top: 0.5px solid #69007F">
    </div>
    <br>
    <div>
        <table class="table-head-2" width="100%">

            <tr  style=" text-align:left" >    
                <td rowspan="4" style="font-weight: normal;width:5%;vertical-align:top; padding-top: 0px;  ">
                    <label for="" style=" color: #69007F">MUCOSAS:</label>
                </td>
                <td style="font-weight: normal;width:8%;vertical-align:top; padding-top: 0px;   " class="stilo">
                    @if($detalles[0]->rosada !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">ROSADAS</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">ROSADAS</label>
                    @endif
                </td>
                <td rowspan="4" style="font-weight: normal;width:5%;vertical-align:top; padding-top: 0px;    ">
                    <label for="" style=" color: #69007F">APETITOS: </label>
                </td>
                <td style="font-weight: normal;width:10%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->normal_apetito !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NORMAL</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NORMAL</label>
                    @endif
                </td>
                <td rowspan="4" style="font-weight: normal;width:10%;vertical-align:top; padding-top: 0px;    ">
                    <label for="" style=" color: #69007F">HIDRATACIÓN: </label>
                </td>
                <td rowspan="3" style="font-weight: normal;width:10%;width:1%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->hidratacion == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->hidratacion}}</label>
                    @endif 
                </td>
                <td rowspan="4" style="font-weight: normal;width:10%;vertical-align:top; padding-top: 0px;    ">
                    <label for="" style=" color: #69007F">ESTADO NORMAL: </label>
                </td>
                <td style="font-weight: normal;width:20%;width:1%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->bueno_estado !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">BUENO</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">BUENO</label>
                    @endif
                </td>

            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->bueno_estado !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">PALIDAS</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> PALIDAS</label>
                    @endif
                </td>
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->palidas !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">DISMINUIDO</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> DISMINUIDO</label>
                    @endif
                </td>
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->regular !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">REGULAR</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> REGULAR</label>
                    @endif
                </td>
            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->ictericas !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">ICTERICAS</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> ICTERICAS</label>
                    @endif
                </td>
                <td rowspan="2" style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->anorexico !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">ANORÉXICO</label>
                    @else
                    <label style=" color: #69007F ; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> ANORÉXICO</label>
                    @endif
                </td>
                {{-- <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->moderada !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">MODERADA</label>
                    @else
                    <label style=" color: #69007F ; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> MODERADA</label>
                    @endif
                </td> --}}
                <td rowspan="2" style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->malo !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">MALO</label>
                    @else
                    <label style=" color: #69007F ; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> MALO</label>
                    @endif
                </td>
            </tr> 
            <tr  style=" text-align:left" >    
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px;   " >
                    @if($detalles[0]->cianotica !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">CIANÓTICA</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> CIANÓTICA</label>
                    @endif
                </td>
                <td style="font-weight: normal;width:20%;vertical-align:top; padding-top: 0px;   " >
                    {{-- @if($detalles[0]->marcada !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">MARCADA</label>
                    @else
                    <label style=" color: #69007F; padding-top: 0; vertical-align: top"><input type="checkbox" id="cbox1" value="first_checkbox" style=" color: #69007F"> MARCADA</label>
                    @endif --}}
                </td>
            </tr> 
        </table>
    </div>
    <div style="background-color: #69007F">
        <h2 style=" color:white; text-align:center" style="font-weight: normal;width:25%;vertical-align:top">Antecedentes</h2>
    </div>
    <div>
        <table class="table-head-2" width="100%">
            <tr  style=" text-align:left" >    
                <td rowspan="1" style="font-weight: normal;width:35%;vertical-align:top; padding-top: 0px">
                    <label for="" style=" color: #69007F">ANTECEDENTES DE ENFERMEDADES: </label>
                </td>
                <td rowspan="1"style="font-weight: normal;width:6%;vertical-align:top; padding-top: 0px " class="stilo">
                    @if($detalles[0]->enfermedades !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SI</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SI</label>
                    @endif

                </td>
                <td rowspan="1" style="font-weight: normal;width:10%;vertical-align:top; padding-top: 0px  ">
                    @if($detalles[0]->enfermedades !=1)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NO</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NO</label>
                    @endif
                </td>
                <td rowspan="1" style="font-weight: normal;width:2%;vertical-align:top; padding-top: 0px" >
                    <label for="" style=" color: #69007F">CUALES:</label>
                </td>
                <td style="font-weight: normal;width:30%;width:30%;vertical-align:top; padding-top: 0px" >
                    @if($detalles[0]->enfermedades_cuales == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('...........................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->enfermedades_cuales}}</label>
                    @endif
                </td>
                <td rowspan="1" style="font-weight: normal;width:2%;vertical-align:top; padding-top: 0px">
                    <label for="" style=" color: #69007F">CUANDO:</label>
                </td>
                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px " >
                    @if($detalles[0]->enfermedades_cuando == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('..........................................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->enfermedades_cuando}}</label>
                    @endif

                </td>

            </tr> 
            <tr  style=" text-align:left" >    
                <td rowspan="1" style="font-weight: normal;width:35%;vertical-align:top; padding-top: 0px">
                    <label for="" style=" color: #69007F">ANTECEDENTES DE ENFERMEDADES: </label>
                </td>
                <td rowspan="1"style="font-weight: normal;width:6%;vertical-align:top; padding-top: 0px " class="stilo">
                    @if($detalles[0]->cirugia !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SI</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SI</label>
                    @endif
                </td>
                <td rowspan="1" style="font-weight: normal;width:10%;vertical-align:top; padding-top: 0px  ">
                    @if($detalles[0]->cirugia !=1)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NO</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">NO</label>
                    @endif
                </td>
                <td rowspan="1" style="font-weight: normal;width:2%;vertical-align:top; padding-top: 0px " >
                    <label for="" style=" color: #69007F">CUALES:</label>
                </td>
                <td style="font-weight: normal;width:30%;width:30%;vertical-align:top; padding-top: 0px " >
                    @if($detalles[0]->cirugia_cuando == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('...........................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->cirugia_cuando}}</label>
                    @endif
                </td>
                <td rowspan="1" style="font-weight: normal;width:2%;vertical-align:top; padding-top: 0px  ">
                    <label for="" style=" color: #69007F">CUANDO:</label>
                </td>
                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px " >
                    @if($detalles[0]->cirugia_cuales == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('..........................................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->cirugia_cuales}}</label>
                    @endif
                </td>

            </tr>             
        </table>
    </div>
    <br><br><br><br><br>
    <div style="background-color: #69007F">
        <h2 style=" color:white; text-align:center" style="font-weight: normal;width:25%;vertical-align:top">Órganos de Sentido</h2>
    </div>
    <div>
        <table class="table-head-2" width="100%" >
            <tr  style=" text-align:left" >    
                <td rowspan="1" style="font-weight: normal;width:30%;vertical-align:top; padding-top: 0px;">
                    <label for="" style=" color: #69007F">1) OCULAR: </label>
                </td>
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px;" class="stilo">
                    @if($detalles[0]->ocular == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.........................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->ocular}}</label>
                    @endif
                </td>
                <td rowspan="1" style="font-weight: normal;width:30%;vertical-align:top; padding-top: 0px; ">
                    <label for="" style=" color: #69007F">4) PIEL Y ANEXOS: </label>
                </td>
                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px;" >
                    @if($detalles[0]->piel_anexo == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.........................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->piel_anexo}}</label>
                    @endif
                </td>
                <td  rowspan="3"style="font-weight: normal;width:%;width:25%;vertical-align:top; padding-top: 0px;" >
                    <label for="" style=" color: #69007F">7) PREPUCIAL: </label>
                </td>
                <td rowspan="3" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px; ">
                    @if($detalles[0]->prepucial == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.........................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->prepucial}}</label>
                    @endif
                </td>

            </tr> 
            <tr  style=" text-align:left" >    
                <td rowspan="1" style="font-weight: normal;width:30%;vertical-align:top; padding-top: 0px;">
                    <label for="" style=" color: #69007F">2) NARIZ: </label>
                </td>
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px;" class="stilo">
                    @if($detalles[0]->nariz == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.........................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->nariz}}</label>
                    @endif

                </td>
                <td rowspan="1" style="font-weight: normal;width:30%;vertical-align:top; padding-top: 0px; ">
                    <label for="" style=" color: #69007F">5) OÍDOS: </label>
                </td>
                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px;" >
                    @if($detalles[0]->oidos == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.........................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->oidos}}</label>
                    @endif
                </td>

            </tr> 
            <tr  style=" text-align:left" >    
                <td rowspan="1" style="font-weight: normal;width:10%;vertical-align:top; padding-top: 0px;">
                    <label for="" style=" color: #69007F">3) BUCAL: </label>
                </td>
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px;" class="stilo">
                    @if($detalles[0]->bucal == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.........................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->bucal}}</label>
                    @endif
                </td>
                <td rowspan="1" style="font-weight: normal;width:30%;vertical-align:top; padding-top: 0px; ">
                    <label for="" style=" color: #69007F">6) VULVAR: </label>
                </td>
                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px;" >
                    @if($detalles[0]->vulvar == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.........................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->vulvar}}</label>
                    @endif
                </td>
            </tr> 
        </table>
    </div>
    <div style="background-color: #69007F">
        <h2 style=" color:white; text-align:center" style="font-weight: normal;width:25%;vertical-align:top">Datos Fisiológico</h2>
    </div>
    <div>
        <table class="table-head-2" width="100%">
            <tr  style=" text-align:left" >    
                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px">
                    <label for="" style=" color: #69007F"><strong>APARATO DIGESTIVO</strong></label>
                    
                </td>
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px">
                    <label for="" style=" color: #69007F"><strong>APARATO RESPIRATORIO</strong></label>
                    
                </td>
            </tr> 
            <tr  style=" text-align:left" >    
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px ">
                    @if($detalles[0]->digestivo_sin_alteracion !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SIN ALTERACIÓN</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SIN ALTERACIÓN</label>
                    @endif
                </td>
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px ">
                    @if($detalles[0]->respiratorio_sin_alteracion !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SIN ALTERACIÓN</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SIN ALTERACIÓN</label>
                    @endif
                </td>

            </tr> 
            <tr  style=" text-align:left" >    
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px ">
                    @if($detalles[0]->digestivo_obs == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.......................................................................................................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->digestivo_obs}}</label>
                    @endif 
                </td>
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px ">
                    @if($detalles[0]->respiratorio_obs == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.......................................................................................................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->respiratorio_obs}}</label>
                    @endif 
                </td>

            </tr> 
            <tr  style=" text-align:left" >    
                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px">
                    <label for="" style=" color: #69007F"><strong>APARATO GENITO URINARIO</strong></label>
                    
                </td>
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px">
                    <label for="" style=" color: #69007F"><strong>APARATO NERVIOSO</strong></label>
                    
                </td>
            </tr> 

            <tr  style=" text-align:left" >    
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px ">
                    @if($detalles[0]->urinario_sin_alteracion !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SIN ALTERACIÓN</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SIN ALTERACIÓN</label>
                    @endif          
                </td>
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px ">
                    @if($detalles[0]->nervioso_sin_alteracion !=0)
                    <input type="checkbox" id="cbox2" value="true" style=" color: #69007F" checked="checked"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SIN ALTERACIÓN</label>
                    @else
                    <input type="checkbox" id="cbox2" value="second_checkbox" style=" color: #69007F"> <label for="cbox2" style=" color: #69007F; padding-top: 0; vertical-align: top">SIN ALTERACIÓN</label>
                    @endif 
                </td>

            </tr> 
            <tr  style=" text-align:left" >    
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px ">
                    @if($detalles[0]->urinario_obs == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.......................................................................................................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->urinario_obs}}</label>
                    @endif      
                </td>
                <td rowspan="1"style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px ">
                    @if($detalles[0]->nervioso_obs == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('.......................................................................................................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->nervioso_obs}}</label>
                    @endif   
                </td>

            </tr> 
        </table>
    </div>
    <div style="background-color: #69007F" >
        <h2 style=" color:white; text-align:center" style="font-weight: normal;width:25%;vertical-align:top">Exámenes Complementarios</h2>
    </div>
    <div>
        <table class="table-head-2" width="100%">
            <tr  style=" text-align:left" >    
                <td rowspan="1" style="font-weight: normal;width:2%;vertical-align:top; padding-top: 0px" >
                    <label for="" style=" color: #69007F">MUESTRA:</label>
                </td>
                <td style="font-weight: normal;width:30%;width:30%;vertical-align:top; padding-top: 0px">
                    @if($detalles[0]->muestra == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('...................................................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->muestra}}</label>
                    @endif      
                </td>
                <td rowspan="1" style="font-weight: normal;width:15%;vertical-align:top; padding-top: 0px">
                    <label for="" style=" color: #69007F">EXAMEN SOLICITADO:</label>
                </td>
                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px">
                    @if($detalles[0]->examenes_solicitado == '')
                    <label style=" color: #69007F" aria-placeholder="............">{{__('................................................................................................................................')}}</label>
                    @else
                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->examenes_solicitado}}</label>
                    @endif     
                </td>

            </tr>           
        </table>
    </div>
    <div style="background-color: #69007F">
        <h2 style=" color:white; text-align:center" style="font-weight: normal;width:25%;vertical-align:top">Tratamiento Indicado</h2>
    </div>
    <div>
        <table class="table-head-2" width="100%">
            <tr  style=" text-align:left" >    
                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px" >
                    <div >
                        <table class="table-head-2" width="100%" style=" border: 1px solid #69007F">
                            <tr>
                                <td rowspan="1" style="font-weight: normal;width:2%;vertical-align:top; padding-top: 0px; " >
                                    <label for="" style=" color: #69007F">FECHA:</label>
                                </td>
                                <td style="font-weight: normal;width:30%;width:30%;vertical-align:top; padding-top: 0px; " >
                                    @if($detalles[0]->fecha1 == '0000-00-00')
                                    <label style=" color: #69007F" aria-placeholder="............">{{__('.................................................')}}</label>
                                    @else
                                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->fecha1}}</label>
                                    @endif     
                                </td>
                                <td rowspan="1" style="font-weight: normal;width:15%;vertical-align:top; padding-top: 0px;  ">
                                    <label for="" style=" color: #69007F">HRS.</label>
                                </td>
                                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px; " >
                                    @if($detalles[0]->hora1 == '00:00:00')
                                    <label style=" color: #69007F" aria-placeholder="............">{{__('................................................................................................')}}</label>
                                    @else
                                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->hora1}}</label>
                                    @endif     
                                </td>
                                <td rowspan="1" style="font-weight: normal;width:15%;vertical-align:top; padding-top: 0px;  ">
                                    <label for="" style=" color: #69007F">Tº</label>
                                </td>
                                <td rowspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px; " >
                                    @if($detalles[0]->t1 == '0.00')
                                    <label style=" color: #69007F" aria-placeholder="............">{{__('................................................................................................')}}</label>
                                    @else
                                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->t1}}</label>
                                    @endif    
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="1" style="font-weight: normal;width:2%;vertical-align:top; padding-top: 0px; " >
                                    <label for="" style=" color: #69007F">DR.</label>
                                </td>
                                <td colspan="3" style="font-weight: normal;width:30%;width:30%;vertical-align:top; padding-top: 0px; " >
                                    @if($detalles[0]->dr1 == '')
                                    <label style=" color: #69007F" aria-placeholder="............">{{__('...................................................................................................................................................................................................................................................................')}}</label>
                                    @else
                                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->dr1}}</label>
                                    @endif    
                                </td>
                                <td  colspan="1" style="font-weight: normal;width:15%;vertical-align:top; padding-top: 0px;  ;text-align: right ">
                                    {{-- <label for="" style=" color: #69007F ;">COSTO:</label> --}}
                                </td>
                                <td colspan="1" style="font-weight: normal;width:50%;vertical-align:top; padding-top: 0px; " >
                                    {{-- @if($detalles[0]->costo1 == '0.00')
                                    <label style=" color: #69007F" aria-placeholder="............">{{__('......................................................................................')}}</label>
                                    @else
                                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->costo1}}</label>
                                    @endif     --}}
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="1" style="font-weight: normal;width:2%;vertical-align:top; padding-top: 0px; " >
                                    <label for="" style=" color: #69007F">OBS.</label>
                                </td>
                                <td colspan="5" style="font-weight: normal;width:30%;width:30%;vertical-align:top; padding-top: 0px; " >
                                    @if($detalles[0]->observaciones1 == '')
                                    <label style=" color: #69007F" aria-placeholder="............">{{__('...................................................................................................................................................................................................................................................................')}}</label>
                                    @else
                                    <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->observaciones1}}</label>
                                    @endif  
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    @if($detalles[0]->primer_dia == '')
                    <div>
                        <label style=" color: #69007F" aria-placeholder="............">{{__('...................................................................................................................................................................................................................................................................................')}}</label>
                    </div>
                    <div>
                        <label style=" color: #69007F" aria-placeholder="............">{{__('...................................................................................................................................................................................................................................................................................')}}</label>
                    </div>
                    @else
                        <label style=" color: #69007F" aria-placeholder="............">{{$detalles[0]->primer_dia}}</label>
                    @endif  
                </td>

            </tr>           
        </table>
    </div>
</header>
<body>
    @php
        $numero = 1;
        $totalCaja = 0;
        $totalBanco = 0;
        $totalTarjeta = 0;
        $origenVM = "";
        $color5 = "#eff2d5";
        $defecto = "defecto.png";
        $color6 = "white";
        $color7 = "";
        $saldo_anterior = 0;
        $pago = 0;
        $saldo_por_pagar= 0;
        $tamaño = 1;
        $tamaño2 = 1;
        $x1 = 0;
        $xx=0;
        $x5=6;

        
    @endphp


    </body>
</html>

