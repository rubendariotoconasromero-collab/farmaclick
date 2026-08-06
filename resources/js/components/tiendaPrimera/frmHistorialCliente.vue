<template>
    <customer-sales-report-workspace
        :view="view"
        :active-tab="activeTab"
        :rows="currentRows"
        :pagination="currentPagination"
        :pages="pagesNumber"
        :search="currentFilter.search"
        :criterion="currentFilter.criterion"
        :loading="loading"
        :selected-client="selectedClient"
        :sale-data="saleData"
        :details="details"
        @change-tab="changeTab"
        @update:criterion="currentFilter.criterion = $event"
        @update:search="currentFilter.search = $event; scheduleSearch()"
        @search="searchCurrent"
        @page="changePage"
        @view-client="viewClientCredits"
        @view-sale="viewSale"
        @back="goBack"
    />
</template>

<script>
import Swal from '../../utils/appSwal';
import CustomerSalesReportWorkspace from '../sales/reports/CustomerSalesReportWorkspace.vue';

const pagination = () => ({ total: 0, current_page: 1, per_page: 0, last_page: 1, from: 0, to: 0 });
const emptySale = () => ({
    id: 0, cliente: '', fecha: '', descuento: 0, total: 0, sub_total: 0,
    tipoPago: '', formaPago: '', total_efectivo: 0, total_deposito: 0,
});

export default {
    name: 'FrmTiendaPrimeraHistorialCliente',
    components: { CustomerSalesReportWorkspace },
    data() {
        return {
            view: 'list',
            previousView: 'list',
            activeTab: 'cash',
            cashRows: [],
            creditClients: [],
            creditSales: [],
            details: [],
            saleData: emptySale(),
            selectedClient: {},
            cashPagination: pagination(),
            creditPagination: pagination(),
            creditSalesPagination: pagination(),
            filters: {
                cash: { search: '', criterion: 'cliente.nombre' },
                credit: { search: '', criterion: 'cliente.nombre' },
            },
            loaded: { cash: false, credit: false },
            loading: false,
            searchTimer: null,
            requestSequence: 0,
            offset: 3,
        };
    },
    computed: {
        currentRows() {
            if (this.view === 'client-credit') return this.creditSales;
            return this.activeTab === 'cash' ? this.cashRows : this.creditClients;
        },
        currentPagination() {
            if (this.view === 'client-credit') return this.creditSalesPagination;
            return this.activeTab === 'cash' ? this.cashPagination : this.creditPagination;
        },
        currentFilter() {
            return this.filters[this.activeTab];
        },
        pagesNumber() {
            const paginationData = this.currentPagination;
            if (!paginationData.to || paginationData.last_page <= 1) return [];
            let from = Math.max(1, paginationData.current_page - this.offset);
            const to = Math.min(paginationData.last_page, from + this.offset * 2);
            const pages = [];
            while (from <= to) { pages.push(from); from += 1; }
            return pages;
        },
    },
    methods: {
        normalizePagination(data) {
            return {
                total: Number(data.total || 0), current_page: Number(data.current_page || 1),
                per_page: Number(data.per_page || 0), last_page: Number(data.last_page || 1),
                from: Number(data.from || 0), to: Number(data.to || 0),
            };
        },
        async loadCash(page = 1) {
            return this.loadPaged('/venta_tienda1/contado', page, 'cash');
        },
        async loadCreditClients(page = 1) {
            return this.loadPaged('/historialCliente', page, 'credit');
        },
        async loadPaged(url, page, type) {
            const sequence = ++this.requestSequence;
            this.loading = true;
            try {
                const filter = this.filters[type];
                const response = await axios.get(url, { params: { page, buscar: filter.search, criterio: filter.criterion } });
                if (sequence !== this.requestSequence) return;
                const rows = Array.isArray(response.data.data) ? response.data.data : [];
                if (type === 'cash') {
                    this.cashRows = rows;
                    this.cashPagination = this.normalizePagination(response.data);
                } else {
                    this.creditClients = rows.map(row => ({
                        ...row,
                        estado: Number(row.total || 0) > 0 ? 'Pendiente' : 'Cancelado',
                    }));
                    this.creditPagination = this.normalizePagination(response.data);
                }
                this.loaded[type] = true;
            } catch (error) {
                this.showError('No fue posible cargar el reporte por cliente.');
                console.error(error);
            } finally {
                if (sequence === this.requestSequence) this.loading = false;
            }
        },
        async changeTab(tab) {
            if (tab === this.activeTab) return;
            this.activeTab = tab;
            if (!this.loaded[tab]) await this.searchCurrent();
        },
        searchCurrent(page = 1) {
            window.clearTimeout(this.searchTimer);
            return this.activeTab === 'cash' ? this.loadCash(page) : this.loadCreditClients(page);
        },
        scheduleSearch() {
            window.clearTimeout(this.searchTimer);
            this.searchTimer = window.setTimeout(() => this.searchCurrent(1), 350);
        },
        changePage(page) {
            if (this.view === 'client-credit') return this.loadClientCredits(page);
            return this.searchCurrent(page);
        },
        async viewClientCredits(client) {
            this.selectedClient = client;
            this.view = 'client-credit';
            await this.loadClientCredits(1);
        },
        async loadClientCredits(page = 1) {
            if (!this.selectedClient.id_cliente) return;
            this.loading = true;
            try {
                const response = await axios.get('/venta_tienda1/credito', {
                    params: {
                        id_cliente: this.selectedClient.id_cliente,
                        page,
                        buscar: '',
                        criterio: 'cliente.nombre',
                    },
                });
                this.creditSales = Array.isArray(response.data.data) ? response.data.data : [];
                this.creditSalesPagination = this.normalizePagination(response.data);
            } catch (error) {
                this.showError('No fue posible cargar las ventas a crédito del cliente.');
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        async viewSale(sale) {
            this.previousView = this.view;
            this.saleData = {
                ...emptySale(),
                id: sale.id,
                cliente: sale.cliente || this.selectedClient.cliente,
                fecha: sale.fecha,
                descuento: Number(sale.descuento || 0),
                total: Number(sale.total || 0),
                sub_total: Number(sale.sub_total || 0),
                tipoPago: sale.tipoP || '',
                formaPago: sale.formaP || '',
                total_efectivo: Number(sale.total_efectivo || 0),
                total_deposito: Number(sale.total_deposito || 0),
            };
            this.details = [];
            this.view = 'detail';
            this.loading = true;
            try {
                const [detailResponse, packageResponse] = await Promise.all([
                    axios.get('/venta/permiso/detalle_tienda1', { params: { id: sale.id } }),
                    axios.get('/paquete/permiso/detalle/venta', { params: { id: sale.id } }),
                ]);
                this.details = [
                    ...(Array.isArray(detailResponse.data) ? detailResponse.data : []),
                    ...(Array.isArray(packageResponse.data) ? packageResponse.data : []),
                ];
            } catch (error) {
                this.showError('No fue posible cargar el comprobante.');
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        goBack() {
            if (this.view === 'detail') {
                this.view = this.previousView;
                this.details = [];
                this.saleData = emptySale();
                return;
            }
            this.view = 'list';
            this.creditSales = [];
            this.selectedClient = {};
        },
        showError(message) {
            Swal.fire({ icon: 'error', title: 'Ocurrió un problema', text: message });
        },
    },
    mounted() {
        this.loadCash(1);
    },
    beforeDestroy() {
        window.clearTimeout(this.searchTimer);
        this.requestSequence += 1;
    },
};
</script>
