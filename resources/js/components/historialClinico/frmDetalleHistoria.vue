<template>
    <main class="main">
        
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                    <template v-if="listado==0">
                   <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">HISTORIA CLINICA</h3>
                    </div>
                    <br>

                    <div class="card-header">
                            <button type="button" @click="seleccionar_menu()" class="btn btn-info text-white" style='margin-left: 1%'>
                                <i class="icon-plus"></i>&nbsp;Nueva Visita
                            </button>
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group"  style='width:96%;margin-left: 3.3%'>
                                <select class="form-select col-md-3" v-model="criterio">
                                    <option value="paciente.nombre">Mascota</option>
                                </select>
                                &nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscar" @keyup.enter="listarHistoriaClinica(buscar)" @keyup="BuscandoCliente()" class="form-control" placeholder="Texto a buscar" style='width:30%'>
                                <button type="submit" @click="listarHistoriaClinica(buscar)" class="btn btn-info text-white"><i class="fa fa-search"></i> buscar</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Light table -->
                    <div class="table-responsive">
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                        <thead style="background-color: #46546c">
                            <tr>                      
                                <th scope="col" class="text-white">Propietario</th> 
                                <th scope="col" class="text-white">Mascota</th> 
                                <th scope="col" class="text-white">Especie</th>                       
                                <th scope="col" class="text-white">Color</th>
                                <th scope="col" class="text-white">Opcion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="historia in arrayHistoriaC" :key="historia.id">
                                    <td v-text="historia.propietario"></td>
                                    <td v-text="historia.mascota"></td>
                                    <td v-text="historia.especie"></td>
                                    <td v-text="historia.color"></td>
                                    <td>
                                        <button type="button" class="btn btn-info text-white position-relative" @click="Historial(historia)">Historia</button>    
                                    </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <!-- Card Pagination -->
                    <div class="card-footer py-4">
                        <nav>
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page==isActived ? 'active' :'']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page">1</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>
                    <template v-if="listado==1">
                        <div class="card-body border" >  
                            <button type="button" class="btn btn-danger text-white" @click="volverPagoVenta()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>   
                            <button type="button" @click="seleccionar_menu2()" class="btn btn-info text-white" style='margin-left: 1%'>
                                <i class="icon-plus"></i>&nbsp;Actualizar Visita
                            </button>                
                            <div class="form-group row">          
                                <form class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Propietario</label>
                                    <input type="text" class="form-control"  v-model="datos.cliente" disabled>  
                                </div> 
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Mascota</label>
                                    <input type="text" class="form-control"  v-model="datos.paciente" disabled>  
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Especie</label>
                                    <input type="text" class="form-control"  v-model="datos.especie" disabled>  
                                </div> 
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Color</label>
                                    <input type="text" class="form-control"  v-model="datos.color" disabled>  
                                </div>
                                </form>    
                            </div>
                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #46546C">
                                        <tr>                      
                                            <th scope="col" class="text-white" width="10%">Número</th>
                                            <th scope="col" class="text-white">Fecha</th>
                                            <th scope="col" class="text-white">Peso</th>
                                            <th scope="col" class="text-white">Motivo</th>
                                            <th scope="col" class="text-white">Estado</th>
                                            <th scope="col" class="text-white">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayHistoria.length">
                                        <tr v-for="(detalle_cliente,index) in arrayHistoria" :key="index">
                                            <td v-text="index+1"></td>
                                            <td v-text="detalle_cliente.fecha"></td>
                                            <td v-text="detalle_cliente.peso"></td>
                                            <td v-text="detalle_cliente.motivo"></td>     
                                            <td>
                                                <template v-if="detalle_cliente.estado==1">
                                                    <span class="badge bg-success">Registrado</span>
                                                </template>
                                                <template v-else>
                                                    <span class="badge bg-danger">Pediente</span>
                                                </template>
                                            </td> 
                                            <td>
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                                <ul class="dropdown-menu dropdown-menu-end">

                                                        <li><a class="dropdown-item" href="#" @click="actualizarHistoria(detalle_cliente)"><i class="fa fa-edit text-warning"></i> Actualizar Historial</a></li>
                                                        <li><a class="dropdown-item" href="#" @click="cargarPdf(detalle_cliente.id)"><i class="fa fa-file-pdf-o text-danger"></i> Imprimir</a></li>
 
                                                </ul>
                                            </td>  
                                            <!-- <td>
                                                <button type="button" class="btn btn-info text-white position-relative" @click="actualizarHistoria(detalle_cliente)">Actualizar Historial</button>    
                                            </td>                                                                       -->

                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">No hay Pagos Realizados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-if="listado==2">
                        <div class="card-body border" >  
                            <button type="button" class="btn btn-danger text-white" @click="volverHistoria()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>                   
                            <div class="form-group row m-0 p-0">          
                                <form class="row m-0 pl-1 pr-0">
                                <div class="col-md-3">
                                    <label for="exampleInputPassword1" class="form-label">Mascota</label>
                                    <input type="text" class="form-control"  v-model="datos.paciente" disabled>  
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputPassword1" class="form-label">Peso</label>
                                    <input type="text" class="form-control"  v-model="datos.peso" disabled>  
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="text" class="form-control"  v-model="datos.fecha" disabled>  
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputPassword1" class="form-label">Atendido por</label>
                                    <input type="text" class="form-control"  v-model="datos.personal" disabled>  
                                </div>
                                <div class="col-md-12">
                                        <label for="exampleInputPassword1" class="form-label">Anamnesis(MOTIVO DE LA CONSULTA)</label>
                                        <textarea class="form-control" v-model="datos.motivo" rows="2" disabled></textarea>
                                </div>  
                                </form>    
                            </div>
                        </div>
                        <!-- VACUNACIONES -->
                        <div class="card-body border" >
                            <div class="form-group row m-0 p-0">                                                           
                            <form class="row   m-0 pl-1 pr-0">  
                            <div class="col-md-1">
                                      <label for="exampleInputPassword1" class="form-label">VACUNACIONES:</label>
                            </div>
                            <div class="col-md-1">
                            <table class="leght">
                                <tr>
                                    <td>
                                        <label for="exampleInputPassword1" class="form-label">ㅤㅤㅤ PERRO</label>
                                    </td>
                                </tr>
                            </table>
                            </div>
                            <div class="col-md-2">
                                    <table class="leght">
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.parvovirus">
                                        <label class="form-check-label" for="flexCheckIndeterminate">
                                            PARVOVIRUS
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                            <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.hexavalente">
                                        <label class="form-check-label">
                                            HEXAVALENTE
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.octavalente">
                                        <label class="form-check-label">
                                            OCTAVALENTE
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.rabia_perro">
                                        <label class="form-check-label">
                                            RABIA
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.tos_perrera">
                                        <label class="form-check-label">
                                            TOS DE PERRERA
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.ninguna_perro">
                                        <label class="form-check-label" >
                                            NINGUNA
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.obs_p">
                                        <label class="form-check-label">
                                            OBS 
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <template v-if="datos.obs_p == true">
                            <tr>
                                <td>
                                    <div>
                                        <input type="text" class="form-control"  v-model="datos.obs_perro"  placeholder="Obs. .........................." style="border: 0">
                                    </div>
                                </td>
                            </tr>
                            </template>
                        </table>
                            </div>
                            <div class="col-md-1">
                                <table class="leght">
                                    <tr>
                                        <td>
                                            <label for="exampleInputPassword1" class="form-label">ㅤㅤㅤ GATO</label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-2">
                                    <table class="leght">
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1"  v-model="datos.triple_felina">
                                        <label class="form-check-label" >
                                            TRIPLE FELINA
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.rabia_gato">
                                        <label class="form-check-label">
                                            RABIA
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.ninguna_gato">
                                        <label class="form-check-label">
                                            NINGUNA
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.obs_g">
                                        <label class="form-check-label">
                                            Obs
                                        </label>

                                    </div>
                                    <template v-if="datos.obs_g == 1">
                                    <div>
                                            <input type="text" class="form-control"  v-model="datos.obs_gato" placeholder=".................................." style="border: 0">  

                                    </div>
                                    </template>
                                </td>
                            </tr>
                        </table>
                            </div>
                            <div class="col-md-2">
                                    <table class="center">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">ㅤㅤㅤ DESPARASITACIÓN</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <br><br>
                                                    <!-- <label for="exampleInputPassword1" class="form-label">ㅤㅤㅤ CUANDO:</label> -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">ㅤㅤㅤ CUANDO:</label>
                                                </td>
                                            </tr>
                                    </table>

                            </div>
                            <div class="col-md-2">
                                    <table class="leght">
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" v-model="datos.desparacitacion">
                                                <label class="form-check-label" for="inlineRadio1">SI</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0" v-model="datos.desparacitacion">
                                                <label class="form-check-label" for="inlineRadio2">NO</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>
                                                ㅤㅤㅤ<input type="text" class="form-control"  v-model="datos.desparacitacion_cuando"  placeholder="Obs. .........................." style="border: 0">
                                            </div>
                                        </td>
                                    </tr>
                                </table>    
        
                            </div>
                                </form>    
                            </div> 
                        </div>
                        <!-- TEMPERATURA -->
                        <div class="card-body border" >
                            <div class="form-group row m-0 p-0">                                                           
                                <form class="row  m-0 pl-1 pr-0">  
                                    <div class="col-md-1">
                                            <div class="col-md-1">
                                                <label for="exampleInputPassword1" class="form-label">TEMPERATURA:</label>
                                            </div>
                                    </div>
                                    <div class="col-md-2">
                                    <table class="leght">
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control"  v-model="datos.temperatura">  

                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                    <div class="col-md-1">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">ㅤㅤㅤㅤF.C.</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2">
                                    <table class="leght">
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control"  v-model="datos.fc">  

                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                    <div class="col-md-2">
                                            <table class="leght">
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.taquicardia">
                                                <label class="form-check-label">
                                                    TAQUICARDIA
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.arritmia">
                                                <label class="form-check-label">
                                                    ARRITMIA
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.bradicardia">
                                                <label class="form-check-label">
                                                    BRADICARDIA
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.sin_alteracion">
                                                <label class="form-check-label">
                                                    SIN ALTERACIÓN
                                                </label>

                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                    </div>
                                    <div class="col-md-1">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">ㅤㅤㅤㅤF.R.</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2">
                                    <table class="leght">
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control"  v-model="datos.fr">  

                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                    <div class="col-md-1">
                                            <table class="leght">
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.normal_fr">
                                                <label class="form-check-label">
                                                    NORMAL
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate"  v-model="datos.disnea">
                                                <label class="form-check-label">
                                                    DISNEA
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                    </div>
                                </form>    
                            </div> 
                        </div>
                        <!-- MUCOSAS -->
                        <div class="card-body border" >
                            <div class="form-group row m-0 p-0">                                                           
                                <form class="row m-0 pl-1 pr-0">  
                                    <div class="col-md-1">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">MUCOSAS:</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2">
                                            <table class="leght">
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.rosada">
                                                <label class="form-check-label" >
                                                    ROSADAS
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.palidas">
                                                <label class="form-check-label" >
                                                    PÁLIDAS
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.ictericas">
                                                <label class="form-check-label" >
                                                    ICTERICAS
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.cianotica">
                                                <label class="form-check-label" >
                                                    CIANÓTICA
                                                </label>

                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                    </div>
                                    <div class="col-md-1">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">ㅤㅤAPETITO:</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2">
                                            <table class="leght">
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.normal_apetito">
                                                <label class="form-check-label" >
                                                    NORMAL
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.disminuido">
                                                <label class="form-check-label" >
                                                    DISMINUIDO
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.anorexico">
                                                <label class="form-check-label" >
                                                    ANORÉXICO
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                    </div>
                                    <div class="col-md-1">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">HIDRATACIÓN:</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2">
                                            <table class="leght">
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control"  v-model="datos.hidratacion">  
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.leve">
                                                <label class="form-check-label" >
                                                    LEVE
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.moderada">
                                                <label class="form-check-label" >
                                                    MODERADA
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.marcada">
                                                <label class="form-check-label" >
                                                    MARCADA
                                                </label>
                                            </div>
                                        </td>
                                    </tr> -->
                                </table>
                                    </div>
                                    <div class="col-md-1">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">ESTADO GENERAL:</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2">
                                            <table class="leght">
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.bueno_estado">
                                                <label class="form-check-label" >
                                                    BUENO
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.regular">
                                                <label class="form-check-label" >
                                                    REGULAR
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.malo">
                                                <label class="form-check-label" >
                                                    MALO
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                    </div>
                                </form>    
                            </div> 
                        </div>
                        <!-- ANTECEDENTES Y ORGANOS DE SENTIDO -->
                        <div class="card-body border" >
                            <div class="form-group row m-0 p-0">                                                           
                                <form class="row m-0 pl-1 pr-0"> 
                                    <!-- ANTECEDENTES DE ENFERMEDADES  -->
                                    <div class="col-md-3">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">ANTECEDENTES DE ENFERMEDADES:</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2">
                                        <table >
                                            <thead >
                                                <tr>                      
                                                    <th>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" v-model="datos.enfermedades">
                                                            <label class="form-check-label" for="inlineRadio1">SI</label>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0" v-model="datos.enfermedades">
                                                            <label class="form-check-label" for="inlineRadio2">NO</label>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>    
                                        
        
                                    </div>
                                    <div class="col-md-3">
                                        <table >
                                            <thead >
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">CUALESㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.enfermedades_cuales" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>    
                                        
        
                                    </div>
                                    <div class="col-md-4">
                                        <table >
                                            <thead >
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">CUANDOㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.enfermedades_cuando" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>    
                                    </div>
                                    <!-- ANTECEDENTES DE CIRUGÍA -->
                                    <div class="col-md-3">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">ANTECEDENTES DE CIRUGÍA:</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2">
                                        <table >
                                            <thead >
                                                <tr>                      
                                                    <th>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="inlineRadioOptions1" id="inlineRadioR1" value="1" v-model="datos.cirugia">
                                                            <label class="form-check-label" for="inlineRadioR1">SI</label>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="inlineRadioOptions1" id="inlineRadioR2" value="0" v-model="datos.cirugia">
                                                            <label class="form-check-label" for="inlineRadioR2">NO</label>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>    
                                        
        
                                    </div>
                                    <div class="col-md-3">
                                        <table >
                                            <thead >
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">CUALESㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.cirugia_cuales" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>    
                                        
        
                                    </div>
                                    <div class="col-md-4">
                                        <table >
                                            <thead >
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">CUANDOㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.cirugia_cuando" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>    
                                    </div>
                                    <!-- ÓRGANOS DE SENTIDO -->
                                    <div class="col-md-12">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label"><strong>ÓRGANOS DE SENTIDO</strong></label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <table >
                                            <thead >
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">1) OCULARㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.ocular" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">2) NARIZㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.nariz" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">3) BUCALㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.bucal" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>  
                                    </div>
                                    <div class="col-md-4">
                                        <table >
                                            <thead >
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">4) PIEL Y ANEXOS</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.piel_anexo" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">5) OÍDOSㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.oidos" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">6) VULVARㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.vulvar" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>  
                                    </div>
                                    <div class="col-md-4">
                                        <table >
                                            <thead >
                                                <tr>                      
                                                    <th scope="col">
                                                        <label for="exampleInputPassword1" class="form-label">7) PREPUCIALㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.prepucial" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>  
                                    </div>
                                </form>     
                            </div> 
                        </div>
                         <!-- APARATOS DIGESTIVO, RESPIRATORIO, GENITO URINARIO, NERVIOSO -->
                        <div class="card-body border" >
                            <div class="form-group row  m-0 p-0">                                                           
                                <form class="row  m-0 pl-1 pr-0"> 
                                    <div class="col-md-6 border border-dark">

                                        <label for="exampleInputPassword1" class="form-label"><strong>APARATO DIGESTIVO</strong></label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.digestivo_sin_alteracion">
                                            <label class="form-check-label" for="flexCheckIndeterminate">
                                                SIN ALTERACIÓN
                                            </label>
                                        </div>

                                        <textarea class="form-control" v-model="datos.digestivo_obs" rows="2" style="border: 0" 
                                        placeholder="....................................................................................................................................................................................................................................................................................................................................................................................................................................">
                                        </textarea>

                                    </div>
                                    <div class="col-md-6 border border-dark" >

                                        <label for="exampleInputPassword1" class="form-label"><strong>APARATO RESPIRATORIO</strong></label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.respiratorio_sin_alteracion">
                                            <label class="form-check-label" for="flexCheckIndeterminate">
                                                SIN ALTERACIÓN
                                            </label>
                                        </div>

                                        <textarea class="form-control" v-model="datos.respiratorio_obs" rows="2" style="border: 0" 
                                        placeholder="....................................................................................................................................................................................................................................................................................................................................................................................................................................">
                                        </textarea>
