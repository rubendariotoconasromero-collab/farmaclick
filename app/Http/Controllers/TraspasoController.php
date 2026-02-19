<?php

namespace App\Http\Controllers;

use App\Models\traspaso;
use Illuminate\Http\Request;
use App\Models\DetalleTraspaso;
use App\Models\TiendaArticulo;
use DateTime;
use DB;

class TraspasoController extends BitacoraController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $traspaso = Traspaso::join('tienda as t1','traspasos.id_tienda1','=','t1.id')
            ->join('tienda as t2','traspasos.id_tienda2','=','t2.id')
            ->select('traspasos.id','traspasos.id_tienda1','traspasos.id_tienda2','traspasos.fecha',
            'traspasos.hora','traspasos.glosa','traspasos.estado','t1.nombre as tienda1','t2.nombre as tienda2')
            ->orderBy('traspasos.id', 'desc')->paginate(15);
        }
        else{
            $traspaso = Traspaso::join('tienda as t1','traspasos.id_tienda1','=','t1.id')
            ->join('tienda as t2','traspasos.id_tienda2','=','t2.id')
            ->select('traspasos.id','traspasos.id_tienda1','traspasos.id_tienda2','traspasos.fecha',
            'traspasos.hora','traspasos.glosa','traspasos.estado','t1.nombre as tienda1','t2.nombre as tienda2')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('traspasos.id', 'desc')->paginate(15);
        }
        return $traspaso;
    }

    private function actualizarStockTienda1($id,$cantVenta){
        DB::table('tienda_articulo')->where('tienda_articulo.id','=',$id)
        ->decrement('stock', $cantVenta);
    }

    private function actualizarStockTienda2($id_producto,$cantVenta,$tienda2){
        // $obj= TiendaArticulo::->where('tienda_articulo.id_articulo',$id_producto)
        // ->where('tienda_articulo.id_tienda',$tienda2)
        DB::table('tienda_articulo')->where('tienda_articulo.id_articulo','=',$id_producto)
        ->where('tienda_articulo.id_tienda','=',1)
        ->increment('stock', $cantVenta);
    }

    

    public function guardar(Request $request){
        try{
            DB::beginTransaction();
            $traspaso = new Traspaso();
            $traspaso->fecha=$request->fecha;
            $traspaso->hora=$request->hora;
            $traspaso->glosa=$request->glosa;
            $traspaso->id_tienda1=$request->id_tienda1;
            $traspaso->id_tienda2=$request->id_tienda2;
            $traspaso->id_usuario=\Auth::user()->id;
            $traspaso->save();

            $id_tienda1=$request->id_tienda1;
            $id_tienda2=$request->id_tienda2;
            

            $detalles = $request->detalle;
            foreach($detalles as $ep=>$det){
                $obj = new DetalleTraspaso();
                $obj->id_tienda_articulo= $det['id'];
                $obj->id_traspaso= $traspaso->id;
                $obj->cantidad= $det['stock'];
                $obj->save();

                $this->actualizarStockTienda1($det['id'],$det['stock']);
                
                $id_articulo=$det['id_articulo'];
                $tienda_articulo=DB::select("SELECT id, id_articulo, id_tienda, stock FROM tienda_articulo WHERE id_articulo = $id_articulo and id_tienda = $id_tienda2");

                // $tienda_articulo= TiendaArticulo::where('tienda_articulo.id_articulo',$det['id_articulo'])
                // ->where('tienda_articulo.id_tienda',$tienda2)->get();

                //dd($tienda_articulo);
                if($tienda_articulo != []){
                    $tienda_articulo_stock = $tienda_articulo[0]->stock ;
                } else {
                    //
                }

                if($tienda_articulo != []) {
                    $tienda_articulo = TiendaArticulo::findOrFail($tienda_articulo[0]->id);
                    $tienda_articulo->stock = $tienda_articulo_stock + $det['stock'];
                    $tienda_articulo->save();

                    $datos = [
                        'tabla' => 'tienda_articulo',
                        'codigo_tabla' => $tienda_articulo->id,
                        'transaccion' => 'modificar',
                    ];
                    $this->guardarBitacora($datos);


                }else{
                    $obj = new TiendaArticulo();
                    $obj->id_articulo= $id_articulo;
                    $obj->id_tienda= $id_tienda2;
                    $obj->stock= $det['stock'];
                    $obj->save();

                    $datos = [
                        'tabla' => 'tienda_articulo',
                        'codigo_tabla' => $obj->id,
                        'transaccion' => 'guardar',
                    ];
                    $this->guardarBitacora($datos);

                }

                // $articulo = Articulo::findOrFail($det['id_articulo']);
                // $articulo->costo_venta=$det['costo_venta'];
                // $articulo->save(); 
            }


            $datos = [
                'tabla' => 'traspaso',
                'codigo_tabla' => $traspaso->id,
                'transaccion' => 'guardar',
            ];
            $this->guardarBitacora($datos);
        
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function detalleTraspaso(Request $request){
        
        $id=$request->id; 
        $obj= detalleTraspaso::join('traspasos','detalle_traspasos.id_traspaso','=','traspasos.id')
        ->join('tienda_articulo','detalle_traspasos.id_tienda_articulo','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->leftjoin('categoria', function($join){
            $join->orOn('articulo.id_categoria','=','categoria.id');
        })
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_traspasos.id_traspaso','detalle_traspasos.cantidad','detalle_traspasos.id_tienda_articulo',
        'articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre','articulo.marca','categoria.nombre as categoria',
        'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.nombre as articulo',
        'tienda.nombre as tienda','tienda.id as id_tienda')
        ->where('detalle_traspasos.id_traspaso','=',$id)
        ->get();
        return $obj;
    }

    public function pdfTraspaso(Request $request){

        $id = $request->id;
        $foto = $request->foto;
        
        $traspaso = Traspaso::join('users','traspasos.id_usuario','=','users.id')
        ->join('tienda as t1','traspasos.id_tienda1','=','t1.id')
        ->join('tienda as t2','traspasos.id_tienda2','=','t2.id')
        ->select('traspasos.id','traspasos.fecha','traspasos.glosa','t1.nombre as tienda1','t2.nombre as tienda2',
        'users.name')
        ->where('traspasos.id',$id)
        ->orderBy('traspasos.id','desc')->get();

        $detalleCompra= detalleTraspaso::join('traspasos','detalle_traspasos.id_traspaso','=','traspasos.id')
        ->join('tienda_articulo','detalle_traspasos.id_tienda_articulo','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->leftjoin('categoria', function($join){
            $join->orOn('articulo.id_categoria','=','categoria.id');
        })
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_traspasos.id_traspaso','detalle_traspasos.cantidad','detalle_traspasos.id_tienda_articulo',
        'articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre as producto','articulo.marca','categoria.nombre as categoria',
        'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.nombre as articulo',
        'tienda.nombre as tienda','tienda.id as id_tienda')
        ->where('detalle_traspasos.id_traspaso','=',$id)
        ->get();

        $usuario = \Auth::user()->name;
        $objdate = new DateTime();
        $fecha_impresion=$objdate->format('d/m/Y');
        $tienda1=$traspaso[0]->tienda1;
        $fecha=$traspaso[0]->fecha;
        $tienda2=$traspaso[0]->tienda2;
        $usuario=$traspaso[0]->name;
        $total=$traspaso[0]->total;
        $detalles=$detalleCompra;

        $cont=Traspaso::count();
        $pdf = \PDF::loadView('pdf.traspaso.traspaso', [
            'traspasos'=>$traspaso,
            'fecha'=>$fecha,
            'tienda1'=>$tienda1,
            'tienda2'=>$tienda2,
            'detalles'=>$detalles,
            'total'=>$total,
            'foto'=>$foto,
            'fecha_impresion'=>$fecha_impresion,
            'usuario'=>$usuario
        ]);
        return $pdf->setPaper('letter', 'portrait')->stream('Compras.pdf');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\traspaso  $traspaso
     * @return \Illuminate\Http\Response
     */
    public function show(traspaso $traspaso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\traspaso  $traspaso
     * @return \Illuminate\Http\Response
     */
    public function edit(traspaso $traspaso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\traspaso  $traspaso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, traspaso $traspaso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\traspaso  $traspaso
     * @return \Illuminate\Http\Response
     */
    public function destroy(traspaso $traspaso)
    {
        //
    }
}
