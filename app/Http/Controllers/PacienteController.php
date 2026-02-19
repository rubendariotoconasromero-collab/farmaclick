<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Animal;
use App\Models\Cliente;
use App\Http\Requests\PacienteRequest;
use App\Models\Control;
use DB;
use DateTime;

class PacienteController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Paciente::join('cliente','paciente.id_cliente','=','cliente.id')
            ->join('animal','paciente.id_animal','=','animal.id')
            ->select('paciente.id','paciente.nombre as mascota','paciente.especie','paciente.edad','paciente.color','paciente.raza','paciente.sexo','paciente.id_cliente','paciente.id_animal'
            ,'paciente.peso','paciente.cirugias','paciente.enfermedades','paciente.vacunas','animal.nombre as animal','cliente.nombre as cliente','paciente.estado')
            //->where('animal.estado', 1)
            ->orderBy('paciente.id','desc')->paginate(15);
        }
        else{
            $obj= Paciente::join('cliente','paciente.id_cliente','=','cliente.id')
            ->join('animal','paciente.id_animal','=','animal.id')
            ->select('paciente.id','paciente.nombre as mascota','paciente.especie','paciente.edad','paciente.color','paciente.raza','paciente.sexo','paciente.id_cliente','paciente.id_animal'
            ,'paciente.peso','paciente.cirugias','paciente.enfermedades','paciente.vacunas','animal.nombre as animal','cliente.nombre as cliente','paciente.estado')
            //->where('animal.estado', 1)
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('paciente.id','desc')->paginate(15);            
        }
        return $obj;
    }
    public function guardar(Request $request){
        try{

                DB::beginTransaction();
                $obj = new Paciente();
                $obj->nombre=$request->nombre;
                $obj->especie=$request->especie;
                $obj->edad=$request->edad;
                $obj->color=$request->color;
                $obj->raza=$request->raza;
                $obj->sexo=$request->sexo;
                $obj->peso=$request->peso;
                $obj->cirugias=$request->cirugias;
                $obj->enfermedades=$request->enfermedades;
                $obj->vacunas=$request->vacunas;
                $obj->estado=$request->estado;
                $obj->id_cliente=$request->id_cliente;
                $obj->id_animal=$request->id_animal;
                $obj->save();
     
            $datos = [
                'tabla' => 'paciente',
                'codigo_tabla' => $obj->id,
                'transaccion' => 'guardar',
            ];
            $this->guardarBitacora($datos);

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function modificar(Request $request){

            //dd($request->id);
            $obj= Paciente::findOrFail($request->id);
            $obj->nombre=$request->nombre;
            $obj->especie=$request->especie;
            $obj->edad=$request->edad;
            $obj->color=$request->color;
            $obj->raza=$request->raza;
            $obj->sexo=$request->sexo;
            $obj->peso=$request->peso;
            $obj->cirugias=$request->cirugias;
            $obj->enfermedades=$request->enfermedades;
            $obj->estado=$request->estado;
            $obj->id_cliente=$request->id_cliente;
            $obj->id_animal=$request->id_animal;
            $obj->save();


            $datos = [
                'tabla' => 'paciente',
                'codigo_tabla' => $obj->id,
                'transaccion' => 'modificar',
            ];
            $this->guardarBitacora($datos);

    }
    public function desactivar(Request $request){
        $obj = Paciente::findOrFail($request->id);
        $obj->estado = '0';
        $obj->save();

        $datos = [
            'tabla' => 'paciente',
            'codigo_tabla' => $obj->id,
            'transaccion' => 'desactivar',
        ];
        $this->guardarBitacora($datos);
    }
    public function activar(Request $request){
        $obj = Paciente::findOrFail($request->id);
        $obj->estado = '1';
        $obj->save();

        $datos = [
            'tabla' => 'paciente',
            'codigo_tabla' => $obj->id,
            'transaccion' => 'activar',
        ];
        $this->guardarBitacora($datos);
    }
    public function cantidadRegistros(){
        $cantidad = DB::table('paciente')->count();
        $data=['nro' =>$cantidad];
        return $data;
    }
    public function selectPaciente(){  
        $obj = Paciente::join('animal','paciente.id_animal','=','animal.id')
        ->join('cliente','paciente.id_cliente','=','cliente.id')
        ->select('paciente.id', 'paciente.nombre', 'paciente.id_animal','paciente.edad','animal.nombre as animal','paciente.id_cliente','paciente.peso','cliente.nombre as cliente','cliente.telefono','cliente.direccion','paciente.color','paciente.sexo','paciente.raza')->orderBy('paciente.id','desc')->get(); 
        return $obj;
    }

    public function selectPaciente2(Request $request){  
        $id_cliente = $request->id_cliente;
        //dd($id_cliente);
        $obj = Paciente::join('animal','paciente.id_animal','=','animal.id')
        ->select('paciente.id', 'paciente.nombre', 'paciente.id_animal','paciente.edad','animal.nombre as animal','paciente.id_cliente','paciente.peso','paciente.especie','paciente.color','paciente.sexo','paciente.raza')
        ->where('paciente.id_cliente','=',$id_cliente)
        ->orderBy('paciente.id','desc')->get(); 
        return $obj;
    }

    public function selectPaciente3(Request $request){  
        $id_paciente = $request->id_paciente;
        //dd($id_paciente);
        $obj = Paciente::join('animal','paciente.id_animal','=','animal.id')
        ->select('paciente.id', 'paciente.nombre', 'paciente.id_animal','paciente.edad','animal.nombre as animal','paciente.id_cliente','paciente.peso','paciente.especie','paciente.color','paciente.sexo','paciente.raza')
        ->where('paciente.id','=',$id_paciente)
        ->orderBy('paciente.id','desc')->get(); 
        return $obj;
    }
}
