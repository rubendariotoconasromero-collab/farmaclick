<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistorialClinico;
use App\Models\DetalleHistoria;
use DB;

class HistorialClinicoController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $id_usuario = \Auth::user()->id;
        if($buscar==''){
            $obj=DB::select("SELECT historial_clinico.id,cliente.nombre as propietario, paciente.nombre as mascota, paciente.especie 
            , paciente.color,paciente.id as id_paciente,cliente.id as id_cliente,paciente.edad,paciente.raza,cliente.telefono,cliente.direccion,paciente.id_animal , animal.nombre as especie
            FROM  historial_clinico,cliente,paciente,animal
            WHERE historial_clinico.id_cliente=cliente.id and historial_clinico.id_paciente=paciente.id and paciente.id_animal=animal.id
            GROUP by paciente.nombre");   
        }
        else{
            $obj=DB::select("SELECT historial_clinico.id,cliente.nombre as propietario, paciente.nombre as mascota, paciente.especie 
            , paciente.color,paciente.id as id_paciente,cliente.id as id_cliente,paciente.edad,paciente.raza,cliente.telefono,cliente.direccion,paciente.id_animal , animal.nombre as especie
            FROM  historial_clinico,cliente,paciente,animal
            WHERE historial_clinico.id_cliente=cliente.id and historial_clinico.id_paciente=paciente.id and paciente.id_animal=animal.id and paciente.nombre LIKE '%$buscar%'
            GROUP by paciente.nombre");
        }
        return $obj;
    }
    public function historia_clinica(Request $request){
        $id = $request->id;
        //dd($id);
        $cuota=DB::select("SELECT detalle_historia.id,detalle_historia.fecha,detalle_historia.peso,detalle_historia.motivo,detalle_historia.estado
        FROM detalle_historia,historial_clinico
        WHERE detalle_historia.id_historia=historial_clinico.id and detalle_historia.id_historia='$id'");
        return $cuota;
    }
    public function ultimo_id(){
       
        $id_historia = DB::select('SELECT max(historial_clinico.id) as id FROM  historial_clinico');
        return $id_historia;
    }
    public function nro_historia(Request $request){
        $id_cliente = $request->id_cliente;
        $id_paciente = $request->id_paciente;
        //dd($id_paciente);
        $cuota=DB::select("SELECT historial_clinico.id
        FROM historial_clinico 
        WHERE id_cliente='$id_cliente' and id_paciente='$id_paciente'
        GROUP BY historial_clinico.id");
        return $cuota;
    }
    public function guardar(Request $request){
        try{
                DB::beginTransaction();

                // REGISTRO DE CONTROL VACUNA
                $historial_clinico = new HistorialClinico();
                if($request->nro_historia == 0){
                    $historial_clinico->nro_historia=$request->nro_nuevo;
                }else
                {
                    $historial_clinico->nro_historia=$request->nro_historia;
                }
                $historial_clinico->id_cliente=$request->id_cliente;
                $historial_clinico->id_paciente=$request->id_paciente;
                $historial_clinico->id_usuario=\Auth::user()->id;
                $historial_clinico->save();
                
                
                $obj = new DetalleHistoria();
                if($request->nro_historia == 0) {
                    $obj->id_historia= $historial_clinico->id;
                    $obj->parvovirus=$request->parvovirus;
                    $obj->hexavalente=$request->hexavalente;
                    $obj->octavalente=$request->octavalente;
                    $obj->rabia_perro=$request->rabia_perro;
                    $obj->tos_perrera=$request->tos_perrera;
                    $obj->ninguna_perro=$request->ninguna_perro;
                    $obj->obs_p=$request->obs_p;
                    $obj->obs_perro=$request->obs_perro;

                    //GATO
                    $obj->triple_felina=$request->triple_felina;
                    $obj->rabia_gato=$request->rabia_gato;
                    $obj->ninguna_gato=$request->ninguna_gato;
                    $obj->obs_g=$request->obs_g;
                    $obj->obs_gato=$request->obs_gato;
                    $obj->desparacitacion=$request->desparacitacion;
                    $obj->desparacitacion_cuando=$request->desparacitacion_cuando;
                    //TEMPERATURA
                    //$obj->temperatura=$request->temperatura;
                    $obj->temperatura = $request->temperatura == '' ? '0' : $request->temperatura;
                    //FC
                    //$obj->fc=$request->fc;
                    $obj->fc = $request->fc == '' ? '0' : $request->fc;
                    $obj->taquicardia=$request->taquicardia;
                    $obj->arritmia=$request->arritmia;
                    $obj->bradicardia=$request->bradicardia;
                    $obj->sin_alteracion=$request->sin_alteracion;
                    //FR
                    // $obj->fr=$request->fr;
                    $obj->fr = $request->fr == '' ? '0' : $request->fr;
                    $obj->bueno_fr=$request->normal_fr;
                    $obj->disnea=$request->disnea;
                    //MUCOSAS
                    $obj->rosada=$request->rosada;
                    $obj->palidas=$request->palidas;
                    $obj->ictericas=$request->ictericas;
                    $obj->cianotica=$request->cianotica;
                    //APETITO
                    $obj->normal_apetito=$request->normal_apetito;
                    $obj->disminuido=$request->disminuido;
                    $obj->anorexico=$request->anorexico;
                    //HIDRATACION
                    $obj->normal_mucosa=$request->normal_hidratacion;
                    $obj->leve=$request->leve;
                    $obj->moderada=$request->moderada;
                    $obj->marcada=$request->marcada;
                    //ESTADO GENERAL
                    $obj->bueno_estado=$request->bueno_estado;
                    $obj->regular=$request->regular;
                    $obj->malo=$request->malo;
                    //ANTECEDENTES ENFERMEDADES
                    $obj->enfermedades=$request->enfermedades;
                    $obj->enfermedades_cuales=$request->enfermedades_cuales;
                    $obj->enfermedades_cuando=$request->enfermedades_cuando;
                    //ANTECEDENTES CIRUGIA
                    $obj->cirugia=$request->cirugia;
                    $obj->cirugia_cuales=$request->cirugia_cuales;
                    $obj->cirugia_cuando=$request->cirugia_cuando;
                    //ORGANOS DE SENTIDO
                    $obj->ocular=$request->ocular;
                    $obj->nariz=$request->nariz;
                    $obj->bucal=$request->bucal;
                    $obj->piel_anexo=$request->piel_anexo;
                    $obj->oidos=$request->oidos;
                    $obj->vulvar=$request->vulvar;
                    $obj->prepucial=$request->prepucial;
                    //APARATO DIGESTIVO,RESPIRATORIO,GENITO URINARIO,NERVIOSO
                    $obj->digestivo_sin_alteracion=$request->digestivo_sin_alteracion;
                    $obj->digestivo_obs=$request->digestivo_obs;
                    $obj->respiratorio_sin_alteracion=$request->respiratorio_sin_alteracion;
                    $obj->respiratorio_obs=$request->respiratorio_obs;
                    $obj->urinario_sin_alteracion=$request->urinario_sin_alteracion;
                    $obj->urinario_obs=$request->urinario_obs;
                    $obj->nervioso_sin_alteracion=$request->nervioso_sin_alteracion;
                    $obj->nervioso_obs=$request->nervioso_obs;
                    //EXAMENES COMPLEMENTARIOS
                    $obj->muestra=$request->muestra;
                    $obj->examenes_solicitado=$request->examenes_solicitado;
                    //TRATAMIENTO INDICADO
                    $obj->fecha1=$request->fecha1;
                    $obj->t1=$request->temperatura == '' ? '0' : $request->temperatura;
                    $obj->dr1=$request->doctor;
                    $obj->hora1=$request->hora1;
                    $obj->costo1=$request->costo1;
                    $obj->observaciones1=$request->observaciones1;
                    $obj->primer_dia=$request->primer_dia;

                    $obj->hidratacion=$request->hidratacion;
                    //dd($request->nro_nuevo);
                }else
                {
                    $obj->id_historia= $historial_clinico->nro_historia;
                    //vacunaciones
                    $obj->parvovirus=$request->parvovirus;
                    $obj->hexavalente=$request->hexavalente;
                    $obj->octavalente=$request->octavalente;
                    $obj->rabia_perro=$request->rabia_perro;
                    $obj->tos_perrera=$request->tos_perrera;
                    $obj->ninguna_perro=$request->ninguna_perro;
                    $obj->obs_p=$request->obs_p;
                    $obj->obs_perro=$request->obs_perro;

                    //GATO
                    $obj->triple_felina=$request->triple_felina;
                    $obj->rabia_gato=$request->rabia_gato;
                    $obj->ninguna_gato=$request->ninguna_gato;
                    $obj->obs_g=$request->obs_g;
                    $obj->obs_gato=$request->obs_gato;
                    $obj->desparacitacion=$request->desparacitacion;
                    $obj->desparacitacion_cuando=$request->desparacitacion_cuando;
                    //TEMPERATURA
                    //$obj->temperatura=$request->temperatura;
                    $obj->temperatura = $request->temperatura == '' ? '0' : $request->temperatura;
                    //FC
                    //$obj->fc=$request->fc;
                    $obj->fc = $request->fc == '' ? '0' : $request->fc;
                    $obj->taquicardia=$request->taquicardia;
                    $obj->arritmia=$request->arritmia;
                    $obj->bradicardia=$request->bradicardia;
                    $obj->sin_alteracion=$request->sin_alteracion;
                    //FR
                    // $obj->fr=$request->fr;
                    $obj->fr = $request->fr == '' ? '0' : $request->fr;
                    $obj->bueno_fr=$request->normal_fr;
                    $obj->disnea=$request->disnea;
                    //MUCOSAS
                    $obj->rosada=$request->rosada;
                    $obj->palidas=$request->palidas;
                    $obj->ictericas=$request->ictericas;
                    $obj->cianotica=$request->cianotica;
                    //APETITO
                    $obj->normal_apetito=$request->normal_apetito;
                    $obj->disminuido=$request->disminuido;
                    $obj->anorexico=$request->anorexico;
                    //HIDRATACION
                    $obj->normal_mucosa=$request->normal_hidratacion;
                    $obj->leve=$request->leve;
                    $obj->moderada=$request->moderada;
                    $obj->marcada=$request->marcada;
                    //ESTADO GENERAL
                    $obj->bueno_estado=$request->bueno_estado;
                    $obj->regular=$request->regular;
                    $obj->malo=$request->malo;
                    //ANTECEDENTES ENFERMEDADES
                    $obj->enfermedades=$request->enfermedades;
                    $obj->enfermedades_cuales=$request->enfermedades_cuales;
                    $obj->enfermedades_cuando=$request->enfermedades_cuando;
                    //ANTECEDENTES CIRUGIA
                    $obj->cirugia=$request->cirugia;
                    $obj->cirugia_cuales=$request->cirugia_cuales;
                    $obj->cirugia_cuando=$request->cirugia_cuando;
                    //ORGANOS DE SENTIDO
                    $obj->ocular=$request->ocular;
                    $obj->nariz=$request->nariz;
                    $obj->bucal=$request->bucal;
                    $obj->piel_anexo=$request->piel_anexo;
                    $obj->oidos=$request->oidos;
                    $obj->vulvar=$request->vulvar;
                    $obj->prepucial=$request->prepucial;
                    //APARATO DIGESTIVO,RESPIRATORIO,GENITO URINARIO,NERVIOSO
                    $obj->digestivo_sin_alteracion=$request->digestivo_sin_alteracion;
                    $obj->digestivo_obs=$request->digestivo_obs;
                    $obj->respiratorio_sin_alteracion=$request->respiratorio_sin_alteracion;
                    $obj->respiratorio_obs=$request->respiratorio_obs;
                    $obj->urinario_sin_alteracion=$request->urinario_sin_alteracion;
                    $obj->urinario_obs=$request->urinario_obs;
                    $obj->nervioso_sin_alteracion=$request->nervioso_sin_alteracion;
                    $obj->nervioso_obs=$request->nervioso_obs;
                    //EXAMENES COMPLEMENTARIOS
                    $obj->muestra=$request->muestra;
                    $obj->examenes_solicitado=$request->examenes_solicitado;
                    //TRATAMIENTO INDICADO
                    $obj->fecha1=$request->fecha1;
                    $obj->t1=$request->temperatura == '' ? '0' : $request->temperatura;
                    $obj->dr1=$request->doctor;
                    $obj->hora1=$request->hora1;
                    $obj->costo1=$request->costo1;
                    $obj->observaciones1=$request->observaciones1;
                    $obj->primer_dia=$request->primer_dia;

                    $obj->hidratacion=$request->hidratacion;
      
                }
                $obj->id_personal=$request->id_personal;
                $obj->id_paciente=$request->id_paciente;
                $obj->peso= $request->peso;
                $obj->edad= $request->edad;
                $obj->meses= 0;
                $obj->fecha= $request->fecha;
                $obj->motivo= $request->descripcion;
                $obj->estado= 0;
                $obj->save();

                $affected = DB::table('paciente')
                ->where('id', $request->id_paciente)
                ->update(['edad' => $request->edad]); 
                            
                $datos = [
                    'tabla' => 'control_vacuna',
                    'codigo_tabla' => $historial_clinico->id,
                    'transaccion' => 'guardar',
                ];
                $this->guardarBitacora($datos);  
                // FIN DE CONTROL VACUNA
                      


                
            
            
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function detalle_historia(Request $request){
        $id = $request->id;
        //dd($id);
        $id_historial = $request->id_historia;

            $obj=DB::select("SELECT p.nombre as personal ,dh.id,dh.id_historia,dh.id_personal
            ,dh.id_paciente,dh.peso,dh.fecha,dh.motivo,dh.estado,dh.parvovirus,dh.hexavalente,
            dh.octavalente,dh.rabia_perro,dh.tos_perrera,dh.ninguna_perro,dh.obs_p,dh.obs_perro,
            dh.triple_felina,dh.rabia_gato,dh.ninguna_gato,dh.obs_g,dh.obs_gato,dh.desparacitacion,
            dh.desparacitacion_cuando,dh.temperatura,dh.fc,dh.taquicardia,dh.arritmia,dh.bradicardia,
            dh.sin_alteracion,dh.fr,dh.bueno_fr,dh.disnea,dh.rosada,dh.palidas,dh.ictericas,dh.cianotica,
            dh.normal_apetito,dh.disminuido,dh.anorexico,dh.normal_mucosa,dh.leve,dh.moderada,dh.marcada,
            dh.bueno_estado,dh.regular,dh.malo,dh.enfermedades,dh.enfermedades_cuales,dh.enfermedades_cuando,
            dh.cirugia,dh.cirugia_cuales,dh.cirugia_cuando,dh.ocular,dh.nariz,dh.bucal,dh.piel_anexo,dh.oidos,
            dh.vulvar,dh.prepucial,dh.digestivo_sin_alteracion,dh.digestivo_obs,dh.respiratorio_sin_alteracion,
            dh.respiratorio_obs,dh.urinario_sin_alteracion,dh.urinario_obs,dh.nervioso_sin_alteracion,dh.nervioso_obs,
            dh.muestra,dh.examenes_solicitado,dh.fecha1,dh.hora1,dh.t1,dh.dr1,dh.costo1,dh.observaciones1,dh.primer_dia
            FROM detalle_historia as dh,personal as p
            WHERE dh.id_personal=p.id and dh.id='$id'");   

        return $obj;
    }
    public function modificar(Request $request){

            $detalle_historia= DetalleHistoria::findOrFail($request->id);
            $detalle_historia->estado=1;
            //vacunaciones
            $detalle_historia->parvovirus=$request->parvovirus;
            $detalle_historia->hexavalente=$request->hexavalente;
            $detalle_historia->octavalente=$request->octavalente;
            $detalle_historia->rabia_perro=$request->rabia_perro;
            $detalle_historia->tos_perrera=$request->tos_perrera;
            $detalle_historia->ninguna_perro=$request->ninguna_perro;
            $detalle_historia->obs_p=$request->obs_p;
            $detalle_historia->obs_perro=$request->obs_perro;
            //GATO
            $detalle_historia->triple_felina=$request->triple_felina;
            $detalle_historia->rabia_gato=$request->rabia_gato;
            $detalle_historia->ninguna_gato=$request->ninguna_gato;
            $detalle_historia->obs_g=$request->obs_g;
            $detalle_historia->obs_gato=$request->obs_gato;
            $detalle_historia->desparacitacion=$request->desparacitacion;
            $detalle_historia->desparacitacion_cuando=$request->desparacitacion_cuando;
            //TEMPERATURA
            $detalle_historia->temperatura=$request->temperatura;
             //FC
            $detalle_historia->fc=$request->fc;
            $detalle_historia->taquicardia=$request->taquicardia;
            $detalle_historia->arritmia=$request->arritmia;
            $detalle_historia->bradicardia=$request->bradicardia;
            $detalle_historia->sin_alteracion=$request->sin_alteracion;
            //FR
            $detalle_historia->fr=$request->fr;
            $detalle_historia->bueno_fr=$request->normal_fr;
            $detalle_historia->disnea=$request->disnea;
            //MUCOSAS
            $detalle_historia->rosada=$request->rosada;
            $detalle_historia->palidas=$request->palidas;
            $detalle_historia->ictericas=$request->ictericas;
            $detalle_historia->cianotica=$request->cianotica;
            //APETITO
            $detalle_historia->normal_apetito=$request->normal_apetito;
            $detalle_historia->disminuido=$request->disminuido;
            $detalle_historia->anorexico=$request->anorexico;
            //HIDRATACION
            $detalle_historia->normal_mucosa=$request->normal_hidratacion;
            $detalle_historia->leve=$request->leve;
            $detalle_historia->moderada=$request->moderada;
            $detalle_historia->marcada=$request->marcada;
            //ESTADO GENERAL
            $detalle_historia->bueno_estado=$request->bueno_estado;
            $detalle_historia->regular=$request->regular;
            $detalle_historia->malo=$request->malo;
            //ANTECEDENTES ENFERMEDADES
            $detalle_historia->enfermedades=$request->enfermedades;
            $detalle_historia->enfermedades_cuales=$request->enfermedades_cuales;
            $detalle_historia->enfermedades_cuando=$request->enfermedades_cuando;
            //ANTECEDENTES CIRUGIA
            $detalle_historia->cirugia=$request->cirugia;
            $detalle_historia->cirugia_cuales=$request->cirugia_cuales;
            $detalle_historia->cirugia_cuando=$request->cirugia_cuando;
            //ORGANOS DE SENTIDO
            $detalle_historia->ocular=$request->ocular;
            $detalle_historia->nariz=$request->nariz;
            $detalle_historia->bucal=$request->bucal;
            $detalle_historia->piel_anexo=$request->piel_anexo;
            $detalle_historia->oidos=$request->oidos;
            $detalle_historia->vulvar=$request->vulvar;
            $detalle_historia->prepucial=$request->prepucial;
            //APARATO DIGESTIVO,RESPIRATORIO,GENITO URINARIO,NERVIOSO
            $detalle_historia->digestivo_sin_alteracion=$request->digestivo_sin_alteracion;
            $detalle_historia->digestivo_obs=$request->digestivo_obs;
            $detalle_historia->respiratorio_sin_alteracion=$request->respiratorio_sin_alteracion;
            $detalle_historia->respiratorio_obs=$request->respiratorio_obs;
            $detalle_historia->urinario_sin_alteracion=$request->urinario_sin_alteracion;
            $detalle_historia->urinario_obs=$request->urinario_obs;
            $detalle_historia->nervioso_sin_alteracion=$request->nervioso_sin_alteracion;
            $detalle_historia->nervioso_obs=$request->nervioso_obs;
            //EXAMENES COMPLEMENTARIOS
            $detalle_historia->muestra=$request->muestra;
            $detalle_historia->examenes_solicitado=$request->examenes_solicitado;
            //TRATAMIENTO INDICADO
            $detalle_historia->fecha1=$request->fecha1;
            $detalle_historia->t1=$request->temperatura;
            $detalle_historia->dr1=$request->dr1;
            $detalle_historia->hora1=$request->hora1;
            $detalle_historia->costo1=$request->costo1;
            $detalle_historia->observaciones1=$request->observaciones1;
            $detalle_historia->primer_dia=$request->primer_dia;

            $detalle_historia->hidratacion=$request->hidratacion;

            $detalle_historia->save();
            
            $affected = DB::table('paciente')
            ->where('id', $request->id_paciente)
            ->update(['edad' => $request->edad]); 

        $datos = [
            'tabla' => 'detalle_historia',
            'codigo_tabla' => $detalle_historia->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);

    }
     public function pdfHistoria(Request $request){

        $var2=DB::select("SELECT  MAX(id) as historia from detalle_historia");
        $id=$var2[0]->historia;
        //dd($id);
        $foto = $request->foto;
        $empresa_nombre = $request->empresa_nombre;

        //dd($id);
        
        
        $historia= DetalleHistoria::join('historial_clinico','detalle_historia.id_historia','=','historial_clinico.id')
        ->join('personal','detalle_historia.id_personal','=','personal.id')
        ->join('cliente','historial_clinico.id_cliente','=','cliente.id')
        ->join('paciente','historial_clinico.id_paciente','=','paciente.id')
        ->join('animal','paciente.id_animal','=','animal.id')
        ->select('detalle_historia.id','historial_clinico.nro_historia','detalle_historia.fecha','personal.nombre as doctor','cliente.nombre as propietario','cliente.direccion','cliente.telefono',
        'paciente.nombre as mascota','paciente.especie','paciente.raza','paciente.sexo','detalle_historia.peso','detalle_historia.edad','detalle_historia.meses','paciente.cirugias','paciente.enfermedades as enferm','paciente.vacunas',
        'detalle_historia.motivo',
        'detalle_historia.parvovirus','detalle_historia.hexavalente','detalle_historia.octavalente','detalle_historia.rabia_perro','detalle_historia.tos_perrera','detalle_historia.ninguna_perro','detalle_historia.obs_p','detalle_historia.obs_perro',
        'detalle_historia.triple_felina','detalle_historia.rabia_gato','detalle_historia.ninguna_gato','detalle_historia.obs_g','detalle_historia.obs_gato','detalle_historia.desparacitacion','detalle_historia.desparacitacion_cuando','detalle_historia.temperatura',
        'detalle_historia.fc','detalle_historia.taquicardia','detalle_historia.arritmia','detalle_historia.bradicardia','detalle_historia.sin_alteracion','detalle_historia.fr','detalle_historia.bueno_fr','detalle_historia.disnea','detalle_historia.rosada',
        'detalle_historia.palidas','detalle_historia.ictericas','detalle_historia.cianotica','detalle_historia.normal_apetito','detalle_historia.disminuido','detalle_historia.anorexico','detalle_historia.normal_mucosa','detalle_historia.leve',
        'detalle_historia.moderada','detalle_historia.marcada','detalle_historia.bueno_estado','detalle_historia.regular','detalle_historia.malo','detalle_historia.enfermedades','detalle_historia.enfermedades_cuales','detalle_historia.enfermedades_cuando','detalle_historia.cirugia','detalle_historia.cirugia_cuales','detalle_historia.cirugia_cuando',
        'detalle_historia.ocular','detalle_historia.nariz','detalle_historia.bucal','detalle_historia.piel_anexo','detalle_historia.oidos','detalle_historia.vulvar','detalle_historia.prepucial','detalle_historia.digestivo_sin_alteracion','detalle_historia.digestivo_obs','detalle_historia.respiratorio_sin_alteracion','detalle_historia.respiratorio_obs',
        'detalle_historia.urinario_sin_alteracion','detalle_historia.urinario_obs','detalle_historia.nervioso_sin_alteracion','detalle_historia.nervioso_obs','detalle_historia.muestra','detalle_historia.examenes_solicitado',
        'detalle_historia.fecha1','detalle_historia.hora1','detalle_historia.t1','detalle_historia.dr1','detalle_historia.costo1','detalle_historia.observaciones1','detalle_historia.primer_dia','animal.nombre as animal','detalle_historia.hidratacion')
        ->where('detalle_historia.id','=',$id)
        ->orderBy('detalle_historia.id','desc')->get();
  
        $fecha=$historia[0]->fecha;
        $doctor=$historia[0]->doctor;
        $nro_historia=$historia[0]->nro_historia;
        $fecha=$historia[0]->fecha;
        
        $detalles=$historia;

        $cont=DetalleHistoria::count();
        $pdf = \PDF::loadView('pdf.historial.historial', [

            'detalles'=>$detalles,
            'fecha'=>$fecha,
            'doctor'=>$doctor,
            'nro_historia'=>$nro_historia
        ]);
        return $pdf->setPaper('letter', 'portrait')->stream('Historial.pdf');
    }

    public function pdfHistoriaActualizar(Request $request){

        $id = $request->id;

        //dd($id);
        
        $historia= DetalleHistoria::join('historial_clinico','detalle_historia.id_historia','=','historial_clinico.id')
        ->join('personal','detalle_historia.id_personal','=','personal.id')
        ->join('cliente','historial_clinico.id_cliente','=','cliente.id')
        ->join('paciente','historial_clinico.id_paciente','=','paciente.id')
        ->join('animal','paciente.id_animal','=','animal.id')
        ->select('detalle_historia.id','historial_clinico.nro_historia','detalle_historia.fecha','personal.nombre as doctor','cliente.nombre as propietario','cliente.direccion','cliente.telefono',
                'paciente.nombre as mascota','paciente.especie','paciente.raza','paciente.sexo','detalle_historia.peso','detalle_historia.edad','detalle_historia.meses','paciente.cirugias','paciente.enfermedades as enferm','paciente.vacunas',
                'detalle_historia.motivo',
                'detalle_historia.parvovirus','detalle_historia.hexavalente','detalle_historia.octavalente','detalle_historia.rabia_perro','detalle_historia.tos_perrera','detalle_historia.ninguna_perro','detalle_historia.obs_p','detalle_historia.obs_perro',
                'detalle_historia.triple_felina','detalle_historia.rabia_gato','detalle_historia.ninguna_gato','detalle_historia.obs_g','detalle_historia.obs_gato','detalle_historia.desparacitacion','detalle_historia.desparacitacion_cuando','detalle_historia.temperatura',
                'detalle_historia.fc','detalle_historia.taquicardia','detalle_historia.arritmia','detalle_historia.bradicardia','detalle_historia.sin_alteracion','detalle_historia.fr','detalle_historia.bueno_fr','detalle_historia.disnea','detalle_historia.rosada',
                'detalle_historia.palidas','detalle_historia.ictericas','detalle_historia.cianotica','detalle_historia.normal_apetito','detalle_historia.disminuido','detalle_historia.anorexico','detalle_historia.normal_mucosa','detalle_historia.leve',
                'detalle_historia.moderada','detalle_historia.marcada','detalle_historia.bueno_estado','detalle_historia.regular','detalle_historia.malo','detalle_historia.enfermedades','detalle_historia.enfermedades_cuales','detalle_historia.enfermedades_cuando','detalle_historia.cirugia','detalle_historia.cirugia_cuales','detalle_historia.cirugia_cuando',
                'detalle_historia.ocular','detalle_historia.nariz','detalle_historia.bucal','detalle_historia.piel_anexo','detalle_historia.oidos','detalle_historia.vulvar','detalle_historia.prepucial','detalle_historia.digestivo_sin_alteracion','detalle_historia.digestivo_obs','detalle_historia.respiratorio_sin_alteracion','detalle_historia.respiratorio_obs',
                'detalle_historia.urinario_sin_alteracion','detalle_historia.urinario_obs','detalle_historia.nervioso_sin_alteracion','detalle_historia.nervioso_obs','detalle_historia.muestra','detalle_historia.examenes_solicitado',
                'detalle_historia.fecha1','detalle_historia.hora1','detalle_historia.t1','detalle_historia.dr1','detalle_historia.costo1','detalle_historia.observaciones1','detalle_historia.primer_dia','animal.nombre as animal','detalle_historia.hidratacion'
                )
        ->where('detalle_historia.id','=',$id)
        ->orderBy('detalle_historia.id','desc')->get();
  
        $fecha=$historia[0]->fecha;
        $doctor=$historia[0]->doctor;
        $nro_historia=$historia[0]->nro_historia;
        $fecha=$historia[0]->fecha;
        
        $detalles=$historia;

        $cont=DetalleHistoria::count();
        $pdf = \PDF::loadView('pdf.historial.historial', [

            'detalles'=>$detalles,
            'fecha'=>$fecha,
            'doctor'=>$doctor,
            'nro_historia'=>$nro_historia
        ]);
        return $pdf->setPaper('letter', 'portrait')->stream('Historial.pdf');
    }
}
