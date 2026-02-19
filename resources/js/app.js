/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue'
 
import Toaster from 'v-toaster'
import VueNumeric from 'vue-numeric'



// You need a specific loader for CSS files like https://github.com/webpack/css-loader
import 'v-toaster/dist/v-toaster.css'
// optional set default imeout, the default is 10000 (10 seconds).
Vue.use(Toaster, {timeout: 3500})
Vue.use(VueNumeric)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('principal-component', require('./components/frmCaja.vue').default);
Vue.component('frm-cliente', require('./components/frmCliente.vue').default);
Vue.component('frm-personal', require('./components/frmPersonal.vue').default);
Vue.component('frm-proveedor', require('./components/frmProveedor.vue').default);
Vue.component('frm-cargo', require('./components/frmCargo.vue').default);
Vue.component('frm-permiso', require('./components/frmPermiso.vue').default);
Vue.component('frm-grupo', require('./components/frmGrupo.vue').default);
Vue.component('frm-categoria', require('./components/frmCategoria.vue').default);
Vue.component('frm-marca', require('./components/frmMarca.vue').default);
Vue.component('frm-unidad', require('./components/frmUnidadMedida.vue').default);
Vue.component('frm-articulo', require('./components/frmArticulo.vue').default);
Vue.component('frm-tienda', require('./components/frmTienda.vue').default);
Vue.component('frm-compra', require('./components/frmCompra.vue').default);
Vue.component('frm-motivo-gasto', require('./components/frmMotivoGasto.vue').default);
Vue.component('frm-gasto', require('./components/frmGasto.vue').default);
Vue.component('frm-venta', require('./components/frmVenta.vue').default);
Vue.component('frm-ajuste', require('./components/frmAjuste.vue').default);
Vue.component('frm-inventario', require('./components/frmInventario.vue').default);
Vue.component('frm-historial', require('./components/frmHistorialVenta.vue').default);
Vue.component('frm-historialcompra', require('./components/frmHistorialCompra.vue').default);
Vue.component('frm-servicio', require('./components/frmServicio.vue').default);
Vue.component('frm-historialservicio', require('./components/frmHistorialServicio.vue').default);
Vue.component('frm-miempresa', require('./components/frmMiEmpresa.vue').default);
Vue.component('frm-usuario', require('./components/frmUsuario.vue').default);
Vue.component('frm-grupo-usuario', require('./components/frmGrupoUsuario.vue').default);
Vue.component('frm-articulo-servicio', require('./components/frmArticuloServicio.vue').default);
Vue.component('frm-historial-arqueo', require('./components/frmHistorialArqueo.vue').default);
Vue.component('frm-reportes', require('./components/frmReportes.vue').default);
Vue.component('frm-pago-compra', require('./components/frmPagoCompra.vue').default);
Vue.component('frm-lote', require('./components/frmLote.vue').default);
Vue.component('frm-historial-producto', require('./components/frmHistorialProducto.vue').default);


//HISTORIAL CLINICO
Vue.component('frm-animal', require('./components/frmAnimal.vue').default);
Vue.component('frm-paciente', require('./components/frmPaciente.vue').default);
Vue.component('frm-vacuna', require('./components/frmVacuna.vue').default);
Vue.component('frm-vacuna-animal', require('./components/vacuna/frmVacunaAnimal').default);
Vue.component('frm-vacuna-control-vacuna', require('./components/controlVacuna/frmControlVacuna').default);
Vue.component('frm-menu-vacuna', require('./components/controlVacuna/frmMenuVacuna').default);
Vue.component('frm-menu-historial', require('./components/controlVacuna/frmMenuHistorial').default);
Vue.component('frm-vacuna-historial-vacuna', require('./components/controlVacuna/frmHistorialVacuna').default);
Vue.component('frm-antiparasitario', require('./components/antiparasitario/frmAntiparasitario').default);
Vue.component('frm-historial-antiparasitario', require('./components/antiparasitario/frmHistorialAntiparasitario').default);

