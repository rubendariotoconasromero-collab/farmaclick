<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                    <div class="card">
                    <div class="card-header row m-0" style="background-color: #3399FF">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-danger text-white" @click="volverVentaMenu()">
                                <i class="fa fa-reply-all"></i>&nbsp;Volver
                            </button>
                        </div>
                        <div class="col-md-4 text-center text-white"><h3 class="mb-0">REGISTRO CLINICO</h3></div>
                        <div class="col-md-4">&nbsp;</div>
                        <!-- <div class="col-md-2">
                            <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt=""> 
                        </div>  -->
                    </div> 
                        <!-- Listado de Ventas -->
                        <template v-if="listado==0">
                            <div class="card-body">
                                <div class="form-group row" style='margin-left: 1%'>   
                                <form class="row">
                                    <!-- <div class="col-md-2">
                                        <label for="exampleInputEmail1" class="form-label">Propietario</label>
                                        <div class="input-group mb-6">

                                            <section class="dropdown-wrapper form-control bg-disabled">
                                                <div @click="isVisible = !isVisible" class="selected-item">
                                                    <span v-if="datos.cliente==''">Seleccione Propietario</span>
                                                    <span  v-else>{{datos.cliente }} </span>
                                                    <svg :class="isVisible && datos.tipo_venta != 'Venta Servicio' ? 'dropdown' : ''" class="drop-down-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
                                                </div>
                                                <div :class="isVisible  && datos.tipo_venta != 'Venta Servicio' ? 'visible' : 'invisible'" class="dropdown-popover">
                                                    <input type="text" class="form-control" placeholder="Buscar Propietario.."  v-model="buscarCliente" aria-label="Buscar Propietario..">
                                                    <div class="text-center"><span v-if="filteredCliente.length === 0">No existen Propietario</span></div>
                                                    <div class="options">
                                                        <ul>
                                                            <li @click="selectedCliente(cliente)" v-for="(cliente, index) in filteredCliente" :key="`cliente-${index}`" @change="cambiar()">{{cliente.nombre}}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <label for="exampleInputPassword1" class="form-label">Propietario</label>
                                        <input type="text" class="form-control" v-model="datos.propietario" disabled>  
                                    </div>
                                    <div class="col-md-3">
                                        <label for="exampleInputEmail1" class="form-label">Doctor</label>
                                        <div class="input-group mb-6">
                                            <!-- <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2"> -->

                                            <section class="dropdown-wrapper form-control bg-disabled">
                                                <div @click="isVisible3 = !isVisible3" class="selected-item">
                                                    <span v-if="datos.doctor==''">Seleccione Doctor</span>
                                                    <span v-else>{{datos.doctor }}</span>
                                                    <svg :class="isVisible3 && datos.tipo_venta != 'Venta Servicio' ? 'dropdown' : ''" class="drop-down-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
                                                </div>
                                                <div :class="isVisible3  && datos.tipo_venta != 'Venta Servicio' ? 'visible' : 'invisible'" class="dropdown-popover">
                                                    <input type="text" class="form-control" placeholder="Buscar Propietario.."  v-model="buscarDoctor" aria-label="Buscar Propietario..">
                                                    <div class="text-center"><span v-if="filteredDoctor.length === 0">No existen Propietario</span></div>
                                                    <div class="options">
                                                        <ul>
                                                            <li @click="selectedDoctor(doctor)" v-for="(doctor, index) in filteredDoctor" :key="`doctor-${index}`">{{doctor.nombre}}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </section>

                                            <!-- &nbsp;&nbsp;&nbsp;
                                            <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalCliente" @click="listarCliente(buscarP)" :disabled="isDisabledCliente"><i class="fa fa-search"></i> Agregar Clientes</button> -->
                                        </div>
                                    </div>
                                    <template v-if="datos.nro_historia == 0">
                                    <div class="col-md-2">
                                        <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                        <input type="date" class="form-control"  v-model="datos.fecha" disabled>  
                                    </div>
                                    <div class="col-md-2">
                                        <label for="exampleInputPassword1" class="form-label">Nro Historia</label>
                                        <input type="number" class="form-control bg-rojo" v-model="datos.nro_historia" disabled>  
                                    </div>
                                    <div class="col-md-2">
                                        <label for="exampleInputPassword1" class="form-label">Nuevo Nro</label>
                                        <input type="number" class="form-control bg-azul"  v-model="datos.nro_nuevo" disabled>  
                                    </div>
                                    </template>
                                    <template v-else>
                                    <div class="col-md-4">
                                        <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                        <input type="date" class="form-control"  v-model="datos.fecha" disabled>  
                                    </div>                                        
                                    <div class="col-md-2">
                                        <label for="exampleInputPassword1" class="form-label">Nro Historia</label>
                                        <input type="number" class="form-control"  v-model="datos.nro_historia" disabled>  
                                    </div>
                                    </template>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Telefono</label>
                                        <input type="number" class="form-control"  v-model="datos.telefono" disabled>  
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Direccion</label>
                                        <input type="text" class="form-control"  v-model="datos.direccion" disabled>  
                                    </div>
                                    <template v-if="datos.tipo != 0">
                                    <div class="col-md-3">
                                        <label for="exampleInputEmail1" class="form-label">Mascota</label>
                                    <div class="input-group mb-6">
                                            <!-- <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2"> -->

                                            <section class="dropdown-wrapper form-control bg-disabled">
                                                <div @click="isVisible2 = !isVisible2" class="selected-item">
                                                    <span v-if="datos.paciente==''">Seleccione Mascota2</span>
                                                    <span v-else>{{datos.paciente }}</span>
                                                    <svg :class="isVisible2 && datos.tipo_venta != 'Venta Servicio' ? 'dropdown' : ''" class="drop-down-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
                                                </div>
                                                <div :class="isVisible2  && datos.tipo_venta != 'Venta Servicio' ? 'visible' : 'invisible'" class="dropdown-popover">
                                                    <input type="text" class="form-control" placeholder="Buscar Mascota.."  v-model="buscarPaciente" aria-label="Buscar Mascota..">
                                                    <div class="text-center"><span v-if="filteredPaciente.length === 0">No existen Mascota</span></div>
                                                    <div class="options">
                                                        <ul>
                                                            <li @click="selectedMascota(paciente)" v-for="(paciente, index) in filteredPaciente" :key="`paciente-${index}`">{{paciente.nombre}}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </section>

                                            <!-- &nbsp;&nbsp;&nbsp;
                                            <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalCliente" @click="listarCliente(buscarP)" :disabled="isDisabledCliente"><i class="fa fa-search"></i> Agregar Clientes</button> -->
                                    </div>
                                    </div>
                                    </template>
                                    <template v-else>
                                    <div class="col-md-3">
                                        <label for="exampleInputEmail1" class="form-label">Mascota</label>
                                    <div class="input-group mb-6">
                                            <!-- <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2"> -->

                                            <section class="dropdown-wrapper form-control bg-disabled">
                                                <div @click="isVisible2 = !isVisible2" class="selected-item">
                                                    <span v-if="datos.paciente==''">Seleccione Mascota</span>
                                                    <span v-else>{{datos.paciente }}</span>
                                                    <svg :class="isVisible2 && datos.tipo_venta != 'Venta Servicio' ? 'dropdown' : ''" class="drop-down-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
                                                </div>
                                                <div :class="isVisible2  && datos.tipo_venta != 'Venta Servicio' ? 'visible' : 'invisible'" class="dropdown-popover">
                                                    <input type="text" class="form-control" placeholder="Buscar Mascota.."  v-model="buscarPaciente" aria-label="Buscar Mascota..">
                                                    <div class="text-center"><span v-if="filteredPaciente.length === 0">No existen Mascota</span></div>
                                                    <div class="options">
                                                        <ul>
                                                            <li @click="selectedMascota(paciente)" v-for="(paciente, index) in filteredPaciente" :key="`paciente-${index}`">{{paciente.nombre}}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </section>

                                            <!-- &nbsp;&nbsp;&nbsp;
                                            <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalCliente" @click="listarCliente(buscarP)" :disabled="isDisabledCliente"><i class="fa fa-search"></i> Agregar Clientes</button> -->
                                    </div>
                                    </div>
                                    </template>
                                    
                                    <div class="col-md-2">
                                        <label for="exampleInputPassword1" class="form-label">Especie</label>
                                        <input type="text" class="form-control"  v-model="datos.especie" disabled>  
                                    </div>
                                    <div class="col-md-3">
                                        <label for="exampleInputPassword1" class="form-label">Color</label>
                                        <input type="text" class="form-control"  v-model="datos.color" disabled>  
                                    </div>
                                    <div class="col-md-4">
                                        <label for="exampleInputPassword1" class="form-label">Edad</label>
                                        <input type="text" class="form-control"  v-model="datos.edad">  
                                    </div>
                                    <!-- <div class="col-md-2">
                                        <label for="exampleInputPassword1" class="form-label">Meses</label>
                                        <input type="number" class="form-control"  v-model="datos.mes" disabled>  
                                    </div> -->

                                    <div class="col-md-4">
                                        <label for="exampleInputPassword1" class="form-label">Raza</label>
                                        <input type="text" class="form-control"  v-model="datos.raza" disabled>  
                                    </div>
                                    <div class="col-md-4">
                                        <label for="inputPassword" class="form-label">Sexo</label>
                                        <div class="col-sm-10">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" v-model="datos.sexo">
                                                <label class="form-check-label" for="inlineRadio1">Macho</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0" v-model="datos.sexo">
                                                <label class="form-check-label" for="inlineRadio2">Hembra</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="exampleInputPassword1" class="form-label">Peso</label>
                                        <input type="number" class="form-control"  v-model="datos.peso" min="0" >  
                                    </div>
                                    <div class="col-md-12">
                                        <label for="exampleInputPassword1" class="form-label">Anamnesis(MOTIVO DE LA CONSULTA)</label>
                                        <textarea class="form-control" v-model="datos.descripcion" rows="2"></textarea>
                                        <br>
                                    </div>   
                                    <br>
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
                        <br>
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
                                                        <input type="text" class="form-control"  v-model="datos.doctor" placeholder=".................................................................." style="border: 0"> 
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
                                </form>           
                                    <div class="col-md-12">
                                        <div v-show="errorPago" class="form-group row div-error">
                                            <div class="text-center text-error">
                                                <div v-for="error in errorMostrarMsjPago" :key="error" v-text="error">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <!-- <div class="form-group row">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                                        <thead style="background-color: #46546C">
                                            <tr>                      
                                                <th scope="col" class="text-white">Opción</th>
                                                <th scope="col" class="text-white">Categoria</th>
                                                <th scope="col" class="text-white">Nombre</th>
                                                <th scope="col" class="text-white">Tienda</th>
                                                <th scope="col" class="text-white">Precio</th>
                                                <th scope="col" class="text-white">Cantidad</th>
                                                <th scope="col" class="text-white">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                                </td>
                                                <td v-text="detalle.categoria"></td>
                                                <td v-text="detalle.articulo"></td>
                                                <td v-text="detalle.tienda"></td>
                                                <td>
                                                    <div v-if="datos.tipo_venta=='Venta Directa'">

                                                            <vue-numeric v-model="detalle.costo_unitario" :precision="2" id="Unitario" class="form-control"></vue-numeric>
   
                                                    </div>
                                                    <div v-if="datos.tipo_venta=='Venta Servicio' || datos.tipo_venta=='Venta Paquete'">
                                                        {{detalle.costo_venta}}
                                                    </div>
                                                    
                                                </td>
                                                <td>
                                                    <div v-if="datos.tipo_venta=='Venta Directa'">
                                                        <input v-model="detalle.cantidad" type="number" value="3" class="form-control" @keyup="actualizarStockProducto(detalle.id_paquete,detalle.cantidad,detalle.producto_venta)">
                                                    </div>
                                                    <div v-if="datos.tipo_venta=='Venta Servicio'">
                                                        {{detalle.cantidad}}
                                                    </div>
                                                    <span style="color:red;" v-show="detalle.cantidad>detalle.stock && datos.tipo_venta!='Venta Servicio'">Stock: {{detalle.stock}}</span>
                                                </td>
                                                <td>
                                                    <div v-if="datos.tipo_venta=='Venta Directa'">
                                                                {{detalle.sub_total = detalle.costo_unitario*detalle.cantidad}}
                                                    </div>
                                                    <div v-if="datos.tipo_venta=='Venta Servicio'">
                                                        {{detalle.sub_total = detalle.costo_venta*detalle.cantidad}} bs
                                                    </div>
                                                </td>                                                                             
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="6" align="right"> <strong>Sub Total:</strong> </td>
                                                <td>{{datos.sub_total = calcularSubTotal.toFixed(2)}} bs</td> 
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="6" align="right"> <strong>Descuento:</strong> </td>
                                                <td>
                                                    <div v-if="datos.tipo_venta=='Venta Directa'">
                                                        <vue-numeric v-model="datos.descuento" :precision="2" value="0" class="form-control" :max="parseFloat(datos.sub_total)"></vue-numeric>
                                                    </div>
                                                    <div v-if="datos.tipo_venta=='Venta Servicio'">
                                                        {{datos.descuento}}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="6" align="right"> <strong>Total:</strong> </td>
                                                <td>{{datos.total = datos.sub_total- datos.descuento}} bs</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="7">No hay Productos agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                </div> -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                                    <!-- <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaListado()">Cancelar</button> -->
                                    <template v-if="datos.nro_historia == 0">
                                        <button class="btn btn-info btn-lg text-white" type="button" @click="guardarHistoria()">Guardar</button>
                                        <!-- <button class="btn btn-info btn-lg text-white" type="button" @click="cargarPdf2()">Prueba</button> -->
                                    </template>
                                    <template v-else>
                                        <button class="btn btn-info btn-lg text-white" type="button" @click="guardarHistoria2()">Imprimir</button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <!-- Fin Listado de Ventas -->
                    </div>
                </div>
            </div>
        <!-- </div>   -->
    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        created() {
            this.datos.costo_pago = 1;
            this.datos.tipo = 0;
        },
        data(){
            return {
                datos : {
                    id : 0,
                    fecha :  moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_tipo_pago : 1,
                    id_forma_pago : 2,
                    id_cliente : 0,
                    cliente : '',
                    propietario : '',
                    tipoPago : '',
                    formaPago : '',
                    costo_pago : '',
                    id_descuento: '',
                    personal:'',
                    estado:'',
                    tipo_venta: 'Venta Directa',
                    id_orden_servicio: null,
                    telefono : 0,
                    direccion : '',
                    especie : '',
                    color : '',
                    nro_historia: 0,
                    nro_nuevo: 0,
                    sexo:0,
                    raza:'',
                    peso :0,

                    id_paciente : 0,
                    id_animal : 0,
                    animal : '',
                    paciente : '',
                    edad : 0,
                    mes : 0,

                    id_personal : 0,
                    doctor : '',
                    descripcion : '',
                    tipo: 0,


                     //vacunaciones
                    parvovirus :0,
                    hexavalente :0,
                    octavalente :0,
                    rabia_perro :0,
                    tos_perrera :0,
                    ninguna_perro:0,
                    obs_p  :0,
                    obs_perro  :'',
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
                    fecha1 :  moment().format('YYYY-MM-DD'),
                    t1:0,
                    dr1:'',
                    hora1: moment().format('HH:mm:ss'),
                    costo1:0,
                    observaciones1:'',
                    primer_dia:'',
                    // NUEVO ATRIBUTO HIDRATACION
                    hidratacion : '',
                },
                datosPago:{
                    id: 0,
                    fecha :  moment().format('YYYY-MM-DD'),
                    fecha_final : moment().format('YYYY-MM-DD'),
                    //monto_total: 0,
                    saldo : 0,
                    anticipo : 0,
                    descripcion: '',
                },   
                arrayVenta : [],
                arrayDetalle : [],
                arrayArticulo : [],
                arrayDetalleProducto : [],
                arrayProductoPaquete: [],
                //arrayDetallePaqueteProducto : [],
                arrayCliente: [],
                arrayPaciente: [],
                arrayCostoPago: [{id:1,nombre:'Unitario'},{id:2,nombre:'Mayorista'},{id:3,nombre:'Preferencial'}],
                arrayTipoCliente: [],
                arrayPersonal: [],
                arrayPago: [],
                arrayForma: [],
                arrayForma2: [],
                arrayOrden : [],  
                arrayPaquete : [],  
                arrayNroHistoria : [],
                listado : 0,
                tipoAccion : 0,
                errorPago : 0,
                errorMostrarMsjPago : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterioP : 'articulo.nombre_comercial',
                buscar : '',
                buscarP : '',
                isDisabled: true,
                isDisabledCliente: false,
                isDisabledProducto: false,
                isDisabledOrden: false,
                isDisabledPaquete: false,
                setTimeoutBuscador: '',
                isVisible: false,
                isVisible2: false,
                isVisible3: false,
                buscarCliente: '',
                buscarPaciente: '',
                buscarDoctor: '',
                estadoCaja: '',
                listadoVenta: 0,
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
            calcularSubTotal: function(){
                var resultado = 0.0;
                if(this.datos.tipo_venta=='Venta Directa') {
                    for(var i=0;i<this.arrayDetalle.length;i++){

                        resultado = resultado + (this.arrayDetalle[i].costo_unitario*this.arrayDetalle[i].cantidad);
                    }
                } else {
                    for(var i=0;i<this.arrayDetalle.length;i++){
                        resultado = resultado + (this.arrayDetalle[i].costo_venta*this.arrayDetalle[i].cantidad);
                    }
                }
                
                return resultado;
            },
            calcularSaldoAnticipado: function(){
                this.datosPago.saldo = this.datos.total - this.datosPago.anticipo;
            },
            filteredCliente(){
                const data = this.buscarCliente.toLowerCase();
                if(this.buscarCliente == ""){
                    return this.arrayCliente;
                }
                return this.arrayCliente.filter((item)=>{
                    return Object.values(item).some((word=>String(word).toLowerCase().includes(data)))
                })
            },
            filteredPaciente(){
                const data = this.buscarPaciente.toLowerCase();
                if(this.buscarPaciente == ""){
                    return this.arrayPaciente;
                }
                return this.arrayPaciente.filter((item)=>{
                    return Object.values(item).some((word=>String(word).toLowerCase().includes(data)))
                })
            },
            filteredDoctor(){
                const data = this.buscarDoctor.toLowerCase();
                if(this.buscarDoctor == ""){
                    return this.arrayPersonal;
                }
                return this.arrayPersonal.filter((item)=>{
                    return Object.values(item).some((word=>String(word).toLowerCase().includes(data)))
                })
            }

            // actualizarStockProducto(id,cantidad,paquete){
            //     let me = this;

            //     if(paquete == 'Venta Paquete'){
            //         me.arrayProductoPaquete.forEach(item => { item.id_paquete == id ? item.cantidad = item.cantidad*cantidad : ''});
            //     }
                
            // },
        },
        methods : {
            cambiar(){
                let me = this;
                me.datos.tipo = 1;
            },
            volverVentaMenu(){
                this.listado = 0;
                this.$emit('cerrarVentaTienda', this.listadoVenta);
            },
            actualizarStockProducto(id,cantidad,paquete){
                let me = this;
                if(paquete == 'Venta Paquete'){ 
                    me.arrayProductoPaquete.forEach(
                        item => { 
                            item.id_paquete == id  && item.tipo_producto== 'Producto Venta'? item.cantidad_aux = item.cantidad*cantidad : '';
                        });
                }

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
            // listarArticulo(buscarP){
            //     let me = this;
            //     me.buscarCliente= '';
            //     me.isVisible = false;
            //     var url='/tienda/listarSinPaginate2/tienda1?buscar=' + buscarP;
            //     axios.get(url).then(function(response){
            //         me.arrayArticulo= response.data;
            //     })
            //     .catch(function(error){
            //         console.log(error);
            //     });
            // },
            listarArticulo(buscarP, criterioP){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                var url='/tienda/listarSinPaginate2/tienda1?buscar=' + buscarP + '&criterio=' + criterioP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarArticuloBusquedaRapida(){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                var url='/tienda/listarSinPaginate2/tienda1?buscar=' + me.buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            BuscandoProducto(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida,350)
            },
            listarCliente(buscarP){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                var url='/cliente/listarSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayTipoCliente= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarClienteBusquedaRapida(){
                let me = this;
                var url='/cliente/listarSinPaginate?buscar=' + me.buscarP;
                axios.get(url).then(function(response){
                    me.arrayTipoCliente= response.data;
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
            selectedCliente(cliente){
                this.datos.cliente='';
                this.datos.id_cliente=0;
                this.datos.id_descuento='';
                this.isVisible = false;
                this.datos.cliente = cliente.nombre;
                this.datos.id_cliente = cliente.id;
                this.datos.telefono = cliente.telefono;
                this.datos.direccion = cliente.direccion;
                this.datos.id_descuento = cliente.descuento;
                this.selectPaciente();
            },
            selectedMascota(paciente){
                //var fecha1 = new Date(paciente.edad).getTime();
                //var fecha2 = new Date(this.datos.fecha).getTime();
                //console.log(fecha1);
                //console.log(fecha2);
                
                // var cantidad_edad = (fecha2 - fecha1)/(1000*60*60*24*365);

                // var cantidad_entero = Math.floor((fecha2 - fecha1)/(1000*60*60*24*365));
                // var cantidad_meses = ((fecha2 - fecha1)/(1000*60*60*24*365*30)).toFixed(0);
                // var meses = ((cantidad_edad-cantidad_entero)*365)/30;
                // console.log(meses);

                this.datos.paciente='';
                this.datos.id_paciente=0;
                this.datos.id_animal='';
                this.isVisible2 = false;
                this.datos.paciente = paciente.nombre;
                this.datos.id_paciente = paciente.id;
                this.datos.id_animal = paciente.id_animal;
                this.datos.id_cliente = paciente.id_cliente;
                this.datos.animal = paciente.animal;
                this.datos.especie = paciente.animal;
                this.datos.telefono = paciente.telefono;
                this.datos.direccion = paciente.direccion;
                this.datos.color = paciente.color;
                this.datos.sexo = paciente.sexo;
                this.datos.raza = paciente.raza;
                this.datos.edad = paciente.edad;
                this.datos.propietario = paciente.cliente;
                // this.datos.mes = meses.toFixed(0);

                this.Nro_historia();

                //this.datos.id_cliente = paciente.id_cliente;
            },
            selectedDoctor(doctor){
                this.datos.doctor='';
                this.datos.id_personal=0;
                this.isVisible3 = false;
                this.datos.doctor = doctor.nombre;
                this.datos.id_personal = doctor.id;
            },
            listarOrden(buscarP){
                let me = this;
                me.buscarCliente= '',
                me.isVisible = false;
                var url='/servicio/listarOrdenSinPaginate_tienda1?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayOrden= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarPaquete(buscarP){
                let me = this;
                me.buscarCliente= '',
                me.isVisible = false;
                var url='/paquete/listarOrdenSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayPaquete= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_articulo==id ){
                        sw=true;
                    }
                }
                return sw;
            },
            encuentraPaquete(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_paquete==id ){
                        sw=true;
                    }
                }
                return sw;
            },
            
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            
            eliminarProductoPaquete(id){
                let array = [];
                array = this.arrayProductoPaquete.filter(item => item.id_paquete != id);
                this.arrayProductoPaquete = array;
            },
            
            eliminarDetalle(index){
                let me = this;
                let id_paquete = 0;
                if(me.arrayDetalle[index].producto_venta=='Venta Paquete'){
                    id_paquete = me.arrayDetalle[index].id_paquete;
                    me.eliminarProductoPaquete(id_paquete);
                }
                me.arrayDetalle.splice(index,1);
            },


            seleccionarPaquete(data=[]){
                let me = this;
                if(me.encuentraPaquete(data['id'])){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este paquete ya se encuentra agregado!'
                    })
                }
                else{
                    
                    me.arrayDetalle.push({
                        //id_tienda_articulo : data['id'],
                        id_paquete : data['id'],
                        articulo : data['nombre'],
                        tienda : '',
                        costo_unitario : data['total'],
                        costo_mayorista : '',
                        costo_preferencial : '',
                        costo_compra : '',
                        marca : '',
                        id_categoria : '',
                        categoria : '',
                        stock : 1000,
                        cantidad : 1,
                        producto_venta: 'Venta Paquete'
                        //sub_total : data['sub_total'],
                    });
                    me.datos.estado= 'Entregado';
                    me.datos.tipo_venta= 'Venta Directa'
                    me.isDisabled = false;
                    me.isDisabledOrden = true;
                    //me.isDisabledPaquete = true;
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });
                }
                this.cargarDetallePaquete(data['id']);

                    
            },

            cargarDetallePaquete(id){
                let me = this;
                var url='/paquete/permiso/detalle?id=' + id;
                axios.get(url).then(function(response){
                    me.arrayDetalleProducto= response.data;
                    me.arrayDetalleProducto.forEach(
                    item => { 
                        item.cantidad_aux = item.cantidad*1;
                    });

                    if(me.arrayProductoPaquete.length==0){
                        me.arrayProductoPaquete = me.arrayDetalleProducto;
                    }else{
                        me.arrayProductoPaquete=me.arrayProductoPaquete.concat(me.arrayDetalleProducto);
                    }
                    
                })
                .catch(function(error){
                    console.log(error);
                });
            },



            cargarArticuloPaquete(){
                this.arrayProductoPaquete = this.arrayDetalleProducto;
                // me.arrayProductoPaquete.push({
                //     id_tienda_articulo : data['id'],
                //     id_articulo : data['id_articulo'],
                //     articulo : data['articulo'],
                //     tienda : data['tienda'],
                //     costo_unitario : data['costo_unitario'],
                //     costo_mayorista : data['costo_mayorista'],
                //     costo_preferencial : data['costo_preferencial'],
                //     costo_compra : data['costo_compra'],
                //     marca : data['marca'],
                //     id_categoria : data['id_categoria'],
                //     categoria : data['categoria'],
                //     stock : data['stock'],
                //     cantidad : 1,
                //     sub_total : data['sub_total'],
                // });
                
            },


            
            seleccionarTiendaArticulo(data=[]){
                let me = this;
                if(me.encuentra(data['id_articulo'])){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este producto ya se encuentra agregado!'
                    })
                }
                else{
                    me.arrayDetalle.push({
                        id_tienda_articulo : data['id'],
                        id_articulo : data['id_articulo'],
                        articulo : data['articulo'],
                        tienda : data['tienda'],
                        costo_unitario : data['costo_unitario'],
                        costo_mayorista : data['costo_mayorista'],
                        costo_preferencial : data['costo_preferencial'],
                        costo_compra : data['costo_compra'],
                        marca : data['marca'],
                        id_categoria : data['id_categoria'],
                        categoria : data['categoria'],
                        stock : data['stock'],
                        cantidad : 1,
                        sub_total : data['sub_total'],
                        producto_venta: 'Venta Producto'
                    });
                    me.datos.estado= 'Entregado';
                    me.datos.tipo_venta= 'Venta Directa'
                    me.isDisabled = false;
                    me.isDisabledOrden = true;
                    me.isDisabledPaquete = true;
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });
                }
            },

            seleccionarCliente(data=[]){
                this.buscarCliente= '',
                this.datos.id_cliente=data['id'];
                this.datos.cliente= data['nombre'];
                this.datos.id_descuento= data['descuento'];
            },
            volverVentaListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.cliente = '';
                me.datos.estado = '';
                me.datos.id_tipo_pago = 1,
                me.datos.id_forma_pago = 2,
                me.buscarP = '';
                me.listado = 0;
                me.isDisabledCliente = false;
                me.isDisabledProducto = false;
                me.isDisabledPaquete = false;
                me.isDisabledOrden = false;
                me.buscar = '';
            },
            selectCostoPago(){
                let me=this;
                var url='/articulo/selectPrecio';
                axios.get(url).then(function(response){
                    me.arrayCostoPago=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectCliente(){
                let me=this;
                var url='/cliente/selectCliente';
                axios.get(url).then(function(response){
                    me.arrayCliente=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectPersonal(){
                let me=this;
                var url='/personal/selectPersonalDoctor';
                axios.get(url).then(function(response){
                    me.arrayPersonal=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectPaciente(){
                let me=this;
                var url='/paciente/selectPaciente';
                axios.get(url).then(function(response){
                    me.arrayPaciente=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            Nro_historia(){
                let me=this;
                var url='/nro_historia?id_cliente=' + me.datos.id_cliente + '&id_paciente=' + me.datos.id_paciente;
                axios.get(url).then(function(response){
                    //me.arrayPaciente=response.data;
                    me.arrayNroHistoria=response.data;
                    me.datos.nro_historia = 0;
                    me.selectPaciente();
                    me.ultimo_id()
                    if(response.data.indexOf('id') !== -1 ){

                        console.log('Hola');
                    }else
                        me.datos.nro_historia = me.arrayNroHistoria[0].id
                        console.log(me.datos.nro_historia);
                    {
                       //me.totalCompra = parseFloat(response.data[0].total) ? parseFloat(response.data[0].total) : 0
                    }
                    //me.totalCompra = parseFloat(response.data[0].total) ? parseFloat(response.data[0].total) : 0;

                    me.selectPaciente();
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            ultimo_id(){
                let me=this;
                var url='historia/clinica/ultimo';
                axios.get(url).then(function(response){
                    me.arrayNroHistoria=response.data;
                    me.datos.nro_nuevo = response.data[0].id+1;
                    
                    // if(me.datos.id == NULL){
                         
                        //  me.datos.nro_nuevo = parseFloat(response.data[0].id+1) ? parseFloat(response.data[0].id) : 0+1;
                    // }else{
                    // }
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectTipoP(){
                let me=this;
                var url='/tipoPago/selectTipoP';
                axios.get(url).then(function(response){
                    me.arrayPago=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectFormaP(){
                let me=this;
                var url='/formaPago/selectFormaP';
                axios.get(url).then(function(response){
                    me.arrayForma=response.data;
                    me.arrayForma2 = response.data;
                    me.arrayForma2 = me.arrayForma2.filter((item) => item.id !== 1);
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            verVenta(data=[]){
                let me = this;
                me.listado = 2;
                me.datos.id=data['id'];
                me.datos.cliente=data['cliente'];
                me.datos.fecha=data['fecha'];
                me.datos.descuento=data['descuento'];
                me.datos.tipoPago=data['tipoP'];
                me.datos.formaPago=data['formaP'];

                var url='/venta/permiso/detalle/tienda1?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            guardarHistoria(){

                if(this.datos.doctor == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar al Doctor!'
                    })
                }
                if(this.datos.peso == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar al Peso!'
                    })
                }
                if(this.datos.descripcion == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar el Motivo!'
                    })
                }
                if(this.datos.paciente == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar al Mascota!'
                    })
                }
                    else {
                            let me = this;
                            axios.post('/historia/clinica/guardar',{
                                'id_servicio': me.datos.id,
                                'fecha': me.datos.fecha,
                                'nro_historia': me.datos.nro_historia,
                                'nro_nuevo': me.datos.nro_nuevo,
                                'descripcion': me.datos.descripcion,
                                'peso': me.datos.peso,
                                'id_cliente': me.datos.id_cliente,
                                'id_paciente': me.datos.id_paciente,
                                'id_personal': me.datos.id_personal,
                                'edad': me.datos.edad,
                                'mes': me.datos.mes,
                                'edad': me.datos.edad,

                                 //VACUNACIONES
                                //PERRO
                                'parvovirus' : me.datos.parvovirus,
                                'hexavalente' : me.datos.hexavalente, 
                                'octavalente' : me.datos.octavalente ,
                                'rabia_perro' :me.datos.rabia_perro,
                                'tos_perrera' :me.datos.tos_perrera,
                                'ninguna_perro' : me.datos.ninguna_perro,
                                'obs_p' : me.datos.obs_p,
                                'obs_perro' :me.datos.obs_perro,
                                //GATO
                                'triple_felina' :me.datos.triple_felina,
                                'rabia_gato' :me.datos.rabia_gato,
                                'ninguna_gato' :me.datos.ninguna_gato, 
                                'obs_g' :me.datos.obs_g ,
                                'obs_gato' :me.datos.obs_gato,
                                //DESPARASITACION
                                'desparacitacion' :me.datos.desparacitacion, 
                                'desparacitacion_cuando' :me.datos.desparacitacion_cuando ,
                                //TEMPERATURA
                                'temperatura' :me.datos.temperatura ,
                                //FC
                                'fc' :me.datos.fc ,
                                'taquicardia' :me.datos.taquicardia ,
                                'arritmia' :me.datos.arritmia ,
                                'bradicardia' :me.datos.bradicardia, 
                                'sin_alteracion' :me.datos.sin_alteracion, 
                                //FC
                                'fr' :me.datos.fr, 
                                'normal_fr' :me.datos.normal_fr, 
                                'disnea' :me.datos.disnea ,
                                //MUCOSAS
                                'rosada' :me.datos.rosada ,
                                'palidas' :me.datos.palidas,
                                'ictericas' :me.datos.ictericas,
                                'cianotica' :me.datos.cianotica ,
                                //MUCOSAS
                                'normal_apetito' :me.datos.normal_apetito,
                                'disminuido' :me.datos.disminuido ,
                                'anorexico' :me.datos.anorexico ,
                                //HIDRATACION
                                'normal_hidratacion' :me.datos.normal_hidratacion ,
                                'leve' :me.datos.leve ,
                                'moderada' :me.datos.moderada,
                                'marcada' :me.datos.marcada ,
                                //ESTADO GENERAL
                                'bueno_estado' :me.datos.bueno_estado,
                                'regular' :me.datos.regular,
                                'malo' :me.datos.malo,
                                //ANTECEDENTES ENFERMEDADES
                                'enfermedades' :me.datos.enfermedades, 
                                'enfermedades_cuales' :me.datos.enfermedades_cuales, 
                                'enfermedades_cuando' :me.datos.enfermedades_cuando,
                                //ANTECEDENTES CIRUGIA
                                'cirugia' :me.datos.cirugia,
                                'cirugia_cuales' :me.datos.cirugia_cuales, 
                                'cirugia_cuando' :me.datos.cirugia_cuando ,
                                //ORGANOS DE SENTIDO
                                'ocular' :me.datos.ocular ,
                                'nariz' :me.datos.nariz  ,
                                'bucal' :me.datos.bucal ,
                                'piel_anexo' :me.datos.piel_anexo,
                                'oidos' :me.datos.oidos,
                                'vulvar' :me.datos.vulvar,
                                'prepucial' :me.datos.prepucial, 
                                //APARATO DIGESTIVO,RESPIRATORIO,GENITO URINARIO,NERVIOSO
                                'digestivo_sin_alteracion' :me.datos.digestivo_sin_alteracion,
                                'digestivo_obs' :me.datos.digestivo_obs ,
                                'respiratorio_sin_alteracion' :me.datos.respiratorio_sin_alteracion, 
                                'respiratorio_obs' :me.datos.respiratorio_obs,
                                'urinario_sin_alteracion' :me.datos.urinario_sin_alteracion, 
                                'urinario_obs' :me.datos.urinario_obs ,
                                'nervioso_sin_alteracion' :me.datos.nervioso_sin_alteracion ,
                                'nervioso_obs' :me.datos.nervioso_obs,
                                //EXAMENES COMPLEMENTARIOS
                                'muestra' :me.datos.muestra ,
                                'examenes_solicitado' :me.datos.examenes_solicitado,
                                //EXAMENES COMPLEMENTARIOS
                                'fecha1' :me.datos.fecha1,
                                'doctor' :me.datos.doctor ,
                                'hora1' :me.datos.hora1, 
                                'dr1' :me.datos.dr1 ,
                                'costo1' :me.datos.costo1,
                                'observaciones1' :me.datos.observaciones1, 
                                'primer_dia' :me.datos.primer_dia ,

                                'hidratacion' :me.datos.hidratacion,

                                'detalle': me.arrayDetalle,

                            }).then(function(response){
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Historial Clinico registrado exitosamente',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                me.cargarPdf2();
                                me.volverVentaListado();
                                me.limpiarDatosVenta();
                                me.volverVentaMenu();
                                console.log(me.datos);
                            })
                            .catch(function(error){
                                console.log(error);
                            });
                        }
                        
                    
                
                
            }, 
            guardarHistoria2(){

                if(this.datos.doctor == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar al Doctor!'
                    })
                }
                if(this.datos.peso == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar al Peso!'
                    })
                }
                if(this.datos.descripcion == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar el Motivo!'
                    })
                }
                if(this.datos.paciente == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar al Mascota!'
                    })
                }else {
                            let me = this;
                            axios.post('/historia/clinica/guardar',{
                                'id_servicio': me.datos.id,
                                'fecha': me.datos.fecha,
                                'nro_historia': me.datos.nro_historia,
                                'nro_nuevo': me.datos.nro_nuevo,
                                'descripcion': me.datos.descripcion,
                                'peso': me.datos.peso,
                                'id_cliente': me.datos.id_cliente,
                                'id_paciente': me.datos.id_paciente,
                                'id_personal': me.datos.id_personal,
                                'edad': me.datos.edad,
                                'mes': me.datos.mes,

                                //VACUNACIONES
                                //PERRO
                                'parvovirus' : me.datos.parvovirus,
                                'hexavalente' : me.datos.hexavalente, 
                                'octavalente' : me.datos.octavalente ,
                                'rabia_perro' :me.datos.rabia_perro,
                                'tos_perrera' :me.datos.tos_perrera,
                                'ninguna_perro' : me.datos.ninguna_perro,
                                'obs_p' : me.datos.obs_p,
                                'obs_perro' :me.datos.obs_perro,
                                //GATO
                                'triple_felina' :me.datos.triple_felina,
                                'rabia_gato' :me.datos.rabia_gato,
                                'ninguna_gato' :me.datos.ninguna_gato, 
                                'obs_g' :me.datos.obs_g ,
                                'obs_gato' :me.datos.obs_gato,
                                //DESPARASITACION
                                'desparacitacion' :me.datos.desparacitacion, 
                                'desparacitacion_cuando' :me.datos.desparacitacion_cuando ,
                                //TEMPERATURA
                                'temperatura' :me.datos.temperatura ,
                                //FC
                                'fc' :me.datos.fc ,
                                'taquicardia' :me.datos.taquicardia ,
                                'arritmia' :me.datos.arritmia ,
                                'bradicardia' :me.datos.bradicardia, 
                                'sin_alteracion' :me.datos.sin_alteracion, 
                                //FC
                                'fr' :me.datos.fr, 
                                'normal_fr' :me.datos.normal_fr, 
                                'disnea' :me.datos.disnea ,
                                //MUCOSAS
                                'rosada' :me.datos.rosada ,
                                'palidas' :me.datos.palidas,
                                'ictericas' :me.datos.ictericas,
                                'cianotica' :me.datos.cianotica ,
                                //MUCOSAS
                                'normal_apetito' :me.datos.normal_apetito,
                                'disminuido' :me.datos.disminuido ,
                                'anorexico' :me.datos.anorexico ,
                                //HIDRATACION
                                'normal_hidratacion' :me.datos.normal_hidratacion ,
                                'leve' :me.datos.leve ,
                                'moderada' :me.datos.moderada,
                                'marcada' :me.datos.marcada ,
                                //ESTADO GENERAL
                                'bueno_estado' :me.datos.bueno_estado,
                                'regular' :me.datos.regular,
                                'malo' :me.datos.malo,
                                //ANTECEDENTES ENFERMEDADES
                                'enfermedades' :me.datos.enfermedades, 
                                'enfermedades_cuales' :me.datos.enfermedades_cuales, 
                                'enfermedades_cuando' :me.datos.enfermedades_cuando,
                                //ANTECEDENTES CIRUGIA
                                'cirugia' :me.datos.cirugia,
                                'cirugia_cuales' :me.datos.cirugia_cuales, 
                                'cirugia_cuando' :me.datos.cirugia_cuando ,
                                //ORGANOS DE SENTIDO
                                'ocular' :me.datos.ocular ,
                                'nariz' :me.datos.nariz  ,
                                'bucal' :me.datos.bucal ,
                                'piel_anexo' :me.datos.piel_anexo,
                                'oidos' :me.datos.oidos,
                                'vulvar' :me.datos.vulvar,
                                'prepucial' :me.datos.prepucial, 
                                //APARATO DIGESTIVO,RESPIRATORIO,GENITO URINARIO,NERVIOSO
                                'digestivo_sin_alteracion' :me.datos.digestivo_sin_alteracion,
                                'digestivo_obs' :me.datos.digestivo_obs ,
                                'respiratorio_sin_alteracion' :me.datos.respiratorio_sin_alteracion, 
                                'respiratorio_obs' :me.datos.respiratorio_obs,
                                'urinario_sin_alteracion' :me.datos.urinario_sin_alteracion, 
                                'urinario_obs' :me.datos.urinario_obs ,
                                'nervioso_sin_alteracion' :me.datos.nervioso_sin_alteracion ,
                                'nervioso_obs' :me.datos.nervioso_obs,
                                //EXAMENES COMPLEMENTARIOS
                                'muestra' :me.datos.muestra ,
                                'examenes_solicitado' :me.datos.examenes_solicitado,
                                //EXAMENES COMPLEMENTARIOS
                                'fecha1' :me.datos.fecha1,
                                't1' :me.datos.t1 ,
                                'hora1' :me.datos.hora1, 
                                'doctor' :me.datos.doctor ,
                                'costo1' :me.datos.costo1,
                                'observaciones1' :me.datos.observaciones1, 
                                'primer_dia' :me.datos.primer_dia ,

                                'hidratacion' :me.datos.hidratacion,

                                'detalle': me.arrayDetalle,

                            }).then(function(response){
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Historial Clinico registrado exitosamente',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                me.volverVentaMenu();
                                me.cargarPdf2();
                                me.volverVentaListado();
                                me.limpiarDatosVenta();
                                console.log(me.datos);
                            })
                            .catch(function(error){
                                console.log(error);
                            });
                        }
                        
                    
                
                
            },         
            validarCompra(){
                this.errorPago = 0;
                this.errorMostrarMsjPago = [];

                if(!this.datos.nombre) this.errorMostrarMsjPago.push("El nombre del Pago no puede estar vacio ");
                if(this.errorMostrarMsjPago.length) this.errorPago=1;
                return this.errorPago;
            },
            frmVenta(){
                this.listado = 1;
                this.selectCliente();
                this.selectPaciente();
                this.selectPersonal();
                this.selectTipoP();
                this.selectFormaP();        
            },
            anularVenta(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Anular esta Compra??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/venta/anular1',{'id': id}).then(function (response) {
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este compra se ha Anulado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este categoria no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            limpiarDatosVenta(){
                let me = this;
                me.datos = {
                    id : 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_tipo_pago : 1,
                    id_forma_pago : 2,
                    id_cliente : 0,
                    id_personal : 0,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                    costo_pago : '',
                    id_descuento: '',
                    personal:'',
                    doctor:'',
                    paciente:'',
                    estado:'',
                    peso :'',
                    descripcion:'',
                     //vacunaciones
                    parvovirus :0,
                    hexavalente :0,
                    octavalente :0,
                    rabia_perro :0,
                    tos_perrera :0,
                    ninguna_perro:0,
                    obs_p  :0,
                    obs_perro  :'',
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
                    fecha1 : moment().format('YYYY-MM-DD'),
                    t1:0,
                    dr1:'',
                    hora1: moment().format('HH:mm:ss'),
                    costo1:0,
                    observaciones1:'',
                    primer_dia:'',
                    // NUEVO ATRIBUTO HIDRATACION
                    hidratacion : '',
                }
                me.arrayOrden = [];
                me.arrayDetalle = [];
                me.isDisabledOrden = false;
                me.isDisabledCliente=false;
                me.isDisabledProducto=false;
                me.isDisabledPaquete=false;
                me.buscarP = '';
                me.buscar = '';
                me.buscarCliente= '';
                me.isDisabled=false;
            },
            limpiarArticulo(){
                this.arrayArticulo = [];
                this.buscarP = '';
                this.buscar = '';
                this.arrayDetalle.forEach(item => item.saldoStock = 0);
            },
            limpiarCliente(){
                this.arrayTipoCliente = [];
                this.buscarP = '';
                this.buscar = '';
            },
            calcularSaldo() {
                this.datosPago.saldo = this.datos.total;
            },
            cerrarModalPago() {
                this.datosPago = this.anticipo;
            },
            seleccionarOrdenServicio(data=[]) {
                let me = this;
                me.datos.id=data['id'];
                me.datos.id_cliente=data['id_cliente'];
                me.datos.cliente=data['cliente'];
                me.datos.id_descuento=data['id_descuento'];
                me.datos.tipo_venta='Venta Servicio';
                me.datos.estado='Entregado';
                me.datos.descuento=data['descuento']

                var url='/servicio/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });

                me.isDisabledCliente=true;
                me.isDisabledProducto=true;
                me.isDisabledPaquete=true;
            },
            seleccionarPaquete2(data=[]) {
                let me = this;
                // me.datos.id=data['id'];
                // me.datos.id_cliente=data['id_cliente'];
                // me.datos.cliente=data['cliente'];
                // me.datos.id_descuento=data['id_descuento'];
                // me.datos.tipo_venta='Venta Servicio';
                me.datos.estado='Entregado';
                // me.datos.descuento=data['descuento']

                var url='/paquete/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });

                //me.isDisabledCliente=true;
                //me.isDisabledProducto=true;
                me.isDisabledOrden=true;
            },

            numericValidate(event) {
                const keyValidate = new RegExp('^[0-9]*$');
                const keysCaracter = ['Delete', 'Backspace', 'ArrowLeft', 'ArrowRight', 'KeyX', 'KeyC', 'KeyV', 'Home', 'End', 'Tab'];
                if (keysCaracter.includes(event.code)) {
                    switch (event.code) {
                    case 'KeyX':
                        if (!event.ctrlKey) {
                        event.preventDefault();
                        }
                        break;
                    case 'KeyC':
                        if (!event.ctrlKey) {
                        event.preventDefault();
                        }
                        break;
                    case 'KeyV':
                        if (!event.ctrlKey) {
                        event.preventDefault();
                        }
                        break;
                    default:
                        break;
                    }
                    return;
                }
                if (!keyValidate.test(event.key)) {
                    event.preventDefault();
                }
            },
            cargarPdf2() {
                axios.get('/historia/pdfHistoria',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }

        },
        mounted() {
            this.selectCliente();
            this.selectPersonal();
            this.selectPaciente();
            this.selectTipoP();
            this.selectFormaP();  
            this.verificarCaja();
        }
    }
</script>
<style scoped lang="scss">
    .dropdown-wrapper{
        position: relative;
        //margin: 0 auto;

        .selected-item{
            height: 25px;
            //border: 2px solid lightgray;
            border-radius: 5px;
            padding: 5px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            //font-size: 16px;
            //font-weight: 500;

            .drop-down-icon{
                transform: rotate(0deg);
                transition: all 0.5s ease;
                &.dropdown{
                    transform: rotate(180deg);
                }
            }
        }

        .dropdown-popover{
            position: absolute;
            border: 2px solid lightgray;
            top: 46;
            left: 0;
            right: 0;
            background-color: #fff;
            max-width: 100%;
            align-items: center;
            padding: 10px;
            visibility: hidden;
            transition: all 0.35s linear;
            max-height: 0px;
            overflow: hidden;


            &.visible{
                max-height: 450px;
                visibility: visible;
            }
            input{
                width: 100%;
                height: 30px;
                border: 2px solid lightgray;
                font-size: 18px;
                padding-left: 8px;
            }

            .options{
                width: 100%;
                padding-top: 12px;

                ul{
                    list-style: none;
                    text-align:left;
                    padding-left: 2px;
                    max-height: 200px;
                    overflow-y: scroll;
                    overflow-x: hidden;
                }

                li{
                    width: 100%;
                    border-bottom: 1px solid lightgray;
                    padding: 5px;
                    border: 1px solid lightgray;
                    background-color: #f1f1f1;
                    cursor: pointer;
                    &:hover {
                        background: #44536E;
                        color: #fff;
                        font-weight: bold;
                    }
                }
            }
        }
    }



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
    .tamaño{
        width: 100px !important; 
    }
    .bg-disabled{
        background-color: #D8DBE1;
    }
    .bg-rojo{
     background-color: 	#FFD2D6 !important;
     /* color: white !important; */
    }
    .bg-azul{
     background-color: 	#E0FEFE !important;
     /* color: white !important; */
    }

</style>