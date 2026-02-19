<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleHistoria extends Model
{
    use HasFactory;
    protected $table = 'detalle_historia';
    protected $fillable = [ 
        'id',
        'id_historia',
        'id_personal',
        'peso',
        'edad',
        'meses',
        'motivo',
        'estado',
        'fecha',

        'parvovirus',
        'hexavalente',
        'octavalente',
        'rabia_perro',
        'tos_perrera',
        'ninguna_perro',
        'obs_p' ,
        'obs_perro' ,
        //GATO
        'triple_felina',
        'rabia_gato',
        'ninguna_gato',
        'obs_g',
        'obs_gato',
        'desparacitacion',
        'desparacitacion_cuando',
        //TEMPERATURA
        'temperatura',
        //FC
        'fc',
        'taquicardia',
        'arritmia',
        'bradicardia',
        'sin_alteracion',
        //FR
        'fr',
        'normal_fr',
        'disnea',
        //MUCOSAS
        'rosada',
        'palidas',
        'ictericas',
        'cianotica',
        //APETITO
        'normal_apetito',
        'disminuido',
        'anorexico',
        //HIDRATACION
        'normal_hidratacion',
        'leve',
        'moderada',
        'marcada',
        //ESTADO GENERAL
        'bueno_estado',
        'regular',
        'malo',
        //ANTECEDENTES ENFERMEDADES
        'enfermedades',
        'enfermedades_cuales',
        'enfermedades_cuando',
        //ANTECEDENTES CIRUGIA
        'cirugia',
        'cirugia_cuales',
        'cirugia_cuando',
        //ORGANOS DE SENTIDO
        'ocular',
        'nariz',
        'bucal',
        'piel_anexo',
        'oidos',
        'vulvar',
        'prepucial',
        //APARATO DIGESTIVO,RESPIRATORIO,GENITO URINARIO,NERVIOSO
        'digestivo_sin_alteracion',
        'digestivo_obs',
        'respiratorio_sin_alteracion',
        'respiratorio_obs',
        'urinario_sin_alteracion',
        'urinario_obs',
        'nervioso_sin_alteracion',
        'nervioso_obs',
        //EXAMENES COMPLEMENTARIOS
        'muestra',
        'examenes_solicitado',
        //TRATAMIENTO INDICADO
        'fecha1',
        't1',
        'dr1',
        'hora1',
        'costo1',
        'observaciones1',
        'primer_dia',

        //NUEVO ATRIBUTO HIDRATACION
        'hidratacion'

    ];
    public $timestamps = false;
}