Vue.component('frm-historial-clinico', require('./components/historialClinico/frmHistorialClinico').default);
Vue.component('frm-historial-clinico2', require('./components/historialClinico/frmHistorialClinico2').default);
Vue.component('frm-detalle-historia', require('./components/historialClinico/frmDetalleHistoria').default);




Vue.component('frm-dashboard', require('./components/frmDashboard.vue').default);
//TIENDA PRIMERA
Vue.component('frm-tp1-venta', require('./components/tiendaPrimera/frmVenta.vue').default);
Vue.component('frm-tp1-servicio', require('./components/tiendaPrimera/frmServicio.vue').default);
Vue.component('frm-tp1-paquete', require('./components/tiendaPrimera/frmPaquete.vue').default);
Vue.component('frm-tp1-historialpaquete', require('./components/tiendaPrimera/frmHistorialPaquete.vue').default);
Vue.component('frm-tp1-historialventa', require('./components/tiendaPrimera/frmHistorialVenta.vue').default);
Vue.component('frm-tp1-historialcliente', require('./components/tiendaPrimera/frmHistorialCliente.vue').default);
Vue.component('frm-tp1-historialservicio', require('./components/tiendaPrimera/frmHistorialServicio.vue').default);
Vue.component('frm-tp1-pago', require('./components/tiendaPrimera/frmPago.vue').default);
Vue.component('frm-tp1-pago-servicio', require('./components/tiendaPrimera/frmPagoVentaServicio.vue').default);
Vue.component('frm-tp1-cotizacion', require('./components/tiendaPrimera/frmCotizacion.vue').default);
Vue.component('frm-tp1-historialcotizacion', require('./components/tiendaPrimera/frmHistorialCotizacion.vue').default);
//TIENDA SEGUNDA
Vue.component('frm-ts2-venta', require('./components/tiendaSegunda/frmVenta.vue').default);
Vue.component('frm-ts2-servicio', require('./components/tiendaSegunda/frmServicio.vue').default);
Vue.component('frm-ts2-historialventa', require('./components/tiendaSegunda/frmHistorialVenta.vue').default);
Vue.component('frm-ts2-historialservicio', require('./components/tiendaSegunda/frmHistorialServicio.vue').default);
Vue.component('frm-ts2-pago', require('./components/tiendaSegunda/frmPago.vue').default);
Vue.component('frm-ts2-pago-servicio', require('./components/tiendaSegunda/frmPagoVentaServicio.vue').default);
Vue.component('frm-tp2-cotizacion', require('./components/tiendaSegunda/frmCotizacion.vue').default);
Vue.component('frm-tp2-historialcotizacion', require('./components/tiendaSegunda/frmHistorialCotizacion.vue').default);
//TIENDA TERCERA
Vue.component('frm-tt3-venta', require('./components/tiendaTercera/frmVenta.vue').default);
Vue.component('frm-tt3-servicio', require('./components/tiendaTercera/frmServicio.vue').default);
Vue.component('frm-tt3-historialventa', require('./components/tiendaTercera/frmHistorialVenta.vue').default);
Vue.component('frm-tt3-historialservicio', require('./components/tiendaTercera/frmHistorialServicio.vue').default);
Vue.component('frm-tt3-pago', require('./components/tiendaTercera/frmPago.vue').default);
Vue.component('frm-tt3-pago-servicio', require('./components/tiendaTercera/frmPagoVentaServicio.vue').default);
Vue.component('frm-tp3-cotizacion', require('./components/tiendaTercera/frmCotizacion.vue').default);
Vue.component('frm-tp3-historialcotizacion', require('./components/tiendaTercera/frmHistorialCotizacion.vue').default);

Vue.component('frm-traspaso', require('./components/frmTraspaso.vue').default);
Vue.component('frm-pago', require('./components/frmPago.vue').default);
Vue.component('frm-caja', require('./components/frmCaja.vue').default);
Vue.component('frm-toast', require('./components/frmToast.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data : {
        menu : 0
    }
});