<br>
                                    </div>
                                    <div class="col-md-6 border border-dark">

                                        <label for="exampleInputPassword1" class="form-label"> <strong>APARATO GENITO URINARIO</strong></label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.urinario_sin_alteracion">
                                            <label class="form-check-label" for="flexCheckIndeterminate">
                                                SIN ALTERACIÓN
                                            </label>
                                        </div>

                                        <textarea class="form-control" v-model="datos.urinario_obs" rows="2" style="border: 0" 
                                        placeholder="....................................................................................................................................................................................................................................................................................................................................................................................................................................">
                                        </textarea>
                                    <br>
                                    </div>
                                    <div class="col-md-6 border border-dark">

                                        <label for="exampleInputPassword1" class="form-label"><strong> APARATO NERVIOSO</strong></label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.nervioso_sin_alteracion">
                                            <label class="form-check-label" for="flexCheckIndeterminate">
                                                SIN ALTERACIÓN
                                            </label>
                                        </div>

                                        <textarea class="form-control" v-model="datos.nervioso_obs" rows="2" style="border: 0" 
                                        placeholder="....................................................................................................................................................................................................................................................................................................................................................................................................................................">
                                        </textarea>

                                    </div>
                                </form>     
                            </div> 
                        </div>
                        <!-- EXAMENES COMPLEMENTARIOS -->
                        <div class="card-body border" >
                            <div class="form-group row m-0 p-0">                                                           
                                <form class="row m-0 pl-1 pr-0"> 
                                    <h3 class="text-center">EXÁMENES COMPLEMENTARIOS</h3>
                                    <div class="col-md-1">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">MUESTRA:</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control"  v-model="datos.muestra" style="border: 0" placeholder="...............................................................................................................................................................">  
                                    </div>
                                    <div class="col-md-2">
                                        <table class="leght">
                                            <tr>
                                                <td>
                                                    <label for="exampleInputPassword1" class="form-label">ㅤㅤㅤEXAMENES SOLICITADO:</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control"  v-model="datos.examenes_solicitado" style="border: 0" placeholder="...............................................................................................................................................................">  
                                    </div>
                                </form>     
                            </div> 
                        </div>
                        <!-- TRATAMIENTO INDICADO -->
                        <div class="card-body border" >
                            <div class="form-group row m-0 p-0 " >                                                           
                                <form class="row  m-0 pl-1 pr-0" > 
                                    <h3 class="text-center">TRATAMIENTO INDICADO</h3>
                                    <div class="col-md-6 mb-2 border border-dark rounded">
                                        <table class="col-md-12  pb-4">
                                            <thead >
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">FECHA:</label>
                                                    </th>
                                                    <th>
                                                        <input type="date" class="form-control"  v-model="datos.fecha1" style="border: 0" > 
                                                    </th>
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">HRS.</label>
                                                    </th>
                                                    <th colspan="2">
                                                        <input type="time" class="form-control"  v-model="datos.hora1" style="border: 0"> 
                                                    </th>
                                                    <th colspan="2">
                                                        <label for="exampleInputPassword1" class="form-label">Tºㅤ</label>
                                                    </th>
                                                    <th>
                                                        <input type="text" class="form-control"  v-model="datos.temperatura" placeholder="...................................................................." style="border: 0" disabled> 
                                                    </th>
                                                </tr>
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">DR.</label>
                                                    </th>
                                                    <th colspan="5">
                                                        <input type="text" class="form-control"  v-model="datos.dr1" placeholder=".................................................................." style="border: 0"> 
                                                    </th>
                                                    <!-- <th  colspan="3">
                                                        <label for="exampleInputPassword1" class="form-label">ㅤㅤㅤㅤCOSTO:</label>
                                                    </th>
                                                    <th colspan="2" class="pl-4 ml-4">
                                                        <input type="text" class="form-control"  v-model="datos.costo1" 
                                                        placeholder="................................................................................" style="border: 0"> 
                                                    </th> -->
                                                </tr>
                                                <tr>                      
                                                    <th>
                                                        <label for="exampleInputPassword1" class="form-label">OBS:ㅤ</label>
                                                    </th>
                                                    <th colspan="7">
                                                        <input type="text" class="form-control"  v-model="datos.observaciones1" style="border: 0" 
                                                        placeholder="......................................................................................................................................................................................................................................................................................................................................................................................................................................................"> 
                                                    </th>
                                                </tr>

                                            </thead>
                                        </table>  
                                    </div>
                                    <div class="col-md-6 mb-2">
                                         <textarea class="form-control" v-model="datos.primer_dia" rows="5"
                                         placeholder="........................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................"></textarea>
                                    </div>
                                </form>
                            </div> 
                        </div>
                        <br>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                                        <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverHistoria()">Cancelar</button>
                                        <button class="btn btn-info btn-lg text-white" type="button" @click="guardarHistoria()">Actualizar Historia</button>
    
                                </div>     
                                <br>
                    </template>
                    <template v-if="listado==3">
                        <frm-historial-clinico  :selectMenu="selectMenu" @cerrarVentaTienda="listadoVenta"></frm-historial-clinico>
                    </template>
                    <template v-if="listado==4">
                        <frm-historial-clinico2  :selectMascota="selectMascota" @cerrarVentaTienda="listadoVenta"></frm-historial-clinico2>
                    </template>

                </div>
                </div>
            </div>
        <!-- </div> -->
        



    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        data(){
            return {
                datos : {
                    id : 0,
                    nombre : '',
                    matricula : '',
                    telefono : '',
                    direccion : '',
                    descripcion : '',
                    estado : '1',
                    cliente: '',
                    paciente: '',
                    especie: '',
                    raza: '',
                    
                    peso:0,
                    motivo:'',
                    fecha:'',
                    personal:'',

                    id_paciente:0,
                    id_cliente:0,
                    color:'',
                    edad:0,
                    especie:'',
                    id_animal:0,
                    id_historial_clinico:0,



                    //vacunaciones
                    parvovirus :0,
                    hexavalente :0,
                    octavalente :0,
                    rabia_perro :0,
                    tos_perrera :0,
                    ninguna_perro:0,
                    obs_p  :'',
                    obs_perro  :0,
                    //GATO
                    triple_felina:0,
                    rabia_gato : 0,
                    ninguna_gato : 0,
                    obs_g : 0,
                    obs_gato:'',
                    desparacitacion : 0,
                    desparacitacion_cuando:'',
                    //TEMPERATURA
                    temperatura:'',
                    //FC
                    fc:'',
                    taquicardia:0,
                    arritmia:0,
                    bradicardia:0,
                    sin_alteracion:0,
                    //FR
                    fr:'',
                    normal_fr:0,
                    disnea:0,
                    //MUCOSAS
                    rosada:0,
                    palidas:0,
                    ictericas:0,
                    cianotica:0,
                    //APETITO
                    normal_apetito:0,
                    disminuido:0,
                    anorexico:0,
                    //HIDRATACION
                    normal_hidratacion:0,
                    leve:0,
                    moderada:0,
                    marcada:0,
                    //ESTADO GENERAL
                    bueno_estado:0,
                    regular:0,
                    malo:0,
                    //ANTECEDENTES ENFERMEDADES
                    enfermedades:0,
                    enfermedades_cuales:'',
                    enfermedades_cuando:'',
                    //ANTECEDENTES CIRUGIA
                    cirugia:0,
                    cirugia_cuales:'',
                    cirugia_cuando:'',
                    //ORGANOS DE SENTIDO
                    ocular:'',
                    nariz:'',
                    bucal:'',
                    piel_anexo:'',
                    oidos:'',
                    vulvar:'',
                    prepucial:'',
                    //APARATO DIGESTIVO,RESPIRATORIO,GENITO URINARIO,NERVIOSO
                    digestivo_sin_alteracion:0,
                    digestivo_obs:'',
                    respiratorio_sin_alteracion:0,
                    respiratorio_obs:'',
                    urinario_sin_alteracion:0,
                    urinario_obs:'',
                    nervioso_sin_alteracion:0,
                    nervioso_obs:'',
                    //EXAMENES COMPLEMENTARIOS
                    muestra:'',
                    examenes_solicitado:'',
                    //TRATAMIENTO INDICADO
                    fecha1:'',
                    t1:0,
                    dr1:'',
                    hora1: moment().format('HH:mm:ss'),
                    costo1:0,
                    observaciones1:'',
                    primer_dia:'',
                    // NUEVO ATRIBUTO HIDRATACION
                    hidratacion : '',


                },
                selectMenu:{
                    // id_animal:0,
                    // animal:'',
                },  
                selectMascota:
                {
                    //
                },
                datosPago:{
                    id: 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    fecha_final : moment().format('YYYY-MM-DD'),
                    monto_total: '',
                    saldo : 0,
                    amortizacion :0,
                    descripcion: '',
                    id_pago: '',
                },    
                id_compra_aux:0, 
                arrayCXCobrar : [],
                arrayDetalleHistoria: [],

                ultimoPago : {},
                CXCobrar: [],                            
                arrayTienda : [],
                arrayHistoriaC : [],
                arrayHistoria : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCliente : 0,
                listado : 0,
                errorMostrarMsjCliente : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'paciente.nombre',
                buscar : '',
                setTimeoutBuscador: '',
                id_tienda : 1,
                tipo_producto: 'Venta Directa',
                estadoCaja: '',
                id_historial:0,
            }
        },
        computed : {
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }                
                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularSaldo: function(){
                var resultado = 0.0
                var saldo = this.datosPago.amortizacion;
                resultado = this.ultimoPago.saldo - saldo;
                return resultado;
            }
        },
        methods : {
            listadoVenta(listado,id){
                let me=this;
                me.listado = listado;
                me.datos.id = id;
                me.listarHistoriaClinica(this.buscar);

                var url='/historial/detalle?id=' + me.datos.id;
                axios.get(url).then(function(response){
                    me.arrayHistoria= response.data;
                    //me.id_historial= arrayHistoria[0].id;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            seleccionar_menu()
            {
              this.listado=3;
            },
            seleccionar_menu2()
            {
              this.listado=4;
              this.selectMascota.paciente=this.datos.paciente;
              this.selectMascota.id_paciente=this.datos.id_paciente;
              this.selectMascota.propietario=this.datos.cliente;
              this.selectMascota.id_cliente=this.datos.id_cliente;
              this.selectMascota.color=this.datos.color;
              this.selectMascota.edad=this.datos.edad;
              this.selectMascota.raza=this.datos.raza;
              this.selectMascota.telefono=this.datos.telefono;
              this.selectMascota.direccion=this.datos.direccion;
              this.selectMascota.id_animal=this.datos.id_animal;
              this.selectMascota.direccion=this.datos.direccion;
              this.selectMascota.especie=this.datos.especie;
              this.selectMascota.id_historial_clinico=this.datos.id_historial_clinico;
            },
            verificarCaja(){
                let me = this;
                var url='/arqueo_caja/estado_caja';
                axios.get(url).then(function(response){
                    me.estadoCaja= response.data.estado;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            actualizarHistoria(pago) {
                let me = this;
                me.listado = 2;
                // me.datos.id=data['id'];
                // me.datos.cliente=data['propietario'];
                // me.datos.paciente=data['mascota'];
                // me.datos.especie=data['especie'];
                // me.datos.color=data['color'];

                // var url='/historial/detalle?id=' + pago.id_historia ;
                // axios.get(url).then(function(response){
                //     me.arrayHistoria= response.data;
                // })
                // .catch(function(error){
                //     console.log(error);
                // });
                var url='/detalle/historia?id=' + pago.id;
                axios.get(url).then(function(response){
                    me.arrayDetalleHistoria= response.data;
                    me.id_historial = me.arrayDetalleHistoria[0].id_historia;
                    me.datos.id = me.arrayDetalleHistoria[0].id;
                    me.datos.peso = me.arrayDetalleHistoria[0].peso;
                    me.datos.motivo = me.arrayDetalleHistoria[0].motivo;
                    me.datos.personal = me.arrayDetalleHistoria[0].personal;
                    me.datos.fecha = me.arrayDetalleHistoria[0].fecha;
                    //VACUNACIONES
                    //PERRO
                    me.datos.parvovirus = me.arrayDetalleHistoria[0].parvovirus;
                    me.datos.hexavalente = me.arrayDetalleHistoria[0].hexavalente;
                    me.datos.octavalente = me.arrayDetalleHistoria[0].octavalente;
                    me.datos.rabia_perro = me.arrayDetalleHistoria[0].rabia_perro;
                    me.datos.tos_perrera = me.arrayDetalleHistoria[0].tos_perrera;
                    me.datos.ninguna_perro = me.arrayDetalleHistoria[0].ninguna_perro;
                    me.datos.obs_p = me.arrayDetalleHistoria[0].obs_p;
                    me.datos.obs_perro = me.arrayDetalleHistoria[0].obs_perro;
                    //GATO
                    me.datos.triple_felina = me.arrayDetalleHistoria[0].triple_felina;
                    me.datos.rabia_gato = me.arrayDetalleHistoria[0].rabia_gato;
                    me.datos.ninguna_gato = me.arrayDetalleHistoria[0].ninguna_gato;
                    me.datos.obs_g = me.arrayDetalleHistoria[0].obs_g;
                    me.datos.obs_gato = me.arrayDetalleHistoria[0].obs_gato;
                    //DESPARASITACION
                    me.datos.desparacitacion = me.arrayDetalleHistoria[0].desparacitacion;
                    me.datos.desparacitacion_cuando = me.arrayDetalleHistoria[0].desparacitacion_cuando;
                    //TEMPERATURA
                    me.datos.temperatura = me.arrayDetalleHistoria[0].temperatura;
                    //FC
                    me.datos.fc = me.arrayDetalleHistoria[0].fc;
                    me.datos.taquicardia = me.arrayDetalleHistoria[0].taquicardia;
                    me.datos.arritmia = me.arrayDetalleHistoria[0].arritmia;
                    me.datos.bradicardia = me.arrayDetalleHistoria[0].bradicardia;
                    me.datos.sin_alteracion = me.arrayDetalleHistoria[0].sin_alteracion;
                    //FC
                    me.datos.fr = me.arrayDetalleHistoria[0].fr;
                    me.datos.normal_fr = me.arrayDetalleHistoria[0].bueno_fr;
                    me.datos.disnea = me.arrayDetalleHistoria[0].disnea;
                    //MUCOSAS
                    me.datos.rosada = me.arrayDetalleHistoria[0].rosada;
                    me.datos.palidas = me.arrayDetalleHistoria[0].palidas;
                    me.datos.ictericas = me.arrayDetalleHistoria[0].ictericas;
                    me.datos.cianotica = me.arrayDetalleHistoria[0].cianotica;
                    //MUCOSAS
                    me.datos.normal_apetito = me.arrayDetalleHistoria[0].normal_apetito;
                    me.datos.disminuido = me.arrayDetalleHistoria[0].disminuido;
                    me.datos.anorexico = me.arrayDetalleHistoria[0].anorexico;
                    //HIDRATACION
                    me.datos.normal_hidratacion = me.arrayDetalleHistoria[0].normal_mucosa;
                    me.datos.leve = me.arrayDetalleHistoria[0].leve;
                    me.datos.moderada = me.arrayDetalleHistoria[0].moderada;
                    me.datos.marcada = me.arrayDetalleHistoria[0].marcada;
                    //ESTADO GENERAL
                    me.datos.bueno_estado = me.arrayDetalleHistoria[0].bueno_estado;
                    me.datos.regular = me.arrayDetalleHistoria[0].regular;
                    me.datos.malo = me.arrayDetalleHistoria[0].malo;
                    //ANTECEDENTES ENFERMEDADES
                    me.datos.enfermedades = me.arrayDetalleHistoria[0].enfermedades;
                    me.datos.enfermedades_cuales = me.arrayDetalleHistoria[0].enfermedades_cuales;
                    me.datos.enfermedades_cuando = me.arrayDetalleHistoria[0].enfermedades_cuando;
                    //ANTECEDENTES CIRUGIA
                    me.datos.cirugia = me.arrayDetalleHistoria[0].cirugia;
                    me.datos.cirugia_cuales = me.arrayDetalleHistoria[0].cirugia_cuales;
                    me.datos.cirugia_cuando = me.arrayDetalleHistoria[0].cirugia_cuando;
                    //ORGANOS DE SENTIDO
                    me.datos.ocular = me.arrayDetalleHistoria[0].ocular;
                    me.datos.nariz = me.arrayDetalleHistoria[0].nariz;
                    me.datos.bucal = me.arrayDetalleHistoria[0].bucal;
                    me.datos.piel_anexo = me.arrayDetalleHistoria[0].piel_anexo;
                    me.datos.oidos = me.arrayDetalleHistoria[0].oidos;
                    me.datos.vulvar = me.arrayDetalleHistoria[0].vulvar;
                    me.datos.prepucial = me.arrayDetalleHistoria[0].prepucial;
                    //APARATO DIGESTIVO,RESPIRATORIO,GENITO URINARIO,NERVIOSO
                    me.datos.digestivo_sin_alteracion = me.arrayDetalleHistoria[0].digestivo_sin_alteracion;
                    me.datos.digestivo_obs = me.arrayDetalleHistoria[0].digestivo_obs;
                    me.datos.respiratorio_sin_alteracion = me.arrayDetalleHistoria[0].respiratorio_sin_alteracion;
                    me.datos.respiratorio_obs = me.arrayDetalleHistoria[0].respiratorio_obs;
                    me.datos.urinario_sin_alteracion = me.arrayDetalleHistoria[0].urinario_sin_alteracion;
                    me.datos.urinario_obs = me.arrayDetalleHistoria[0].urinario_obs;
                    me.datos.nervioso_sin_alteracion = me.arrayDetalleHistoria[0].nervioso_sin_alteracion;
                    me.datos.nervioso_obs = me.arrayDetalleHistoria[0].nervioso_obs;
                    //EXAMENES COMPLEMENTARIOS
                    me.datos.muestra = me.arrayDetalleHistoria[0].muestra;
                    me.datos.examenes_solicitado = me.arrayDetalleHistoria[0].examenes_solicitado;
                    //EXAMENES COMPLEMENTARIOS
                    me.datos.fecha1 = me.arrayDetalleHistoria[0].fecha;
                    me.datos.t1 = me.arrayDetalleHistoria[0].temperatura;
                    me.datos.hora1 = me.arrayDetalleHistoria[0].hora1;
                    me.datos.dr1 = me.arrayDetalleHistoria[0].personal;
                    me.datos.costo1 = me.arrayDetalleHistoria[0].costo1;
                    me.datos.observaciones1 = me.arrayDetalleHistoria[0].observaciones1;
                    me.datos.primer_dia = me.arrayDetalleHistoria[0].primer_dia;

                    me.datos.hidratacion = me.arrayDetalleHistoria[0].hidratacion;






                })
                .catch(function(error){
                    console.log(error);
                });



            },
            guardarHistoria(){

                let me = this;
                axios.put('/historia/clinica/modificar',{
                    'id': me.datos.id,
                    'nro_historia': me.datos.nro_historia,
                    'nro_nuevo': me.datos.nro_nuevo,
                    'descripcion': me.datos.descripcion,
                    'peso': me.datos.peso,
                    'id_cliente': me.datos.id_cliente,
                    'id_paciente': me.datos.id_paciente,
                    'id_personal': me.datos.id_personal,
                    'parvovirus': me.datos.parvovirus,
                    'hexavalente': me.datos.hexavalente,
                    'octavalente': me.datos.octavalente,
                    'rabia_perro': me.datos.rabia_perro,
                    'tos_perrera': me.datos.tos_perrera,
                    'ninguna_perro': me.datos.ninguna_perro,
                    'obs_p': me.datos.obs_p,
                    'obs_perro': me.datos.obs_perro,
                    //GATO
                    'triple_felina': me.datos.triple_felina,
                    'rabia_gato': me.datos.rabia_gato,
                    'ninguna_gato': me.datos.ninguna_gato,
                    'obs_g': me.datos.obs_g,
                    'obs_gato': me.datos.obs_gato,
                    'desparacitacion': me.datos.desparacitacion,
                    'desparacitacion_cuando': me.datos.desparacitacion_cuando,
                    //TEMPERATURA
                    'temperatura': me.datos.temperatura,
                    //FC
                    'fc': me.datos.fc,
                    'taquicardia': me.datos.taquicardia,
                    'arritmia': me.datos.arritmia,
                    'bradicardia': me.datos.bradicardia,
                    'sin_alteracion': me.datos.sin_alteracion,
                    //FR
                    'fr': me.datos.fr,
                    'normal_fr': me.datos.normal_fr,
                    'disnea': me.datos.disnea,
                    //MUCOSAS
                    'rosada': me.datos.rosada,
                    'palidas': me.datos.palidas,
                    'ictericas': me.datos.ictericas,
                    'cianotica': me.datos.cianotica,
                    //APETITO
                    'normal_apetito': me.datos.normal_apetito,
                    'disminuido': me.datos.disminuido,
                    'anorexico': me.datos.anorexico,
                    //HIDRATACION
                    'normal_hidratacion': me.datos.normal_hidratacion,
                    'leve': me.datos.leve,
                    'moderada': me.datos.moderada,
                    'marcada': me.datos.marcada,
                    //ESTADO GENERAL
                    'bueno_estado': me.datos.bueno_estado,
                    'regular': me.datos.regular,
                    'malo': me.datos.malo,
                    //ANTECEDENTES ENFERMEDADES
                    'enfermedades': me.datos.enfermedades,
                    'enfermedades_cuales': me.datos.enfermedades_cuales,
                    'enfermedades_cuando': me.datos.enfermedades_cuando,
                    //ANTECEDENTES CIRUGIA
                    'cirugia': me.datos.cirugia,
                    'cirugia_cuales': me.datos.cirugia_cuales,
                    'cirugia_cuando': me.datos.cirugia_cuando,
                    //ORGANOS DE SENTIDO
                    'ocular': me.datos.ocular,
                    'nariz': me.datos.nariz,
                    'bucal': me.datos.bucal,
                    'piel_anexo': me.datos.piel_anexo,
                    'oidos': me.datos.oidos,
                    'vulvar': me.datos.vulvar,
                    'prepucial': me.datos.prepucial,
                    //APARATO DIGESTIVO,RESPIRATORIO,GENITO URINARIO,NERVIOSO
                    'digestivo_sin_alteracion': me.datos.digestivo_sin_alteracion,
                    'digestivo_obs': me.datos.digestivo_obs,
                    'respiratorio_sin_alteracion': me.datos.respiratorio_sin_alteracion,
                    'respiratorio_obs': me.datos.respiratorio_obs,
                    'urinario_sin_alteracion': me.datos.urinario_sin_alteracion,
                    'urinario_obs': me.datos.urinario_obs,
                    'nervioso_sin_alteracion': me.datos.nervioso_sin_alteracion,
                    'nervioso_obs': me.datos.nervioso_obs,
                    //EXAMENES COMPLEMENTARIOS
                    'muestra': me.datos.muestra,
                    'examenes_solicitado': me.datos.examenes_solicitado,
                    //TRATAMIENTO INDICADO
                    'fecha1': me.datos.fecha1,
                    //'t1': me.datos.t1,
                    'dr1': me.datos.dr1,
                    'hora1': me.datos.hora1,
                    'costo1': me.datos.costo1,
                    'observaciones1': me.datos.observaciones1,
                    'primer_dia': me.datos.primer_dia,
                    
                    'hidratacion': me.datos.hidratacion,


                }).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Historial Clinico actualizado exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    me.volverHistoria();
                    me.cargarPdf(me.datos.id);
                    // me.limpiarDatosVenta();
                    // console.log(me.datos);
                    
                })
                .catch(function(error){
                    console.log(error);
                });
            
                        
                    
                
                
            }, 
            limpiarCXCobrar(){
                this.datosPago ={
                    id: 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    monto_total: 0,
                    saldo : 0,
                    amortizacion : 0,
                    descripcion: '',
                    amortizacion: 0,
                    id_pago: 0,
                },
                
                this.ultimoPago = {
                    monto_total: 0,
                    amorticacion: 0,
                    saldo: 0,
                };
            },
            listarCXCobrar(pago){
                let me = this;
                var url='/detalle_pago_creditoC?id_pago=' + pago;
                axios.get(url).then(function(response){
                    me.arrayCXCobrar= response.data;
                    me.CXCobrar = response.data;
                    me.cargarUltimoPago();
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            cargarUltimoPago(){
                const array = this.CXCobrar;
                this.ultimoPago=array[array.length-1]; 
            },
            listarPagos(buscarP){
                let me = this;
                var url='/pago_compra?buscar=' + buscarP + '&id_tienda=' + me.id_tienda +  '&tipo_producto=' + me.tipo_producto;
                axios.get(url).then(function(response){
                    me.arrayListaPagos= response.data;
                    me.datosPago.fecha_final=me.arrayListaPagos[0].fecha_final;
                    me.datosPago.id_pago=me.arrayListaPagos[0].id;
                    
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarHistoriaClinica(buscar){
                let me = this;
                var url='/historial?buscar=' + buscar;
                axios.get(url).then(function(response){
                    me.arrayHistoriaC= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarClienteBusquedaRapida(){
                let me = this;
                var url='/historial?buscar=' + me.buscar;
                axios.get(url).then(function(response){
                    me.arrayHistoriaC= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            BuscandoCliente(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarClienteBusquedaRapida,350)
            },
            volverPagoVenta(){
                let me = this;
                me.arrayHistoriaC = [];
                me.arrayHistoria = [];
                me.listado = 0;
                this.listarHistoriaClinica(this.buscar);
            },
            volverHistoria(){
                let me = this;
                me.arrayHistoria=[];

                var url='/historial/detalle?id=' +  me.id_historial;
                    axios.get(url).then(function(response){
                me.arrayHistoria= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
                me.arrayHistoriaC = [];
                me.arrayDetalleHistoria = [];
                me.listado = 1;
                this.listarHistoriaClinica(this.buscar);
            },
            limpiarDatosPagos() {
                this.datosPago.amortizacion = 0;
              },
            guardarAmortizacion() {
              let me = this;
              if (me.datosPago.amortizacion == 0) {
                Swal.fire({
                  icon: "error",
                  title: "Error...",
                  text: "Ingrese un monto!",
                });
                me.limpiarCXCobrar();
              } else {
                console.log(me.ultimoPago.amortizacion);
                if (me.datosPago.amortizacion > parseFloat(me.ultimoPago.saldo)) {
                  Swal.fire({
                    icon: "error",
                    title: "Error...",
                    text: "Monto Mayor al Saldo!",
                  });
                  me.limpiarPago();
                } else {
                  axios.post("/c_x_pagar/guardar", {
                      fecha: me.datosPago.fecha,
                      monto_total: parseFloat(me.ultimoPago.monto_total).toFixed(2),
                      amortizacion: parseFloat(me.datosPago.amortizacion).toFixed(2),
                      saldo: me.ultimoPago.saldo,
                      descripcion: me.datosPago.descripcion,
                      id_pago: me.datosPago.id_pago,
                    })
                    .then(function (response) {
                      me.limpiarPago();
                      Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Pago registrado exitosamente",
                        showConfirmButton: false,
                        timer: 1500,
                      });
                      me.volverVentaListado();
                      me.listarVenta(1, "", "nombre");
                      me.listarCXCobrar(me.datosPago.id_pago);
                      me.listarPagosArticulos();
                    })
                    .catch(function (error) {
                      console.log(error);
                    });
                }
              }
            },
            cargarPdf(id) {
            let time=6000;
            this.downloadReport(time);
                axios.get('/historia/pdfHistoriaActualizar?id=' + id,{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            downloadReport(milisecond){
                let timerInterval
                    Swal.fire({
                    title: 'Cargando Reporte!',
                    html: 'Por favor espere!! <b></b> milliseconds.',
                    timer: milisecond,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                        timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                        }, 300)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                    }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log('I was closed by the timer')
                    }
                })
            },
            errorReport(){
                Swal.fire({
                    icon: 'error',
                    title: 'Error al Cargar el Reporte!...',
                    text: 'Comuniquese con el Administrador del Sistema',
                })
            },
            Historial(data=[]){
                let me = this;
                me.listado = 1;
                me.datos.id=data['id'];
                me.datos.cliente=data['propietario'];
                me.datos.paciente=data['mascota'];
                me.datos.color=data['color'];
                me.datos.id_paciente=data['id_paciente'];
                me.datos.id_cliente=data['id_cliente'];
                me.datos.color=data['color'];
                me.datos.raza=data['raza'];
                me.datos.edad=data['edad'];
                me.datos.telefono=data['telefono'];
                me.datos.direccion=data['direccion'];
                me.datos.id_animal=data['id_animal'];
                me.datos.especie=data['especie'];
                me.datos.id_historial_clinico=data['id'];

                //me.CargarDetalle(data['id']);
                var url='/historial/detalle?id=' +  data['id'];
                axios.get(url).then(function(response){
                    me.arrayHistoria= response.data;
                    //me.id_historial= arrayHistoria[0].id;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            CargarDetalle(id)
            {
                var url='/historial/detalle?id=' + id ;
                axios.get(url).then(function(response){
                    me.arrayHistoria= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            limpiarPago(){
                this.datosPago.amortizacion = 0;
                this.datosPago.descripcion = '';
            },
            cambiarPagina(page,buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarHistoriaClinica( buscar);
            }
        },
        mounted() {
            this.listarHistoriaClinica(this.buscar);
            this.verificarCaja();
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #d1cdcd7a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .footer{
        position:relative !important;
        width: auto !important;
    }
</style>