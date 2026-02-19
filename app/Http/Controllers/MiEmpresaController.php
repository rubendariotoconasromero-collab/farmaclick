<?php

namespace App\Http\Controllers;

use App\Models\MiEmpresa;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DB;

class MiEmpresaController extends BitacoraController
{

    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $mi_empresa = MiEmpresa::orderBy('mi_empresa.id', 'desc')->paginate(15);
        }
        else{
            $mi_empresa = MiEmpresa::where('mi_empresa.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('mi_empresa.id', 'desc')->paginate(15);
        }
        return $mi_empresa;
    }

    public function indexEmpresa(Request $request){
        $mi_empresa = MiEmpresa::where('id',1)->get();
        return $mi_empresa;
    }

    public function guardar(Request $request){
        try{
                DB::beginTransaction();

                $obj = new MiEmpresa();
                $obj->nombre=$request->nombre;
                $obj->nit=$request->nit;
                $obj->representante=$request->representante;
                $obj->direccion=$request->direccion;
                $obj->telefono=$request->telefono;
                $obj->descripcion=$request->descripcion;
                $obj->localidad=$request->localidad;
                $obj->sitio_web=$request->sitio_web;
                $obj->correo=$request->correo;

                if($request->foto==null){
                    $obj->foto ='defecto.png';
                }
                else{
                    $explode=explode(',',$request->foto);
                    $decoded=\base64_decode($explode[1]);
                    if(str_contains($explode[0],'jpeg')){
                        $extension='jpg';
                    }
                    else{
                        $extension='png';
                    }
                    $fileName = \str_random().'.'.$extension;
                    $path= 'img/mi_empresa'.'/'.$fileName;
                    \file_put_contents($path,$decoded);
                    $obj->foto=$fileName;
                }

                $obj->save();


            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function modificar(Request $request){
        $obj= MiEmpresa::findOrFail($request->id);
        $obj->nombre=$request->nombre;
        $obj->nit=$request->nit;
        $obj->representante='';
        $obj->direccion=$request->direccion;
        $obj->telefono=$request->telefono;
        $obj->descripcion='';
        $obj->localidad='';
        $obj->sitio_web='';
        $obj->correo='';
        $obj->color_login=$request->colorLogin;
        $obj->color_menu=$request->colorMenu;

        if($request->foto==null){
            $obj->foto ='logo.png';
        }
        else{
            if($request->imagenActual==$request->foto){
                $obj->foto =$request->imagenActual;
            }
            else{
                $explode=explode(',',$request->foto);
                $decoded=\base64_decode($explode[1]);
                if(str_contains($explode[0],'jpeg')){
                    $extension='jpg';
                }
                else{
                    $extension='png';
                }
                $fileName = \str_random().'.'.$extension;
                $path= 'img/logo'.'/'.$fileName;
                \file_put_contents($path,$decoded);
                $obj->foto=$fileName;
            }
        }

        if($request->logo==null){
            $obj->logo_sistema ='logo.png';
        }
        else{
            if($request->imagenActualLogo==$request->logo){
                $obj->logo_sistema =$request->imagenActualLogo;
            }
            else{
                $explode=explode(',',$request->logo);
                $decoded=\base64_decode($explode[1]);
                if(str_contains($explode[0],'jpeg')){
                    $extension='jpg';
                }
                else{
                    $extension='png';
                }
                $fileName = \str_random().'.'.$extension;
                $path= 'img/logo'.'/'.$fileName;
                \file_put_contents($path,$decoded);
                $obj->logo_sistema=$fileName;
            }
        }


        if($request->logo_login==null){
            $obj->logo_login ='logo_login.png';
        }
        else{
            if($request->imagenActualLogoLogin==$request->logo_login){
                $obj->logo_login =$request->imagenActualLogoLogin;
            }
            else{
                $explode=explode(',',$request->logo_login);
                $decoded=\base64_decode($explode[1]);
                if(str_contains($explode[0],'jpeg')){
                    $extension='jpg';
                }
                else{
                    $extension='png';
                }
                $fileName = \str_random().'.'.$extension;
                $path= 'img/logo'.'/'.$fileName;
                \file_put_contents($path,$decoded);
                $obj->logo_login=$fileName;
            }
        }

        if($request->logo_usuario==null){
            $obj->logo_usuario ='logo.png';
        }
        else{
            if($request->imagenActualLogoUsuario==$request->logo_usuario){
                $obj->logo_usuario =$request->imagenActualLogoUsuario;
            }
            else{
                $explode=explode(',',$request->logo_usuario);
                $decoded=\base64_decode($explode[1]);
                if(str_contains($explode[0],'jpeg')){
                    $extension='jpg';
                }
                else{
                    $extension='png';
                }
                $fileName = \str_random().'.'.$extension;
                $path= 'img/logo'.'/'.$fileName;
                \file_put_contents($path,$decoded);
                $obj->logo_usuario=$fileName;
            }
        }

        if($request->fondo_login==null){
            $obj->fondo_login ='fondo_login.png';
        }
        else{
            if($request->imagenActualFondoLogin==$request->fondo_login){
                $obj->fondo_login =$request->imagenActualFondoLogin;
            }
            else{
                $explode=explode(',',$request->fondo_login);
                $decoded=\base64_decode($explode[1]);
                if(str_contains($explode[0],'jpeg')){
                    $extension='jpg';
                }
                else{
                    $extension='png';
                }
                $fileName = \str_random().'.'.$extension;
                $path= 'img/logo'.'/'.$fileName;
                \file_put_contents($path,$decoded);
                $obj->fondo_login=$fileName;
            }
        }

        $obj->save();


        $datos = [
            'tabla' => 'mi_empresa',
            'codigo_tabla' => $obj->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);


        //modificar Tienda


        $id_tienda = 1;
        $tienda= Tienda::findOrFail($id_tienda);
        $tienda->direccion=$request->direccion;
        $tienda->nombre=$request->nombre;
        $tienda->telefono=$request->telefono;

        if($request->foto==null){
            $tienda->foto ='logo.png';
        }
        else{
            if($request->imagenActual==$request->foto){
                $tienda->foto =$request->imagenActual;
            }
            else{
                $explode=explode(',',$request->foto);
                $decoded=\base64_decode($explode[1]);
                if(str_contains($explode[0],'jpeg')){
                    $extension='jpg';
                }
                else{
                    $extension='png';
                }
                $fileName = \str_random().'.'.$extension;
                $path= 'img/mi_empresa'.'/'.$fileName;
                \file_put_contents($path,$decoded);
                $tienda->foto=$fileName;
            }
        }
        $tienda->save();

        $id_motivo_ajuste=0;
        if($id_tienda == 2) {
            $id_motivo_ajuste = 7;
        }else if($id_tienda == 3){
            $id_motivo_ajuste = 8;
        }else if($id_tienda == 4){
            $id_motivo_ajuste = 9;
        }else{
            //
        }

        $affected = DB::table('motivo_ajuste')
                ->where('id',$id_motivo_ajuste)
                ->update(['nombre' => 'Venta '.$request->nombre]);

        $datos = [
            'tabla' => 'tienda',
            'codigo_tabla' => $tienda->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);
    }

    public function cantidadRegistros(){
        $cantidad = DB::table('mi_empresa')->count();

        $data=['nro' =>$cantidad];
        return $data;
    }
}
