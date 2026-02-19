@extends('index')
@section('content')
@php $user = Auth::user()->id_grupo @endphp
    <template v-if="{{$user}} != 1">
        <template v-if="menu==0">
            <principal-component></principal-component>
        </template>
    </template>
    <template v-else>
        <template v-if="menu==0">
            {{-- <frm-articulo></frm-articulo> --}}
            <frm-dashboard></frm-dashboard>
        </template>
    </template>

    <template v-if="menu==1">
        <frm-cliente></frm-cliente>
    </template>

    <template v-if="menu==2">
        <frm-personal></frm-personal>
    </template>

    <template v-if="menu==3">
        <frm-proveedor></frm-proveedor>
    </template>

    <template v-if="menu==4">
        <frm-cargo></frm-cargo>
    </template>

    <template v-if="menu==5">
        <frm-grupo></frm-grupo>
    </template>

    <template v-if="menu==6">
        <frm-permiso></frm-permiso>
    </template>

    <template v-if="menu==7">
        <frm-categoria></frm-categoria>
    </template>

    <template v-if="menu==8">
        <frm-articulo></frm-articulo>
    </template>

    <template v-if="menu==9">
        <frm-inventario></frm-inventario>
    </template>

    <template v-if="menu==10">
        <frm-compra></frm-compra>
    </template>

    <template v-if="menu==11">
        <frm-motivo-gasto></frm-motivo-gasto>
    </template>

    <template v-if="menu==12">
        <frm-gasto></frm-gasto>
    </template>

    <template v-if="menu==13">
        <frm-venta></frm-venta>
    </template>

    <template v-if="menu==14">
        <frm-ajuste></frm-ajuste>
    </template>

    <template v-if="menu==15">
        <frm-inventario></frm-inventario>
    </template>

    <template v-if="menu==16">
        <frm-historial></frm-historial>
    </template>

    <template v-if="menu==17">
        <frm-historialCompra></frm-historialCompra>
    </template>

    <template v-if="menu==18">
        <frm-servicio></frm-servicio>
    </template>

    <template v-if="menu==19">
        <frm-historialservicio></frm-historialservicio>
    </template>

    <template v-if="menu==20">
        <frm-miempresa></frm-miempresa>
    </template>

    <template v-if="menu==21">
        <frm-usuario></frm-usuario>
    </template>

    <template v-if="menu==22">
        <frm-grupo-usuario></frm-grupo-usuario>
    </template>

    <template v-if="menu==23">
        <frm-traspaso></frm-traspaso>
    </template>

    <template v-if="menu==24">
        <frm-articulo-servicio></frm-articulo-servicio>
    </template>

    <template v-if="menu==25">
        <frm-pago></frm-pago>
    </template>

    <template v-if="menu==26">
        <frm-caja></frm-caja>
    </template>

    <template v-if="menu==27">
        <frm-historial-arqueo></frm-historial-arqueo>
    </template>

    <template v-if="menu==28">
        <frm-reportes></frm-reportes>
    </template>

    <template v-if="menu==50">
        <frm-unidad></frm-unidad>
    </template>

    <template v-if="menu==70">
        <frm-pago-compra></frm-pago-compra>
    </template>

    <template v-if="menu==90">
        <frm-marca></frm-marca>
    </template>

    <template v-if="menu==83">
        <frm-lote></frm-lote>
    </template>


    {{-- Historial Clinico --}}
    
    <template v-if="menu==73">
        <frm-paciente></frm-paciente>
    </template>
    <template v-if="menu==74">
        <frm-animal></frm-animal>
    </template>
    <template v-if="menu==75">
        <frm-vacuna-control-vacuna></frm-vacuna-control-vacuna>
    </template>
    <template v-if="menu==76">
        <frm-vacuna-historial-vacuna></frm-vacuna-historial-vacuna>
    </template>
    <template v-if="menu==77">
        <frm-antiparasitario></frm-antiparasitario>
    </template>
    <template v-if="menu==78">
        <frm-historial-antiparasitario></frm-historial-antiparasitario>
    </template>
    <template v-if="menu==79">
        <frm-menu-vacuna></frm-menu-vacuna>
    </template>
    <template v-if="menu==80">
        <frm-menu-historial></frm-menu-historial>
    </template>
    <template v-if="menu==81">
        <frm-historial-clinico></frm-historial-clinico>
    </template>
    <template v-if="menu==82">
        <frm-detalle-historia></frm-detalle-historia>
    </template>




    <!-- Tienda Primera -->
    
    <template v-if="menu==33">
        <frm-tp1-venta></frm-tp1-venta>
    </template>

    <template v-if="menu==34">
        <frm-tp1-historialventa></frm-tp1-historialventa>
    </template>

    <template v-if="menu==35">
        <frm-tp1-servicio></frm-tp1-servicio>
    </template>

    <template v-if="menu==36">
        <frm-tp1-historialservicio></frm-tp1-historialservicio>
    </template>

    <template v-if="menu==37">
        <frm-tp1-pago></frm-tp1-pago>
    </template>

    <template v-if="menu==38">
        <frm-tp1-pago-servicio></frm-tp1-pago-servicio>
    </template>

    <template v-if="menu==59">
        <frm-tp1-cotizacion></frm-tp1-cotizacion>
    </template>

    <template v-if="menu==60">
        <frm-tp1-historialcotizacion></frm-tp1-historialcotizacion>
    </template>
    <template v-if="menu==71">
        <frm-tp1-paquete></frm-tp1-paquete>
    </template>
    <template v-if="menu==72">
        <frm-tp1-historialpaquete></frm-tp1-historialpaquete>
    </template>
    <template v-if="menu==91">
        <frm-tp1-historialcliente></frm-tp1-historialcliente>
    </template>
    <template v-if="menu==92">
        <frm-historial-producto></frm-historial-producto>
    </template>


    <!-- Fin Tienda Primera -->

    <!-- Tienda Segunda -->
    
    <template v-if="menu==43">
        <frm-ts2-venta></frm-ts2-venta>
    </template>

    <template v-if="menu==44">
        <frm-ts2-historialventa></frm-ts2-historialventa>
    </template>

    <template v-if="menu==45">
        <frm-ts2-servicio></frm-ts2-servicio>
    </template>

    <template v-if="menu==46">
        <frm-ts2-historialservicio></frm-ts2-historialservicio>
    </template>

    <template v-if="menu==47">
        <frm-ts2-pago></frm-ts2-pago>
    </template>

    <template v-if="menu==48">
        <frm-ts2-pago-servicio></frm-ts2-pago-servicio>
    </template>

    <template v-if="menu==61">
    <frm-tp2-cotizacion></frm-tp2-cotizacion>
    </template>
    
    <template v-if="menu==62">
        <frm-tp2-historialcotizacion></frm-tp2-historialcotizacion>
    </template>

    <!-- Fin Tienda Segunda -->

    <!-- Tienda Tercera -->
    
    <template v-if="menu==53">
        <frm-tt3-venta></frm-tt3-venta>
    </template>

    <template v-if="menu==54">
        <frm-tt3-historialventa></frm-tt3-historialventa>
    </template>

    <template v-if="menu==55">
        <frm-tt3-servicio></frm-tt3-servicio>
    </template>

    <template v-if="menu==56">
        <frm-tt3-historialservicio></frm-tt3-historialservicio>
    </template>

    <template v-if="menu==57">
        <frm-tt3-pago></frm-tt3-pago>
    </template>

    <template v-if="menu==58">
        <frm-tt3-pago-servicio></frm-tt3-pago-servicio>
    </template>

    <template v-if="menu==63">
        <frm-tp3-cotizacion></frm-tp3-cotizacion>
        </template>
        
    <template v-if="menu==64">
        <frm-tp3-historialcotizacion></frm-tp3-historialcotizacion>
    </template>

    


    <!-- Fin Tienda Tercera -->


@endsection