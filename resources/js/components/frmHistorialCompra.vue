<template>
    <main class="main">
        <!-- <div class="container"> -->
            <template v-if="vista==0">
                <div class="row">
                    <div class="col">
                    &nbsp;
                    <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">HISTORIAL COMPRAS</h3></div>
                        <template v-if="listado==0">

                            <div class="form-group row">
                                <div class="col-md-8">
                                    &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                        <select class="form-select col-md-3" v-model="criterio">
                                            <option value="proveedor.nombre">Proveedor</option>
                                        </select>
                                        &nbsp;&nbsp;&nbsp;
                                        <input type="text" v-model="buscar" @keyup.enter="listarCompra(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="submit" @click="listarCompra(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <!-- Light table -->
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <th scope="col" class="text-white">Fecha</th>
                                        <th scope="col" class="text-white">Proveedor</th>
                                        <th scope="col" class="text-white">Usuario</th>
                                        <th scope="col" class="text-white">Descripcion</th>
                                        <th scope="col" class="text-white">Descuento</th>
                                        <th scope="col" class="text-white">Total</th>
                                        <th scope="col" class="text-white">Estado</th>
                                        <th scope="col" class="text-white">Tipo Pago</th>
                                        <th scope="col" class="text-white">Forma Pago</th>
                                        <th scope="col" class="text-white">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="compra in arrayCompra" :key="compra.id">
                                        <td v-text="compra.fecha"></td>
                                        <td v-text="compra.proveedor"></td>
                                        <td v-text="compra.name"></td>
                                        <td v-text="compra.descripcion"></td>
                                        <td v-text="compra.descuento"></td>
                                        <td v-text="compra.total"></td>
                                        <td class="text-center">
                                            <template v-if="compra.estado=='Registrado'">
                                                <span class="badge bg-success tamaño text-white">{{compra.estado}}</span>
                                            </template>
                                            <template v-if="compra.estado=='Anulado'">
                                                <span class="badge bg-danger tamaño text-white">{{compra.estado}}</span>
                                            </template>
                                            <template v-if="compra.estado=='Cancelado'">
                                                <span class="badge bg-warning tamaño text-white">{{compra.estado}}</span>
                                            </template>
                                        </td>
                                        <td v-text="compra.tipo"></td>
                                        <td class="text-center">
                                            <template v-if="compra.formaP=='Efectivo'">
                                                <span >{{compra.formaP}}</span>
                                            </template>
                                            <template v-if="compra.formaP=='Transferencia'">
                                                <span >{{compra.formaP}}</span>
                                            </template>
                                            <template v-if="compra.formaP=='Pago por QR'">
                                                <span >{{compra.formaP}}</span>
                                            </template>
                                            <template v-if="compra.formaP=='Depósito'">
                                                <span >{{compra.formaP}}</span>
                                            </template>
                                            <template v-if="compra.formaP=='Mixta'">
                                                <span >{{compra.formaP}}</span>
                                            </template>
                                            <template v-if="compra.formaP=='Cuenta por Cobrar'">
                                                <span >Cuenta por Pagar</span>
                                            </template>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" @click="verCompra(compra)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                                <template v-if="compra.estado != 'Cancelado'">
                                                <li><a class="dropdown-item" href="#" @click="verModificar(compra)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                                </template>
                                                <template v-if="compra.estado=='Registrado'">
                                                    <li><a class="dropdown-item" href="#" @click="anularCompra(compra.id)"><i class="fa fa-lock text-danger"></i> Anular</a></li>
                                                </template>
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
                        <template v-if="listado==1">
                            <div class="card-body">
                                <!-- <button type="button" class="btn btn-danger text-white" @click="volverCompraListado()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                                <div class="card-header text-center" style="background-color: #CEECF5"><h3 class="mb-0">REGISTRO DE COMPRA - ALMACEN GENERAL</h3></div>                             -->
                                <div class="form-group row">
                                <form class="row">
                                    <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Proveedor</label>
                                        <select class="form-select" v-model="datos.id_proveedor">
                                            <option value="0" disabled>Seleccione el Proveedor</option>
                                            <option v-for="proveedor in arrayProveedor" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.nombre"></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                        <input type="date" class="form-control"  v-model="datos.fecha" disabled>
                                    </div>
                                    <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                        <select class="form-select" v-model="datos.id_forma_pago">
                                            <option value="0" disabled>Seleccione la Forma Pago</option>
                                            <option  v-for="forma_pago in arrayFormaPago" :key="forma_pago.id" :value="forma_pago.id " v-text="forma_pago.nombre"></option>
                                        </select>
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label for="exampleInputPassword1" class="form-label">Descuento</label>
                                        <input type="number" class="form-controls" v-model="datos.descuento">
                                    </div> -->


                                    <div class="col-md-12">
                                        <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                        <textarea class="form-control" v-model="datos.descripcion" rows="2"></textarea>
                                    </div>
                                    &nbsp;


                                </form>
                                    <div class="col-md-12">
                                        <div v-show="errorCompra" class="form-group row div-error">
                                            <div class="text-center text-error">
                                                <div v-for="error in errorMostrarMsjCompra" :key="error" v-text="error">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <div class="form-group row">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>
                                                <th scope="col" class="text-white">Opción</th>
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
                                            <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                                </td>
                                                <td v-text="detalle.categoria"></td>
                                                <td v-text="detalle.articulo"></td>
                                                <td v-text="detalle.marca"></td>
                                                <td v-text="detalle.tienda"></td>
                                                <td><input v-model="detalle.costo_compra" type="number" value="3" class="form-control"></td>
                                                <td><input v-model="detalle.cantidad" type="number" value="3" class="form-control"></td>
                                                <td>{{detalle.sub_total = detalle.costo_compra*detalle.cantidad-detalle.descuento}}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="5" align="right"> <strong>Total:</strong> </td>
                                                <td>{{datos.total = calcularTotal.toFixed(2)}} bs</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="5" align="right"> <strong>Descuento:</strong> </td>
                                                <td><input v-model="datos.descuento" type="number" value="0" class="form-control"></td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="5" align="right"> <strong>Sub Total:</strong> </td>
                                                <td>{{datos.sub_total = datos.total- datos.descuento}} bs</td>
                                            </tr>

                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">No hay Permisos agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverCompraListado()">Cancelar</button>
                                    <button class="btn btn-info btn-lg text-white" type="button" @click="guardarCompra()">Guardar</button>
                                </div>
                            </div>
                        </template>

                        <template v-if="listado==2">
                            <div class="card-header row m-0">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-danger text-white" @click="volverCompraListado()">
                                        <i class="fa fa-reply-all"></i>&nbsp;Volver
                                    </button>
                                    <button type="button" @click="cargarPdf(datos.id, datos.foto)" class="btn btn-info">
                                        <i class="icon-doc text-white"></i><span class="text-white">&nbsp;Reporte</span>
                                    </button>
                                </div>
                                <div class="col-md-4 text-center"><h3 class="mb-0">REGISTRO DE COMPRA</h3></div>
                                <div class="col-md-4">&nbsp;</div>
                                <!-- <div class="col-md-2">
                                    <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt="">
                                </div>  -->
                            </div>
                            <div class="card-body" >
                                <!-- <button type="button" class="btn btn-danger text-white" @click="volverCompraListado()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button> -->
                                <div class="form-group row">
                                    <form class="row g-1" >
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Proveedor</label>
                                        <input type="text" class="form-control"  v-model="datos.proveedor" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                        <input type="date" class="form-control"  v-model="datos.fecha" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                        <input type="text" class="form-control"  v-model="datos.formaPago" disabled>
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label for="exampleInputPassword1" class="form-label">Descuento</label>
                                        <input type="number" class="form-control" v-model="datos.descuento">
                                    </div> -->

                                    <div class="col-md-12">
                                        <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                        <textarea class="form-control" v-model="datos.descripcion" rows="2" disabled></textarea>
                                    </div>

                                </form>
                                </div>

                                <br>
                                <div class="form-group row">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>
                                                <th scope="col" class="text-white">Categoria</th>
                                                <th scope="col" class="text-white">Nombre</th>
                                                <!-- <th scope="col" class="text-white">Marca</th> -->
                                                <th scope="col" class="text-white">Tienda</th>
                                                <th scope="col" class="text-white">Precio</th>
                                                <th scope="col" class="text-white">Cantidad</th>
                                                <th scope="col" class="text-white">Descuento</th>
                                                <th scope="col" class="text-white">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                                <td v-text="detalle.categoria"></td>
                                                <td v-text="detalle.articulo"></td>
                                                <!-- <td v-text="detalle.marca"></td> -->
                                                <td v-text="detalle.tienda"></td>
                                                <td v-text="detalle.costo_compra"></td>
                                                <td v-text="detalle.cantidad"></td>
                                                <td v-text="detalle.descuento"></td>
                                                <td v-text="detalle.sub_total"></td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="6" align="right"> <strong>Sub Total:</strong> </td>
                                                <td>{{datos.total = calcularTotal.toFixed(2)}} bs</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="6" align="right"> <strong>Descuento:</strong> </td>
                                                <td v-text="datos.descuento"></td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="6" align="right"> <strong>Total:</strong> </td>
                                                <td>{{datos.sub_total = (datos.total- datos.descuento).toFixed(2)}} bs</td>
                                            </tr>
                                            <template v-if="datos.formaPago == 'Mixta'">
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="5"></td>
                                                <td align="right"> <strong>Total Efect.:</strong> </td>
                                                <td v-text="datos.total_efectivo"></td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="5"></td>
                                                <td align="right"> <strong>Total Dep.:</strong> </td>
                                                <td>{{datos.total_deposito}} bs</td>
                                            </tr>
                                            </template>

                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="8">No hay Productos agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <!-- <button class="btn btn-danger me-md-2 text-white" type="button">Cancelar</button> -->
                                    <button class="btn btn-info btn-lg text-white" type="button" @click="modificarFecha()">Modificar</button>
                                </div>
                            </div>
                        </template>

                        <template v-if="listado==3">
                            <div class="card-header row m-0">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-danger text-white" @click="volverCompraListado()">
                                        <i class="fa fa-reply-all"></i>&nbsp;Volver
                                    </button>
                                    <!-- <button type="button" @click="cargarPdf(datos.id, datos.foto)" class="btn btn-info">
                                        <i class="icon-doc text-white"></i><span class="text-white">&nbsp;Reporte</span>
                                    </button>  -->
                                </div>
                                <div class="col-md-4 text-center"><h3 class="mb-0">REGISTRO DE COMPRA</h3></div>
                                <div class="col-md-4">&nbsp;</div>
                                <!-- <div class="col-md-2">
                                    <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt="">
                                </div>  -->
                            </div>
                            <div class="card-body" >
                                <!-- <button type="button" class="btn btn-danger text-white" @click="volverCompraListado()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button> -->
                                <div class="form-group row">
                                    <form class="row g-1" >
                                    <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Proveedor<span style="color:red;" > *</span></label>
                                        <select class="form-select" v-model="datos.id_proveedor" disabled>
                                            <option value="0" disabled>Seleccione el Proveedor</option>
                                            <option v-for="proveedor in arrayProveedor" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.nombre"></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                        <input type="date" class="form-control"  v-model="datos.fecha">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Tipo Pago</label>
                                            <select class="form-select" v-model="datos.id_tipo_pago" @change="tipoPagoChange()">
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
                                            <select class="form-select" v-model="datos.id_forma_pago" @change="formaPagoChange()">
                                                <option value="0" disabled>Seleccione la Forma de Pago</option>
                                                <option v-for="forma_pago in arrayForma2" :key="forma_pago.id" :value="forma_pago.id" v-text="forma_pago.nombre"></option>
                                            </select>
                                        </template>
                                    </div>

                                    <!-- <div class="mb-3">
                                        <label for="exampleInputPassword1" class="form-label">Descuento</label>
                                        <input type="number" class="form-control" v-model="datos.descuento">
                                    </div> -->

                                    <div class="col-md-12">
                                        <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                        <textarea class="form-control" v-model="datos.descripcion" rows="2"></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        </div>&nbsp;
                                        <div class="col-md-12">
                                        <label>Productos<span style="color:red;" >(*Seleccione)</span></label>
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalArticulo" @click="listarArticulo(1,buscarP,criterioP)"><i class="fa fa-search"></i> Agregar Productos</button>
                                    </div>

                                </form>
                                </div>

                                <br>
                                <div class="form-group row">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>
                                                <th scope="col" class="text-white">Opción</th>
                                                <th scope="col" class="text-white">Categoria</th>
                                                <th scope="col" class="text-white">Nombre</th>
                                                <th scope="col" class="text-white">Marca</th>
                                                <th scope="col" class="text-white">Tienda</th>
                                                <th scope="col" class="text-white">Precio</th>
                                                <th scope="col" class="text-white">Cantidad</th>
                                                <th scope="col" class="text-white">Fecha Vecimiento</th>
                                                <th scope="col" class="text-white">Lote</th>
                                                <th scope="col" class="text-white">Descuento</th>
                                                <th scope="col" class="text-white">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle,index) in arrayDetalle" :key="index">
                                                <td v-if="detalle.eliminado == 0">
                                                    <!-- <button @click="eliminarDetalle(index,detalle.nuevo_articulo,detalle.id_articulo)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button> -->
                                                    <button @click="eliminarDetalle(index,detalle.articulo_nuevo,detalle.id_articulo)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                                </td>
                                                <td v-if="detalle.eliminado == 0" v-text="detalle.categoria"></td>
                                                <td v-if="detalle.eliminado == 0" v-text="detalle.articulo"></td>
                                                <td v-if="detalle.eliminado == 0" v-text="detalle.marca"></td>
                                                <td v-if="detalle.eliminado == 0" v-text="detalle.tienda"></td>
                                                <!-- <td v-if="detalle.eliminado == 0" v-text="detalle.costo_compra"></td> -->
                                                <td v-if="detalle.eliminado == 0"><input v-model="detalle.costo_compra" type="number" value="3" class="form-control"></td>
                                                <!-- <td v-text="detalle.cantidad"></td> -->
                                                <!-- <td v-text="detalle.sub_total"></td>                                                                              -->
                                                <td v-if="detalle.eliminado == 0">
                                                    <input v-model="detalle.cantidad" type="number"  class="form-control" min="0">
                                                    <span style="color:red;" v-show="parseFloat(detalle.cantidad) < parseFloat(detalle.cantidad_auxiliar)" >Stock Lote: {{detalle.stock}}</span>

                                                </td>
                                                <td v-if="detalle.eliminado == 0"><input v-model="detalle.fecha_vecimiento" type="date" value="3" class="form-control"></td>
                                                <td v-if="detalle.eliminado == 0"><input v-model="detalle.lote" type="text" value="3" class="form-control"></td>
                                                <td v-if="detalle.eliminado == 0">
                                                    <input v-model="detalle.descuento" type="number" value="3" class="form-control" min='0'>
                                                </td>
                                                <td v-if="detalle.eliminado == 0">{{detalle.sub_total = isNaN(detalle.costo_compra*detalle.cantidad-parseFloat(detalle.descuento)) ? (detalle.costo_compra*detalle.cantidad).toFixed(2)  : (Math.round((detalle.costo_compra*detalle.cantidad-(parseFloat(detalle.descuento)))*100)/100).toFixed(2)}}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="10" align="right"> <strong>Sub Total:</strong> </td>
                                                <td>{{datos.sub_total = calcularTotal.toFixed(2)}} bs</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="10" align="right"> <strong>Descuento:</strong> </td>
                                                <td><input v-model="datos.descuento" type="number" value="0" class="form-control"></td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="10" align="right"> <strong>Total:</strong> </td>
                                                <td>{{datos.total = isNaN(datos.sub_total- datos.descuento) ? (datos.sub_total).toFixed(2) : (datos.sub_total- datos.descuento).toFixed(2)}} bs</td>
                                            </tr>
                                            <template v-if="datos.formaPago == 'Mixta'">
                                                <tr style="background-color: #CEECF5">
                                                    <td colspan="10" align="right"> <strong>Total Efect.:</strong> </td>
                                                    <td>
                                                        <!-- <vue-numeric v-model="datos.total_efectivo" :precision="2" value="0" class="form-control" ></vue-numeric> -->
                                                        <input v-model="datos.total_efectivo" type="number"  class="form-control" min="0">
                                                    </td>
                                                </tr>
                                                <tr style="background-color: #CEECF5">
                                                    <td colspan="10" align="right"> <strong>Total Desp.:</strong> </td>
                                                    <td>{{datos.total_deposito = datos.total- datos.total_efectivo}} bs</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="12">No hay Productos agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <!-- <button class="btn btn-danger me-md-2 text-white" type="button">Cancelar</button> -->
                                    <button class="btn btn-info btn-lg text-white" type="button" @click="modificarCantidad()">Modificar</button>
                                </div>
                            </div>
                        </template>
                    </div>
                    </div>

                </div>
            <!-- </div>   -->

            <!--Modal Formulario Articulo-->
                <div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-fullscreen" role="document">
                    <div class="modal-content">
                        <div class="modal-header btn btn-info text-white" style="height:45px;" >
                        <h5 class="modal-title ">BUSQUEDA DE PRODUCTOS</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">

    <!-- df -->
                        <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-select col-md-3" v-model="criterioP">
                                            <option value="articulo.nombre_comercial">Producto</option>
                                            <option value="unidad_medida.nombre">Presentación</option>
                                            <option value="proveedor.nombre">Laboratorio</option>
                                            <option value="categoria.nombre">Categoria</option>
                                        </select>&nbsp;&nbsp;&nbsp;
                                        <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(1,buscarP, criterioP)" @keyup="BuscandoArticulo()" class="form-control" placeholder="Texto a buscar">
                                        <!-- <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(1,buscarP, criterioP)"  class="form-control" placeholder="Texto a buscar"> -->
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="submit" @click="listarArticulo(1,buscarP, criterioP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>&nbsp;
    <!-- KCda                         -->
                            <!-- <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(buscarP,criterioPbuscarP)" class="form-control" placeholder="Texto a buscar">
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="submit" @click="listarArticulo(buscarP,criterioPbuscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>&nbsp; -->
                            <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <th scope="col" class="text-white">Categoria</th>
                                        <th scope="col" class="text-white">Nombre</th>
                                        <th scope="col" class="text-white">presentación</th>
                                        <th scope="col" class="text-white">Laboratorio</th>
                                        <th scope="col" class="text-white">Precio</th>
                                        <th scope="col" class="text-white">Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="tienda_articulo in arrayArticulo" :key="tienda_articulo.id">
                                        <td v-text="tienda_articulo.categoria"></td>
                                        <td v-text="tienda_articulo.articulo"></td>
                                        <td v-text="tienda_articulo.presentacion"></td>
                                        <td v-text="tienda_articulo.laboratorio"></td>
                                        <td v-text="tienda_articulo.costo_compra"></td>

                                        <td>
                                            <button type="button" @click="seleccionarTiendaArticulo(tienda_articulo)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>
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
                                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscarP, criterioP)">Ant</a>
                                            </li>
                                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page==isActived ? 'active' :'']">
                                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscarP, criterioP)" v-text="page">1</a>
                                            </li>
                                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscarP, criterioP)">Sig</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
            </div>

                </div>
                </template>
            <!--Fin modal Formulario Articulo-->
            <template v-if="vista==1">
                <frm-historialcompra></frm-historialcompra>
            </template>
            <template v-if="vista==2">
                <frm-historialcompra></frm-historialcompra>
            </template>
            <template v-if="vista==3">
                <frm-historialcompra></frm-historialcompra>
            </template>
            <template v-if="vista==4">
                <frm-historialcompra></frm-historialcompra>
            </template>
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
                    fecha : moment().format('YYYY-MM-DD'),
                    descripcion : '',
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_proveedor : 0,
                    id_forma_pago : 0,
                    proveedor : '',
                    tipoPago : '',
                    formaPago : '',
                    formaPagoAux : '',
                    total_aux:0,
                    total_efectivo:0,
                    total_deposito:0,
                    total_efectivo_aux:0,
                    total_deposito_aux:0,
                    fecha_vecimiento :'',

                    id_tipo_pago: 0,
                    id_tipo_pago_anterior: 0,
                    id_proveedor_aux: 0,
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


                arrayCompra : [],
                arrayDetalle : [],
                arrayArticulo : [],
                arrayProveedor: [],
                arrayFormaPago: [],
                arrayForma2: [],
                arrayPago: [],
                listado : 0,
                tipoAccion : 0,
                errorCompra : 0,
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
                criterio : 'proveedor.nombre',
                buscar : '',
                buscarP : '',
                inventario :0,
                errores :{},
                saldo:0,
                isDisabledProducto: false,
                modalP:0,
                buscarP : '',
                criterioP : 'articulo.nombre_comercial',
                vista: 0,
                setTimeoutBuscador: '',

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
                    if(this.arrayDetalle[i].eliminado==0){
                        resultado = resultado + (this.arrayDetalle[i].costo_compra*this.arrayDetalle[i].cantidad - this.arrayDetalle[i].descuento);
                    }
                }
                if(isNaN(resultado)){
                    resultado = (this.arrayDetalle[i].costo_compra*this.arrayDetalle[i].cantidad).toFixed(2);
                }
                return resultado;
            }
        },
        methods : {
            abrirModalP(){
                let me=this;
                me.modalP=1;
                me.limpiarArticulo();
            },
            cerrarModalP(){
                let me=this;
                me.modalP=0;
                me.limpiarArticulo();

            },
            limpiarArticulo(){
                //this.arrayArticulo = [];
                this.buscarP = '';
                this.buscar = '';
                this.listarArticulo(1,this.buscarP, this.criterioP);
                //this.arrayDetalle.forEach(item => item.saldoStock = 0);
            },
            listarCompra(page, buscar, criterio){
                let me=this;
                var url='/compra?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayCompra=response.data.data;
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
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarCompra(page, buscar, criterio);
            },
            listarArticulo(page,buscarP, criterioP){
                let me = this;
                this.isBusy=1;
                var url='/tienda/listarSinPaginate?id_proveedor='+me.datos.id_proveedor+'&page=' + page +'&buscar=' + buscarP + '&criterio=' + criterioP;
                axios.get(url).then(function(response){
                    me.arrayArticulo=response.data.data;
                    me.pagination={total:response.data.total,
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarArticuloBusquedaRapida(){
                let me=this;
                var url='/tienda/listarSinPaginate?id_proveedor='+me.datos.id_proveedor+'&page=' + 1 +'&buscar=' + me.buscarP + '&criterio=' + me.criterioP;
                axios.get(url).then(function(response){
                    me.arrayArticulo=response.data.data;
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
            BuscandoArticulo(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida,350)
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_articulo==id){
                        sw=true;
                    }
                }
                return sw;
            },
            encuentraEliminado(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_articulo==id && this.arrayDetalle[i].eliminado==1){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index,nuevo,id_articulo){
                let me = this;
                var cantidad_eliminado = 0;
                var total_producto = me.arrayDetalle.length;
                var cantidad_sin_eliminar = 0;
                if(nuevo == 1){
                    me.arrayDetalle.splice(index,1);
                }else{
                    me.arrayDetalle.forEach(item => {
                        if(item.eliminado == 1){
                            cantidad_eliminado++;
                        }
                    })
                    cantidad_sin_eliminar = total_producto - cantidad_eliminado;
                    if(cantidad_sin_eliminar == 1){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'No puede eliminar Todos los productos, anule la compra o agregue un nuevo producto!'
                        })
                    }else{
                        if(nuevo==1){
                            me.arrayDetalle.splice(index,1);
                        }else{
                            me.arrayDetalle.forEach( item =>{
                                if(item.id_articulo == id_articulo){
                                    item.eliminado = 1;
                                }
                            })
                        }
                    }
                }


            },
            // eliminarDetalle(index){
            //     let me = this;
            //     me.arrayDetalle.splice(index,1);

            // },
            seleccionarTiendaArticulo(data=[]){
                let me = this;
                // if(me.encuentra(data['id_articulo'])){
                //     if(me.encuentraEliminado(data['id_articulo'])){
                //         me.arrayDetalle.forEach(item => {
                //             var elim_temp = item.eliminado
                //             item.id_articulo == data['id_articulo'] ? (item.eliminado = 0, item.cantidad = 1, item.descuento = 0) : elim_temp
                //         })
                //             Swal.fire({
                //             position: 'top-end',
                //             icon: 'success',
                //             title: 'Producto agregado...',
                //             showConfirmButton: false,
                //             timer: 500
                //         });
                //     }else{
                //         Swal.fire({
                //             icon: 'error',
                //             title: 'Error...',
                //             text: 'Este articulo ya se encuentra agregado!'
                //         })
                //     }
                // }
                // else{
                    me.arrayDetalle.push({
                        id_tienda_articulo : data['id'],
                        id_articulo : data['id_articulo'],
                        id_producto : data['id_articulo'],
                        articulo : data['articulo'],
                        tienda : data['tienda'],
                        costo_compra : data['costo_compra'],
                        cantidad : 1,
                        sub_total : data['sub_total'],
                        cantidad_auxiliar : data['cantidad_auxiliar'],
                        categoria : data['categoria'],
                        tienda : data['tienda'],
                        articulo_nuevo: 1,
                        eliminar_temporal: 0,
                        eliminado: 0,
                        fecha_vecimiento :  moment().format('YYYY-MM-DD'),
                        lote: '',
                        descuento: 0
                    });
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });
                // }
            },
            cancelarCompra(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
            },
            volverCompraListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
                me.listado = 0;
                me.limpiarDatosCompra();
            },
            selectProveedor(){
                let me=this;
                var url='/proveedor/selectProveedor';
                axios.get(url).then(function(response){
                    me.arrayProveedor=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectFormaP(){
                let me=this;
                var url='/formaPago/selectFormaP';
                axios.get(url).then(function(response){
                    me.arrayFormaPago=response.data;
                    me.arrayForma2 = response.data;
                    me.arrayForma2 = me.arrayForma2.filter((item) => item.id !== 1);
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            async guardarProveedor(){
                let me = this;
                try {
                    const res = await axios.post('/proveedor/guardar',this.datos)
                     Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    me.cerrarModal();
                    me.listarProveedor(1, '', 'nombre');
                    me.registrosProveedor();
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },
            async verCompra(data=[]){
                let me = this;
                try {
                    const res = await axios.get('/compra?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio)
                    me.arrayCompra=res.data.data;
                    me.pagination={total:res.data.total,
                        current_page:res.data.current_page,
                        per_page: res.data.per_page,
                        last_page: res.data.last_page,
                        from: res.data.from,
                        to: res.data.to
                    }
                    me.listado = 2;
                    me.datos.id=data['id'];
                    me.datos.proveedor=data['proveedor'];
                    me.datos.fecha=data['fecha'];
                    me.datos.descripcion=data['descripcion'];
                    me.datos.descuento=data['descuento'];
                    me.datos.estado=data['estado'];
                    me.datos.formaPago=data['formaP'];
                    me.datos.total_efectivo=data['total_efectivo'];
                    me.datos.total_deposito=data['total_deposito'];

                    const res1 = await axios.get('/compra/permiso/detalle?id=' + data['id'])
                    me.arrayDetalle=res1.data;

                } catch (error) {
                    if(error.response.data){
                        me.errores=error.response.data.errors;
                    }
                }


                // //me.listarArticulo(me.buscarP);

                // var url='/compra?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                // axios.get(url).then(function(response){
                //     me.arrayCompra=response.data.data;
                //     me.pagination={total:response.data.total,
                //         current_page:response.data.current_page,
                //         per_page: response.data.per_page,
                //         last_page: response.data.last_page,
                //         from: response.data.from,
                //         to: response.data.to
                //     }
                // })
                // .catch(function(error){
                //     console.log(error)
                // });

                // me.listado = 2;
                // me.datos.id=data['id'];
                // me.datos.proveedor=data['proveedor'];
                // me.datos.fecha=data['fecha'];
                // me.datos.descripcion=data['descripcion'];
                // me.datos.descuento=data['descuento'];
                // me.datos.estado=data['estado'];
                // me.datos.formaPago=data['formaP'];

                // var url='/compra/permiso/detalle?id=' + data['id'];
                // axios.get(url).then(function(response){
                //     me.arrayDetalle= response.data;
                // })
                // .catch(function(error){
                //     console.log(error);
                // });
            },
            async verModificar(data=[]){
                let me = this;
                try {
                    const res = await axios.get('/compra?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio)
                    me.arrayCompra=res.data.data;
                    me.pagination={total:res.data.total,
                        current_page:res.data.current_page,
                        per_page: res.data.per_page,
                        last_page: res.data.last_page,
                        from: res.data.from,
                        to: res.data.to
                    }
                    me.selectProveedor();
                    me.selectFormaP();
                    me.selectTipoP();
                    me.listado = 3;
                    me.datos.id=data['id'];
                    me.datos.proveedor=data['proveedor'];
                    me.datos.id_proveedor=data['id_proveedor'];
                    me.datos.id_proveedor_aux=data['id_proveedor'];
                    me.datos.fecha=data['fecha'];
                    me.datos.descripcion=data['descripcion'];
                    me.datos.descuento=data['descuento'];
                    me.datos.estado=data['estado'];
                    me.datos.formaPago=data['formaP'];
                    me.datos.formaPagoAux=data['formaP'];
                    me.datos.id_forma_pago=data['id_forma_pago'];
                    me.datos.total_aux=data['total'];
                    me.datos.total_efectivo=data['total_efectivo'];
                    me.datos.total_deposito=data['total_deposito'];
                    me.datos.total_efectivo_aux=data['total_efectivo'];
                    me.datos.total_deposito_aux=data['total_deposito'];
                    me.datos.id_tipo_pago=data['id_tipo_pago'];
                    me.datos.id_tipo_pago_anterior=data['id_tipo_pago'];

                    const res1 = await axios.get('/compra/permiso/detalle?id=' + data['id'])
                    me.arrayDetalle=res1.data;
                    // me.arrayDetalle.forEach(item => {item.cantidad_auxiliar = item.cantidad})
                    me.arrayDetalle.forEach(item => {item.cantidad_auxiliar = parseFloat(item.cantidad) - parseFloat(item.stock), item.eliminar_temporal = 0, item.articulo_nuevo = 0})

                } catch (error) {
                    if(error.response.data){
                        me.errores=error.response.data.errors;
                    }
                }


                // //me.listarArticulo(me.buscarP);

                // var url='/compra?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                // axios.get(url).then(function(response){
                //     me.arrayCompra=response.data.data;
                //     me.pagination={total:response.data.total,
                //         current_page:response.data.current_page,
                //         per_page: response.data.per_page,
                //         last_page: response.data.last_page,
                //         from: response.data.from,
                //         to: response.data.to
                //     }
                // })
                // .catch(function(error){
                //     console.log(error)
                // });

                // me.listado = 2;
                // me.datos.id=data['id'];
                // me.datos.proveedor=data['proveedor'];
                // me.datos.fecha=data['fecha'];
                // me.datos.descripcion=data['descripcion'];
                // me.datos.descuento=data['descuento'];
                // me.datos.estado=data['estado'];
                // me.datos.formaPago=data['formaP'];

                // var url='/compra/permiso/detalle?id=' + data['id'];
                // axios.get(url).then(function(response){
                //     me.arrayDetalle= response.data;
                // })
                // .catch(function(error){
                //     console.log(error);
                // });
            },
            cargarPdf(id,foto) {
                let time=1000;
                this.downloadReport(time);
                axios.get('/compra/pdfCompraGeneral?id=' + id  + '&foto='+ foto,{responseType: 'blob'})
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
            guardarCompra(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe permisos agregados!'
                    })
                }
                let me = this;
                axios.post('/compra/guardar',{
                    'fecha': me.datos.fecha,
                    'descripcion': me.datos.descripcion,
                    'sub_total': me.datos.sub_total,
                    'descuento': me.datos.descuento,
                    'total': me.datos.total,
                    'id_proveedor': me.datos.id_proveedor,
                    'id_forma_pago': me.datos.id_forma_pago,
                    'detalle': me.arrayDetalle
                }).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Compra registrado exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    me.volverCompraListado();
                    me.listarCompra(1,'', 'users.name');
                    me.limpiarDatosCompra();
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            limpiarDatosCompra(){
                this.datos = {
                    id : 0,
                    id_proveedor : 0,
                    id_forma_pago : 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    descripcion : '',
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                }
            },
            validarCompra(){
                this.errorCompra = 0;
                this.errorMostrarMsjCompra = [];

                if(!this.datos.nombre) this.errorMostrarMsjCompra.push("El nombre del Compra no puede estar vacio ");
                if(this.errorMostrarMsjCompra.length) this.errorCompra=1;
                return this.errorCompra;
            },
            frmCompra(){
                this.listado = 1;
                this.selectProveedor();
                this.selectFormaP();
            },
            anularCompra(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Anular esta Compra??',
                    text: "No Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/compra/anular',{'id': id}).then(function (response) {
                        me.listarCompra(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Anulado!',
                        'Este compra se ha Anulado.',
                        'success'
                        )
                        me.inventario = 1;
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Esta compra no ha tenido cambios :)',
                    'error'
                    )
                }
                })
            },
            modificarFecha(){
                let me = this;
                //me.arrayVenta.id= id;
                //me.listado = 4;
                axios.put('/compra/modificar/fecha',this.datos).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Fecha modificado...',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    me.listado = 0;
                    me.arrayDetalle = [];
                    me.arrayArticulo = [];
                    me.datos.nombre = '';
                    me.buscarP = '';
                    me.datos.fecha = '';
                    me.limpiarDatosCompra();
                    me.listarCompra(1,'', 'users.name');
                }).catch(function(error){
                    console.log(error);
                });

            },
            modificarCantidad(){
                let me = this;
                // if(this.arrayDetalle.length==0){
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Error...',
                //         text: 'No Existe productos agregados!'
                //     })
                // }
                if(parseFloat(me.datos.total) < 0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Total no puede ser menor a 0'
                    })
                }else{
                    if(this.datos.descuento < 0){
                        Swal.fire({
                            icon: 'error',
                            title: 'Descuento no puede ser menor a 0'
                        })
                    }else{
                        if(this.arrayDetalle.find(seg=> seg.descuento < 0)){
                            Swal.fire({
                                icon: 'error',
                                title: 'Descuento no puede ser menor a 0'
                            })
                        }else{
                            if(this.arrayDetalle.find(seg=> seg.total < 0)){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Sub Total no puede ser menor a 0'
                                })
                            }else{
                                if(this.arrayDetalle.find(seg => (seg.cantidad - seg.cantidad_auxiliar < 0))){
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error...',
                                        text: 'No hay stock para el producto!'
                                    })
                                } else {
                                axios.put('/compra/modificarCantidad',{
                                    'id': me.datos.id,
                                    'total_aux': me.datos.total_aux,
                                    'total': me.datos.total,
                                    'formaPago': me.datos.formaPago,
                                    'formaPagoAux': me.datos.formaPagoAux,
                                    'total_efectivo': me.datos.total_efectivo,
                                    'total_deposito': me.datos.total_deposito,
                                    'total_efectivo_aux': me.datos.total_efectivo_aux,
                                    'total_deposito_aux': me.datos.total_deposito_aux,
                                    'sub_total': me.datos.sub_total,
                                    'detalle': me.arrayDetalle,

                                    'id_proveedor': me.datos.id_proveedor,
                                    'id_forma_pago': me.datos.id_forma_pago,
                                    'fecha': me.datos.fecha,
                                    'descripcion': me.datos.descripcion,

                                    'datosPago': me.datosPago,
                                    'id_tipo_pago': me.datos.id_tipo_pago,
                                    'id_tipo_pago_anterior': me.datos.id_tipo_pago_anterior,
                                    'descuento': me.datos.descuento,


                                }).then(function(response){
                                    me.inventario = 1;
                                    me.arrayDetalle=[];
                                    me.vista = 1;
                                    me.vista = 2;
                                    me.vista = 3;
                                    me.vista = 4;
                                    me.listarCompra(1, me.buscar, me.criterio);
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Cantidad modificada...',
                                        showConfirmButton: false,
                                        timer: 500
                                    });
                                })
                                .catch(function(error){
                                    console.log(error);
                                });
                                }
                            }
                        }
                    }
                }
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
            tipoPagoChange(){
                if(this.datos.id_tipo_pago == 1){
                    this.datos.id_forma_pago = 2;
                    this.datos.formaPago = 'Efectivo'
                }
                if(this.datos.id_tipo_pago == 2){
                    this.datos.id_forma_pago = 1;
                    this.datos.formaPago="Cuenta por Cobrar"
                }
            },
            proveedorChange(id_proveedor){
                console.log('PROVEEDOR NUEVO=',id_proveedor, ' PROVEEDOR ANTERIOR =', this.datos.id_proveedor_aux)
                if(id_proveedor != this.datos.id_proveedor_aux){
                    this.arrayDetalle.forEach(item => {

                            item.eliminar_temporal = 1

                    })
                }else{
                    this.arrayDetalle.forEach(item => {

                            item.eliminar_temporal = 0

                    })
                }
            },

            // tipoPagoChange(){
            //     if(this.datos.id_tipo_pago_anterior = 1){
            //         if(this.datos.id_tipo_pago == 1){
            //             this.datos.id_forma_pago = 2;
            //             this.datos.formaPago = 'Efectivo'
            //         }
            //         if(this.datos.id_tipo_pago == 2){
            //             this.datos.formaPago="Cuenta por Cobrar"
            //         }
            //     }else if(this.datos.id_tipo_pago_anterior = 2){
            //         if(this.datos.id_tipo_pago == 2){
            //             this.datos.formaPago="Cuenta por Cobrar"
            //         }
            //         if(this.datos.id_tipo_pago == 1){
            //             this.datos.id_forma_pago = 2;
            //             this.datos.formaPago = 'Efectivo'
            //         }
            //     }
            // },
            formaPagoChange(){
                if(this.datos.id_forma_pago == 2){
                    this.datos.formaPago = 'Efectivo'
                }else if(this.datos.id_forma_pago == 3){
                    this.datos.formaPago = 'Transferencia'
                }else if(this.datos.id_forma_pago == 4){
                    this.datos.formaPago = 'Pago por QR'
                }else if(this.datos.id_forma_pago == 5){
                    this.datos.formaPago = 'Depósito'
                }else if(this.datos.id_forma_pago == 6){
                    this.datos.formaPago = 'Mixta'
                }
            }
        },
        mounted() {
            this.listarCompra(1, this.buscar, this.criterio);
            this.listarArticulo(1,'', 'nombre_comercial');

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
