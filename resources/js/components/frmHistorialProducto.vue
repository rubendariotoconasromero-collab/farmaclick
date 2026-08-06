<template>
    <product-sales-report-workspace
        :view="view"
        :active-tab="activeTab"
        :rows="currentRows"
        :pagination="currentPagination"
        :pages="pagesNumber"
        :filters="currentFilters"
        :providers="providers"
        :loading="loading"
        :selected-lot="selectedLot"
        :detail-rows="filteredDetailRows"
        :detail-search="detailSearch"
        :printing="printing"
        @change-tab="changeTab"
        @update-filter="updateFilter"
        @search="searchCurrent(1)"
        @page="searchCurrent"
        @view-lot="viewLot"
        @update-detail-search="detailSearch = $event"
        @filter-detail="filterDetail"
        @print="printDetail"
        @back="goBack"
    />
</template>

<script>
import moment from 'moment';
import Swal from '../utils/appSwal';
import ProductSalesReportWorkspace from './sales/reports/ProductSalesReportWorkspace.vue';

const pagination = () => ({ total: 0, current_page: 1, per_page: 0, last_page: 1, from: 0, to: 0 });

export default {
    name: 'FrmHistorialProducto',
    components: { ProductSalesReportWorkspace },
    data() {
        const today = moment().format('YYYY-MM-DD');
        return {
            view: 'list',
            activeTab: 'sales',
            salesRows: [],
            movementRows: [],
            detailRows: [],
            selectedLot: {},
            providers: [],
            salesPagination: pagination(),
            movementPagination: pagination(),
            filters: {
                sales: { startDate: today, endDate: today, search: '', criterion: 'articulo.nombre_comercial', providerId: 0 },
                movements: { startDate: today, endDate: today, search: '', criterion: 'articulo.nombre_comercial', providerId: 0 },
            },
            loaded: { sales: false, movements: false },
            detailSearch: '',
            appliedDetailSearch: '',
            loading: false,
            printing: false,
            requestSequence: 0,
            offset: 3,
        };
    },
    computed: {
        currentRows() { return this.activeTab === 'sales' ? this.salesRows : this.movementRows; },
        currentPagination() { return this.activeTab === 'sales' ? this.salesPagination : this.movementPagination; },
        currentFilters() { return this.filters[this.activeTab]; },
        filteredDetailRows() {
            const search = this.appliedDetailSearch.trim().toLowerCase();
            if (!search) return this.detailRows;
            return this.detailRows.filter(row => String(row.usuario || '').toLowerCase().includes(search));
        },
        pagesNumber() {
            const data = this.currentPagination;
            if (!data.to || data.last_page <= 1) return [];
            let from = Math.max(1, data.current_page - this.offset);
            const to = Math.min(data.last_page, from + this.offset * 2);
            const pages = [];
            while (from <= to) { pages.push(from); from += 1; }
            return pages;
        },
    },
    methods: {
        normalizePagination(data, defaultPerPage) {
            return {
                total: Number(data.total || 0), current_page: Number(data.current_page || 1),
                per_page: Number(data.per_page || defaultPerPage), last_page: Number(data.last_page || 1),
                from: Number(data.from || 0), to: Number(data.to || 0),
            };
        },
        validDates(filters) {
            if (!filters.startDate || !filters.endDate) {
                this.showError('Seleccione las fechas inicial y final.');
                return false;
            }
            if (filters.startDate > filters.endDate) {
                this.showError('La fecha inicial no puede ser posterior a la fecha final.');
                return false;
            }
            return true;
        },
        updateFilter({ key, value }) {
            this.currentFilters[key] = value;
        },
        async changeTab(tab) {
            if (tab === this.activeTab) return;
            this.activeTab = tab;
            if (!this.loaded[tab]) await this.searchCurrent(1);
        },
        searchCurrent(page = 1) {
            return this.activeTab === 'sales' ? this.loadSales(page) : this.loadMovements(page);
        },
        async loadSales(page = 1) {
            const filters = this.filters.sales;
            if (!this.validDates(filters)) return;
            const sequence = ++this.requestSequence;
            this.loading = true;
            try {
                const response = await axios.get('/cantidadProductoFecha', {
                    params: {
                        page, fecha_producto: filters.startDate, fecha_fin: filters.endDate,
                        buscar: filters.search, criterio: filters.criterion,
                    },
                });
                if (sequence !== this.requestSequence) return;
                this.salesRows = Array.isArray(response.data.data) ? response.data.data : [];
                this.salesPagination = this.normalizePagination(response.data, 70);
                this.loaded.sales = true;
            } catch (error) {
                this.showError('No fue posible cargar las ventas por producto.');
                console.error(error);
            } finally {
                if (sequence === this.requestSequence) this.loading = false;
            }
        },
        async loadMovements(page = 1) {
            const filters = this.filters.movements;
            if (!this.validDates(filters)) return;
            const sequence = ++this.requestSequence;
            this.loading = true;
            try {
                const response = await axios.get('/ajuste/producto', {
                    params: {
                        page, fecha_inicio: filters.startDate, fecha_final: filters.endDate,
                        buscarProducto: filters.search, criterio: filters.criterion,
                        id_proveedor: filters.providerId,
                    },
                });
                if (sequence !== this.requestSequence) return;
                this.movementRows = Array.isArray(response.data.data) ? response.data.data : [];
                this.movementPagination = this.normalizePagination(response.data, 50);
                this.loaded.movements = true;
            } catch (error) {
                this.showError('No fue posible cargar los movimientos de producto.');
                console.error(error);
            } finally {
                if (sequence === this.requestSequence) this.loading = false;
            }
        },
        async loadProviders() {
            try {
                const response = await axios.get('/proveedor/selectProveedor');
                this.providers = Array.isArray(response.data) ? response.data : [];
            } catch (error) {
                this.providers = [];
                console.error(error);
            }
        },
        async viewLot(row) {
            this.selectedLot = { ...row };
            this.view = 'detail';
            this.detailSearch = '';
            this.appliedDetailSearch = '';
            this.loading = true;
            try {
                const filters = this.filters.sales;
                const response = await axios.get('/cantidadProductoUsuario', {
                    params: {
                        id_lote: row.id_lote, fecha_producto: filters.startDate,
                        fecha_fin: filters.endDate, buscar: '', criterioP: 'users.name',
                    },
                });
                this.detailRows = Array.isArray(response.data) ? response.data : [];
            } catch (error) {
                this.detailRows = [];
                this.showError('No fue posible cargar la trazabilidad del lote.');
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        filterDetail() {
            this.appliedDetailSearch = this.detailSearch;
        },
        goBack() {
            this.view = 'list';
            this.selectedLot = {};
            this.detailRows = [];
            this.detailSearch = '';
            this.appliedDetailSearch = '';
        },
        async printDetail() {
            if (!this.selectedLot.id_lote || this.printing) return;
            this.printing = true;
            try {
                const filters = this.filters.sales;
                const response = await axios.get('/reporte/pdfHistorialProductoUsuario', {
                    params: {
                        id_lote: this.selectedLot.id_lote,
                        fecha_producto: filters.startDate,
                        fecha_fin: filters.endDate,
                    },
                    responseType: 'blob',
                });
                const url = URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
                window.open(url, '_blank');
                window.setTimeout(() => URL.revokeObjectURL(url), 60000);
            } catch (error) {
                this.showError('No fue posible generar el reporte.');
                console.error(error);
            } finally {
                this.printing = false;
            }
        },
        showError(message) {
            Swal.fire({ icon: 'error', title: 'Ocurrió un problema', text: message });
        },
    },
    mounted() {
        this.loadSales(1);
        this.loadProviders();
    },
    beforeDestroy() {
        this.requestSequence += 1;
    },
};
</script>
