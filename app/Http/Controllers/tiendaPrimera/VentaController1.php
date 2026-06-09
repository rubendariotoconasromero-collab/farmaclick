<?php

namespace App\Http\Controllers\tiendaPrimera;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\OrdenServicio;
use App\Models\Control;
use App\Models\Articulo;
use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\DetalleVentaPaquete;
use App\Models\Ajuste;
use App\Models\Pago;
use App\Models\CXCobrar;
use App\Models\ArqueoCaja;
use App\Models\Cliente;
use App\Models\Auxiliar;
use App\Http\Controllers\BitacoraController;
use DB;
use DateTime;

class VentaController1 extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $id_usuario = $request->id_usuario;
        if($buscar==''){
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->join('grupo','users.id_grupo','=','grupo.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio','venta.total_efectivo','venta.total_deposito','grupo.id as grupo','venta.id_usuario','venta.id_usuario')
            //->where('venta.tipo_venta','=','Venta Directa')
            ->whereNotBetween('venta.tipo_venta', ['Venta Directa', 'Venta Cotizacion','Venta Control Vacuna','Venta Antiparasitario'])
            //->orWhere('venta.tipo_venta','=','Venta Cotizacion')
            ->where('venta.id_tienda','=',1)
            //->whereBetween('fecha_final', [$fecha1, $fecha2])
            // ->whereNotBetween('venta.estado', ['Entregado', 'Cancelado'])
            ->where('venta.estado','!=','Anulado')

            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        else{
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->join('grupo','users.id_grupo','=','grupo.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio','venta.total_efectivo','venta.total_deposito','grupo.id as grupo','venta.id_usuario')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->whereNotBetween('venta.tipo_venta', ['Venta Directa', 'Venta Cotizacion'])
            ->where('venta.estado','!=','Anulado')
            ->where('venta.id_tienda','=',1)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        return $obj;
    }
    public function indexContado(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $id_usuario = $request->id_usuario;
        if($buscar==''){
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->join('grupo','users.id_grupo','=','grupo.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio','grupo.id as grupo','venta.id_usuario','venta.total_efectivo','venta.total_deposito')
            //->where('venta.tipo_venta','=','Venta Directa')
            ->whereNotBetween('venta.tipo_venta', ['Venta Directa', 'Venta Cotizacion','Venta Control Vacuna','Venta Antiparasitario'])
            //->orWhere('venta.tipo_venta','=','Venta Cotizacion')
            ->where('venta.id_tienda','=',1)
            ->where('venta.id_tipo_pago','=',1)
            // ->where('venta.estado','=','Entregado')
            ->where('cliente.estado','=',1)
            ->where('venta.estado','!=','Anulado')
            //->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        else{
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->join('grupo','users.id_grupo','=','grupo.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio','grupo.id as grupo','venta.total_efectivo','venta.total_deposito')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->whereNotBetween('venta.tipo_venta', ['Venta Directa', 'Venta Cotizacion'])
            ->where('venta.id_tienda','=',1)
            ->where('venta.id_tipo_pago','=',1)
            ->where('cliente.estado','=',1)
            ->where('venta.estado','!=','Anulado')
            //->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        return $obj;
    }
    public function indexCredito(Request $request){
        $id_cliente = $request->id_cliente;
        //dd($id_cliente);
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $id_usuario = $request->id_usuario;
        if($buscar==''){
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->join('grupo','users.id_grupo','=','grupo.id')
            ->join('pago','venta.id','=','pago.id_venta')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio','grupo.id as grupo','venta.id_usuario','venta.total_efectivo','venta.total_deposito','pago.saldo')
            //->where('venta.tipo_venta','=','Venta Directa')
            ->whereNotBetween('venta.tipo_venta', ['Venta Directa', 'Venta Cotizacion','Venta Control Vacuna','Venta Antiparasitario'])
            //->orWhere('venta.tipo_venta','=','Venta Cotizacion')
            ->where('venta.id_tienda','=',1)
            ->where('venta.id_tipo_pago','=',2)
            ->where('venta.estado','!=','Anulado')
            //->where('venta.estado','=','Cancelado')
            ->where('cliente.id','=',$id_cliente)
           // ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(1000);
        }
        else{
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->join('grupo','users.id_grupo','=','grupo.id')
            ->join('pago','venta.id','=','pago.id_venta')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio','grupo.id as grupo','venta.id_usuario','venta.total_efectivo','venta.total_deposito','pago.saldo')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->whereNotBetween('venta.tipo_venta', ['Venta Directa', 'Venta Cotizacion'])
            ->where('venta.estado','!=','Anulado')
            ->where('venta.id_tienda','=',1)
            ->where('venta.id_tipo_pago','=',2)
            ->where('users.id','=',\Auth::user()->id)
            ->where('cliente.id','=',$id_cliente)
            ->orderBy('venta.id','desc')->paginate(1000);
        }
        return $obj;
    }
    public function indexServicio(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where('venta.tipo_venta','=','Venta Servicio')
            ->where('venta.id_tienda','=',1)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        else{
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('venta.tipo_venta','=','Venta Servicio')
            ->where('venta.id_tienda','=',1)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        return $obj;
    }
    public function indexControl(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where('venta.tipo_venta','=','Venta Control Vacuna')
            ->where('venta.estado','!=','Entregado')
            ->where('venta.id_tienda','=',1)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        else{
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('venta.tipo_venta','=','Venta Control Vacuna')
            ->where('venta.estado','!=','Entregado')
            ->where('venta.id_tienda','=',1)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        return $obj;
    }
    public function indexAntiparasitario(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where('venta.tipo_venta','=','Venta Antiparasitario')
            ->where('venta.estado','!=','Entregado')
            ->where('venta.id_tienda','=',1)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        else{
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('venta.tipo_venta','=','Venta Antiparasitario')
            ->where('venta.estado','!=','Entregado')
            ->where('venta.id_tienda','=',1)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        return $obj;
    }
    private function actualizarCaja($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.registro_venta', $monto);
    }
    private function actualizarCajaDeposito($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.registro_venta', $monto);
    }
    private function actualizarCajaContado($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.total_contado', $monto);
    }
    private function actualizarCajaContadoDeposito($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.total_contado_deposito', $monto);
    }
    private function descontarCaja($monto,$id_usuario){
        //DD($id_usuario);
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.registro_venta', $monto);
    }
    private function descontarCajaDeposito($monto,$id_usuario){
        //DD($id_usuario);
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.registro_venta', $monto);
    }
    private function descontarCajaContado($monto,$id_usuario){
        //DD($id_usuario);
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.total_contado', $monto);
    }
    private function descontarCajaContadoDeposito($monto,$id_usuario){
        //DD($id_usuario);
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.total_contado_deposito', $monto);
    }
    private function descontarCajaCredito($monto,$id_usuario){
        //DD($id_usuario);
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.total_credito', $monto);
    }
    private function descontarCajaCreditoDeposito($monto,$id_usuario){
        //DD($id_usuario);
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.total_credito_deposito', $monto);
    }
    private function actualizarStock($id_lote,$cantVenta){
        //dd($cantVenta);
        DB::table('lote')->where('lote.id','=',$id_lote)
        ->decrement('cantidad', $cantVenta);
    }
    public function guardar(Request $request){
        try{
                DB::beginTransaction();
                $registro_venta = $request->total;
                $registro_venta_efectivo = $request->total_efectivo;
                $registro_venta_deposito = $request->total_deposito;
                $id_usuario=\Auth::user()->id;

                $id_cliente = $request->id_cliente;
                /*if($id_cliente==0){
                    $buscar_cliente = DB::select("SELECT id FROM cliente WHERE cliente.nombre = '$request->cliente'");
                    if($buscar_cliente == []){
                        $cliente= new Cliente();
                        $cliente->nombre=$request->cliente;
                        $cliente->matricula=0;
                        $cliente->telefono=0;
                        $cliente->direccion='';
                        $cliente->descripcion='';
                        $cliente->descuento=1;
                        $cliente->estado=1;
                        $cliente->save();

                        $var2=DB::select("SELECT  MAX(id) as id_cliente from cliente");
                        //dd($var2);
                        $id_cliente=$var2[0]->id_cliente;
                    } else {
                        $id_cliente = $buscar_cliente[0]->id;
                    }
                }*/
                // Si viene 0, significa que es un cliente nuevo o hay que buscarlo por nombre
                if ($id_cliente == 0) {
                    
                    // Obtenemos el nombre, si viene vacío forzamos 'SN' (protección extra al frontend)
                    $nombreCliente = trim($request->cliente) === '' ? 'SN' : trim($request->cliente);

                    // USAMOS ELOQUENT firstOrCreate:
                    // Esto busca un cliente con ese nombre. 
                    // Si NO existe, lo crea con los datos del segundo array.
                    // Si SÍ existe, simplemente devuelve el registro encontrado.
                    $cliente = Cliente::firstOrCreate(
                        ['nombre' => $nombreCliente], // Condición de búsqueda
                        [ 
                            // Datos para crear si no existe
                            'matricula' => 0,
                            'telefono' => 0, // Opcional: $request->telefono si lo enviaras
                            'direccion' => '',
                            'descripcion' => 'Cliente creado desde Venta',
                            'descuento' => 1, // Ojo: pusiste 1 antes, ¿es 1% o true? Si es % pon lo correcto.
                            'estado' => 1
                        ]
                    );

                    // Asignamos el ID encontrado o creado a la variable para usarla en la venta
                    $id_cliente = $cliente->id;
                }

                $venta = new Venta();
                $venta->fecha=$request->fecha;
                $venta->sub_total=$request->sub_total;
                $venta->descuento=$request->descuento;
                $venta->total=$request->total;
                $venta->estado=$request->estado;
                $venta->id_cliente=$id_cliente;
                $venta->tipo_venta=$request->tipo_venta;
                $venta->id_orden_servicio=$request->id_servicio ? $request->id_servicio : null;
                if($request->id_forma_pago == 6){
                $venta->total_efectivo = $request->total_efectivo == '' ? '0' : $request->total_efectivo;
                $venta->total_deposito = $request->total_deposito == '' ? '0' : $request->total_deposito;
                }
                if($request->id_forma_pago == 2)
                {
                    $venta->total_efectivo =$request->total;
                    $venta->total_deposito = $request->total_deposito == '' ? '0' : $request->total_deposito;
                } 
                if($request->id_forma_pago == 3)
                {
                    $venta->total_efectivo = $request->total_efectivo == '' ? '0' : $request->total_efectivo;
                    $venta->total_deposito =$request->total;
                }
                if($request->id_forma_pago == 4)
                {
                    $venta->total_efectivo = $request->total_efectivo == '' ? '0' : $request->total_efectivo;
                    $venta->total_deposito =$request->total;
                }
                if($request->id_forma_pago == 5)
                {
                    $venta->total_efectivo = $request->total_efectivo == '' ? '0' : $request->total_efectivo;
                    $venta->total_deposito =$request->total;
                }
                $venta->id_tipo_pago=$request->id_tipo_pago;
                $venta->id_tienda=1;
                if($request->id_tipo_pago == 1) {
                    $venta->id_forma_pago=$request->id_forma_pago;
                }else if ($request->id_tipo_pago == 2) {
                    $venta->id_forma_pago=$request->id_forma_pago;
                } else {
                    //
                }
                //$venta->id_pago=$pago->id;
                $venta->id_usuario=$id_usuario;
                $venta->efectivo=$request->efectivo;
                $venta->cambio=$request->cambio;

                $venta->save();
                $correlativo = 0;
                $objdate = new DateTime();
                $fechaactual= $objdate->format('Y-m-d');
                $hora= $objdate->format('H:i:s');

                $year = $objdate->format('y');
                $correlativo = $this->correlativoControl();
                $control = new control();
                $control->tabla = $request->tabla = "Venta";
                $control->id_tabla = $venta->id;
                $control->codigo = $request->codigo = 'VP-'.strval($correlativo + 1);
                $control->fecha = $fechaactual;
                $control->save();

                if($request->id_tipo_pago == 1) {
                    $pago = new Pago();
                    $pago->id = $venta->id;
                    $pago->fecha = $request->fecha;
                    $pago->fecha_final = $request->fecha_final;
                    $pago->monto = $request->total;
                    $pago->saldo = $request->total;
                    $pago->descripcion = "";
                    $pago->id_tipo_pago = $request->id_tipo_pago;
                    $pago->id_venta = $venta->id;
                    $pago->save();

                } else if($request->id_tipo_pago == 2) {

                    $pago = new Pago();
                    $pago->id = $venta->id;
                    $pago->fecha = $request->fecha;
                    $pago->fecha_final = $request->fecha_final;
                    $pago->monto = $request->total;
                    $pago->saldo = $request->total;
                    $pago->descripcion = $request->descripcion = "";
                    $pago->id_tipo_pago = $request->id_tipo_pago;
                    $pago->id_venta = $venta->id;
                    $pago->save();

                    $cxcobrar = new CXCobrar();
                    $cxcobrar->fecha = $request->fecha;
                    $cxcobrar->monto_total = $request->monto_total;
                    $cxcobrar->descripcion = $request->descripcion_pago;
                    $cxcobrar->saldo = $request->monto_total;
                    $cxcobrar->id_pago = $venta->id;
                    $cxcobrar->id_usuario = $id_usuario;
                    $cxcobrar->id_forma_pago = 0;
                    $cxcobrar->save();

                } else {
                    //
                }

                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;

                $stock_producto_paquetes = $request->stock_producto_paquete;
                foreach($stock_producto_paquetes as $ep=>$stock){
                    if($stock['tipo_producto']=='Producto Venta'){


                    //  Descontar Lote
                    $id_prod = $stock['id_articulo'];
                    //dd($id_prod);
                    $variable=DB::select("SELECT id as id from lote where estado!=0 and cantidad>0 and  id_producto= $id_prod");
                    $var = $variable;
                    $var2 = 0;
                    $var3 = 0;
                    $var5 = 0;
                    $var7 = $stock['cantidad_aux'];
                    $array = json_decode(json_encode($var), true);

                    //dd($variable);
                       foreach ($array as $i => $value) {
                           $var2=$array[$i];
                           $gg = implode(" ",$var2);
                           $id_lote = intval($gg);
                           $var4=DB::select("SELECT cantidad as cantidad from lote where id=$gg");
                           $var6 = $var4;
                           $array2 = json_decode(json_encode($var6), true);
                           $var5=$array2[0];
                           $ggc = implode(" ",$var5);
                           $valor_lote = intval($ggc);
                           //dd($valor_lote);
                           if($var7 != 0)
                           {
                               if($var7>$valor_lote)
                               {
                                   DB::table('lote')->where('lote.id','=',$id_lote)->decrement('cantidad',$valor_lote);
                                   DB::table('lote')->where('lote.id','=',$id_lote)->update(['estado' => 2]);

                                   $var7=$var7-$valor_lote;

                               }
                               else
                               {
                                   DB::table('lote')->where('lote.id','=',$id_lote)->decrement('cantidad',$var7);
                                   DB::table('lote')->where('lote.id','=',$id_lote)->update(['estado' => 2]);

                                   if($request->tipo_venta=='Venta Directa'){
                                    $consulta = DB::select('CALL stock(?)', [$stock['id_tienda_articulo']]);
                                } else {

                                }
                                    $var7=0;
                               }
                           }
                       }

                    }
                }
                $aux=1;
                //$contadorS=0;
                foreach($detalles as $ep=>$det){
                    if($det['producto_venta']=='Venta Producto'){
                        $obj = new DetalleVenta();
                        $obj->id_venta= $venta->id;
                        $obj->id_producto= $det['id_tienda_articulo'];
                        $obj->id_lote= $det['id_lote'];
                        $obj->cantidad= $det['cantidad'];
                        $obj->estado= 0;
                        $obj->presentacion= $det['contador'];
                        $obj->total_cantidad= $det['descuento_stock'];
                        $obj->id_eliminado= 0;

                        //$obj->costo_venta= $det['costo_venta'];
                        if($request->tipo_venta=='Venta Directa') {
                            if($det['contador'] == 0 ) {
                                $obj->costo_venta= $det['costo_unitario'];
                            } else if ($det['contador'] == 1) {
                                $obj->costo_venta= $det['precio_blister'];
                            } else if ($det['contador'] == 2) {
                                $obj->costo_venta= $det['precio_caja'];
                            } else {}
                        } else {
                            $obj->costo_venta= $det['costo_venta'];
                        }
                        $obj->sub_total= $det['sub_total'];
                        $obj->save();
                        $this->actualizarStock($det['id_lote'],$det['descuento_stock']);

                        $consulta = DB::select('CALL stock(?)', [$det['id_tienda_articulo']]);

                        $tienda_articulo=DB::select("SELECT ta.stock
                        FROM tienda_articulo ta
                        WHERE ta.id = '$obj->id_producto'");
                        //dd($obj->id_producto);
                        //Ajuste por Venta tienda 1
                        if($request->tipo_venta=='Venta Directa'){
                            //dd($det['stock'],$det['descuento_stock'],$det['cantidad']);
                            $ajuste = new Ajuste();
                            $ajuste->stock=$det['descuento_stock'];
                            $ajuste->costo_compra=0;
                            $ajuste->costo_unitario=$det['costo_unitario'];
                            $ajuste->costo_mayorista=$det['costo_mayorista'];
                            $ajuste->costo_preferencial=$det['costo_preferencial'];
                            $ajuste->stock_anterior=$det['stock'];
                            $ajuste->stock_actual=$det['stock']-$det['descuento_stock'];
                            $ajuste->stock_general_anterior=$tienda_articulo[0]->stock + $det['descuento_stock'];
                            $ajuste->stock_general=$tienda_articulo[0]->stock;
                            $ajuste->costo_unitario=0;
                            $ajuste->costo_mayorista=0;
                            $ajuste->costo_preferencial=0;
                            if($request->tipo_venta=='Venta Directa') {
                                if($det['contador'] == 0 ) {
                                    $ajuste->costo_venta= $det['costo_unitario'];
                                } else if ($det['contador'] == 1) {
                                    $ajuste->costo_venta= $det['precio_blister'];
                                } else if ($det['contador'] == 2) {
                                    $ajuste->costo_venta= $det['precio_caja'];
                                } else {}
                            } else {
                                $ajuste->costo_venta= $det['costo_venta'];
                            }
                            $ajuste->observacion=$request->descripcion;
                            $ajuste->id_lote=$det['id_lote'];
                            $ajuste->fecha=$venta->fecha;
                            $ajuste->id_usuario=$id_usuario;
                            $ajuste->id_venta=$venta->id;
                            $ajuste->id_compra=0;
                            $ajuste->id_motivo_ajuste=7;
                            $ajuste->id_transaccion=$venta->id;
                            $ajuste->hora=$hora;
                            $ajuste->save();
                        } else {
                            //
                        }
                        

                    }elseif($det['producto_venta']=='Venta Servicio'){
                        $obj = new DetalleVenta();
                        $obj->id_venta= $venta->id;
                        $obj->id_producto= $det['id_tienda_articulo'];
                        $obj->cantidad= $det['cantidad'];

                        //$obj->costo_venta= $det['costo_venta'];
                        if($request->tipo_venta=='Venta Directa') {
                            if($request->id_costo_pago == 1) {
                                $obj->costo_venta= $det['costo_unitario'];
                            } else if ($request->id_costo_pago == 2) {
                                $obj->costo_venta= $det['costo_mayorista'];
                            } else if ($request->id_costo_pago == 3) {
                                $obj->costo_venta= $det['costo_preferencial'];
                            } else {}
                        } else {
                            $obj->costo_venta= $det['costo_venta'];
                        }

                        //Ajuste por Venta tienda 1
                        if($request->tipo_venta=='Venta Directa'){
                            $ajuste = new Ajuste();
                            $ajuste->stock=$det['cantidad'];
                            $ajuste->costo_compra=0;
                            $ajuste->costo_unitario=$det['costo_unitario'];
                            $ajuste->costo_mayorista=$det['costo_mayorista'];
                            $ajuste->costo_preferencial=$det['costo_preferencial'];
                            $ajuste->stock_anterior=$det['stock'];
                            $ajuste->stock_actual=$det['stock']-$det['cantidad'];
                            if($request->tipo_venta=='Venta Directa') {
                                if($det['contador'] == 0 ) {
                                    $ajuste->costo_venta= $det['costo_unitario'];
                                } else if ($det['contador'] == 1) {
                                    $ajuste->costo_venta= $det['precio_blister'];
                                } else if ($det['contador'] == 2) {
                                    $ajuste->costo_venta= $det['precio_caja'];
                                } else {}
                            } else {
                                $ajuste->costo_venta= $det['costo_venta'];
                            }
                            $ajuste->observacion=$request->descripcion;
                            $ajuste->observacion=$request->descripcion;
                            $ajuste->id_articulo=$det['id_articulo'];
                            $ajuste->fecha=$venta->fecha;
                            $ajuste->id_usuario=$id_usuario;
                            $ajuste->id_motivo_ajuste=7;
                            $ajuste->id_transaccion=$request->id;

                            $ajuste->save();


                        } else {
                            //
                        }
                        $obj->sub_total= $det['sub_total'];
                        $obj->save();
                        if($request->tipo_venta=='Venta Directa'){
                            //$this->actualizarStock($det['id_articulo'],$det['cantidad']);
                        } else {
                            //
                        }

                    }else{

                        //modelo
                        $obj = new DetalleVentaPaquete();
                        $obj->id_venta= $venta->id;
                        $obj->id_paquete= $det['id_paquete'];
                        $obj->cantidad= $det['cantidad'];
                        $obj->costo_venta= $det['costo_unitario'];
                        $obj->sub_total= $det['sub_total'];
                        $obj->save();
                    }

                }


                if($request->tipo_venta == 'Venta Servicio') {
                    $affected = DB::table('orden_servicio')
                    ->where('id', $request->id_servicio)
                    ->update([
                        'estado' => 'Entregado',
                    ]);
                }else{
                    //
                }


                $datos = [
                    'tabla' => 'venta',
                    'codigo_tabla' => $venta->id,
                    'transaccion' => 'guardar tienda 1',
                ];
                $this->guardarBitacora($datos);



            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function guardarCotizacion(Request $request){
        try{
                DB::beginTransaction();
                $registro_venta = $request->total;
                $id_usuario=\Auth::user()->id;
                //dd($registro_venta, $id_usuario, $request->id_tipo_pago);



                //Modificar Cotizacion
                $cotizacion = Cotizacion::findOrFail($request->id);
                $cotizacion->fecha=$request->fecha;
                $cotizacion->fecha_venci=$request->fecha_venci;
                $cotizacion->dias_credito=$request->dias_credito;
                $cotizacion->tiempo_entrega=$request->tiempo_entrega;
                $cotizacion->lugar_entrega=$request->lugar_entrega;
                $cotizacion->sub_total=$request->sub_total;
                $cotizacion->descuento=$request->descuento;
                $cotizacion->total=$request->total;
                $cotizacion->estado=$request->estado;
                $cotizacion->id_cliente=$request->id_cliente;
                $cotizacion->id_tipo_pago=$request->id_tipo_pago;
                $cotizacion->tipo_venta=$request->tipo_venta;
                $cotizacion->nota=$request->nota;
                $cotizacion->id_tienda=2;
                if($request->id_tipo_pago == 1) {
                    $cotizacion->id_forma_pago=$request->id_forma_pago;
                }else if ($request->id_tipo_pago == 2) {
                    $cotizacion->id_forma_pago=$request->id_forma_pago;
                } else {
                    //
                }
                //$cotizacion->id_pago=$pago->id;
                $cotizacion->id_usuario=$id_usuario;
                $cotizacion->save();
                $eliminar = DetalleCotizacion::where('detalle_cotizacion.id_cotizacion','=',$cotizacion->id);
                $eliminar->delete();

                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;
                foreach($detalles as $ep=>$det){
                    $obj = new DetalleCotizacion();
                    $obj->id_cotizacion= $cotizacion->id;
                    $obj->id_producto= $det['id_tienda_articulo'];
                    $obj->cantidad= $det['cantidad'];
                    $obj->costo_venta= $det['costo_venta'];
                    $obj->sub_total= $det['sub_total'];
                    $obj->tiempo_entrega= $det['tiempo_entrega'];
                    $obj->save();
                    if($request->tipo_venta=='Venta Cotizacion'){
                        //$this->actualizarStock($det['id_articulo'],$det['cantidad']);
                    } else {
                        //
                    }

                    // $articulo = Articulo::findOrFail($det['id_articulo']);
                    // $articulo->costo_venta=$det['costo_venta'];
                    // $articulo->save();
                    if($request->tipo_venta=='Venta Cotizacion'){
                        $ajuste = new Ajuste();
                        $ajuste->stock=$det['cantidad'];
                        $ajuste->costo_compra=0;
                        $ajuste->costo_venta=$det['costo_unitario'];
                        $ajuste->observacion='';
                        $ajuste->id_articulo=$det['id_articulo'];
                        $ajuste->id_motivo_ajuste=7;
                        $ajuste->save();
                    }else{
                        //
                    }
                }


                if($request->tipo_venta == 'Venta Servicio') {
                    // $servicio= OrdenServicio::findOrFail($request->id);
                    // $servicio->estado=$request->estado;
                    // $servicio->save();
                    $affected = DB::table('orden_servicio')
                    ->where('id', $request->id_servicio)
                    ->update([
                        'estado' => 'Entregado',
                    ]);
                }else{
                    //
                }


                $datos = [
                    'tabla' => 'cotizacion',
                    'codigo_tabla' => $cotizacion->id,
                    'transaccion' => 'guardar',
                ];
                $this->guardarBitacora($datos);

                ///Fin de Modificar Cotizacion


                $venta = new Venta();
                $venta->fecha=$request->fecha;
                $venta->sub_total=$request->sub_total;
                $venta->descuento=$request->descuento;
                $venta->total=$request->total;
                $venta->estado=$request->estado;
                $venta->id_cliente=$request->id_cliente;
                $venta->id_tipo_pago=$request->id_tipo_pago;
                $venta->tipo_venta=$request->tipo_venta;
                $venta->id_orden_servicio=$request->id_servicio ? $request->id_servicio : null;
                $venta->id_tienda=2;
                if($request->id_tipo_pago == 1) {
                    $venta->id_forma_pago=$request->id_forma_pago;
                }else if ($request->id_tipo_pago == 2) {
                    $venta->id_forma_pago=$request->id_forma_pago;
                } else {
                    //
                }
                //$venta->id_pago=$pago->id;
                $venta->id_usuario=\Auth::user()->id;
                $venta->save();

                //AGREGAR CODIGO DE VENTA

                $correlativo = 0;
                $objdate = new DateTime();
                $fechaactual= $objdate->format('Y-m-d');
                $year = $objdate->format('y');
                $correlativo = $this->correlativoControl();
                $control = new control();
                $control->tabla = $request->tabla = "Venta";
                $control->id_tabla = $venta->id;
                $control->codigo = $request->codigo = 'VP-'.strval($correlativo + 1);
                $control->fecha = $fechaactual;
                $control->save();

                if($request->id_tipo_pago == 1) {
                    $pago = new Pago();
                    $pago->id = $venta->id;
                    $pago->fecha = $request->fecha;
                    $pago->fecha_final = $request->fecha_final;
                    $pago->monto = $request->total;
                    $pago->saldo = $request->total;
                    $pago->descripcion = "";
                    $pago->id_tipo_pago = $request->id_tipo_pago;
                    $pago->id_venta = $venta->id;
                    $pago->save();

                    $this->actualizarCaja($id_usuario,$registro_venta);

                } else if($request->id_tipo_pago == 2) {

                    $pago = new Pago();
                    $pago->id = $venta->id;
                    $pago->fecha = $request->fecha;
                    $pago->fecha_final = $request->fecha_final;
                    $pago->monto = $request->total;
                    $pago->saldo = $request->total;
                    $pago->descripcion = $request->descripcion = "";
                    $pago->id_tipo_pago = $request->id_tipo_pago;
                    $pago->id_venta = $venta->id;
                    $pago->save();

                    $cxcobrar = new CXCobrar();
                    $cxcobrar->fecha = $request->fecha;
                    $cxcobrar->monto_total = $request->monto_total;
                    $cxcobrar->descripcion = $request->descripcion_pago;
                    $cxcobrar->saldo = $request->monto_total;
                    $cxcobrar->id_pago = $venta->id;
                    $cxcobrar->save();

                } else {
                    //
                }

                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;

                foreach($detalles as $ep=>$det){
                    $obj = new DetalleVenta();
                    $obj->id_venta= $venta->id;
                    $obj->id_producto= $det['id_tienda_articulo'];
                    $obj->cantidad= $det['cantidad'];
                    $obj->costo_venta= $det['costo_venta'];

                     //Ajuste por Venta tienda 1
                    if($request->tipo_venta=='Venta Directa'){
                        $ajuste = new Ajuste();
                        $ajuste->stock=$det['cantidad'];
                        $ajuste->costo_compra=$det['costo_compra'];
                        $ajuste->costo_unitario=$det['costo_unitario'];
                        $ajuste->costo_mayorista=$det['costo_mayorista'];
                        $ajuste->costo_preferencial=$det['costo_preferencial'];
                        $ajuste->stock_anterior=$det['stock'];
                        $ajuste->stock_actual=$det['stock']-$det['cantidad'];
                        if($request->tipo_venta=='Venta Directa') {
                            if($request->id_costo_pago == 1) {
                                $ajuste->costo_venta= $det['costo_unitario'];
                            } else if ($request->id_costo_pago == 2) {
                                $ajuste->costo_venta= $det['costo_mayorista'];
                            } else if ($request->id_costo_pago == 3) {
                                $ajuste->costo_venta= $det['costo_preferencial'];
                            } else {}
                        } else {
                            $ajuste->costo_venta= $det['costo_venta'];
                        }
                        $ajuste->observacion=$request->descripcion;
                        $ajuste->id_articulo=$det['id_articulo'];
                        $ajuste->id_motivo_ajuste=8;
                        $ajuste->save();
                    } else {
                        //
                    }


                    $obj->sub_total= $det['sub_total'];
                    $obj->save();
                    if($request->tipo_venta=='Venta Cotizacion'){
                        $this->actualizarStock($det['id_articulo'],$det['cantidad']);
                    } else {
                        //
                    }
                }

                if($request->tipo_venta == 'Venta Cotizacion') {
                    $affected = DB::table('cotizacion')
                    ->where('id', $request->id)
                    ->update([
                        'estado' => 'Entregado',
                    ]);
                }else{
                    //
                }
                //dd($request->id);

                if($request->tipo_venta == 'Venta Servicio') {
                    $affected = DB::table('orden_servicio')
                    ->where('id', $request->id_servicio)
                    ->update([
                        'estado' => 'Entregado',
                    ]);
                }else{
                    //
                }


                $datos = [
                    'tabla' => 'venta',
                    'codigo_tabla' => $venta->id,
                    'transaccion' => 'guardar tienda 1',
                ];
                $this->guardarBitacora($datos);

                // if($request->estado=='Entregado'){
                //     $affected = DB::table('cotizacion')
                //     //->where('estado', $request->id_articulo)
                //     ->update(['estado' => 'Entregado']);

                // }
                //dd($request->dias_credito,$request->tiempo_entrega,$request->lugar_entrega,$request->fecha_venci);







            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function obtenerCabecera(Request $request){

        $id=$request->id;
        $obj= Venta::join('proveedor','compra.id_proveedor','=','proveedor.id')
        ->select('compra.id','compra.fecha','compra.descripcion','compra.total','proveedor.nombre as proveedor')
        ->where('compra.id','=',$id)
        ->get();

        return $obj;
    }
    public function detalleVenta(Request $request){

        $id=$request->id;
        $obj= detalleVenta::join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
        ->join('venta','detalle_venta.id_venta','=','venta.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_venta.id','detalle_venta.id_venta','detalle_venta.costo_venta','articulo.costo_unitario',
        'articulo.costo_mayorista','articulo.costo_preferencial','articulo.nombre_comercial as articulo','articulo.id_categoria',
        'categoria.nombre as categoria','detalle_venta.cantidad','detalle_venta.sub_total','tienda.nombre as tienda',
        'tienda_articulo.stock','tienda.id as id_tienda','tienda_articulo.id_articulo as id_articulo',
        'tienda_articulo.id_articulo as id_tienda_articulo','venta.id_usuario')
        ->where('detalle_venta.id_venta','=',$id)
        ->where('detalle_venta.estado','=',0)
        ->orderBy('categoria.nombre','asc')
        ->orderBy('articulo.nombre_comercial','asc')
        ->get();
        return $obj;
    }
    public function anular(Request $request){
    try{
        DB::beginTransaction();
        $registro_venta = DB::select("SELECT id, total, id_tipo_pago,id_forma_pago,total_efectivo,total_deposito FROM venta WHERE venta.id = $request->id");
        $total = $registro_venta[0]->total;
        $total_efectivo = $registro_venta[0]->total_efectivo;
        $total_deposito = $registro_venta[0]->total_deposito;
        //dd($request->id);
        $forma_pago = $registro_venta[0]->id_forma_pago;
        $tipo_pago = $registro_venta[0]->id_tipo_pago;
        $id_usuario=$request->id_usuario;
        //DD($request->id_usuario);
        $objdate = new DateTime();
        $fechaactual= $objdate->format('Y-m-d');
        $hora= $objdate->format('H:i:s');

        if($tipo_pago == "1"){
            // if($forma_pago ==2){
            //     $this->descontarCaja($total,$id_usuario);
            //     $this->descontarCajaContado($total,$id_usuario);
            // }
            // if($forma_pago ==3 || $forma_pago ==4 || $forma_pago ==5){
            //     $this->descontarCaja($total,$id_usuario);
            //     $this->descontarCajaContadoDeposito($total,$id_usuario);
            // }
            // if($forma_pago ==6){
            //     $this->descontarCaja($total_efectivo,$id_usuario);
            //     $this->descontarCajaContado($total_efectivo,$id_usuario);

            //     $this->descontarCajaDeposito($total_deposito,$id_usuario);
            //     $this->descontarCajaContadoDeposito($total_deposito,$id_usuario);
            // }
        }else if ($tipo_pago == "2"){
            $total_monto_credito= 0;
            $registro_venta_pago = DB::select("SELECT id, id_venta FROM pago WHERE id_venta = $request->id");
            $id_pago = $registro_venta_pago[0]->id;
            $registro_venta_credito = DB::select("SELECT id, id_pago, amortizacion,id_forma_pago FROM c_x_cobrar WHERE c_x_cobrar.id_pago = $id_pago");
            //$id_forma_pago = $registro_venta_credito[0]->id_forma_pago;
            foreach($registro_venta_credito as $credito){
                $total_monto_credito = $total_monto_credito + floatval($credito->amortizacion);
                //dd($credito->id_forma_pago);
                // if($credito->id_forma_pago == 2){
                //     $this->descontarCajaCredito($credito->amortizacion,$id_usuario);
                // }
                // if($credito->id_forma_pago == 3 || $credito->id_forma_pago == 4 || $credito->id_forma_pago == 5){
                //     $this->descontarCajaCreditoDeposito($credito->amortizacion,$id_usuario);
                // }
            }
            // $this->descontarCaja($total_monto_credito,$id_usuario);

        }

        $obj = Venta::findOrFail($request->id);
        $obj->estado = 'Anulado';
        $obj->save();

        if(isset($request->id_orden_servicio)){
            $obj = OrdenServicio::findOrFail($request->id_orden_servicio);
            $obj->estado = 'Anulado';
            $obj->save();
        }


        $pago = Pago::findOrFail($request->id);
        $pago->estado = 0;
        $pago->save();


        // $detalles = DetalleVenta::select('id_venta','id_producto','cantidad')
        // ->where('detalle_venta.id_venta',$request->id)->get();

        $detalles = DetalleVenta::select('detalle_venta.id_venta','detalle_venta.costo_venta','detalle_venta.id_lote','detalle_venta.cantidad','detalle_venta.id_producto','detalle_venta.total_cantidad','lote.cantidad as cantidad_lote')
        ->join('lote','detalle_venta.id_lote','=','lote.id')
        ->where('detalle_venta.id_venta',$request->id)->get();
        //dd($detalles);
        foreach($detalles as $ep=>$det){
            DB::table('lote')->where('lote.id','=',$det->id_lote)
            //->where('combo','=',0)
            ->increment('cantidad', $det->total_cantidad);
            $consulta = DB::select('CALL stock(?)', [$det->id_producto]);

            $tienda_articulo=DB::select("SELECT ta.stock
            FROM tienda_articulo ta
            WHERE ta.id = '$det->id_producto'");

            $ajuste = new Ajuste();
            $ajuste->stock=$det->total_cantidad;
            $ajuste->costo_compra=0;
            $ajuste->costo_unitario=0;
            $ajuste->costo_mayorista=0;
            $ajuste->costo_preferencial=0;
            $ajuste->stock_anterior=$det->cantidad_lote;
            $ajuste->stock_actual=$det->cantidad_lote + $det->total_cantidad;
            $ajuste->stock_general_anterior=$tienda_articulo[0]->stock - $det->total_cantidad;
            $ajuste->stock_general=$tienda_articulo[0]->stock;
            $ajuste->costo_unitario=0;
            $ajuste->costo_mayorista=0;
            $ajuste->costo_preferencial=0;
            $ajuste->costo_venta= $det['costo_venta'];
            $ajuste->observacion='';
            $ajuste->id_lote=$det['id_lote'];
            $ajuste->fecha=$fechaactual;
            $ajuste->id_usuario=$id_usuario;
            $ajuste->id_venta=$request->id;
            $ajuste->id_compra=0;
            $ajuste->id_motivo_ajuste=11;
            $ajuste->id_transaccion=$request->id;
            $ajuste->hora=$hora;
            $ajuste->save();
            //dd($det->id_producto);
        }
            // foreach($detalles as $ep=>$det){
            //     // DB::table('tienda_articulo')->where('tienda_articulo.id','=',$det->id_producto)
            //     // ->increment('stock', $det->cantidad);
            //     $consulta = DB::select('CALL anular(?)', [$id_producto]);
            //     }


        $detalles = DetalleVentaPaquete::join('paquetes','detalle_venta_paquete.id_paquete','=','paquetes.id')
        ->join('detalle_paquete','paquetes.id','=','detalle_paquete.id_paquete')
        ->select('detalle_venta_paquete.id_venta','detalle_paquete.id_producto','detalle_venta_paquete.cantidad','detalle_venta_paquete.id')
        ->where('detalle_venta_paquete.id_venta',$request->id)->get();
        //dd($detalles);
        // foreach($detalles as $ep=>$det2){
        //     DB::table('Producto')->where('Producto.Id_Producto','=',$det->Id_Producto)
        //     ->where('combo','=',1);

        //     //->increment('Stock', $det->Cantidad);
        // }
        foreach($detalles as $ep=>$det2){
            // $detallesCombo = DetalleCombo::select(DB::raw('detalle.Cantidad *'.$det2->Cantidad.'as Cantidad'),'DetalleCombo.Id_Producto')->where('DetalleCombo.Id_Combo','=',$det2->Id_Producto)->where('DetalleCombo.Eliminado','=',0)->get();
            $detallesCombo = DetalleVentaPaquete::join('paquetes','detalle_venta_paquete.id_paquete','=','paquetes.id')
            ->join('detalle_paquete','paquetes.id','=','detalle_paquete.id_paquete')
            ->select(DB::raw('detalle_paquete.cantidad *'.$det2->cantidad.' as Cantidad'),'detalle_paquete.id_producto')
            ->where('detalle_venta_paquete.id','=',$det2->id)
            ->where('detalle_paquete.id_producto','=',$det2->id_producto)
            ->get();
            foreach($detallesCombo as $ep=>$det3){
                DB::table('tienda_articulo')->where('tienda_articulo.id_articulo','=',$det3->id_producto)
                ->increment('stock', $det3->Cantidad);
            }

           //dd($det3->Cantidad);

        }
        //dd($detalles);

        $datos = [
            'tabla' => 'venta',
            'codigo_tabla' => $request->id,
            'transaccion' => 'Anular Venta Tienda 1',
        ];
        $this->guardarBitacora($datos);
        DB::commit();
    } catch (Exception $e){
        DB::rollBack();
    }

    }
    public function cantidadRegistros(){
        $mayor = DB::table('control')->count();
        return $mayor;
    }
    private function correlativoControl(){
       $mayor = DB::table('control')->where('control.tabla','=','Venta')->count();
        return $mayor;
    }
    public function pdfVentas(Request $request){

        $id = $request->id;
        $foto = $request->foto;

        $venta= Venta::join('users','venta.id_usuario','=','users.id')
        ->join('cliente','venta.id_cliente','=','cliente.id')
        ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
        ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
        ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento',
        'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP')
        ->where('venta.id',$id)
        ->orderBy('venta.id','desc')->get();

        $detalleVenta= detalleVenta::join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_venta.id_venta','detalle_venta.costo_venta','articulo.costo_unitario as costo_unitario','articulo.costo_mayorista as costo_mayorista','articulo.costo_preferencial as costo_preferencial','articulo.nombre as articulo','detalle_venta.cantidad','detalle_venta.sub_total','tienda.nombre as tienda')
        ->where('detalle_venta.id_venta','=',$id)
        ->get();

        $cliente=$venta[0]->cliente;
        $fecha=$venta[0]->fecha;
        $tipo_pago=$venta[0]->tipoP;
        $forma_pago=$venta[0]->formaP;
        $id_descuento=$venta[0]->id_descuento;
        $detalles=$detalleVenta;
        $total=$venta[0]->total;
        $descuento=$venta[0]->descuento;
        $sub_total=$venta[0]->sub_total;
        if($venta[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($venta[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($venta[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.venta.venta', [
            'ventas'=>$venta,
            'fecha'=>$fecha,
            'cliente'=>$cliente,
            'tipo_pago'=>$tipo_pago,
            'forma_pago'=>$forma_pago,
            'descuento_pago'=>$descuento_pago,
            'id_descuento' =>$id_descuento,
            'detalles'=>$detalles,
            'total'=>$total,
            'descuento'=>$descuento,
            'sub_total'=>$sub_total,
            'foto'=>$foto
        ]);
        return $pdf->stream('Ventas.pdf');
    }
    public function pdfVentasGeneral(Request $request){

        $id = $request->id;
        $foto = $request->foto;
        $empresa_nombre = $request->empresa_nombre;

        $venta= Venta::join('users','venta.id_usuario','=','users.id')
        ->join('cliente','venta.id_cliente','=','cliente.id')
        ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
        ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
        ->join('tienda','venta.id_tienda','=','tienda.id')
        ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento',
        'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP',
        'tienda.cod_tienda','tienda.nombre as tienda','tienda.direccion as tienda_direccion','tienda.cod_almacen')
        ->where('venta.id',$id)
        ->orderBy('venta.id','desc')->get();

        $detalleVenta= detalleVenta::join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_venta.id_venta','detalle_venta.costo_venta','articulo.costo_unitario as costo_unitario','articulo.costo_mayorista as costo_mayorista','articulo.costo_preferencial as costo_preferencial','articulo.nombre as articulo','detalle_venta.cantidad','detalle_venta.sub_total','tienda.nombre as tienda')
        ->where('detalle_venta.id_venta','=',$id)
        ->get();

        $usuario = \Auth::user()->name;
        $objdate = new DateTime();
        $fecha_impresion=$objdate->format('d/m/Y');
        $cliente=$venta[0]->cliente;
        $fecha=$venta[0]->fecha;
        $tipo_pago=$venta[0]->tipoP;
        $forma_pago=$venta[0]->formaP;
        $id_descuento=$venta[0]->id_descuento;
        $detalles=$detalleVenta;
        $total=$venta[0]->total;
        $descuento=$venta[0]->descuento;
        $sub_total=$venta[0]->sub_total;

        $tienda=$venta[0]->tienda;
        if($venta[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($venta[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($venta[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.venta.venta-general', [
            'ventas'=>$venta,
            'fecha'=>$fecha,
            'cliente'=>$cliente,
            'tipo_pago'=>$tipo_pago,
            'forma_pago'=>$forma_pago,
            'descuento_pago'=>$descuento_pago,
            'id_descuento' =>$id_descuento,
            'detalles'=>$detalles,
            'total'=>$total,
            'descuento'=>$descuento,
            'sub_total'=>$sub_total,
            'foto'=>$foto,
            'fecha_impresion'=>$fecha_impresion,
            'usuario'=>$usuario,
            'empresa_nombre'=>$empresa_nombre,

            'tienda'=>$tienda
        ]);
        return $pdf->stream('Ventas.pdf');
    }
    public function modificar(Request $request){
        try{
            DB::beginTransaction();
            $registro_venta = $request->total;
            $id_usuario=\Auth::user()->id;

            $venta= Venta::findOrFail($request->id);
            $venta->id_tipo_pago=$request->id_tipo_pago;
            $venta->estado=$request->estado;
            if($request->id_tipo_pago == 1) {
                $venta->id_forma_pago=$request->id_forma_pago;
            }else if ($request->id_tipo_pago == 2) {
                $venta->id_forma_pago=$request->id_forma_pago;
            } else {
                //
            }
            $venta->save();

            if($request->id_tipo_pago == 1) {
                $pago = new Pago();
                $pago->id = $venta->id;
                $pago->fecha = $request->fecha;
                $pago->fecha_final = $request->fecha_final;
                $pago->monto = $request->total;
                $pago->saldo = $request->total;
                $pago->descripcion = "";
                $pago->id_tipo_pago = $request->id_tipo_pago;
                $pago->id_venta = $venta->id;
                //dd($request->total);
                $pago->save();


                $this->actualizarCaja($id_usuario,$registro_venta);

            } else if($request->id_tipo_pago == 2) {

                $pago = new Pago();
                $pago->id = $venta->id;
                $pago->fecha = $request->fecha;
                $pago->fecha_final = $request->fecha_final;
                $pago->monto = $request->total;
                $pago->saldo = $request->total;
                $pago->descripcion = $request->descripcion = "";
                $pago->id_tipo_pago = $request->id_tipo_pago;
                $pago->id_venta = $venta->id;
                $pago->save();

                $cxcobrar = new CXCobrar();
                $cxcobrar->fecha = $request->fecha;
                $cxcobrar->monto_total = $request->monto_total;
                $cxcobrar->descripcion = $request->descripcion_pago;
                $cxcobrar->saldo = $request->monto_total;
                $cxcobrar->id_pago = $venta->id;
                $cxcobrar->save();

            } else {
                //
            }

            $eliminar = DetalleVenta::where('detalle_venta.id_venta','=',$venta->id);
            $eliminar->delete();

            $detalles = $request->detalle;
            foreach($detalles as $ep=>$det){
                $obj = new DetalleVenta();
                $obj->id_venta= $venta->id;
                $obj->id_producto= $det['id_tienda_articulo'];
                $obj->cantidad= $det['cantidad'];
                $obj->costo_venta= $det['costo_venta'];
                $obj->sub_total= $det['sub_total'];

                //  Descontar Lote
                $variable=DB::select("SELECT id as id from lote where estado!=0 and cantidad>0 and  id_producto=$obj->id_producto");
                $var = $variable;
                $var2 = 0;
                $var3 = 0;
                $var5 = 0;
                $var7 = $obj->cantidad;
                $array = json_decode(json_encode($var), true);

                //dd($variable);
                    foreach ($array as $i => $value) {
                        $var2=$array[$i];
                        $gg = implode(" ",$var2);
                        $id_lote = intval($gg);
                        $var4=DB::select("SELECT cantidad as cantidad from lote where id=$gg");
                        $var6 = $var4;
                        $array2 = json_decode(json_encode($var6), true);
                        $var5=$array2[0];
                        $ggc = implode(" ",$var5);
                        $valor_lote = intval($ggc);
                        //dd($valor_lote);
                        if($var7 != 0)
                        {
                            if($var7>$valor_lote)
                            {
                                DB::table('lote')->where('lote.id','=',$id_lote)->decrement('cantidad',$valor_lote);
                                DB::table('lote')->where('lote.id','=',$id_lote)->update(['estado' => 2]);


                                $var7=$var7-$valor_lote;

                                // dd($var7);

                                // dd($det['id_tienda_articulo']);
                            //    if($request->tipo_venta=='Venta Directa'){
                            //         $consulta = DB::select('CALL stock(?)', [$det['id_tienda_articulo']]);
                            //     } else {
                            //         //
                            //     }

                                // //dd($id_lote,$valor_lote);
                                // //Registrar Auxiliar
                                $objaux = new Auxiliar();
                                $objaux->id_venta= $venta->id;
                                $objaux->id_lote= $id_lote;
                                $objaux->cantidad= $valor_lote;
                                $objaux->save();
                                // //Fin Registrar Auxiliar
                            }
                            else
                            {
                                DB::table('lote')->where('lote.id','=',$id_lote)->decrement('cantidad',$var7);
                                DB::table('lote')->where('lote.id','=',$id_lote)->update(['estado' => 2]);

                                // if($request->tipo_venta=='Venta Directa'){
                                $consulta = DB::select('CALL stock(?)', [$det['id_tienda_articulo']]);
                            // } else {

                            // }
                                //  dd($det['id_tienda_articulo']);
                            //    if($request->tipo_venta=='Venta Directa'){
                            //         $consulta = DB::select('CALL stock(?)', [$det['id_tienda_articulo']]);
                            //     } else {
                            //         //
                            //     }
                                //dd($id_lote,$var7);
                                //Registrar Auxiliar
                                $objaux = new Auxiliar();
                                $objaux->id_venta= $venta->id;
                                $objaux->id_lote= $id_lote;
                                $objaux->cantidad= $var7;
                                $objaux->save();
                                // //Fin Registrar Auxiliar
                                $var7=0;
                            }
                        }
                    }
                    //Fin Descontar Lote





                $obj->save();
                // if($request->tipo_venta=='Venta Control Vacuna'){
                    //dd($det['id_articulo']);
                    //$this->actualizarStock($det['id_articulo'],$det['cantidad']);
                // } else {
                //     //
                // }
            }

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function modificarAntiparasitario(Request $request){
        try{
            DB::beginTransaction();
            $registro_venta = $request->total;
            $id_usuario=\Auth::user()->id;

            $venta= Venta::findOrFail($request->id);
            $venta->id_tipo_pago=$request->id_tipo_pago;
            $venta->estado=$request->estado;
            if($request->id_tipo_pago == 1) {
                $venta->id_forma_pago=$request->id_forma_pago;
            }else if ($request->id_tipo_pago == 2) {
                $venta->id_forma_pago=$request->id_forma_pago;
            } else {
                //
            }
            $venta->save();

            if($request->id_tipo_pago == 1) {
                $pago = new Pago();
                $pago->id = $venta->id;
                $pago->fecha = $request->fecha;
                $pago->fecha_final = $request->fecha_final;
                $pago->monto = $request->total;
                $pago->saldo = $request->total;
                $pago->descripcion = "";
                $pago->id_tipo_pago = $request->id_tipo_pago;
                $pago->id_venta = $venta->id;
                //dd($request->total);
                $pago->save();


                $this->actualizarCaja($id_usuario,$registro_venta);

            } else if($request->id_tipo_pago == 2) {

                $pago = new Pago();
                $pago->id = $venta->id;
                $pago->fecha = $request->fecha;
                $pago->fecha_final = $request->fecha_final;
                $pago->monto = $request->total;
                $pago->saldo = $request->total;
                $pago->descripcion = $request->descripcion = "";
                $pago->id_tipo_pago = $request->id_tipo_pago;
                $pago->id_venta = $venta->id;
                $pago->save();

                $cxcobrar = new CXCobrar();
                $cxcobrar->fecha = $request->fecha;
                $cxcobrar->monto_total = $request->monto_total;
                $cxcobrar->descripcion = $request->descripcion_pago;
                $cxcobrar->saldo = $request->monto_total;
                $cxcobrar->id_pago = $venta->id;
                $cxcobrar->save();

            } else {
                //
            }

            $eliminar = DetalleVenta::where('detalle_venta.id_venta','=',$venta->id);
            $eliminar->delete();

            $detalles = $request->detalle;
            foreach($detalles as $ep=>$det){
                $obj = new DetalleVenta();
                $obj->id_venta= $venta->id;
                $obj->id_producto= $det['id_tienda_articulo'];
                $obj->cantidad= $det['cantidad'];
                $obj->costo_venta= $det['costo_venta'];
                $obj->sub_total= $det['sub_total'];

                //  Descontar Lote
                $variable=DB::select("SELECT id as id from lote where estado!=0 and cantidad>0 and  id_producto=$obj->id_producto");
                $var = $variable;
                $var2 = 0;
                $var3 = 0;
                $var5 = 0;
                $var7 = $obj->cantidad;
                $array = json_decode(json_encode($var), true);

                //dd($variable);
                    foreach ($array as $i => $value) {
                        $var2=$array[$i];
                        $gg = implode(" ",$var2);
                        $id_lote = intval($gg);
                        $var4=DB::select("SELECT cantidad as cantidad from lote where id=$gg");
                        $var6 = $var4;
                        $array2 = json_decode(json_encode($var6), true);
                        $var5=$array2[0];
                        $ggc = implode(" ",$var5);
                        $valor_lote = intval($ggc);
                        //dd($valor_lote);
                        if($var7 != 0)
                        {
                            if($var7>$valor_lote)
                            {
                                DB::table('lote')->where('lote.id','=',$id_lote)->decrement('cantidad',$valor_lote);
                                DB::table('lote')->where('lote.id','=',$id_lote)->update(['estado' => 2]);


                                $var7=$var7-$valor_lote;

                                // dd($var7);

                                // dd($det['id_tienda_articulo']);
                            //    if($request->tipo_venta=='Venta Directa'){
                            //         $consulta = DB::select('CALL stock(?)', [$det['id_tienda_articulo']]);
                            //     } else {
                            //         //
                            //     }

                                // //dd($id_lote,$valor_lote);
                                // //Registrar Auxiliar
                                $objaux = new Auxiliar();
                                $objaux->id_venta= $venta->id;
                                $objaux->id_lote= $id_lote;
                                $objaux->cantidad= $valor_lote;
                                $objaux->save();
                                // //Fin Registrar Auxiliar
                            }
                            else
                            {
                                DB::table('lote')->where('lote.id','=',$id_lote)->decrement('cantidad',$var7);
                                DB::table('lote')->where('lote.id','=',$id_lote)->update(['estado' => 2]);

                                // if($request->tipo_venta=='Venta Directa'){
                                $consulta = DB::select('CALL stock(?)', [$det['id_tienda_articulo']]);
                            // } else {

                            // }
                                //  dd($det['id_tienda_articulo']);
                            //    if($request->tipo_venta=='Venta Directa'){
                            //         $consulta = DB::select('CALL stock(?)', [$det['id_tienda_articulo']]);
                            //     } else {
                            //         //
                            //     }
                                //dd($id_lote,$var7);
                                //Registrar Auxiliar
                                $objaux = new Auxiliar();
                                $objaux->id_venta= $venta->id;
                                $objaux->id_lote= $id_lote;
                                $objaux->cantidad= $var7;
                                $objaux->save();
                                // //Fin Registrar Auxiliar
                                $var7=0;
                            }
                        }
                    }
                    //Fin Descontar Lote

                $obj->save();

            }

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function eliminarDetalle(Request $request){
        //dd($request->id_tipo_pago);
        $total_d = $request->totalAux;
        $total = $request->total;

        $total_efectivo = $request->total_efectivo;
        $total_deposito = $request->total_deposito;

        $total_efectivo_aux = $request->total_efectivo_aux;


        $id_usuario= $request->id_usuario;

        $id_eliminado=\Auth::user()->id;
        $objdate = new DateTime();
        $fechaactual= $objdate->format('Y-m-d');
        $hora= $objdate->format('H:i:s');
        //dd($request->id);
        $affected = DB::table('detalle_venta')
        ->where('id', $request->id)
        ->update(['estado' => '1','id_eliminado' => $id_eliminado]);

        //dd($total_d,$total);
        $detalles = DetalleVenta::join('venta','detalle_venta.id_venta','=','venta.id')
        ->join('lote','detalle_venta.id_lote','=','lote.id')
        ->select('detalle_venta.id_venta','detalle_venta.id_producto','detalle_venta.id_lote'
        ,'detalle_venta.cantidad','detalle_venta.sub_total','venta.id_forma_pago','detalle_venta.total_cantidad','costo_venta','lote.cantidad as lote_cantidad')
        ->where('detalle_venta.id',$request->id)->get();
 
            foreach($detalles as $ep=>$det){
                DB::table('lote')->where('lote.id','=',$det->id_lote)
                ->increment('cantidad', $det->total_cantidad);

                        $tienda_articulo=DB::select("SELECT ta.stock
                        FROM tienda_articulo ta
                        WHERE ta.id = '$det->id_producto'");

                        $consulta = DB::select('CALL stock(?)', [$det->id_producto]);


                        $ajuste = new Ajuste();
                        $ajuste->stock=$det->total_cantidad;
                        $ajuste->costo_compra=0;
                        $ajuste->costo_unitario=0;
                        $ajuste->costo_mayorista=0;
                        $ajuste->costo_preferencial=0;
                        $ajuste->stock_anterior=$tienda_articulo[0]->stock;
                        $ajuste->stock_actual=$tienda_articulo[0]->stock + $det->total_cantidad;
                        $ajuste->stock_general_anterior=$tienda_articulo[0]->stock;
                        $ajuste->stock_general=$tienda_articulo[0]->stock + $det->total_cantidad;
                        $ajuste->costo_unitario=0;
                        $ajuste->costo_mayorista=0;
                        $ajuste->costo_preferencial=0;
                        $ajuste->costo_venta= $det['costo_venta'];
                        $ajuste->observacion='';
                        $ajuste->id_lote=$det['id_lote'];
                        $ajuste->fecha=$fechaactual;
                        $ajuste->id_usuario=$id_usuario;
                        $ajuste->id_venta=$request->id;
                        $ajuste->id_compra=0;
                        $ajuste->id_motivo_ajuste=10;
                        $ajuste->id_transaccion=$request->id;
                        $ajuste->hora=$hora;
                        $ajuste->save();

                        if($request->id_tipo_pago == 'Contado'){
                            $affected = DB::table('venta')
                            ->where('id', $det->id_venta)
                            ->update(['estado' => 'Devolucion']);
                        }
                }
      
    }
    public function modificarVenta(Request $request){
        try{
            DB::beginTransaction();
            $objdate = new DateTime();
            $fechaactual= $objdate->format('Y-m-d');
            $id_usuario=\Auth::user()->id;

            $total_d = $request->totalAux;
            $total = $request->total;

            $total_efectivo_aux = $request->total_efectivo_aux;
            $total_deposito_aux = $request->total_deposito_aux;
            //dd($total_deposito_aux);
            $total_efectivo = $request->total_efectivo;
            $total_deposito = $request->total_deposito;

            //dd($total_d,$total);

            $total_deposito = $request->total_deposito;
            $id_usuario= $request->id_usuario;
            $id_eliminado=\Auth::user()->id;

            //dd($request->id_usuario);

            if($request->formaPago == 'Efectivo'){
                $affected = DB::table('venta')
                ->where('id', $request->id_venta)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_efectivo' => $request->total]);
            }
            elseif($request->formaPago == 'Transferencia'){
                $affected = DB::table('venta')
                ->where('id', $request->id_venta)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
            }elseif($request->formaPago == 'Pago por QR'){
                $affected = DB::table('venta')
                ->where('id', $request->id_venta)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
            }elseif($request->formaPago == 'Depósito'){
                $affected = DB::table('venta')
                ->where('id', $request->id_venta)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
            }elseif($request->formaPago == 'Mixta'){
                $affected = DB::table('venta')
                ->where('id', $request->id_venta)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_efectivo' => $request->total_efectivo,'total_deposito' => $request->total_deposito]);
            }elseif($request->formaPago == 'Cuenta por Cobrar'){
                $affected = DB::table('venta')
                ->where('id', $request->id_venta)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total]);
            }



            // if($request->formaPago == 'Efectivo'){

            //     $this->descontarCaja($total_d,$id_usuario);
            //     $this->descontarCajaContado($total_d,$id_usuario);
                
            //     $this->actualizarCaja($id_usuario,$total);
            //     $this->actualizarCajaContado($id_usuario,$total);

            // }
            // if($request->formaPago == 'Transferencia' || $request->formaPago == 'Pago por QR' || $request->formaPago == 'Depósito'){

            //     $this->descontarCaja($total_d,$id_usuario);
            //     $this->descontarCajaContadoDeposito($total_d,$id_usuario);
                
            //     $this->actualizarCaja($id_usuario,$total);
            //     $this->actualizarCajaContadoDeposito($id_usuario,$total);

            // }
            if($request->formaPago == 'Mixta'){

                // $this->descontarCaja($total_efectivo_aux,$id_usuario);
                // $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
                
                // $this->actualizarCaja($id_usuario,$total_efectivo);
                // $this->actualizarCajaContado($id_usuario,$total_efectivo);


                // $this->descontarCajaDeposito($total_deposito_aux,$id_usuario);
                // $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
                
                // $this->actualizarCajaDeposito($id_usuario,$total_deposito);
                // $this->actualizarCajaContadoDeposito($id_usuario,$total_deposito);

                $affected = DB::table('pago')
                ->where('id_venta', $request->id_venta)
                ->update(['monto' => $total_efectivo,'saldo'=>$total_efectivo]);
            }
            if($request->formaPago == 'Cuenta por Cobrar'){



                // $detalles = CXCobrar::select('detalle_venta.id_venta','detalle_venta.id_producto','detalle_venta.id_lote'
                // ,'detalle_venta.cantidad','detalle_venta.sub_total')
                // ->where('c_x_cobrar.id',$request->id_venta)->get();
                //$credentials = CXCobrar::where('id_pago', $request->id_venta)->orderBy('id', 'desc')->first();
                $credentials2 = CXCobrar::select(DB::raw('SUM(amortizacion) as amortizacion'),'id')->where('id_pago', $request->id_venta)->orderBy('id', 'desc')->get();

              //dd($credentials2[0]->amortizacion);
                //  foreach($credentials as $cobrar){
                //$consulta = CXCobrar::select("c_x_cobrar")->latest()->where('c_x_cobrar.id_pago','=',$request->id_venta)->first();
                $affected = DB::table('pago')
                ->where('id_venta', $request->id_venta)
                ->update(['monto' => $total,'saldo'=>($total-$credentials2[0]->amortizacion)]);
                
                $saldoTotal = $total-$credentials2[0]->amortizacion;
                //dd($saldoTotal);
                $cxcobrar = new CXCobrar();
                $cxcobrar->fecha = $fechaactual;
                $cxcobrar->monto_total = $total;
                $cxcobrar->amortizacion = $credentials2[0]->amortizacion;
                $cxcobrar->descripcion = '';
                $cxcobrar->saldo = $saldoTotal;
                $cxcobrar->id_pago = $request->id_venta;
                $cxcobrar->id_usuario = $id_usuario;
                $cxcobrar->id_forma_pago = 0;
                $cxcobrar->save();

                // $affected = DB::table('c_x_cobrar')
                // ->where('id', $credentials->id)
                // //->first()
                // ->update(['monto_total' => $total,'amortizacion' => $credentials2[0]->amortizacion,'saldo' => ($total-$credentials2[0]->amortizacion)]);
                //;

                // }
            }
            $affected = DB::table('pago')
            ->where('id_venta', $request->id_venta)
            ->update(['monto' => $total]);

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function cantidadProducto(Request $request){
        $fecha_producto = $request->fecha_producto;
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        //dd($fecha_producto);

        if ($buscar==''){
            $obj=DB::select("SELECT SUM(detalle_venta.cantidad) as cantidad, articulo.nombre_comercial, detalle_venta.id_venta, detalle_venta.id_lote, venta.fecha,proveedor.nombre as laboratorio
            FROM detalle_venta, articulo, venta ,proveedor
            WHERE  detalle_venta.id_producto = articulo.id and detalle_venta.id_venta = venta.id and articulo.id_proveedor=proveedor.id and venta.estado!='Anulado'
            GROUP BY  detalle_venta.id_lote");
        } else{

            $obj=DB::select("SELECT SUM(detalle_venta.cantidad) as cantidad, articulo.nombre_comercial, detalle_venta.id_venta, detalle_venta.id_lote, venta.fecha,proveedor.nombre as laboratorio
            FROM detalle_venta, articulo, venta ,proveedor
            WHERE  detalle_venta.id_producto = articulo.id and detalle_venta.id_venta = venta.id  and articulo.id_proveedor=proveedor.id and venta.estado!='Anulado' and $criterio like '%$buscar%'
            GROUP BY  detalle_venta.id_lote");
               //->get();
           }

        return $obj;
     }

     public function cantidadProductoUsuario(Request $request){
        $id_lote = $request->id_lote;
        $fecha_producto = $request->fecha_producto;
        $fecha_fin = $request->fecha_fin;
        //dd($id_lote,$fecha_producto,$fecha_fin);
        //$fecha_fin = $request->fecha_fin;
        $buscar = $request->buscar;
        $criterio = $request->criterioP;
        //dd($criterio);
        // dd($request);
        if ($buscar==''){
            $obj=DB::select("SELECT detalle_venta.cantidad, articulo.nombre_comercial, detalle_venta.id_venta, detalle_venta.id_lote,
            venta.fecha,proveedor.nombre as laboratorio, users.name as usuario,lote.lote
            FROM detalle_venta, articulo, venta ,proveedor,users,lote
            WHERE  detalle_venta.id_producto = articulo.id and detalle_venta.id_venta = venta.id and articulo.id_proveedor=proveedor.id and detalle_venta.id_lote=lote.id
             and venta.id_usuario=users.id and venta.estado!='Anulado'
             and venta.fecha BETWEEN '$fecha_producto'  and '$fecha_fin'
             and detalle_venta.id_lote='$id_lote'
             ORDER BY venta.fecha");

        } else{
            // if($criterio="proveedor.nombre"){
            //     $obj=DB::select("SELECT detalle_venta.cantidad, articulo.nombre_comercial, detalle_venta.id_venta, detalle_venta.id_lote,
            //     venta.fecha,proveedor.nombre as laboratorio, users.name as usuario,lote.lote
            //     FROM detalle_venta, articulo, venta ,proveedor,users,lote
            //     WHERE  detalle_venta.id_producto = articulo.id and detalle_venta.id_venta = venta.id and articulo.id_proveedor=proveedor.id and detalle_venta.id_lote=lote.id
            //      and venta.id_usuario=users.id and venta.estado!='Anulado'
            //      and venta.fecha BETWEEN '$fecha_producto'  and '$fecha_fin'
            //      and detalle_venta.id_lote='$id_lote'
            //      and $criterio,like,'%'.$buscar
            //      ORDER BY venta.fecha");
            // }else{
                $obj=DB::select("SELECT detalle_venta.cantidad, articulo.nombre_comercial, detalle_venta.id_venta, detalle_venta.id_lote,
                venta.fecha,proveedor.nombre as laboratorio, users.name as usuario,lote.lote
                FROM detalle_venta, articulo, venta ,proveedor,users,lote
                WHERE  detalle_venta.id_producto = articulo.id and detalle_venta.id_venta = venta.id and articulo.id_proveedor=proveedor.id and detalle_venta.id_lote=lote.id
                 and venta.id_usuario=users.id and venta.estado!='Anulado'
                 and venta.fecha BETWEEN '$fecha_producto'  and '$fecha_fin'
                 and detalle_venta.id_lote='$id_lote'
                 and $criterio,like,'%'.$buscar.'%'
                 ORDER BY venta.fecha");
                   //->get();
            // }
           }

        return $obj;
     }

     /*public function cantidadProductoFecha(Request $request){
        $fecha_producto = $request->fecha_producto;
        $fecha_fin = $request->fecha_fin;
        $buscar = $request->buscar;
         $criterio = $request->criterio;

        if ($buscar==''){

            $obj= DetalleVenta::join('venta','detalle_venta.id_venta','=','venta.id')
            ->join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
            ->join('articulo','articulo.id','=','tienda_articulo.id_articulo')
            ->join('lote','detalle_venta.id_lote','=','lote.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->select(DB::raw('SUM(detalle_venta.cantidad) cantidad'),'articulo.nombre_comercial','detalle_venta.id_venta','detalle_venta.id_lote','venta.fecha','proveedor.nombre as laboratorio','lote.lote',
            'lote.fecha_vecimiento')
            ->whereIn('venta.estado',['Entregado','Devolucion'])
            ->whereDate('venta.fecha', ">=", $fecha_producto)
            ->whereDate('venta.fecha', "<=", $fecha_fin)
            ->groupBy('detalle_venta.id_lote')
            ->orderBy('venta.fecha','desc')
            ->paginate(70); 
        } else{
            if($criterio=="proveedor.nombre"){

                $obj= DetalleVenta::join('venta','detalle_venta.id_venta','=','venta.id')
                ->join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
                ->join('articulo','articulo.id','=','tienda_articulo.id_articulo')
                ->join('lote','detalle_venta.id_lote','=','lote.id')
                ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
                ->select(DB::raw('SUM(detalle_venta.cantidad) cantidad'),'articulo.nombre_comercial','detalle_venta.id_venta','detalle_venta.id_lote','venta.fecha','proveedor.nombre as laboratorio','lote.lote',
                'lote.fecha_vecimiento')
                ->whereIn('venta.estado',['Entregado','Devolucion'])
                ->whereDate('venta.fecha', ">=", $fecha_producto)
                ->whereDate('venta.fecha', "<=", $fecha_fin)
                ->where($criterio, 'like', '%'.$buscar.'%')
                ->groupBy('detalle_venta.id_lote')
                ->orderBy('venta.fecha','desc')
                ->paginate(70); 

            }else{

                $obj= DetalleVenta::join('venta','detalle_venta.id_venta','=','venta.id')
                ->join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
                ->join('articulo','articulo.id','=','tienda_articulo.id_articulo')
                ->join('lote','detalle_venta.id_lote','=','lote.id')
                ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
                ->select(DB::raw('SUM(detalle_venta.cantidad) cantidad'),'articulo.nombre_comercial','detalle_venta.id_venta','detalle_venta.id_lote','venta.fecha','proveedor.nombre as laboratorio','lote.lote',
                'lote.fecha_vecimiento')
                ->whereIn('venta.estado',['Entregado','Devolucion'])
                ->whereDate('venta.fecha', ">=", $fecha_producto)
                ->whereDate('venta.fecha', "<=", $fecha_fin)
                ->where($criterio, 'like', '%'.$buscar.'%')
                ->groupBy('detalle_venta.id_lote')
                ->orderBy('venta.fecha','desc')
                ->paginate(70); 
            }
           }
        return $obj;
     }*/

    public function cantidadProductoFecha(Request $request)
    {
        $fechaInicio = $request->input('fecha_producto');
        $fechaFin = $request->input('fecha_fin');
        $buscar = trim($request->input('buscar', ''));
        $criterio = $request->input('criterio', '');

        $query = DetalleVenta::join('venta', 'detalle_venta.id_venta', '=', 'venta.id')
            ->leftJoin('cliente', 'venta.id_cliente', '=', 'cliente.id')
            ->join('tienda_articulo', 'detalle_venta.id_producto', '=', 'tienda_articulo.id')
            ->join('articulo', 'articulo.id', '=', 'tienda_articulo.id_articulo')
            ->join('lote', 'detalle_venta.id_lote', '=', 'lote.id')
            ->join('proveedor', 'articulo.id_proveedor', '=', 'proveedor.id')
            ->select(
                DB::raw('SUM(detalle_venta.cantidad) as cantidad'),
                'articulo.nombre_comercial',
                'detalle_venta.id_venta',
                'detalle_venta.id_lote',
                'venta.fecha',
                'proveedor.nombre as laboratorio',
                'lote.lote',
                'lote.fecha_vecimiento',
                'cliente.nombre as cliente'
            )
            ->whereIn('venta.estado', ['Entregado', 'Devolucion'])
            ->whereBetween(DB::raw('DATE(venta.fecha)'), [$fechaInicio, $fechaFin]);

        if ($buscar !== '' && $criterio !== '') {
            $criteriosValidos = ['articulo.nombre_comercial', 'proveedor.nombre', 'lote.lote'];
            if (in_array($criterio, $criteriosValidos)) {
                $query->where($criterio, 'like', "%{$buscar}%");
            }
        }

        $obj = $query->groupBy('detalle_venta.id_lote', 'detalle_venta.id_venta')
            ->orderByDesc('venta.fecha')
            ->paginate(70);

        return response()->json($obj);
    }


     public function historialCliente(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $venta = Venta::join('cliente','venta.id_cliente','=','cliente.id')
            ->join('pago','venta.id','=','pago.id_venta')
            ->select('venta.id_cliente','cliente.nombre as cliente',DB::raw('count(cliente.id) as contador'),DB::raw('SUM(pago.saldo) as total'))
            ->where('venta.id_tipo_pago','=',2)
            ->where('cliente.estado','=',1)
            ->whereIn('venta.estado',['Entregado','Cancelado'])
            //->where('venta.estado','!=','Cancelado')
            ->orderBy('venta.id', 'desc')
            ->groupBy('cliente.id')
            ->paginate(15);
        }
        else{
            $venta = Venta::join('cliente','venta.id_cliente','=','cliente.id')
            ->join('pago','venta.id','=','pago.id_venta')
            ->select('venta.id_cliente','cliente.nombre as cliente',DB::raw('count(cliente.id) as contador'),DB::raw('SUM(pago.saldo) as total'))
            ->where('venta.id_tipo_pago','=',2)
            ->where('cliente.estado','=',1)
            ->whereIn('venta.estado',['Entregado','Cancelado'])
            //->where('venta.estado','!=','Cancelado')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('venta.id', 'desc')
            ->groupBy('cliente.id')
            ->paginate(15);
        }
        return $venta;
    }
    
    public function VentaArqueoEfectivo(Request $request){
        $id_usuario=\Auth::user()->id;

        $venta=Venta::select(DB::raw('SUM(venta.total_efectivo) as total_e'))
        // ->where('venta.estado','=','Entregado')
        ->whereIn('venta.estado',['Entregado','Devolucion'])
        ->where('venta.id_tipo_pago','=',1)
        ->where('venta.control','=',0)
        ->where('venta.id_usuario','=',$id_usuario)
        ->get();
        $data=(object) $venta;
        return $data;
        
    }
    public function VentaArqueoDeposito(Request $request){
        $id_usuario=\Auth::user()->id;

        $venta=Venta::select(DB::raw('SUM(venta.total_deposito) as total_d'))
        // ->where('venta.estado','=','Entregado')
        ->whereIn('venta.estado',['Entregado','Devolucion'])
        ->where('venta.id_tipo_pago','=',1)
        ->where('venta.control','=',0)
        ->where('venta.id_usuario','=',$id_usuario)
        ->get();
        $data=(object) $venta;
        return $data;
        
    }
}
