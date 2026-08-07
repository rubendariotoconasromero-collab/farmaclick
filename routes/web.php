<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\MotivoGastoController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\AjusteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\OrderServicioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CXCobrarController;
use App\Http\Controllers\CXPagarController;
use App\Http\Controllers\MiEmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TraspasoController;
use App\Http\Controllers\ArqueoCajaController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PagoCompraController;
use App\Http\Controllers\PaqueteController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\RbacController;
//use App\Http\Controllers\CotizacionController;

//Historial Clinico
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\VacunaController;
use App\Http\Controllers\ControlVacunaController;
use App\Http\Controllers\AntiparasitarioController;
use App\Http\Controllers\HistorialClinicoController;


//Reportes
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ReporteExcelController;

//Tienda Primera
use App\Http\Controllers\tiendaPrimera\VentaController1;
use App\Http\Controllers\tiendaPrimera\OrderServicioController1;
use App\Http\Controllers\tiendaPrimera\CotizacionController1;

//Tienda Segunda
use App\Http\Controllers\tiendaSegunda\VentaController2;
use App\Http\Controllers\tiendaSegunda\OrderServicioController2;
use App\Http\Controllers\tiendaSegunda\CotizacionController;

//Tienda Tercera
use App\Http\Controllers\tiendaTercera\VentaController3;
use App\Http\Controllers\tiendaTercera\OrderServicioController3;
use App\Http\Controllers\tiendaTercera\CotizacionController3;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware'=>['guest']],function(){
    Route::get('/',[LoginController::class, 'showLoginForm']);
    Route::post('/usuario',[LoginController::class, 'usuario'])->name('usuario');
});
Auth::routes();
Route::group(['middleware'=>['auth', 'rbac']],function(){
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard',[DashboardController::class,'__invoke']);
    Route::get('/dashboard/Producto',[DashboardController::class,'listarProductoMesDashboad']);
    Route::get('/dashboard/Producto/Meses',[DashboardController::class,'listarProductoMesesDashboad']);

    Route::get('/main/{path?}', function () {
        return view('contenido/contenido');
    })->where('path', '.*')->name('main');

    Route::get('/rbac/roles/{role}', [RbacController::class, 'show'])
        ->middleware('permission:users.manage,users.permissions.manage');
    Route::prefix('rbac')->middleware('permission:users.permissions.manage')->group(function () {
        Route::get('/roles', [RbacController::class, 'roles']);
        Route::post('/roles', [RbacController::class, 'store']);
        Route::put('/roles/{role}', [RbacController::class, 'update']);
        Route::put('/roles/{role}/status', [RbacController::class, 'updateStatus']);
        Route::put('/roles/{role}/permissions', [RbacController::class, 'updatePermissions']);
        Route::get('/permissions', [RbacController::class, 'permissions']);
    });

    //Rutas Cliente
    Route::get('/cliente', [ClienteController::class, 'index']);
    Route::post('/cliente/guardar', [ClienteController::class, 'guardar']);
    Route::get('/cliente/selectCliente', [ClienteController::class, 'selectCliente']);
    Route::get('/cliente/listarSinPaginate', [ClienteController::class, 'listarSinPaginate']);
    Route::get('/cliente/selectClienteId', [ClienteController::class, 'listarClienteId']);
    Route::get('/cliente/cantidad', [ClienteController::class, 'cantidadRegistros']);
    Route::put('/cliente/modificar', [ClienteController::class, 'modificar']);
    Route::put('/cliente/desactivar', [ClienteController::class, 'desactivar']);
    Route::put('/cliente/activar', [ClienteController::class, 'activar']);
    Route::get('/cliente/pdfListarClientes',[ClienteController::class, 'pdfListarClientes']);
    Route::get('/cliente_pago', [ClienteController::class, 'index_pago']);
    Route::get('/cliente_direccion', [ClienteController::class, 'direccion']);
    Route::get('/cliente_id', [ClienteController::class, 'cliente_id']);

    Route::get('/clientes/buscar', [ClienteController::class, 'buscarClientes']);
    Route::get('/clientes/{id}', [ClienteController::class, 'obtenerCliente']);
    Route::get('/clientes', [ClienteController::class, 'listarClientes']);

    //Rutas Proveedor
    Route::get('/proveedor', [ProveedorController::class, 'index']);
    Route::get('/proveedor/selectProveedor',[ProveedorController::class, 'selectProveedor']);
    Route::get('/proveedor/cantidad', [ProveedorController::class, 'cantidadRegistros']);
    Route::post('/proveedor/guardar', [ProveedorController::class, 'guardar']);
    Route::put('/proveedor/modificar', [ProveedorController::class, 'modificar']);
    Route::put('/proveedor/desactivar', [ProveedorController::class, 'desactivar']);
    Route::put('/proveedor/activar', [ProveedorController::class, 'activar']);
    Route::get('/proveedor_pago', [ProveedorController::class, 'index_pagoP']);


    //Rutas Cargo
    Route::get('/cargo', [CargoController::class, 'index']);
    Route::get('/cargo/selectCargo', [CargoController::class, 'selectCargo']);
    Route::get('/cargo/cantidad', [CargoController::class, 'cantidadRegistros']);
    Route::post('/cargo/guardar', [CargoController::class, 'guardar']);
    Route::put('/cargo/modificar', [CargoController::class, 'modificar']);
    Route::put('/cargo/desactivar', [CargoController::class, 'desactivar']);
    Route::put('/cargo/activar', [CargoController::class, 'activar']);

    //Rutas Personal
    Route::get('/personal', [PersonalController::class, 'index']);
    Route::get('/personal/cantidad', [PersonalController::class, 'cantidadRegistros']);
    Route::get('/personal/listarSinPaginate', [PersonalController::class, 'listarSinPaginate']);
    Route::post('/personal/guardar', [PersonalController::class, 'guardar']);
    Route::put('/personal/modificar', [PersonalController::class, 'modificar']);
    Route::put('/personal/desactivar', [PersonalController::class, 'desactivar']);
    Route::put('/personal/activar', [PersonalController::class, 'activar']);
    Route::get('/personal/selectPersonal', [PersonalController::class, 'selectPersonal']);
    Route::get('/personal/selectPersonalDoctor', [PersonalController::class, 'selectPersonalDoctor']);

    //Rutas Grupo
    Route::get('/grupo', [GrupoController::class, 'index']);
    Route::get('/grupo/selectGrupo', [GrupoController::class, 'selectGrupo']);
    Route::get('/grupo_listar', [GrupoController::class, 'listar']);

    //Rutas Categoria
    Route::get('/categoria', [CategoriaController::class, 'index']);
    Route::get('/categoria/cantidad', [CategoriaController::class, 'cantidadRegistros']);
    Route::get('/categoria/selectCategoria',[CategoriaController::class, 'selectCategoria']);
    Route::post('/categoria/guardar', [CategoriaController::class, 'guardar']);
    Route::put('/categoria/modificar', [CategoriaController::class, 'modificar']);
    Route::put('/categoria/desactivar', [CategoriaController::class, 'desactivar']);
    Route::put('/categoria/activar', [CategoriaController::class, 'activar']);

    //Rutas Categoria
    Route::get('/categoria', [CategoriaController::class, 'index']);
    Route::get('/categoria/cantidad', [CategoriaController::class, 'cantidadRegistros']);
    Route::get('/categoria/selectCategoria',[CategoriaController::class, 'selectCategoria']);
    Route::post('/categoria/guardar', [CategoriaController::class, 'guardar']);
    Route::put('/categoria/modificar', [CategoriaController::class, 'modificar']);
    Route::put('/categoria/desactivar', [CategoriaController::class, 'desactivar']);
    Route::put('/categoria/activar', [CategoriaController::class, 'activar']);

    //Rutas Categoria
    Route::get('/marca', [MarcaController::class, 'index']);
    Route::get('/marca/cantidad', [MarcaController::class, 'cantidadRegistros']);
    Route::get('/marca/selectMarca',[MarcaController::class, 'selectMarca']);
    Route::post('/marca/guardar', [MarcaController::class, 'guardar']);
    Route::put('/marca/modificar', [MarcaController::class, 'modificar']);
    Route::put('/marca/desactivar', [MarcaController::class, 'desactivar']);
    Route::put('/marca/activar', [MarcaController::class, 'activar']);

    //Rutas Unidad Medida
    Route::get('/unidad', [UnidadMedidaController::class, 'index']);
    Route::get('/unidad/cantidad', [UnidadMedidaController::class, 'cantidadRegistros']);
    Route::get('/unidad/selectUnidad',[UnidadMedidaController::class, 'selectUnidad']);
    Route::post('/unidad/guardar', [UnidadMedidaController::class, 'guardar']);
    Route::put('/unidad/modificar', [UnidadMedidaController::class, 'modificar']);
    Route::put('/unidad/desactivar', [UnidadMedidaController::class, 'desactivar']);
    Route::put('/unidad/activar', [UnidadMedidaController::class, 'activar']);

    //Rutas Articulo
    Route::get('/articulo', [ArticuloController::class, 'index']);
    Route::get('/articulo/contador', [ArticuloController::class, 'contador']);
    Route::get('/articuloFarmacia', [ArticuloController::class, 'indexFarmacia']);
    Route::get('/articulo2', [ArticuloController::class, 'index2']);
    Route::get('/articulo/selectPrecio', [ArticuloController::class, 'selectPrecio']);
    Route::get('/articulo/listarSinPaginate', [ArticuloController::class, 'listarSinPaginate']);
    Route::get('/articulo/listarSinPaginate2', [ArticuloController::class, 'listarSinPaginate2']);
    Route::get('/articulo/cantidad', [ArticuloController::class, 'cantidadRegistros']);
    Route::post('/articulo/guardar', [ArticuloController::class, 'guardar']);
    Route::post('/articulo/guardarServicio', [ArticuloController::class, 'guardarServicio']);
    Route::put('/articulo/modificar', [ArticuloController::class, 'modificar']);
    Route::put('/articulo/modificarServicio', [ArticuloController::class, 'modificarServicio']);
    Route::put('/articulo/desactivar', [ArticuloController::class, 'desactivar']);
    Route::put('/articulo/activar', [ArticuloController::class, 'activar']);
    Route::get('/articulo/listarSinPaginate2Producto', [ArticuloController::class, 'listarSinPaginate2Producto']);
    Route::get('/articulo/listarSinPaginate2Servicio', [ArticuloController::class, 'listarSinPaginate2Servicio']);
    Route::get('/articulo/cantidadProducto', [ArticuloController::class, 'cantidadRegistrosProductos']);
    Route::get('/articulo/cantidadServicio', [ArticuloController::class, 'cantidadRegistrosServicios']);
    Route::get('/articulo/ultimo/id', [ArticuloController::class, 'ultimo_id_articulo']);
    Route::get('/articulo/detalleLote', [ArticuloController::class, 'detalleLote']);
    Route::get('/articulo/detalleLote/principal', [ArticuloController::class, 'detalleLotePrincipal']);
    Route::get('/articulo/selectUnitario', [ArticuloController::class, 'selectUnitario']);
    Route::get('/articulo/selectArticulo', [ArticuloController::class, 'selectArticulo']);




    //Rutas Tienda
    Route::get('/tienda', [TiendaController::class, 'index']);
    Route::put('/tienda/modificar', [TiendaController::class, 'modificar']);
    Route::get('/tienda/selectTienda', [TiendaController::class, 'selectTienda']);
    Route::get('/tienda/selectTienda2', [TiendaController::class, 'selectTienda2']);
    Route::get('/tienda/inventario', [TiendaController::class, 'inventario']);
    Route::get('/tienda/listarSinPaginate', [TiendaController::class, 'listarSinPaginate']);
    Route::get('/tienda/listarSinPaginateLote', [TiendaController::class, 'listarSinPaginateLote']);
    Route::get('/tienda/listarSinPaginateAjuste', [TiendaController::class, 'listarSinPaginateAjuste']);
    Route::get('/tienda/listarSinPaginateVenta', [TiendaController::class, 'listarSinPaginateVenta']);
    Route::get('/tienda/listarSinPaginateInventario', [TiendaController::class, 'listarSinPaginateInventario']);
    Route::get('/tienda/listarSinPaginate2', [TiendaController::class, 'listarSinPaginate2']);
    Route::get('/tienda/listarSinPaginateP', [TiendaController::class, 'listarSinPaginateP']);
    Route::get('/tienda/producto', [TiendaController::class, 'listarOrdenProducto']);
    Route::get('/tienda/servicio', [TiendaController::class, 'listarOrdenServicio']);
    Route::post('/tienda/articulo/guardar', [TiendaController::class, 'guardarArticulo']);
    Route::get('/tienda/detalle/articulo', [TiendaController::class, 'detalleArticuloTienda']);
    Route::get('/tienda/detalle/articulo_producto', [TiendaController::class, 'detalleArticuloTiendaProducto']);
    Route::get('/tienda/detalle/articulo_servicio', [TiendaController::class, 'detalleArticuloTiendaServicio']);
    Route::get('/articulo/selectProveedorB', [TiendaController::class, 'selectProveedorB']);


    //Rutas Compra
    Route::get('/compra', [CompraController::class, 'index']);
    Route::get('/compra/permiso/detalle', [CompraController::class, 'detalleCompra']);
    Route::get('/compra/obtenerCabecera', [CompraController::class, 'obtenerCabecera']);
    Route::post('/compra/guardar', [CompraController::class, 'guardar']);
    Route::put('/compra/anular', [CompraController::class, 'anular']);
    Route::put('/compra/modificar/fecha', [CompraController::class, 'modificar']);
    Route::put('/compra/modificarCantidad', [CompraController::class, 'modificarCantidad']);
    //Reportes Compras
    Route::get('/compra/pdfCompra',[CompraController::class, 'pdfCompras']);
    Route::get('/compra/pdfCompraGeneral',[CompraController::class, 'pdfComprasGeneral']);
    Route::get('/compra/pdfCompraGeneral2',[CompraController::class, 'pdfComprasGeneral2']);
    Route::get('/compra/{id}/nota/{formato}', [CompraController::class, 'notaCompra'])
        ->where(['id' => '[0-9]+', 'formato' => 'carta|ticket']);
    Route::get('/notacompra/montoT', [CompraController::class, 'montoT']);

    Route::get('/compra/CompraArqueoEfectivo', [CompraController::class, 'CompraArqueoEfectivo']);
    Route::get('/compra/CompraArqueoDeposito', [CompraController::class, 'CompraArqueoDeposito']);


    //Rutas MotivoGasto
    Route::get('/motivo_gasto', [MotivoGastoController::class, 'index']);
    Route::get('/motivo_gasto/cantidad', [MotivoGastoController::class, 'cantidadRegistros']);
    Route::get('/motivo_gasto/selectMotivoGasto',[MotivoGastoController::class, 'selectMotivoGasto']);
    Route::post('/motivo_gasto/guardar', [MotivoGastoController::class, 'guardar']);
    Route::put('/motivo_gasto/modificar', [MotivoGastoController::class, 'modificar']);

    //Rutas MotivoGasto
    Route::get('/gasto', [GastoController::class, 'index']);
    Route::get('/gasto/cantidad', [GastoController::class, 'cantidadRegistros']);
    Route::post('/gasto/guardar', [GastoController::class, 'guardar']);
    Route::put('/gasto/modificar', [GastoController::class, 'modificar']);

    Route::get('/gasto/GastoArqueoEfectivo', [GastoController::class, 'GastoArqueoEfectivo']);
    Route::get('/gasto/GastoArqueoDeposito', [GastoController::class, 'GastoArqueoDeposito']);


    //Rutas FormaPago

    Route::get('/formaPago', [FormaPagoController::class, 'index']);
    Route::get('/formaPago/selectFormaP',[FormaPagoController::class, 'selectFormaP']);
    Route::get('/formaPago/selectFormaPago',[FormaPagoController::class, 'selectFormaPago']);
    Route::get('/formaPago/selectFormaPago2',[FormaPagoController::class, 'selectFormaPago2']);

    //Rutas TipoPago
     Route::get('/tipoPago/selectTipoP',[TipoPagoController::class, 'selectTipoP']);

     //Rutas Ajuste
     Route::get('/ajuste',[AjusteController::class, 'index']);
     Route::get('/ajuste/lote',[AjusteController::class, 'LoteStock']);
     Route::get('/ajuste/producto',[AjusteController::class, 'indexProducto']);
     Route::get('/ajuste/producto2',[AjusteController::class, 'indexProducto2']);
     Route::get('/ajuste/cantidad', [AjusteController::class, 'cantidadRegistros']);
     Route::get('/motivo/selectMotivo', [AjusteController::class, 'selectMotivo']);
     Route::post('/ajuste/guardar', [AjusteController::class, 'guardar']);

     //Rutas Venta
    Route::get('/venta', [VentaController::class, 'index']);
    Route::get('/ventaServicio', [VentaController::class, 'indexServicio']);
    Route::get('/venta/permiso/detalle', [VentaController::class, 'detalleVenta']);
    Route::get('/venta/obtenerCabecera', [VentaController::class, 'obtenerCabecera']);
    Route::post('/venta/guardar', [VentaController::class, 'guardar']);
    Route::put('/venta/anular', [VentaController::class, 'anular']);
    Route::get('/ventaPago', [VentaController::class, 'indexPago']);
    Route::get('/ventaServicioPago', [VentaController::class, 'indexServicioPago']);
    Route::get('/notaventa/montoT', [VentaController::class, 'montoT']);
    Route::get('/notaventa/montoD', [VentaController::class, 'montoD']);
    Route::get('/notaventa/montoS', [VentaController::class, 'montoS']);

    //Rutas Cotizacion

    Route::get('/cotizacion', [CotizacionController::class, 'index']);
    Route::get('/ventaServicio', [CotizacionController::class, 'indexServicio']);
    Route::get('/cotizacion/permiso/detalle', [CotizacionController::class, 'detalleCotizacion']);
    Route::get('/cotizacion/obtenerCabecera', [CotizacionController::class, 'obtenerCabecera']);
    Route::post('/cotizacion/guardar', [CotizacionController::class, 'guardar']);
    Route::put('/cotizacion/anular', [CotizacionController::class, 'anular']);
    //Route::get('/ventaPago', [CotizacionController::class, 'indexPago']);
    //Route::get('/ventaServicioPago', [CotizacionController::class, 'indexServicioPago']);
    // Route::get('/notaventa/montoT', [CotizacionController::class, 'montoT']);
    Route::put('/cotizacion/modificar', [CotizacionController::class, 'modificar']);
    Route::put('/cotizacion/anular', [CotizacionController::class, 'anular']);
    Route::get('/cotizacion/pdfProforma',[CotizacionController::class, 'pdfCotizacion']);
    Route::get('/cotizacion/pdfCotizacionSimple',[CotizacionController::class, 'pdfCotizacionSimple']);


    //Reportes Ventas
    Route::get('/venta/pdfVentas',[VentaController::class, 'pdfVentas']);
    Route::get('/venta/pdfVentasGeneral',[VentaController::class, 'pdfVentasGeneral']);
    Route::get('/venta/pdfVentasGeneral2',[VentaController::class, 'pdfVentasGeneral2']);

    //Rutas Pago
    Route::get('/pagos', [PagoController::class, 'index']);
    Route::get('/pago_listar_articulo', [PagoController::class, 'indexPagoArticulo']);
    Route::get('/pago_listar_servicio', [PagoController::class, 'indexPagoArticulo']);
    Route::get('/pago_listar_articulo/tienda1', [PagoController::class, 'indexPagoTiendaArticulo1']);
    Route::get('/pago_listar_articulo/tienda2', [PagoController::class, 'indexPagoTiendaArticulo2']);
    Route::get('/pago_listar_articulo/tienda3', [PagoController::class, 'indexPagoTiendaArticulo3']);
    Route::get('/pago_listar_servicio/tienda1', [PagoController::class, 'indexPagoTiendaServicio1']);
    Route::get('/pago_listar_servicio/tienda2', [PagoController::class, 'indexPagoTiendaServicio2']);
    Route::get('/pago_listar_servicio/tienda3', [PagoController::class, 'indexPagoTiendaServicio3']);
    Route::get('/pago_listar_articulo/detalle/tienda1', [PagoController::class, 'indexPagoTiendaArticuloDetalle1']);
    Route::get('/pago_listar_articulo/detalle_servicio/tienda1', [PagoController::class, 'indexPagoTiendaArticuloDetalleServicio1']);
    Route::post('/pagos/guardar', [PagoController::class, 'guardar']);
    Route::get('/pago_venta', [PagoController::class, 'index2']);
    Route::get('/pagos_cliente', [PagoController::class, 'pagos_cliente']);


    //Rutas Pago Compra
    Route::get('/pagos_proveedor', [PagoCompraController::class, 'pagos_proveedor']);
    Route::get('/pago_compra', [PagoCompraController::class, 'index2C']);


    //Rutas TipoPago
    Route::get('/tipoPago/selectTipoP',[TipoPagoController::class, 'selectTipoP']);

     //Rutas Orden Servicio
    Route::get('/servicio', [OrderServicioController::class, 'index']);
    Route::get('/servicio/permiso/detalle', [OrderServicioController::class, 'detalleOrdenServicio']);
    Route::get('/servicio/obtenerCabecera', [OrderServicioController::class, 'obtenerCabecera']);
    Route::post('/servicio/guardar', [OrderServicioController::class, 'guardar']);
    Route::put('/servicio/modificar', [OrderServicioController::class, 'modificar']);
    Route::put('/servicio/anular', [OrderServicioController::class, 'anular']);
    Route::get('/servicio/listarOrdenSinPaginate', [OrderServicioController::class, 'listarOrdenSinPaginate']);

    //Rutas Paquete
    Route::get('/paquete', [PaqueteController::class, 'index']);
    Route::put('/mostrar', [PaqueteController::class, 'show']);
    Route::get('/paquete/permiso/detalle', [PaqueteController::class, 'detallePaquete']);
    Route::get('/paquete/permiso/detalle/venta', [PaqueteController::class, 'detalleVentaPaquete']);
    Route::get('/paquete/obtenerCabecera', [PaqueteController::class, 'obtenerCabecera']);
    Route::post('/paquete/guardar', [PaqueteController::class, 'guardar']);
    Route::put('/paquete/modificar', [PaqueteController::class, 'modificar']);
    Route::put('/paquete/desactivar', [PaqueteController::class, 'desactivar']);
    Route::put('/paquete/activar', [PaqueteController::class, 'activar']);
    Route::get('/paquete/listarOrdenSinPaginate', [PaqueteController::class, 'listarOrdenSinPaginate']);
    Route::get('/paquete/listarSinPaginate', [PaqueteController::class, 'listarSinPaginate']);

    //Reportes Servicios
    Route::get('/servicio/pdfServicios',[OrderServicioController::class, 'pdfServicios']);
    Route::get('/servicio/pdfOrdenServiciosGeneral',[OrderServicioController::class, 'pdfOrdenServiciosGeneral']);
    Route::get('/servicio/pdfOrdenServiciosGeneral2',[OrderServicioController::class, 'pdfOrdenServiciosGeneral2']);

    //Rutas CXCobrar
    Route::get('/c_x_cobrar', [CXCobrarController::class, 'index']);
    Route::post('/c_x_cobrar/guardar', [CXCobrarController::class, 'guardar']);
    Route::get('/detalle_pago_credito', [CXCobrarController::class, 'detallePagoCredito']);

    Route::get('/c_x_cobrar/VentaCobrarArqueoEfectivo', [CXCobrarController::class, 'VentaCobrarArqueoEfectivo']);
    Route::get('/c_x_cobrar/VentaCobrarArqueoDeposito', [CXCobrarController::class, 'VentaCobrarArqueoDeposito']);


    //Rutas CXPagar
    Route::get('/c_x_pagar', [CXPagarController::class, 'index']);
    Route::post('/c_x_pagar/guardar', [CXPagarController::class, 'guardar']);
    Route::get('/detalle_pago_creditoC', [CXPagarController::class, 'detallePagoCreditoC']);
    Route::get('/c_x_pagar/CompraCobrarArqueoEfectivo', [CXPagarController::class, 'CompraCobrarArqueoEfectivo']);
    Route::get('/c_x_pagar/CompraCobrarArqueoDeposito', [CXPagarController::class, 'CompraCobrarArqueoDeposito']);


    Route::get('/mi_empresa', [MiEmpresaController::class, 'index']);
    Route::get('/mi_empresa/cantidad', [MiEmpresaController::class, 'cantidadRegistros']);
    Route::put('/mi_empresa/modificar', [MiEmpresaController::class, 'modificar']);

    Route::get('/mi_empresa/datos', [MiEmpresaController::class, 'indexEmpresa']);
    Route::get('/usuario_auth', [UsuarioController::class, 'usuario']);


    //Rutas Usuario
    Route::get('/usuario', [UsuarioController::class, 'index']);
    Route::get('/usuario/grupo_usuario', [UsuarioController::class, 'listarGrupoUsuario']);
    Route::post('/usuario/guardar', [UsuarioController::class, 'guardar']);
    Route::put('/usuario/modificar', [UsuarioController::class, 'modificar']);
    Route::put('/usuario/desactivar',[UsuarioController::class, 'desactivar']);
    Route::put('/usuario/activar',[UsuarioController::class, 'activar']);
    Route::get('/usuario_maximo_id', [UsuarioController::class, 'maximoId']);
    Route::get('/usuario_id', [UsuarioController::class, 'usuarioId']);
    Route::get('/notaventa/montoUsuario',[UsuarioController::class, 'montoUsuario']);
    Route::get('/notaventa/montoUsuario/Cajero',[UsuarioController::class, 'montoCajeros']);
    Route::get('/usuario/selectUsuario',[UsuarioController::class, 'selectUsuario']);



    //Rutas Traspaso
    Route::get('/traspaso', [TraspasoController::class, 'index']);
    Route::post('/traspaso/guardar', [TraspasoController::class, 'guardar']);
    Route::get('/traspaso/permiso/detalle', [TraspasoController::class, 'detalleTraspaso']);
    //Rutas Reporte Traspaso
    Route::get('/traspaso/pdfTraspaso', [TraspasoController::class, 'pdfTraspaso']);

    //Rutas Arqueo de Caja
    Route::get('/arqueo_caja', [ArqueoCajaController::class, 'index']);
    Route::post('/arqueo_caja/guardar', [ArqueoCajaController::class, 'guardar']);
    Route::get('/arqueo_caja/estado_caja', [ArqueoCajaController::class, 'estadoArqueoCaja']);
    Route::get('/arqueo/estado', [ArqueoCajaController::class, 'estadoCaja']);

    //Rutas Caja
    Route::get('/arqueo', [ArqueoCajaController::class, 'index']);
    Route::get('/arqueo_usuario', [ArqueoCajaController::class, 'indexArqueo']);
    Route::get('/arqueo2', [ArqueoCajaController::class, 'index2']);
    Route::get('/importacion/cantidad', [ArqueoCajaController::class, 'cantidadRegistros']);
    Route::get('/arqueo/resumen', [ArqueoCajaController::class, 'resumenArqueo']);
    Route::post('/arqueo/guardar', [ArqueoCajaController::class, 'guardar']);
    Route::put('/arqueo/modificar', [ArqueoCajaController::class, 'modificar']);
    Route::post('/arqueo/actualizar_ventas', [ArqueoCajaController::class, 'actualizarVentasAlCerrarCaja']);

    //Rutas lote
    Route::post('/lote/guardar', [LoteController::class, 'guardar']);
    Route::put('/lote/anular', [LoteController::class, 'anular']);


    //HISTORIAL CLINICO

    //Rutas Cargo
    Route::get('/animal', [AnimalController::class, 'index']);
    Route::get('/animal/selectAnimal', [AnimalController::class, 'selectAnimal']);
    Route::get('/animal/cantidad', [AnimalController::class, 'cantidadRegistros']);
    Route::post('/animal/guardar', [AnimalController::class, 'guardar']);
    Route::put('/animal/modificar', [AnimalController::class, 'modificar']);
    Route::put('/animal/desactivar', [AnimalController::class, 'desactivar']);
    Route::put('/animal/activar', [AnimalController::class, 'activar']);

    //Rutas Paciente
    Route::get('/paciente', [PacienteController::class, 'index']);
    Route::get('/paciente/selectPaciente', [PacienteController::class, 'selectPaciente']);
    Route::get('/paciente/selectPaciente2', [PacienteController::class, 'selectPaciente2']);
    Route::get('/paciente/selectPaciente3', [PacienteController::class, 'selectPaciente3']);
    Route::get('/paciente/cantidad', [PacienteController::class, 'cantidadRegistros']);
    Route::post('/paciente/guardar', [PacienteController::class, 'guardar']);
    Route::put('/paciente/modificar', [PacienteController::class, 'modificar']);
    Route::put('/paciente/desactivar', [PacienteController::class, 'desactivar']);
    Route::put('/paciente/activar', [PacienteController::class, 'activar']);


    //Rutas Paciente
    Route::get('/vacuna', [VacunaController::class, 'index']);
    Route::get('/vacuna/selectPaciente', [VacunaController::class, 'selectPaciente']);
    Route::get('/vacuna/cantidad', [VacunaController::class, 'cantidadRegistros']);
    Route::post('/vacuna/guardar', [VacunaController::class, 'guardar']);
    Route::put('/vacuna/modificar', [VacunaController::class, 'modificar']);
    Route::put('/vacuna/desactivar', [VacunaController::class, 'desactivar']);
    Route::put('/vacuna/activar', [VacunaController::class, 'activar']);
    Route::get('/vacuna/permiso/detalle', [VacunaController::class, 'detalleVacuna']);
    Route::get('/tienda/listarSinPaginateControlVacuna', [VacunaController::class, 'listarSinPaginateControlVacuna']);
    Route::get('/tienda/listarSinPaginateControlAntiparasitario', [VacunaController::class, 'listarSinPaginateAntiparasitario']);

    //Rutas Control Vacuna
    Route::get('/contro/vacuna', [ControlVacunaController::class, 'index']);
    Route::post('/contro/vacuna/guardar', [ControlVacunaController::class, 'guardar']);
    Route::put('/contro/vacuna/modificar', [ControlVacunaController::class, 'modificar']);
    Route::get('/contro/vacuna/permiso/detalle', [ControlVacunaController::class, 'detalleControlVacuna']);
    Route::put('/control/vacuna/estado', [ControlVacunaController::class, 'ActEstado']);
    Route::put('/control/vacuna/modificar', [ControlVacunaController::class, 'modificar']);
    Route::get('/controlvacuna//pdfVacuna',[ControlVacunaController::class, 'pdfVacuna']);


    //Rutas Antiparasitario
    Route::get('/antiparasitario', [AntiparasitarioController::class, 'index']);
    Route::post('/antiparasitario/guardar', [AntiparasitarioController::class, 'guardar']);
    Route::put('/antiparasitario/modificar', [AntiparasitarioController::class, 'modificar']);
    Route::get('/antiparasitario/permiso/detalle', [AntiparasitarioController::class, 'detalleAntiparasitario']);
    Route::put('/antiparasitario/estado', [AntiparasitarioController::class, 'ActEstado']);
    Route::put('/antiparasitario/modificar', [AntiparasitarioController::class, 'modificar']);
    Route::get('/antiparasitario//pdfAntiparacitario',[AntiparasitarioController::class, 'pdfAntiparacitario']);


    //Rutas Historia Clinica
    //Route::get('/antiparasitario', [AntiparasitarioController::class, 'index']);
    Route::get('/historial', [HistorialClinicoController::class, 'index']);
    Route::get('/nro_historia', [HistorialClinicoController::class, 'nro_historia']);
    Route::get('/historial/detalle', [HistorialClinicoController::class, 'historia_clinica']);
    Route::post('/historia/clinica/guardar', [HistorialClinicoController::class, 'guardar']);
    Route::get('/historia/clinica/ultimo', [HistorialClinicoController::class, 'ultimo_id']);
    Route::get('/detalle/historia', [HistorialClinicoController::class, 'detalle_historia']);
    Route::put('/historia/clinica/modificar', [HistorialClinicoController::class, 'modificar']);
    Route::get('/historia/pdfHistoria',[HistorialClinicoController::class, 'pdfHistoria']);
    Route::get('/historia/pdfHistoriaActualizar',[HistorialClinicoController::class, 'pdfHistoriaActualizar']);













        //TIENDA PRIMERA
        //TIENDAS
        Route::get('/tienda/producto_tienda1', [TiendaController::class, 'listarOrdenProductoTienda1']);
        Route::get('/tienda/listarSinPaginate2/tienda1', [TiendaController::class, 'listarSinPaginate2tienda1']);
        Route::get('/tienda/listarSinPaginateVacuna', [TiendaController::class, 'listarSinPaginateVacuna']);
        Route::get('/tienda/listarSinPaginateVacuna2', [TiendaController::class, 'listarSinPaginateVacuna2']);
        Route::get('/tienda/listarSinPaginateAntiparasitario', [TiendaController::class, 'listarSinPaginateAntiparasitario']);
        Route::get('/tienda/servicio_tienda1', [TiendaController::class, 'listarOrdenServicioTienda1']);

        //Rutas Venta
        Route::get('/venta_tienda1', [VentaController1::class, 'index']);
        Route::get('/cantidadProducto', [VentaController1::class, 'cantidadProducto']);
        Route::get('/cantidadProductoUsuario', [VentaController1::class, 'cantidadProductoUsuario']);
        Route::get('/cantidadProductoFecha', [VentaController1::class, 'cantidadProductoFecha']);
        Route::get('/venta_tienda1/contado', [VentaController1::class, 'indexContado']);
        Route::get('/venta_tienda1/credito', [VentaController1::class, 'indexCredito']);
        Route::get('/ventaServicio_tienda1', [VentaController1::class, 'indexServicio']);
        Route::get('/ventaControl_tienda1', [VentaController1::class, 'indexControl']);
        Route::get('/ventaAntiparasitario_tienda1', [VentaController1::class, 'indexAntiparasitario']);
        Route::get('/ventaCotizacion_tienda1', [VentaController1::class, 'indexCotizacion']);
        Route::get('/venta/permiso/detalle_tienda1/', [VentaController1::class, 'detalleVenta']);
        Route::get('/venta/obtenerCabecera1_tienda1', [VentaController1::class, 'obtenerCabecera']);
        Route::post('/venta/guardar_tienda1', [VentaController1::class, 'guardar']);
        Route::put('/venta/anular_tienda1', [VentaController1::class, 'anular']);
        Route::post('/venta/guardar_tienda1/cotizacion', [VentaController1::class, 'guardarCotizacion']);
        //Reportes Ventas
        Route::get('/venta/pdf_ventas_tienda1',[VentaController1::class, 'pdfVentas']);
        Route::get('/venta/pdf_ventas_general_tienda1',[VentaController1::class, 'pdfVentasGeneral']);
        Route::get('/venta/tienda1/{id}/nota/{formato}', [VentaController1::class, 'notaVenta'])
            ->where(['id' => '[0-9]+', 'formato' => 'carta|ticket']);
        Route::put('/venta/modificar', [VentaController1::class, 'modificar']);
        Route::put('/venta/modificar/antiparasitario', [VentaController1::class, 'modificarAntiparasitario']);
        Route::get('/historialCliente', [VentaController1::class, 'historialCliente']);
        Route::put('/detalle/eliminar', [VentaController1::class, 'eliminarDetalle']);
        Route::put('/venta/modificarVenta', [VentaController1::class, 'modificarVenta']);

        Route::get('/venta_tienda1/VentaArqueoEfectivo', [VentaController1::class, 'VentaArqueoEfectivo']);
        Route::get('/venta_tienda1/VentaArqueoDeposito', [VentaController1::class, 'VentaArqueoDeposito']);



        //Rutas Orden Servicio
        Route::get('/servicio_tienda1', [OrderServicioController1::class, 'index']);
        Route::get('/servicio/permiso/detalle_tienda1/', [OrderServicioController1::class, 'detalleOrdenServicio']);
        Route::get('/servicio/obtenerCabecera_tienda1', [OrderServicioController1::class, 'obtenerCabecera']);
        Route::post('/servicio/guardar_tienda1', [OrderServicioController1::class, 'guardar']);
        Route::put('/servicio/modificar_tienda1', [OrderServicioController1::class, 'modificar']);
        Route::put('/servicio/anular_tienda1', [OrderServicioController1::class, 'anular']);
        Route::get('/servicio/listarOrdenSinPaginate_tienda1', [OrderServicioController1::class, 'listarOrdenSinPaginate']);
        //Reportes Servicios
        Route::get('/servicio/pdf_servicios_tienda1',[OrderServicioController1::class, 'pdfServicios']);
        Route::get('/servicio/pdf_servicios_general_tienda_1',[OrderServicioController1::class, 'pdfServiciosGeneral']);

        //Rutas Cotizacion
        Route::get('/cotizacion1', [CotizacionController1::class, 'index']);
        Route::get('/ventaServicio1', [CotizacionController1::class, 'indexServicio']);
        Route::get('/cotizacion/permiso/detalle1', [CotizacionController1::class, 'detalleCotizacion']);
        Route::get('/cotizacion/obtenerCabecera1', [CotizacionController1::class, 'obtenerCabecera']);
        Route::post('/cotizacion/guardar1', [CotizacionController1::class, 'guardar']);
        Route::put('/cotizacion/anular1', [CotizacionController1::class, 'anular']);
        Route::get('/ventaPago1', [CotizacionController1::class, 'indexPago']);
        Route::get('/ventaServicioPago1', [CotizacionController1::class, 'indexServicioPago']);
        // Route::get('/notaventa/montoT', [CotizacionController::class, 'montoT']);
        Route::put('/cotizacion/modificar1', [CotizacionController1::class, 'modificar']);
        Route::put('/cotizacion/anular1', [CotizacionController1::class, 'anular']);
        Route::get('/cotizacion/pdfProforma1',[CotizacionController1::class, 'pdfCotizacion']);
        Route::get('/cotizacion/pdfCotizacionSimple1',[CotizacionController1::class, 'pdfCotizacionSimple']);
        Route::get('/cotizacion/pdfCotizacionReporte1',[CotizacionController1::class, 'pdfCotizacionReporte']);





        //TIENDA SEGUNDA
        //TIENDAS
        Route::get('/tienda/producto_tienda2', [TiendaController::class, 'listarOrdenProductoTienda2']);
        Route::get('/tienda/listarSinPaginate2/tienda2', [TiendaController::class, 'listarSinPaginate2tienda2']);
        Route::get('/tienda/servicio_tienda2', [TiendaController::class, 'listarOrdenServicioTienda2']);

        //Rutas Venta
        Route::get('/venta_tienda2', [VentaController2::class, 'index']);
        Route::get('/ventaServicio_tienda2', [VentaController2::class, 'indexServicio']);
        Route::get('/ventaCotizacion_tienda2', [VentaController2::class, 'indexCotizacion']);
        Route::get('/venta/permiso/detalle_tienda2/', [VentaController2::class, 'detalleVenta']);
        Route::get('/venta/obtenerCabecera1_tienda2', [VentaController2::class, 'obtenerCabecera']);
        Route::post('/venta/guardar_tienda2', [VentaController2::class, 'guardar']);
        Route::post('/venta/guardar_tienda2/cotizacion', [VentaController2::class, 'guardarCotizacion']);
        Route::put('/venta/anular_tienda2', [VentaController2::class, 'anular']);
        //Reportes Ventas
        Route::get('/venta/pdf_ventas_tienda2',[VentaController2::class, 'pdfVentas']);
        Route::get('/venta/pdf_ventas_general_tienda2',[VentaController2::class, 'pdfVentasGeneral']);

        //Rutas Orden Servicio
        Route::get('/servicio_tienda2', [OrderServicioController2::class, 'index']);
        Route::get('/servicio/permiso/detalle_tienda2/', [OrderServicioController2::class, 'detalleOrdenServicio']);
        Route::get('/servicio/obtenerCabecera_tienda2', [OrderServicioController2::class, 'obtenerCabecera']);
        Route::post('/servicio/guardar_tienda2', [OrderServicioController2::class, 'guardar']);
        Route::put('/servicio/modificar_tienda2', [OrderServicioController2::class, 'modificar']);
        Route::put('/servicio/anular_tienda2', [OrderServicioController2::class, 'anular']);
        Route::get('/servicio/listarOrdenSinPaginate_tienda2', [OrderServicioController2::class, 'listarOrdenSinPaginate']);
        //Reportes Servicios
        Route::get('/servicio/pdf_servicios_tienda2',[OrderServicioController2::class, 'pdfServicios']);
        Route::get('/servicio/pdf_servicios_general_tienda_2',[OrderServicioController2::class, 'pdfServiciosGeneral']);

        //Rutas Cotizacion
        Route::get('/cotizacion', [CotizacionController::class, 'index']);
        Route::get('/ventaServicio', [CotizacionController::class, 'indexServicio']);
        Route::get('/cotizacion/permiso/detalle', [CotizacionController::class, 'detalleCotizacion']);
        Route::get('/cotizacion/obtenerCabecera', [CotizacionController::class, 'obtenerCabecera']);
        Route::post('/cotizacion/guardar', [CotizacionController::class, 'guardar']);
        Route::put('/cotizacion/anular', [CotizacionController::class, 'anular']);
        //Route::get('/ventaPago', [CotizacionController::class, 'indexPago']);
        //Route::get('/ventaServicioPago', [CotizacionController::class, 'indexServicioPago']);
        // Route::get('/notaventa/montoT', [CotizacionController::class, 'montoT']);
        Route::put('/cotizacion/modificar', [CotizacionController::class, 'modificar']);
        Route::put('/cotizacion/anular', [CotizacionController::class, 'anular']);
        Route::get('/cotizacion/pdfProforma',[CotizacionController::class, 'pdfCotizacion']);
        Route::get('/cotizacion/pdfCotizacionSimple',[CotizacionController1::class, 'pdfCotizacionSimple']);
        Route::get('/cotizacion/pdfCotizacionReporte',[CotizacionController1::class, 'pdfCotizacionReporte']);



        //TIENDA TERCERA
        //TIENDAS
        Route::get('/tienda/producto_tienda3', [TiendaController::class, 'listarOrdenProductoTienda3']);
        Route::get('/tienda/listarSinPaginate2/tienda3', [TiendaController::class, 'listarSinPaginate2tienda3']);
        Route::get('/tienda/servicio_tienda3', [TiendaController::class, 'listarOrdenServicioTienda3']);

        //Rutas Venta
        Route::get('/venta_tienda3', [VentaController3::class, 'index']);
        Route::get('/ventaServicio_tienda3', [VentaController3::class, 'indexServicio']);
        Route::get('/ventaCotizacion_tienda3', [VentaController3::class, 'indexCotizacion']);
        Route::get('/venta/permiso/detalle_tienda3/', [VentaController3::class, 'detalleVenta']);
        Route::get('/venta/obtenerCabecera1_tienda3', [VentaController3::class, 'obtenerCabecera']);
        Route::post('/venta/guardar_tienda3', [VentaController3::class, 'guardar']);
        Route::put('/venta/anular_tienda3', [VentaController3::class, 'anular']);
        Route::put('/venta/anular3', [VentaController3::class, 'anular']);
        Route::post('/venta/guardar_tienda3/cotizacion', [VentaController3::class, 'guardarCotizacion']);

        //Reportes Ventas
        Route::get('/venta/pdf_ventas_tienda3',[VentaController3::class, 'pdfVentas']);
        Route::get('/venta/pdf_ventas_general_tienda3',[VentaController3::class, 'pdfVentasGeneral']);

        //Rutas Orden Servicio
        Route::get('/servicio_tienda3', [OrderServicioController3::class, 'index']);
        Route::get('/servicio/permiso/detalle_tienda3/', [OrderServicioController3::class, 'detalleOrdenServicio']);
        Route::get('/servicio/obtenerCabecera_tienda3', [OrderServicioController3::class, 'obtenerCabecera']);
        Route::post('/servicio/guardar_tienda3', [OrderServicioController3::class, 'guardar']);
        Route::put('/servicio/modificar_tienda3', [OrderServicioController3::class, 'modificar']);
        Route::put('/servicio/anular_tienda3', [OrderServicioController3::class, 'anular']);
        Route::get('/servicio/listarOrdenSinPaginate_tienda3', [OrderServicioController3::class, 'listarOrdenSinPaginate']);
        //Reportes Servicios
        Route::get('/servicio/pdf_servicios_tienda3',[OrderServicioController3::class, 'pdfServicios']);
        Route::get('/servicio/pdf_servicios_general_tienda_3',[OrderServicioController3::class, 'pdfServiciosGeneral']);

        //Rutas Cotizacion
        Route::get('/cotizacion3', [CotizacionController3::class, 'index']);
        Route::get('/ventaServicio3', [CotizacionController3::class, 'indexServicio']);
        Route::get('/cotizacion/permiso/detalle3', [CotizacionController3::class, 'detalleCotizacion']);
        Route::get('/cotizacion/obtenerCabecera3', [CotizacionController3::class, 'obtenerCabecera']);
        Route::post('/cotizacion/guardar3', [CotizacionController3::class, 'guardar']);
        Route::put('/cotizacion/anular3', [CotizacionController3::class, 'anular']);
        Route::get('/ventaPago3', [CotizacionController3::class, 'indexPago']);
        Route::get('/ventaServicioPago3', [CotizacionController3::class, 'indexServicioPago']);
        // Route::get('/notaventa/montoT', [CotizacionController::class, 'montoT']);
        Route::put('/cotizacion/modificar3', [CotizacionController3::class, 'modificar']);
        Route::put('/cotizacion/anular3', [CotizacionController3::class, 'anular']);
        Route::get('/cotizacion/pdfProforma3',[CotizacionController3::class, 'pdfCotizacion']);
        Route::get('/cotizacion/pdfCotizacionSimple3',[CotizacionController3::class, 'pdfCotizacionSimple']);
        Route::get('/cotizacion/pdfCotizacionReporte3',[CotizacionController1::class, 'pdfCotizacionReporte']);

        //Rutas Reporte
        Route::get('/reporte/pdfCliente',[ReporteController::class, 'pdfCliente']);
        Route::get('/reporte/pdfPersonal',[ReporteController::class, 'pdfPersonal']);
        Route::get('/reporte/pdfUsuario',[ReporteController::class, 'pdfUsuario']);
        Route::get('/reporte/pdfOrdenGeneral',[ReporteController::class, 'pdfOrdenGeneral']);
        Route::get('/reporte/pdfOrdenDetallada',[ReporteController::class, 'pdfOrdenDetallada']);
        Route::get('/reporte/pdfCompraGeneral',[ReporteController::class, 'pdfCompraGeneral']);
        Route::get('/reporte/pdfCompraDetallada',[ReporteController::class, 'pdfCompraDetallada']);
        Route::get('/reporte/pdfCompraDetalladaAnular',[ReporteController::class, 'pdfCompraDetalladaAnular']);
        Route::get('/reporte/pdfProformaGeneral',[ReporteController::class, 'pdfProformaGeneral']);
        Route::get('/reporte/pdfProformaDetallada',[ReporteController::class, 'pdfProformaDetallada']);
        Route::get('/reporte/pdfVentaGeneral',[ReporteController::class, 'pdfVentaGeneral']);
        Route::get('/reporte/pdfVentaGeneralUsuario',[ReporteController::class, 'pdfVentaGeneralUsuario']);
        Route::get('/reporte/pdfVentaDetallada',[ReporteController::class, 'pdfVentaDetallada']);
        Route::get('/reporte/pdfVentaDetalladaDevolucion',[ReporteController::class, 'pdfVentaDetalladaDevolucion']);
        Route::get('/reporte/pdfVentaDetalladaAnulada',[ReporteController::class, 'pdfVentaDetalladaAnulada']);
        Route::get('/reporte/pdfVentaDetalladaEfectivo',[ReporteController::class, 'pdfVentaDetalladaEfectivo']);
        Route::get('/reporte/pdfVentaDetalladaTransfencia',[ReporteController::class, 'pdfVentaDetalladaTransfencia']);
        Route::get('/reporte/pdfVentaDetalladaQr',[ReporteController::class, 'pdfVentaDetalladaQr']);
        Route::get('/reporte/pdfVentaDetalladaDeposito',[ReporteController::class, 'pdfVentaDetalladaDeposito']);
        Route::get('/reporte/pdfVentaDetalladaMixta',[ReporteController::class, 'pdfVentaDetalladaMixta']);
        Route::get('/reporte/pdfCaja',[ReporteController::class, 'pdfCaja']);
        Route::get('/reporte/pdfGasto',[ReporteController::class, 'pdfGasto']);
        Route::get('/reporte/pdfGastoCliente',[ReporteController::class, 'pdfGastoCliente']);
        Route::get('/reporte/pdfPagoCompra',[ReporteController::class, 'pdfPagoCompra']);
        Route::get('/reporte/pdfPagoVenta',[ReporteController::class, 'pdfPagoVenta']);


        Route::get('/reporte/pdfProveedor',[ReporteController::class, 'pdfProveedor']);

        Route::get('/reporte/pdfVentaDetalladaUsuario',[ReporteController::class, 'pdfVentaDetalladaUsuario']);
        Route::get('/reporte/pdfVentaClienteCreditoUsuario',[ReporteController::class, 'pdfVentaClienteCreditoUsuario']);
        Route::get('/reporte/pdfVentaDetalladaEfectivoUsuario',[ReporteController::class, 'pdfVentaDetalladaEfectivoUsuario']);
        Route::get('/reporte/pdfVentaDetalladaTransfenciaUsuario',[ReporteController::class, 'pdfVentaDetalladaTransfenciaUsuario']);
        Route::get('/reporte/pdfVentaDetalladaQrUsuario',[ReporteController::class, 'pdfVentaDetalladaQrUsuario']);
        Route::get('/reporte/pdfVentaDetalladaDepositoUsuario',[ReporteController::class, 'pdfVentaDetalladaDepositoUsuario']);
        Route::get('/reporte/pdfVentaDetalladaMixtaUsuario',[ReporteController::class, 'pdfVentaDetalladaMixtaUsuario']);
        Route::get('/reporte/pdfVentaDetalladaCliente',[ReporteController::class, 'pdfVentaDetalladaCliente']);

        Route::get('/reporte/pdfVentaProductos_Vencimiento',[ReporteController::class, 'listarProductoMeses1']);
        Route::get('/reporte/pdfVentaProductos_Vencimiento1',[ReporteController::class, 'listarProductoMes1']);

        Route::get('/reporte/listarProductoMes',[ReporteController::class, 'listarProductoMes']);
        Route::get('/reporte/listarProductoMeses',[ReporteController::class, 'listarProductoMeses']);
        Route::get('/reporte/pdfVentaClienteCredito',[ReporteController::class, 'pdfVentaClienteCredito']);
        Route::get('/reporte/pdfCompraProveedorCredito',[ReporteController::class, 'pdfCompraProveedorCredito']);
        Route::get('/reporte/pdfHistorialProductoUsuario',[ReporteController::class, 'pdfHistorialProductoUsuario']);
        Route::get('/reporte/pdfProductoLaboratorio',[ReporteController::class, 'pdfProductoLaboratorio']);
        

        //Reporte Excel
        Route::get('/reporte/ExcelProducto',[ReporteExcelController::class, 'ExcelProducto']);
        Route::get('/reporte/ExcelProductoMinimo',[ReporteExcelController::class, 'ExcelProductoMinimo']);
        Route::get('/reporte/ExcelProductoInventario',[ReporteExcelController::class, 'ExcelProductoInventario']);

        // Modulo Reportes Ventas por Arqueos
        Route::get('/listado_arqueos', [ReporteController::class, 'listadoArqueos'])->name('arqueo.listado');
        Route::get('/reportes/arqueo/{arqueoId}/{tipo}', [ReporteController::class, 'reportePorArqueo'])
            ->name('reporte.arqueo.tipo');

        Route::get('/reporte/pdfProductoVencerse', [ReporteController::class, 'pdfProductosPorVencer']);
        
    
});
