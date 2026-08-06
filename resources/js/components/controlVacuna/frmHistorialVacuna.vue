<template>
    <main class="main">
        <div class="row">
            <div class="col">
            &nbsp;
            <div class="card">
                <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">HISTORIAL CONTROL VACUNAS</h3></div>
                <template v-if="listado==0">
                    <div class="card-header row m-0">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger text-white" @click="volverVentaMenu()">
                                    <i class="fa fa-reply-all"></i>&nbsp;Volver
                                </button>
                            </div>
                            <div class="col-md-4">&nbsp;</div>
                            <!-- <div class="col-md-2">
                                <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt=""> 
                            </div>  -->
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8">
                            &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                <select class="form-select col-md-3" v-model="criterio">
                                    <option value="paciente.nombre">Mascota</option>
                                    <option value="cliente.nombre">Cliente</option>
                                </select>
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscar" @keyup.enter="listarControlVacuna(1, buscar, criterio)" @keyup="BuscandoVenta()" class="form-control" placeholder="Texto a buscar">
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" @click="listarControlVacuna(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Light table -->
                    <div class="table-responsive">
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                        <thead style="background-color: #46546C">
                            <tr>                      
                                <th scope="col" class="text-white">Propietario</th>
                                <th scope="col" class="text-white">Mascota</th>
                                <th scope="col" class="text-white">Edad</th>
                                <th scope="col" class="text-white">Prox. Fecha</th>
                                <th scope="col" class="text-white text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="control_vacuna in arrayControlVacuna" :key="control_vacuna.id">
                                <td v-text="control_vacuna.cliente"></td>
                                <td v-text="control_vacuna.paciente"></td>
                                <td v-text="control_vacuna.edad"></td>
                                <td v-text="control_vacuna.prox_fecha"></td>
                                <!-- <td class="text-center">
                                    <template v-if="control_vacuna.estado=='Registrado'">
                                        <span class="badge bg-info tamaño">{{control_vacuna.estado}}</span>                                           
                                    </template>
                                    <template v-if="control_vacuna.estado=='Entregado'">
                                        <span class="badge bg-success tamaño">{{control_vacuna.estado}}</span>
                                    </template>
                                    <template v-if="control_vacuna.estado=='Anulado'">
                                        <span class="badge bg-danger tamaño">{{control_vacuna.estado}}</span>                                           
                                    </template>
                                </td>  -->
                                <td class="text-center">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" @click="verControl(control_vacuna)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                        <template v-if="control_vacuna.estado!='Anulado'">
                                            <template v-if="control_vacuna.estado!='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="editarDetalle(control_vacuna)"><i class="fa fa-edit text-warning"></i> Control Vacuna</a></li>
                                                <li><a class="dropdown-item" href="#" @click="cargarPdf(control_vacuna.id)"><i class="fa fa-file-pdf-o text-danger"></i> Imprimir</a></li>

                                            </template>
                                        </template>
                                        <!-- <template v-if="control_vacuna.estado!='Anulado'">
                                            <template v-if="control_vacuna.estado!='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="anularCotizacion(control_vacuna.id)"><i class="fa fa-lock text-danger"></i> Anular</a></li>
                                            </template>
                                        </template>
                                        <li><a class="dropdown-item" href="#" @click="cargarPdfSimple(control_vacuna.id, datos.foto, datos.empresa_nombre)"><i class="fa fa-file-pdf-o text-danger   "></i> Reporte</a></li> -->
                                        <!-- <li><a class="dropdown-item" href="#" @click="cargarPdf(venta.id)"><i class="fa fa-file-pdf-o text-info"></i> Reporte Detallado</a></li> -->
                                    </ul>
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

                
                <!-- Modificar -->
                <template v-if="listado==3">    
                    <div class="card-body">
                        <div class="form-group row" style='margin-left: 1%'>   
                        <form class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="form-label">Paciente</label>
                                <div class="input-group mb-6">
                                    <!-- <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2"> -->

                                    <section class="dropdown-wrapper form-control bg-disabled">
                                        <div @click="isVisible = !isVisible" class="selected-item">
                                            <span v-if="datos.paciente==''">Seleccione Paciente</span>
                                            <span v-else>{{datos.paciente }}</span>
                                            <svg :class="isVisible  ? 'dropdown' : ''" class="drop-down-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
                                        </div>
                                        <div :class="isVisible   ? 'visible' : 'invisible'" class="dropdown-popover">
                                            <input type="text" class="form-control" placeholder="Buscar Paciente.."  v-model="buscarPaciente" aria-label="Buscar Paciente..">
                                            <div class="text-center"><span v-if="filteredPaciente.length === 0">No existen Paciente</span></div>
                                            <div class="options">
                                                <ul>
                                                    <li @click="selectedPaciente(paciente)" v-for="(paciente, index) in filteredPaciente" :key="`cliente-${index}`">{{paciente.nombre}}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </section>

                                    &nbsp;&nbsp;&nbsp;
                                    <!-- <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalCliente" @click="listarCliente(buscarP)" :disabled="isDisabledCliente"><i class="fa fa-search"></i> Agregar Clientes</button> -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputPassword1" class="form-label">Animal</label>
                                <input type="text" class="form-control"  v-model="datos.animal" disabled>  
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                <input type="date" class="form-control"  v-model="datos.fecha">  
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputPassword1" class="form-label">Prox. Fecha</label>
                                <input type="date" class="form-control"  v-model="datos.prox_fecha">  
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputPassword1" class="form-label">Edad</label>
                                <input type="text" class="form-control"  v-model="datos.edad">  
                            </div>
                            <div class="col-md-6">
                            </div>&nbsp;
                            <template v-if="datos.id_paciente==0">
                            <div class="col-md-12">
                                <label>Vacunas<span style="color:red;" >(*Seleccione)</span></label>
                                <button type="button" disabled class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalArticulo" @click="listarArticulo(buscarP)"><i class="fa fa-search"></i> Agregar Vacunas</button>
                            </div> 
                            </template>
                            <template v-else>
                            <div class="col-md-12">
                                <label>Vacunas<span style="color:red;" >(*Seleccione)</span></label>
                                <button type="button"  class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalArticulo" @click="listarArticulo(buscarP)"  :disabled="isDisabledProducto"><i class="fa fa-search"></i> Agregar Vacunas</button>
                            </div> 
                            </template>
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
                        <div class="form-group row">
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                                <thead style="background-color: #46546C">
                                    <tr>                      
                                        <th scope="col" class="text-white">Opción</th>
                                        <th scope="col" class="text-white">Nombre</th>
                                        <th scope="col" class="text-white">Categoria</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                        <td>
                                            <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                        </td>
                                        <td v-text="detalle.articulo"></td>
                                        <td v-text="detalle.categoria"></td>
                                    </tr>

                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="7">No hay Productos agregados</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                            <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaListado()">Cancelar</button>
                            <button class="btn btn-info btn-lg text-white" type="button" @click="modificarControl()">Guardar</button>
                        </div>
                    </div>
                </template>
                <!-- Fin de modificar -->

                <!-- Vender -->
                <template v-if="listado==4 && estadoCaja == 'Abierta'">
                            <div class="card-body">
                                <div class="form-group row" style='margin-left: 1%'>   
                                <form class="row">
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1" class="form-label">Cliente</label>
                                        <div class="input-group mb-6">
                                            <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2">
                                    </div>
                                </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                        <input type="date" class="form-control"  v-model="datos.fecha" disabled>  
                                    </div>
                                    <div class="col-md-6" >
                                        <label for="exampleInputPassword1" class="form-label">Días Crédito</label>
                                        <input type="number" class="form-control" v-model="datos.dias_credito" @keyup="cambiarDias()" >
                                    </div>
                                    <div class="col-md-6" >
                                        <label for="exampleInputPassword1" class="form-label">Fecha Vencimiento</label>
                                        <input type="date" class="form-control"  v-model="datos.fecha_venci" disabled>  
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Tiempo de Entrega</label>
                                        <input type="text" class="form-control" v-model="datos.tiempo_entrega" disabled >
                                    </div>
                                    <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Tipo Pago</label>
                                        <select class="form-select" v-model="datos.id_tipo_pago">
                                            <option value="0" disabled>Seleccione el Tipo Pago</option>
                                            <option v-for="tipo_pago in arrayPago" :key="tipo_pago.id" :value="tipo_pago.id" v-text="tipo_pago.nombre"></option>
                                        </select>  
                                    </div>
                                    <div class="col-md-6" v-if="datos.id_tipo_pago == 2">
                                        <label for="exampleInputPassword1" class="form-label">Fecha Final</label>
                                        <input type="date" class="form-control"  v-model="datosPago.fecha_final">  
                                    </div>
                                    <div class="col-md-6" v-if="datos.id_tipo_pago == 1">
                                        <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                        <template>
                                            <select class="form-select" v-model="datos.id_forma_pago">
                                                <option value="0" disabled>Seleccione la Forma de Pago</option>
                                                <option v-for="forma_pago in arrayForma2" :key="forma_pago.id" :value="forma_pago.id" v-text="forma_pago.nombre"></option>
                                            </select>  
                                        </template> 
                                    </div>
                                     <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Lugar de Entrega</label>
                                    <input type="text" class="form-control" v-model="datos.lugar_entrega"  >
                                </div>
                                <!-- <div class="col-md-6">
                                    <label for="inputPassword" class="form-label">¿Con Factura?</label>
                                    <div class="col-sm-10">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" v-model="datos.con_factura">
                                            <label class="form-check-label" for="inlineRadio1">Si</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0" v-model="datos.con_factura">
                                            <label class="form-check-label" for="inlineRadio2">No</label>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-md-12">
                                    <label for="exampleInputPassword1" class="form-label">Nota</label>
                                    <textarea class="form-control" v-model="datos.nota" rows="2" ></textarea>
                                    <!-- <input type="text" class="form-control" v-model="datos.descripcion"  > -->
                                </div>
                                    <!-- <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Pagos</label>
                                        <template v-if="datos.id_descuento == 1">
                                            <input value="Unitario" type="text"  id="Unitario" class="form-control" disabled>
                                        </template> 
                                        <template v-if="datos.id_descuento == 2">
                                            <input value="Mayorista" type="text"  id="Mayorista" class="form-control" disabled>
                                        </template>
                                        <template v-if="datos.id_descuento == 3">
                                            <input value="Preferencial" type="text"  id="Preferencial" class="form-control" disabled>
                                        </template>
                                        <template v-if="datos.id_descuento == 0">
                                            <input value="" type="text"  class="form-control" disabled>
                                        </template> 
                                    </div> -->
                                    <div class="col-md-6">
                                    </div>&nbsp;
                                    <div class="col-md-12">
                                        <label>Productos<span style="color:red;" >(*Seleccione)</span></label>
                                        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalArticulo" @click="listarArticulo(buscarP)"  :disabled="isDisabledProducto"><i class="fa fa-search"></i> Agregar Productos</button>
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
                                <div class="form-group row">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                                        <thead style="background-color: #46546C">
                                            <tr>                      
                                                <th scope="col" class="text-white">Opción</th>
                                                <th scope="col" class="text-white">Tiempo Entrega</th>
                                                <th scope="col" class="text-white">Categoria</th>
                                                <th scope="col" class="text-white">Nombre</th>
                                                <th scope="col" class="text-white">Marca</th>
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
                                                <td><input v-model="detalle.tiempo_entrega" type="text" value="3" class="form-control"></td>
                                                <td v-text="detalle.categoria"></td>
                                                <td v-text="detalle.articulo"></td>
                                                <td v-text="detalle.marca"></td>
                                                <td v-text="detalle.tienda"></td>
                                                <td>
                                                    <template v-if="detalle.producto != true">
                                                        <input v-model="detalle.costo_venta" type="number" value="3" class="form-control">
                                                    </template>
                                                    <template v-else>
                                                        <template v-if="datos.id_descuento == 1">
                                                            <vue-numeric v-model="detalle.costo_venta" :precision="2" id="Unitario" class="form-control"></vue-numeric>
                                                        </template> 
                                                        <template v-if="datos.id_descuento == 2">
                                                            <vue-numeric v-model="detalle.costo_venta" :precision="2" id="Mayorista" class="form-control"></vue-numeric>
                                                        </template>
                                                        <template v-if="datos.id_descuento == 3">
                                                            <vue-numeric v-model="detalle.costo_venta" :precision="2" id="Preferencial" class="form-control"></vue-numeric>
                                                        </template>
                                                        <template v-if="datos.id_descuento != 1 && datos.id_descuento != 2 && datos.id_descuento != 3">
                                                            <vue-numeric :value='0' :precision="2"  id="Preferencial" class="form-control"></vue-numeric>
                                                        </template> 
                                                    </template>
                                                </td>
                                                <td>
                                                    <div v-if="datos.tipo_venta=='Venta Cotizacion'">
                                                        <vue-numeric v-model="detalle.cantidad" type="number" value="3" class="form-control"></vue-numeric>
                                                    </div>
                                                    <div v-if="datos.tipo_venta=='Venta Servicio'">
                                                        {{detalle.cantidad}}
                                                    </div>
                                                    <span style="color:red;" v-show="detalle.cantidad>detalle.stock && datos.tipo_venta!='Venta Servicio'">Stock: {{detalle.stock}}</span>
                                                </td>
                                                <td>
                                                    
                                                       {{ detalle.sub_total = detalle.costo_venta*detalle.cantidad}}
                                                    
                                                </td>                                                                                  
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="8" align="right"> <strong>Sub Total:</strong> </td>
                                                <td>{{datos.sub_total = calcularSubTotal.toFixed(2)}} bs</td> 
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="8" align="right"> <strong>Descuento:</strong> </td>
                                                <td>
                                                    <div v-if="datos.tipo_venta=='Venta Cotizacion'">
                                                        <vue-numeric v-model="datos.descuento" :precision="2" value="0" class="form-control" :max="parseFloat(datos.sub_total)"></vue-numeric>
                                                    </div>
                                                    <div v-if="datos.tipo_venta=='Venta Servicio'">
                                                        {{datos.descuento}}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="8" align="right"> <strong>Total:</strong> </td>
                                                <td>{{datos.total = datos.sub_total- datos.descuento}} bs</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="9">No hay Productos agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                                    <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaListado()">Cancelar</button>
                                    <button class="btn btn-info btn-lg text-white" type="button" @click="guardarVenta()">Guardar</button>
                                </div>
                            </div>
                </template>
                <template v-if="listado==4 && estadoCaja == 'Cerrada'">
                    <div class="alert alert-warning alert-dismissable text-center">
                        <strong>¡Cuidado!</strong> Se requiere Aperturar Caja Primero.
                    </div>
                </template>
                <!-- Fin de modificar -->

                <!-- Ver -->
                <template v-if="listado==2">
                    <div class="card-header row m-0">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-danger text-white" @click="volverServicioListado()">
                                <i class="fa fa-reply-all"></i>&nbsp;Volver
                            </button>
                            <!-- <button type="button" @click="cargarPdfSimple(datos.id, datos.foto)" class="btn btn-info">
                                <i class="icon-doc text-white"></i><span class="text-white">&nbsp;Reporte</span>
                            </button>  -->
                        </div>
                        <div class="col-md-4 text-center"><h3 class="mb-0">REGISTRO DE CONTROL DE VACUNAS</h3></div>
                        <div class="col-md-4">&nbsp;</div>
                        <!-- <div class="col-md-2">
                            <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt=""> 
                        </div>  -->
                    </div> 
                    <div class="card-body">
                        <div class="form-group row" style='margin-left: 1%'>   
                            <form class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1" class="form-label">Cliente</label>
                                    <div class="input-group mb-6">
                                        <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2">
                                    </div>
                                </div>
                                 <div class="col-md-6" >
                                        <label for="exampleInputPassword1" class="form-label">Paciente</label>
                                        <input type="text" class="form-control"  v-model="datos.paciente" disabled>  
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                        <input type="date" class="form-control"  v-model="datos.fecha2" disabled>  
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Prox. Fecha</label>
                                        <input type="date" class="form-control bg-rojo"  v-model="datos.prox_fecha2" disabled>  
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Edad</label>
                                        <input type="text" class="form-control" v-model="datos.edad" disabled >
                                    </div>
                                &nbsp;
                                

                            </form>    
                        </div>

                        <br>
                        <div class="form-group row">
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                                <thead style="background-color: #46546C">
                                    <tr>                      
                                        <th scope="col" class="text-white">Vacuna</th>
                                        <th scope="col" class="text-white">Categoria</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                        <td v-text="detalle.articulo"></td>                                                                           
                                        <td v-text="detalle.categoria"></td>
                                    </tr>

                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="7">No hay Permisos agregados</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <!-- <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                            <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaListado()">Cancelar</button>
                            <button class="btn btn-info btn-lg text-white" type="button" @click="guardarServicio()">Guardar</button>
                        </div> -->
                    </div>
                </template>
                <!-- Fin de ver -->
                
            </div>
            </div>
        </div>

        <!--Modal Formulario Producto-->
        <div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDA DE PRODUCTOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- nuevo -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-select col-md-3" v-model="criterioP">
                                        <option value="articulo.nombre_comercial">Producto</option>   
                                        <option value="categoria.nombre">Categoria</option>
                                        <!-- <option value="articulo.marca">Marca</option>                                     -->
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(buscarP, criterioP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(buscarP, criterioP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                       <!-- fin -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Categoria</th>                      
                                    <th scope="col" class="text-white">stock</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayArticulo.length">
                                <tr v-for="tienda_articulo in arrayArticulo" :key="tienda_articulo.id">
                                    <td v-text="tienda_articulo.articulo" ></td>
                                    <td v-text="tienda_articulo.categoria"></td>
                                    <td v-text="tienda_articulo.stock"></td>
                                    <td>
                                        <button type="button" @click="seleccionarTiendaArticulo(tienda_articulo)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                    </td>                                 
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="9">No hay Productos agregados</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="limpiarArticulo()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin moda Formulario Producto-->

        <!--Modal Formulario Cliente-->
        <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white"  style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDAS DE CLIENTES</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarCliente(buscarP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarCliente(buscarP)" class="btn btn-primary text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">CI/NIT</th>
                                    <th scope="col" class="text-white">Telefono</th>
                                    <th scope="col" class="text-white">Direccion</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cliente in arrayTipoCliente" :key="cliente.id">
                                    <td v-text="cliente.nombre"></td>
                                    <td v-text="cliente.matricula"></td>
                                    <td v-text="cliente.telefono"></td>
                                    <td v-text="cliente.direccion"></td>
                                    <td>
                                        <button type="button"  data-dismiss="modal" @click="seleccionarCliente(cliente)" class="btn btn-success btn-sm" data-bs-dismiss="modal"><i class="fa fa-check text-white"></i></button> 

                                    </td>                                 
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin modal Formulario Cliente-->

        <!--Modal Formulario Personal-->
        <div class="modal fade" id="modalPersonal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white"  style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDAS DE TECNICOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarPersonal(buscarP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarPersonal(buscarP)" class="btn btn-primary text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Telefono</th>
                                    <th scope="col" class="text-white">Direccion</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="personal in arrayPersonal" :key="personal.id">
                                    <td v-text="personal.nombre"></td>
                                    <td v-text="personal.telefono"></td>
                                    <td v-text="personal.direccion"></td>
                                    <td>
                                        <button type="button"  data-dismiss="modal" @click="seleccionarPersonal(personal)" class="btn btn-success btn-sm" data-bs-dismiss="modal"><i class="fa fa-check text-white"></i></button> 

                                    </td>                                 
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin modal Formulario Personal-->
    </main>
</template>

<script>
    import Swal, { dangerConfirm } from '../../utils/appSwal';
    import moment from 'moment';
    export default {
        created() {
            this.datos.costo_pago = 1;
             this.datos.estado = '0';
        },
        data(){
            return {
                datos : {
                    id : 0,
                    con_factura : '1',
                    fecha :  moment().format('YYYY-MM-DD'),
                    prox_fecha : moment().format('YYYY-MM-DD'),
                    fecha2 :  moment().format('YYYY-MM-DD'),
                    prox_fecha2 : moment().format('YYYY-MM-DD'),
                    cliente : '',
                    paciente : '',
                    edad : 0,
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_paciente : 0,
                    id_cliente : 0,
                    id_animal : 0,
                    animal : '',

                    id_tipo_pago : 1,
                    id_forma_pago : 2,
                    tipoPago : '',
                    formaPago : '',
                    costo_pago : '',
                    id_descuento: '',
                    nota:'',
                    personal:'',
                    estado:0,
                    tipo_venta: 'Venta Cotizacion',
                    id_orden_servicio: null,
                    dias_credito: 0,
                    tiempo_entrega: 'Especificado en cada Producto',
                    lugar_entrega : '',
                    tipo:'',

                    foto: '',
                    empresa_nombre: '',

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

                arrayEmpresa : [],            
                arrayControlVacuna : [],
                arrayDetalle : [],
                arrayArticulo : [],
                arrayArticuloServicio : [],
                arrayCliente: [],
                arrayCostoPago: [{id:1,nombre:'Unitario'},{id:2,nombre:'Mayorista'},{id:3,nombre:'Preferencial'}],
                arrayTipoCliente: [],
                arrayClienteId: [],
                arrayPersonal: [],
                arrayPago: [],
                arrayForma: [],
                arrayForma2:[],
                listado : 0,
                tipoAccion : 0,
                errorCompra : 0,
                errorPago : 0,
                errorMostrarMsjPago : [],
                errorMostrarMsjCompra : [],
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
                criterioP : 'articulo.nombre_comercial',
                buscar : '',
                buscarP : '',
                isDisabledProducto: false,
                disabledRecepcionado: false,
                disabledConcluido: false,
                disabledEntregado: false,
                disabledAnulado: false,
                setTimeoutBuscador : '',
                isVisible: false,
                buscarPaciente: '',
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
            calcularTotal: function(){
                var resultado = 0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.datos.id_descuento ==1){
                    resultado = resultado + (this.arrayDetalle[i].costo_unitario*this.arrayDetalle[i].cantidad);
                    }else if(this.datos.id_descuento ==2)
                    { resultado = resultado + (this.arrayDetalle[i].costo_mayorista*this.arrayDetalle[i].cantidad);
                    }else if(this.datos.id_descuento ==3){
                     resultado = resultado + (this.arrayDetalle[i].costo_preferencial*this.arrayDetalle[i].cantidad);
                    }else
                    {}
                    
                }
                return resultado;
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
            calcularSubTotalEstado: function(){
                var resultado = 0.0;
                if(this.datos.tipo_venta=='Venta Directa') {
                    for(var i=0;i<this.arrayDetalle.length;i++){
                        if(this.arrayDetalle[i].estado ==0){
                        resultado = resultado + (this.arrayDetalle[i].costo_unitario*this.arrayDetalle[i].cantidad);
                        }else
                        {
                            //
                        }
                    }
                } else {
                    for(var i=0;i<this.arrayDetalle.length;i++){
                        if(this.arrayDetalle[i].estado ==0){
                        resultado = resultado + (this.arrayDetalle[i].costo_venta*this.arrayDetalle[i].cantidad);
                        }else
                        {
                            //
                        }
                    }
                }
                
                return resultado;
            },
            filteredPaciente(){
                const data = this.buscarPaciente.toLowerCase();
                if(this.buscarPaciente == ""){
                    return this.arrayCliente;
                }
                return this.arrayCliente.filter((item)=>{
                    return Object.values(item).some((word=>String(word).toLowerCase().includes(data)))
                })
            }
        },
        methods : {
            volverVentaMenu(){
                this.listado = 0;
                this.$emit('cerrarVentaTienda', this.listadoVenta);
            },
            cambiarDias(){
                let me = this;
                me.datos.fecha_venci = moment(me.datos.fecha).add(me.datos.dias_credito,'day').format('YYYY-MM-DD');
            },
            listarControlVacuna(page, buscar, criterio){
                let me=this;
                var url='/contro/vacuna?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayControlVacuna=response.data.data;
                    me.pagination={total:response.data.total, 
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            BuscandoVenta(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarVentaServicioVentaDirecta,350)
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarControlVacuna(page, buscar, criterio);
            },
            listarArticulo(buscarP, criterioP){
                let me = this;
                me.buscarPaciente= '';
                me.isVisible = false;
                var url='/tienda/listarSinPaginateControlVacuna?id_animal='+me.datos.id_animal+'&buscar=' + buscarP + '&criterio=' + criterioP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            selectedPaciente(paciente){
                this.datos.paciente='';
                this.datos.id_paciente=0;
                this.datos.id_animal='';
                this.isVisible = false;
                this.datos.paciente = paciente.nombre;
                this.datos.id_paciente = paciente.id;
                this.datos.id_animal = paciente.id_animal;
                this.datos.animal = paciente.animal;
                this.datos.edad = paciente.edad;
                this.datos.id_cliente = paciente.id_cliente;
            },

            listarArticulo2(buscarP){
                let me = this;

                var url='/tienda/servicio?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticuloServicio= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarCliente(buscarP){
                let me = this;
                var url='/cliente/listarSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayTipoCliente= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarPersonal(buscarP){
                let me = this;
                var url='/personal/listarSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayPersonal= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_tienda_articulo==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            // seleccionarTiendaArticulo(data=[]){
            //     let me = this;
            //     let estado = 0;
            //     if(me.encuentra(data['id_articulo'])){
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Error...',
            //             text: 'Este producto ya se encuentra agregado!'
            //         })
            //     }
            //     else{
            //         me.arrayDetalle.push({
            //             id_tienda_articulo : data['id'],
            //             id_articulo : data['id_articulo'],
            //             articulo : data['articulo'],
            //             tienda : data['tienda'],
            //             costo_unitario : data['costo_unitario'],
            //             costo_venta : data['costo_unitario'],
            //             costo_mayorista : data['costo_mayorista'],
            //             costo_preferencial : data['costo_preferencial'],
            //             costo_compra : data['costo_compra'],
            //             marca : data['marca'],
            //             id_categoria : data['id_categoria'],
            //             categoria : data['categoria'],
            //             stock : data['stock'],
            //             cantidad : 1,
            //             sub_total : data['costo_unitario']*1,
            //             producto_venta: 'Venta Producto',
            //             estado : 0
            //         });
            //         me.datos.estado= 'Entregado';
            //         me.datos.tipo_venta= 'Venta Directa'
            //         me.isDisabled = false;
            //         me.isDisabledOrden = true;
            //         me.isDisabledPaquete = true;
            //         Swal.fire({
            //             position: 'top-end',
            //             icon: 'success',
            //             title: 'Producto agregado...',
            //             showConfirmButton: false,
            //             timer: 500
            //         });
            //     }
            //     // me.calcularSubTotal();
            //     me.datos.sub_total = me.calcularSubTotalEstado.toFixed(2);
            //     me.datos.total = me.datos.sub_total- me.datos.descuento

            // },
            seleccionarTiendaArticulo(data=[]){
                let me = this;
                let estado = 0;
                // if(me.encuentra(data['id_articulo'])){
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Error...',
                //         text: 'Este producto ya se encuentra agregado!'
                //     })
                // }
                // else{
                    me.arrayDetalle.push({
                        id_tienda_articulo : data['id'],
                        id_articulo : data['id_articulo'],
                        articulo : data['articulo'],
                        tienda : data['tienda'],
                        costo_unitario : data['costo_unitario'],
                        costo_venta : data['costo_unitario'],
                        costo_mayorista : data['costo_mayorista'],
                        costo_preferencial : data['costo_preferencial'],
                        costo_compra : data['costo_compra'],
                        marca : data['marca'],
                        id_categoria : data['id_categoria'],
                        categoria : data['categoria'],
                        stock : data['stock'],
                        cantidad : 1,
                        sub_total : data['costo_unitario']*1,
                        producto_venta: 'Venta Producto',
                        estado : 0
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
                //}
                // me.calcularSubTotal();
                me.datos.sub_total = me.calcularSubTotalEstado.toFixed(2);
                me.datos.total = me.datos.sub_total- me.datos.descuento

                var url='/control/vacuna/estado?id='+ data['id'];
                axios.put(url).then(function(response){
                    //me.arrayDetalle = response.data;
                })
                .catch(function(error){
                    console.log(error)
                });

            },
            seleccionarTiendaArticulo2(data=[]){
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
                        stock : data['stock'],
                        cantidad : 1,
                        sub_total : data['sub_total']
                    });
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
                this.datos.id_cliente=data['id'];
                this.datos.cliente= data['nombre'];
                this.datos.id_descuento= data['descuento'];
                this.arrayCliente=[];
            },
            seleccionarPersonal(data=[]){
                this.datos.id_personal=data['id'];
                this.datos.personal= data['nombre'];
            },
            cancelarCompra(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
            },
            volverVentaListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
                me.listado = 0;
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
                var url='/paciente/selectPaciente';
                axios.get(url).then(function(response){
                    me.arrayCliente=response.data;
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
            listarClienteId(buscarP){
                let me = this;
                var url='/cliente/selectClienteId?id_cliente=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayClienteId= response.data;
                })
                .catch(function(error){
                    console.log(error);
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
            verControl(data=[]){
                let me = this;
                me.listado = 2;
                me.datos.id=data['id'];
                me.datos.id_cliente=data['id_cliente'];
                me.datos.id_paciente=data['id_paciente'];
                me.datos.id_animal=data['id_animal'];
                me.datos.cliente=data['cliente'];
                me.datos.paciente=data['paciente'];
                me.datos.fecha2=data['fecha'];
                me.datos.prox_fecha2=data['prox_fecha'];
                me.datos.edad=data['edad'];
            
        
                var url='/contro/vacuna/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });

                var url='/tienda?page=' + 1 + '&buscar=' + this.buscar + '&criterio=' + this.criterio;
                axios.get(url).then(function(response){
                    me.arrayEmpresa=response.data.data;
                    me.empresa = me.arrayEmpresa.find(seg => (seg.id == me.arrayDetalle[0].id_tienda));
                    me.datos.foto = me.empresa.foto;
                    me.datos.empresa_nombre = me.empresa.nombre;
                    me.datos.empresa_direccion = me.empresa.direccion;
                    me.pagination={total:response.data.total, 
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            // actEstado(){
            //     let me = this;
            //     //me.listado = 4;
            //     var url='/control/vacuna/estado?id='+me.datos.id;
            //     axios.put(url).then(function(response){
            //         //me.arrayDetalle = response.data;
            //     })
            //     .catch(function(error){
            //         console.log(error)
            //     });
            // },
            editarDetalle(data=[]){
                let me = this;
                me.listado = 3;
                me.datos.id=data['id'];
                me.datos.id_cliente=data['id_cliente'];
                me.datos.id_paciente=data['id_paciente'];
                me.datos.id_animal=data['id_animal'];
                me.datos.cliente=data['cliente'];
                me.datos.paciente=data['paciente'];
                // me.datos.prox_fecha=data['prox_fecha'];
                me.datos.edad=data['edad'];
                me.datos.animal=data['animal'];
                me.datos.sub_total=0;
                me.datos.total=0;

                var url='/control/vacuna/estado?id='+ data['id'];
                axios.put(url).then(function(response){
                    //me.arrayDetalle = response.data;
                })
                .catch(function(error){
                    console.log(error)
                });

                var url='/contro/vacuna/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });

                // me.actEstado();

            },
            vender(data=[]){
                let me = this;
                me.cambiarDias();




                me.listado = 4;
                me.datos.id=data['id'];
                me.datos.id_cliente=data['id_cliente'];
                me.datos.cliente=data['cliente'];
                me.datos.fecha_venci=data['fecha_venci'];
                me.datos.estado=data['estado'];
                me.datos.fecha=data['fecha'];
                me.datos.descuento=data['descuento'];
                me.datos.dias_credito=data['dias_credito'];
                me.datos.tipoPago=data['pago'];
                me.datos.formaPago=data['formaP'];
                me.datos.id_descuento=data['id_descuento'];
                me.datos.id_tipo_pago=data['id_tipo_pago'];
                me.datos.id_forma_pago=data['id_forma_pago'];
                me.datos.tiempo_entrega=data['tiempo_entrega'];
                me.datos.lugar_entrega=data['lugar_entrega'];
                me.datos.sub_total=data['sub_total'];
                me.datos.total=data['total'];
                me.datos.nota=data['nota'];
                me.disabledEntregado = data['estado'] == 'Registrado' ? true : false;
                me.disabledAnulado = data['estado'] == 'Anulado' ? true : false;
                var url='/cotizacion/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            modificarControl(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe Productos agregados!'
                    })
                }
                if(this.datos.paciente == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar un Paciente!'
                    })
                } 
                if(this.arrayDetalle.find(seg => (seg.stock - seg.cantidad < 0 && this.datos.tipo_venta!='Venta Servicio'))){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No hay stock para el producto!'
                    })
                } else {
                        if(this.datos.total < 0){
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'El total no puedes ser Negativo!'
                            })
                        } else {
                            let me = this;
                            axios.put('/control/vacuna/modificar',{
                                //'id_servicio': me.datos.id,
                                'id': me.datos.id,
                                'fecha': me.datos.fecha,
                                'prox_fecha': me.datos.prox_fecha,
                                'fecha_final': me.datosPago.fecha_final,
                                'edad': me.datos.edad,
                                'sub_total': me.datos.sub_total,
                                'descuento': me.datos.descuento,
                                'total': me.datos.total,
                                'estado': me.datos.estado,
                                'id_paciente': me.datos.id_paciente,

                                'id_cliente': me.datos.id_cliente,
                                'id_tipo_pago': me.datos.id_tipo_pago,
                                'id_forma_pago': (me.datos.id_tipo_pago == 2) ? me.arrayForma[0].id : me.datos.id_forma_pago,
                                'id_costo_pago': me.datos.id_descuento,
                                'detalle': me.arrayDetalle,
                                'monto_total': me.datos.total,
                                'descripcion_pago': me.datosPago.descripcion,
                                'saldo': me.datosPago.saldo,
                                
                                'stock_producto_paquete': me.arrayProductoPaquete,
                            }).then(function(response){
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Registro de Control Vacuna exitosamente',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                //me.cargarPdf2();
                                me.volverVentaListado();
                                me.limpiarDatosVenta();
                                me.listarControlVacuna(1, '', 'nombre');
                                console.log(me.datos);
                            })
                            .catch(function(error){
                                console.log(error);
                            });
                        }
                        
                    }
                
                
            },     
            modificarCotizacion(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe Productos agregados!'
                    })
                }
                if(this.datos.cliente == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar un Cliente!'
                    })
                }
                // if(this.arrayDetalle.find(seg => (seg.stock - seg.cantidad < 0 && this.datos.tipo_venta!='Venta Servicio'))){
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Error...',
                //         text: 'No hay stock para el producto!'
                //     })
                // } else {
                    if(this.datos.total < 0){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'El total no puedes ser Negativo!'
                        })
                    } else {
                        let me = this;
                        axios.put('/cotizacion/modificar1',{
                            'id': me.datos.id,
                            'fecha': me.datos.fecha,
                            'fecha_venci': me.datos.fecha_venci,
                            'sub_total': me.datos.sub_total,
                            'descuento': me.datos.descuento,
                            'tiempo_entrega': me.datos.tiempo_entrega,
                            'lugar_entrega': me.datos.lugar_entrega,
                            'dias_credito': me.datos.dias_credito,
                            'total': me.datos.total,
                            'estado': me.datos.estado,
                            'id_cliente': me.datos.id_cliente,
                            'nota': me.datos.nota,
                            'id_tipo_pago': me.datos.id_tipo_pago,
                            'id_forma_pago': (me.datos.id_tipo_pago == 2) ? me.arrayForma[0].id : me.datos.id_forma_pago,
                            'id_costo_pago': me.datos.id_descuento,
                            'detalle': me.arrayDetalle,
                            'monto_total': me.datos.total,
                            'tipo_venta': me.datos.tipo_venta,
                        }).then(function(response){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Cotizacion modificada exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            me.cargarPdfSimple(me.datos.id);
                            me.volverVentaListado();
                            me.listarControlVacuna(1, '', 'nombre'); 
                            //me.limpiarDatosVenta();
                            console.log(me.datos);
                        })
                        .catch(function(error){
                            console.log(error);
                        });
                    // }
                }
                
            },
            validarCompra(){
                this.errorPago = 0;
                this.errorMostrarMsjPago = [];

                if(!this.datos.nombre) this.errorMostrarMsjPago.push("El nombre del Pago no puede estar vacio ");
                if(this.errorMostrarMsjPago.length) this.errorPago=1;
                return this.errorPago;
            },        
            validarCompra(){
                this.errorCompra = 0;
                this.errorMostrarMsjCompra = [];

                if(!this.datos.nombre) this.errorMostrarMsjCompra.push("El nombre del Compra no puede estar vacio ");
                if(this.errorMostrarMsjCompra.length) this.errorCompra=1;
                return this.errorCompra;
            },
            frmServicio(){
                this.listado = 1;
                this.selectCliente();
                this.selectTipoP();
                this.selectFormaP();        
            },
            anularCotizacion(id){
                dangerConfirm.fire({
                    title: 'Esta seguro de Anular esta Servicio??',
                    text: "No Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/cotizacion/anular',{'id': id}).then(function (response) {
                        me.listarControlVacuna(1,'', 'nombre');
                        Swal.fire(
                        'Anulado!',
                        'Este servicio se ha Anulado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Cancelado',
                    'Este servicio no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            limpiarDatosVenta(){
                this.datos = {
                    id : 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    prox_fecha : moment().format('YYYY-MM-DD'),
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
                    estado:'Registrado',
                    tipo_venta: 'Venta Cotizacion',
                    descripcion:'',
                    arrayArticulo:[],
                    id_orden_servicio: null,
                    
                }
            },
            volverServicioListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
                me.listado = 0;

                //me.limpiarDatosVenta();
            },
            cargarPdf(id) {
             let time=1000;
            this.downloadReport(time);
                axios.get('/controlvacuna//pdfVacuna?id=' + id,{responseType: 'blob'})
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
            limpiarArticulo(){
                this.arrayArticulo = [];
                this.buscarP = '';
                this.buscar = '';
                this.arrayDetalle.forEach(item => item.saldoStock = 0);
            },
            cargarPdfSimple(id) {
                axios.get('/cotizacion/pdfCotizacionSimple1?id=' + id,{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            cargarPdf2() {
                axios.get('/venta/pdfVentasGeneral2',{responseType: 'blob'})
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
            this.listarControlVacuna(1, this.buscar, this.criterio);
            this.selectFormaP();   
            this.selectTipoP(); 
            this.verificarCaja();
            // this.selectCliente();
            // this.selectTipoP();
            // this.selectFormaP();  
  
             
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
</style>