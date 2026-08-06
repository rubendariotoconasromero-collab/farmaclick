/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue'
import router from './router'

import VueNumeric from 'vue-numeric'

Vue.use(VueNumeric)

Vue.component('app-module-header', require('./components/ui/AppModuleHeader.vue').default);
Vue.component('app-button', require('./components/ui/AppButton.vue').default);
Vue.component('app-metric-card', require('./components/ui/AppMetricCard.vue').default);
Vue.component('app-data-panel', require('./components/ui/AppDataPanel.vue').default);
Vue.component('app-table', require('./components/ui/AppTable.vue').default);
Vue.component('app-input', require('./components/ui/AppInput.vue').default);
Vue.component('app-page-skeleton', require('./components/ui/AppPageSkeleton.vue').default);
Vue.component('app-live-search', require('./components/ui/AppLiveSearch.vue').default);
Vue.component('app-multi-select-dropdown', require('./components/ui/AppMultiSelectDropdown.vue').default);
Vue.component('app-icon-button', require('./components/ui/AppIconButton.vue').default);
Vue.component('app-select', require('./components/ui/AppSelect.vue').default);
Vue.component('purchase-pagination', require('./components/purchases/PurchasePagination.vue').default);
Vue.component('purchase-entry-workspace', require('./components/purchases/PurchaseEntryWorkspace.vue').default);
Vue.component('purchase-report-format-modal', require('./components/purchases/PurchaseReportFormatModal.vue').default);
Vue.component('app-report-format-modal', require('./components/purchases/PurchaseReportFormatModal.vue').default);
Vue.component('purchase-history-workspace', require('./components/purchases/PurchaseHistoryWorkspace.vue').default);
Vue.component('purchase-payments-workspace', require('./components/purchases/PurchasePaymentsWorkspace.vue').default);
Vue.component('expense-reasons-workspace', require('./components/expenses/ExpenseReasonsWorkspace.vue').default);
Vue.component('expense-registry-workspace', require('./components/expenses/ExpenseRegistryWorkspace.vue').default);
Vue.component('sales-entry-workspace', require('./components/sales/SalesEntryWorkspace.vue').default);
Vue.component('sales-history-workspace', require('./components/sales/SalesHistoryWorkspace.vue').default);
Vue.component('sales-payments-workspace', require('./components/sales/SalesPaymentsWorkspace.vue').default);
Vue.component('warehouse-module-intro', require('./components/warehouse/WarehouseModuleIntro.vue').default);
Vue.component('warehouse-page-skeleton', require('./components/warehouse/WarehousePageSkeleton.vue').default);
Vue.component('warehouse-inventory-workspace', require('./components/warehouse/WarehouseInventoryWorkspace.vue').default);
Vue.component('warehouse-adjustment-workspace', require('./components/warehouse/WarehouseAdjustmentWorkspace.vue').default);
Vue.component('warehouse-lot-workspace', require('./components/warehouse/WarehouseLotWorkspace.vue').default);
Vue.component('warehouse-products-workspace', require('./components/warehouse/WarehouseProductsWorkspace.vue').default);
Vue.component('company-settings-workspace', require('./components/master-data/CompanySettingsWorkspace.vue').default);
Vue.component('access-summary', require('./components/access/AccessSummary.vue').default);
Vue.component('access-toolbar', require('./components/access/AccessToolbar.vue').default);
Vue.component('permission-editor', require('./components/access/PermissionEditor.vue').default);
Vue.component('group-users-workspace', require('./components/access/GroupUsersWorkspace.vue').default);
Vue.component('users-workspace', require('./components/access/UsersWorkspace.vue').default);
Vue.component('permissions-workspace', require('./components/access/PermissionsWorkspace.vue').default);
Vue.component('report-group-card', require('./components/reports/ReportGroupCard.vue').default);
Vue.component('report-center-workspace', require('./components/reports/ReportCenterWorkspace.vue').default);

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

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data : {
        menu : 0,
        sidebarNavigationHandler: null,
    },
    mounted() {
        this.syncRouteState(this.$route);
        this.setupSidebarNavigation();
    },
    watch: {
        $route: {
            immediate: true,
            handler(route) {
                this.syncRouteState(route);
            },
        },
    },
    methods: {
        setupSidebarNavigation() {
            this.$nextTick(() => {
                const sidebar = this.$el.querySelector('#sidebar');

                if (!sidebar || this.sidebarNavigationHandler) {
                    return;
                }

                this.sidebarNavigationHandler = event => {
                    const toggle = event.target.closest('.nav-group-toggle');

                    if (!toggle || !sidebar.contains(toggle)) {
                        return;
                    }

                    const group = toggle.closest('.nav-group');
                    const willOpen = group && !group.classList.contains('show');

                    if (!willOpen) {
                        return;
                    }

                    sidebar.querySelectorAll('.nav-group.show').forEach(openGroup => {
                        if (openGroup === group) return;
                        openGroup.classList.remove('show');
                        openGroup.setAttribute('aria-expanded', 'false');
                    });

                    window.setTimeout(() => this.revealSidebarGroup(group), 220);
                };

                sidebar.addEventListener('click', this.sidebarNavigationHandler, true);
            });
        },
        revealSidebarGroup(group) {
            if (!group) return;

            const sidebar = this.$el.querySelector('#sidebar');
            const navigation = sidebar && sidebar.querySelector('.sidebar-nav');
            const scroller = navigation && (
                navigation.querySelector('.simplebar-content-wrapper') || navigation
            );

            if (!scroller) return;

            const groupRect = group.getBoundingClientRect();
            const scrollerRect = scroller.getBoundingClientRect();
            const topLimit = scrollerRect.top + 8;
            const bottomLimit = scrollerRect.bottom - 12;

            if (groupRect.bottom > bottomLimit) {
                scroller.scrollBy({ top: groupRect.bottom - bottomLimit, behavior: 'smooth' });
            } else if (groupRect.top < topLimit) {
                scroller.scrollBy({ top: groupRect.top - topLimit, behavior: 'smooth' });
            }
        },
        syncRouteState(route) {
            this.menu = route.meta && Number.isInteger(route.meta.menu)
                ? route.meta.menu
                : 0;

            if (route.meta && route.meta.title) {
                document.title = `${route.meta.title} | FarmaClick`;
            }

            this.$nextTick(() => {
                const sidebar = this.$el.querySelector('#sidebar');

                if (!sidebar) {
                    return;
                }

                sidebar.querySelectorAll('.nav-item.is-menu-active')
                    .forEach(item => item.classList.remove('is-menu-active'));

                sidebar.querySelectorAll('.nav-group.show')
                    .forEach(group => {
                        group.classList.remove('show');
                        group.setAttribute('aria-expanded', 'false');
                    });

                const activeRouteName = route.name === 'inicio'
                    ? 'dashboard'
                    : route.name;
                const activeLink = sidebar.querySelector(
                    `[data-route-name="${activeRouteName || ''}"]`
                );

                if (!activeLink) {
                    return;
                }

                const activeItem = activeLink.closest('.nav-item');

                if (activeItem) {
                    activeItem.classList.add('is-menu-active');
                }

                const parentGroup = activeLink.closest('.nav-group');

                if (parentGroup) {
                    parentGroup.classList.add('show');
                    parentGroup.setAttribute('aria-expanded', 'true');
                    window.setTimeout(() => this.revealSidebarGroup(parentGroup), 220);
                }
            });
        },
    },
    beforeDestroy() {
        const sidebar = this.$el.querySelector('#sidebar');
        if (sidebar && this.sidebarNavigationHandler) {
            sidebar.removeEventListener('click', this.sidebarNavigationHandler, true);
        }
    },
});
