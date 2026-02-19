<template>
    <main class="main">
        <div class="row">
            <div class="col">
            &nbsp;
            <div class="card">
                <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">HISTORIAL COTIZACION</h3></div>
                <template v-if="listado==0">
                    <div class="form-group row">
                        <div class="col-md-8">
                            &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                <select class="form-select col-md-3" v-model="criterio">
                                    <option value="cliente.nombre">Cliente</option>
                                    <option value="users.nombre">Usuario</option>
                                </select>
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscar" @keyup.enter="listarVenta(1, buscar, criterio)" @keyup="BuscandoVenta()" class="form-control" placeholder="Texto a buscar">
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" @click="listarVenta(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Light table -->
                    <div class="table-responsive">
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                        <thead style="background-color: #46546C">
                            <tr>                      
                                <th scope="col" class="text-white">Tienda</th>
                                <th scope="col" class="text-white">Cliente</th>
                                <th scope="col" class="text-white">Fecha Vencimiento</th>
                                <th scope="col" class="text-white">Tiempo Entrega</th>
                                <th scope="col" class="text-white">Lugar de Entrega</th>
                                <th scope="col" class="text-white">Tipo Pago</th>
                                <th scope="col" class="text-white text-center">Estado</th>
                                <th scope="col" class="text-white text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in arrayVenta" :key="venta.id">
                                <td v-text="venta.tienda"></td>
                                <td v-text="venta.cliente"></td>
                                <td v-text="venta.fecha_venci"></td>
                                <td v-text="venta.tiempo_entrega"></td>
                                <td v-text="venta.lugar_entrega"></td>
                                <td v-text="venta.tipo"></td>
                                <td class="text-center">
                                    <template v-if="venta.estado=='Registrado'">
                                        <span class="badge bg-info tamaño">{{venta.estado}}</span>                                           
                                    </template>
                                    <template v-if="venta.estado=='Entregado'">
                                        <span class="badge bg-success tamaño">{{venta.estado}}</span>
                                    </template>
                                    <template v-if="venta.estado=='Anulado'">
                                        <span class="badge bg-danger tamaño">{{venta.estado}}</span>                                           
                                    </template>
                                </td> 
                                <td class="text-center">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" @click="verVenta(venta)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                        <template v-if="venta.estado!='Anulado'">
                                            <template v-if="venta.estado!='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="editarDetalle(venta)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                                <li><a class="dropdown-item" href="#" @click="vender(venta)"><i class="fa fa-inbox text-primary"></i> Vender</a></li>
                                            </template>
                                        </template>
                                        <template v-if="venta.estado!='Anulado'">
                                            <template v-if="venta.estado!='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="anularCotizacion(venta.id)"><i class="fa fa-lock text-danger"></i> Anular</a></li>
                                            </template>
                                        </template>
                                        <li><a class="dropdown-item" href="#" @click="cargarPdfSimple(venta.id, datos.foto, datos.empresa_nombre)"><i class="fa fa-file-pdf-o text-danger   "></i> Reporte</a></li>
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
                                    <!-- <div class="col-md-6" v-if="datos.id_tipo_pago == 2">
                                        <label for="exampleInputPassword1" class="form-label">Fecha Final</label>
                                        <input type="date" class="form-control"  v-model="datosPago.fecha_final">  
                                    </div> -->
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
                                    <div class="col-md-6">
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
                                    </div>
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
                                                    <!-- <span style="color:red;" v-show="detalle.cantidad>detalle.stock && datos.tipo_venta!='Venta Servicio'">Stock: {{detalle.stock}}</span> -->
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

                                                        <vue-numeric v-model="datos.descuento" :precision="2" value="0" class="form-control" :max="parseFloat(datos.sub_total)"></vue-numeric>

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
                                    <button class="btn btn-info btn-lg text-white" type="button" @click="modificarCotizacion()">Guardar</button>
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
                            <button type="button" @click="cargarPdfSimple(datos.id, datos.foto)" class="btn btn-info">
                                <i class="icon-doc text-white"></i><span class="text-white">&nbsp;Reporte</span>
                            </button> 
                        </div>
                        <div class="col-md-4 text-center"><h3 class="mb-0">REGISTRO DE COTIZACION</h3></div>
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

                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha" disabled>  
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Dias Credito</label>
                                    <input type="number" class="form-control"  v-model="datos.dias_credito" disabled>  
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha Vencimiento</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha_venci" disabled>  
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Tiempo Entrega</label>
                                    <input type="text" class="form-control"  v-model="datos.tiempo_entrega" disabled>  
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Tipo Pago</label>
                                    <input type="text" class="form-control"  v-model="datos.tipo" disabled>  
                                </div>
                                <div class="col-md-12">
                                    <label for="exampleInputPassword1" class="form-label">Nota</label>
                                    <textarea class="form-control" v-model="datos.nota" rows="2" disabled></textarea>
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
                                        <th scope="col" class="text-white">Categoria</th>
                                        <th scope="col" class="text-white">Nombre</th>
                                        <th scope="col" class="text-white">Marca</th>
                                        <th scope="col" class="text-white">Tienda</th>
                                        <th scope="col" class="text-white">Tiempo Entrega</th>
                                        <th scope="col" class="text-white">Precio</th>
                                        <th scope="col" class="text-white">Cantidad</th>
                                        <th scope="col" class="text-white">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                        <td v-text="detalle.categoria"></td>
                                        <td v-text="detalle.articulo"></td>
                                        <td v-text="detalle.marca"></td>
                                        <td v-text="detalle.tienda"></td>
                                        <td v-text="detalle.tiempo_entrega"></td>
                                        <td>
                                            <input v-model="detalle.costo_venta" type="number" value="3" class="form-control" disabled>
                                        </td>
                                        <td>
                                            <input v-model="detalle.cantidad" type="number" value="3" class="form-control" disabled>
                                            <!-- <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span> -->
                                        </td>
                                        <td>
                                            {{detalle.sub_total}} bs
                                        </td>                                                                             
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="7" align="right"> <strong>Sub Total:</strong> </td>
                                        <td>{{datos.sub_total}} bs</td> 
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="7" align="right"> <strong>Descuento:</strong> </td>
                                        <td><input v-model="datos.descuento" type="number" value="0" class="form-control" disabled></td>
                                    </tr>
                                        <tr style="background-color: #CEECF5">
                                        <td colspan="7" align="right"> <strong>Sub Total:</strong> </td>
                                        <td>{{datos.total}} bs</td>
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

        <!--Modal Formulario Producto/Servicio-->
        <div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDA DE PRODUCTOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(buscarP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                             <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Categoria</th>                      
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Marca</th>
                                    <th scope="col" class="text-white">Tienda</th>
                                    <th scope="col" class="text-white">Precio Unitario</th>
                                    <th scope="col" class="text-white">Precio Mayorista</th>
                                    <th scope="col" class="text-white">Precio Preferencial</th>
                                    <th scope="col" class="text-white">stock</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tienda_articulo in arrayArticulo" :key="tienda_articulo.id">
                                    <td v-text="tienda_articulo.categoria"></td>
                                    <td v-text="tienda_articulo.articulo"></td>
                                    <td v-text="tienda_articulo.marca"></td>
                                    <td v-text="tienda_articulo.tienda"></td>
                                    <td v-text="tienda_articulo.costo_unitario"></td>
                                    <td v-text="tienda_articulo.costo_mayorista"></td>
                                    <td v-text="tienda_articulo.costo_preferencial"></td>
                                    <td v-text="tienda_articulo.stock"></td>
                                    <td>
                                        <button type="button" @click="seleccionarTiendaArticulo(tienda_articulo)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                    </td>                                 
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
        <!--Fin modal Formulario Producto/Servicio-->

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
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        created() {
            this.datos.costo_pago = 1;
             this.datos.estado = 'Registrado';
        },
        data(){
            return {
                datos : {
                    id : 0,
                    con_factura : '1',
                    fecha :  moment().format('YYYY-MM-DD'),
                    fecha_venci : moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_tipo_pago : 1,
                    id_forma_pago : 2,
                    id_cliente : 0,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                    costo_pago : '',
                    id_descuento: '',
                    nota:'',
                    personal:'',
                    estado:'',
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
                arrayVenta : [],
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
                criterio : 'cliente.nombre',
                buscar : '',
                buscarP : '',
                isDisabledProducto: false,
                disabledRecepcionado: false,
                disabledConcluido: false,
                disabledEntregado: false,
                disabledAnulado: false,
                setTimeoutBuscador : ''
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
                if(this.datos.tipo_venta=='Venta Cotizacion') {
                    for(var i=0;i<this.arrayDetalle.length;i++){
                        //resultado = resultado + (this.arrayDetalle[i].sub_total);
                        resultado = resultado + (this.arrayDetalle[i].costo_venta*this.arrayDetalle[i].cantidad)
                    }
                } else {
                    //
                }

                // if(this.datos.tipo_venta=='Venta Cotizacion') {
                //     for(var i=0;i<this.arrayDetalle.length;i++){
                //         if(this.datos.id_descuento ==1){
                //         resultado = resultado + (this.arrayDetalle[i].costo_unitario*this.arrayDetalle[i].cantidad);
                //         }else if(this.datos.id_descuento ==2)
                //         { resultado = resultado + (this.arrayDetalle[i].costo_mayorista*this.arrayDetalle[i].cantidad);
                //         }else if(this.datos.id_descuento ==3){
                //         resultado = resultado + (this.arrayDetalle[i].costo_preferencial*this.arrayDetalle[i].cantidad);
                //         }else
                //         {}
                //     }
                // } else {
                //     for(var i=0;i<this.arrayDetalle.length;i++){
                //         resultado = resultado + (this.arrayDetalle[i].costo_venta*this.arrayDetalle[i].cantidad);
                //     }
                // }
                
                
                return resultado;
            }
        },
        methods : {
            cambiarDias(){
                let me = this;
                me.datos.fecha_venci = moment(me.datos.fecha).add(me.datos.dias_credito,'day').format('YYYY-MM-DD');
            },
            listarVenta(page, buscar, criterio){
                let me=this;
                var url='/cotizacion1?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayVenta=response.data.data;
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
            listarVentaServicioVentaDirecta(){
                let me=this;
                var url='/servicio_tienda1?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayVenta=response.data.data;
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
                me.listarVenta(page, buscar, criterio);
            },
            listarArticulo(buscarP){
                let me = this;
                var url='/tienda/listarSinPaginate2/tienda1?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                    //me.arrayArticulo.forEach(item => item.costo_venta = "-1")
                })
                .catch(function(error){
                    console.log(error);
                });
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
            seleccionarTiendaArticulo(data=[]){
                let me = this;
                let venta = 0;
                if(me.encuentra(data['id'])){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este producto ya se encuentra agregado!'
                    })
                }
                else{
                    // if(me.datos.id_descuento == 1) {
                    //     venta =data['costo_unitario'];
                    // }else if(me.datos.id_descuento == 2){
                    //     venta =data['costo_mayorista'];
                    // }else if(me.datos.id_descuento == 3){
                    //     venta =data['costo_preferencial'];
                    // }
                    me.arrayDetalle.push({
                        // id_tienda_articulo : data['id'],
                        // categoria : data['categoria'],
                        // id_articulo : data['id_articulo'],
                        // marca : data['marca'],
                        // articulo : data['articulo'],
                        // tienda : data['tienda'],
                        // costo_unitario : data['costo_unitario'],
                        // costo_mayorista : data['costo_mayorista'],
                        // costo_preferencial : data['costo_preferencial'],
                        // stock : data['stock'],
                        // cantidad : 1,
                        // sub_total : data['sub_total']


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
                        tiempo_entrega:0,
                        //sub_total : data['sub_total'],
                        producto : true,
                        costo_venta : me.datos.id_descuento == 1 ? data['costo_unitario'] : (me.datos.id_descuento == 2 ? data['costo_mayorista'] : (me.datos.id_descuento == 3 ? data['costo_preferencial'] : 0 )),
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
                var url='/cliente/selectCliente';
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
            verVenta(data=[]){
                let me = this;
                me.listado = 2;
                me.datos.id=data['id'];
                me.datos.id_cliente=data['id_cliente'];
                me.datos.id_personal=data['id_personal'];
                me.datos.cliente=data['cliente'];
                
                me.datos.dias_credito=data['dias_credito'];
                me.datos.tiempo_entrega=data['tiempo_entrega'];
                me.datos.lugar_entrega=data['lugar_entrega'];
                me.datos.nota=data['nota'];
                me.datos.tipo=data['tipo'];

                me.datos.personal=data['personal'];
                me.datos.fecha=data['fecha'];
                me.datos.estado=data['estado'];
                me.datos.descuento=data['descuento'];
                me.datos.descripcion=data['nota'];
                me.datos.tipoPago=data['tipoP'];
                me.datos.formaPago=data['formaP'];
                me.datos.id_descuento=data['id_descuento'];
                me.datos.sub_total=data['sub_total'];
                me.datos.total=data['total'];
                me.datos.fecha_venci=data['fecha_venci'];
              

                var url='/cotizacion/permiso/detalle?id=' + data['id'];
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
            editarDetalle(data=[]){
                let me = this;
                me.cambiarDias();
                me.listado = 3;
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
            guardarVenta(){
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
                        me.datos.estado = 'Entregado';
                        axios.post('/venta/guardar_tienda1/cotizacion',{
                            'id': me.datos.id,
                            'fecha': me.datos.fecha,
                            'fecha_final': me.datosPago.fecha_final,
                            'sub_total': me.datos.sub_total,
                            'descuento': me.datos.descuento,
                            'total': me.datos.total,
                            'estado': me.datos.estado,
                            'id_cliente': me.datos.id_cliente,
                            'id_tipo_pago': me.datos.id_tipo_pago,
                            'id_forma_pago': (me.datos.id_tipo_pago == 2) ? me.arrayForma[0].id : me.datos.id_forma_pago,
                            'id_costo_pago': me.datos.id_descuento,
                            'detalle': me.arrayDetalle,
                            'monto_total': me.datos.total,
                            'descripcion_pago': me.datosPago.descripcion,
                            'saldo': me.datosPago.saldo,
                            'tipo_venta': me.datos.tipo_venta,
                            'fecha_venci': me.datos.fecha_venci,
                            'tiempo_entrega': me.datos.tiempo_entrega,
                            'lugar_entrega': me.datos.lugar_entrega,
                            'dias_credito': me.datos.dias_credito,
                            'nota': me.datos.nota,

                        }).then(function(response){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Venta registrado exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            me.cargarPdf2();
                            me.volverVentaListado();
                            me.limpiarDatosVenta();
                            me.listarVenta(1, '', 'nombre');   
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
                            me.listarVenta(1, '', 'nombre'); 
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
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
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
                        me.listarVenta(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Anulado!',
                        'Este servicio se ha Anulado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
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
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_tipo_pago : 0,
                    id_forma_pago : 0,
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
            cargarPdf(id,foto) {
                axios.get('/servicio/pdfOrdenServiciosGeneral?id=' + id  + '&foto='+ foto,{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
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
            this.listarVenta(1, this.buscar, this.criterio);
            this.selectFormaP();   
            this.selectTipoP(); 
            this.verificarCaja();
            // this.selectCliente();
            // this.selectTipoP();
            // this.selectFormaP();  
  
             
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
    .tamaño{
       width: 100px !important; 
    }
</style>