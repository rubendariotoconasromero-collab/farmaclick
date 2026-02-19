<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Formulario;

class FormularioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Formulario::create(['nombre' => 'Inicio']);                         //1              
        Formulario::create(['nombre' => 'Arqueo Caja']);                    //2

        Formulario::create(['nombre' => 'Formulario Compras']);             //3
        Formulario::create(['nombre' => 'Historial Compras']);              //4
        Formulario::create(['nombre' => 'Pagos al Crédito Compra']);        //5 
       
        Formulario::create(['nombre' => 'Ventas']);                         //6    
        Formulario::create(['nombre' => 'Historial Ventas']);               //7
        Formulario::create(['nombre' => 'Pagos Venta']);                    //8   
       
        Formulario::create(['nombre' => 'Inventario']);                     //9
        Formulario::create(['nombre' => 'Formulario Productos']);           //10
        Formulario::create(['nombre' => 'Formulario Categoria']);           //11   
        Formulario::create(['nombre' => 'Formulario Ajuste']);              //12    
        Formulario::create(['nombre' => 'Formulario Presentación']);        //13           
        Formulario::create(['nombre' => 'Formulario Linea']);               //14           
        Formulario::create(['nombre' => 'Formulario Lotes']);               //15        

        Formulario::create(['nombre' => 'Formulario Motivo Gasto']);        //16    
        Formulario::create(['nombre' => 'Gastos']);                         //17  

        Formulario::create(['nombre' => 'Formulario Cliente']);             //18  
        Formulario::create(['nombre' => 'Formulario Labolatorio']);         //19  
        Formulario::create(['nombre' => 'Formulario Personal']);            //20  
        Formulario::create(['nombre' => 'Formulario Cargo']);               //21  
        Formulario::create(['nombre' => 'Datos de la Empresa']);            //22

        Formulario::create(['nombre' => 'Grupo Usuarios']);                 //23
        Formulario::create(['nombre' => 'Formulario Usuarios']);            //24  
        Formulario::create(['nombre' => 'Permisos']);                       //25

        Formulario::create(['nombre' => 'Reportes']);                       //26
        
        //Formulario::create(['nombre' => 'Historial de Caja']); 

        //Formulario::create(['nombre' => 'Pago al Crédito Venta Servicios']);  
 

        //Formulario::create(['nombre' => 'Inventario']); 
    }
}
