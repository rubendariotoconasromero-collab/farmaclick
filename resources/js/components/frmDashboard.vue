<template>
    <main class="dashboard-module">
        <app-module-header
            eyebrow="Resumen operativo"
            title="Datos gráficos"
            :subtitle="`Indicadores comerciales, actividad y vencimientos de ${anio}.`"
        >
            <template v-slot:actions>
                <app-button
                    variant="secondary"
                    icon="icons/reload.svg"
                    :loading="dashboardLoading"
                    @click="loadDashboard(true)"
                >
                    Actualizar datos
                </app-button>
            </template>
        </app-module-header>

        <div v-if="loadError" class="dashboard-alert" role="alert">
            <div>
                <strong>No se pudo actualizar toda la información.</strong>
                <span>{{ loadError }}</span>
            </div>
            <app-button variant="ghost" @click="loadDashboard(true)">Reintentar</app-button>
        </div>

        <section class="dashboard-metrics" aria-label="Indicadores principales">
            <app-metric-card
                label="Ventas acumuladas"
                :value="formatCurrency(totalVenta)"
                hint="Total histórico registrado"
                icon="icons/cart.svg"
                tone="green"
                :loading="loading.summary"
            />
            <app-metric-card
                label="Compras acumuladas"
                :value="formatCurrency(totalCompra)"
                hint="Total histórico registrado"
                icon="icons/basket.svg"
                tone="cyan"
                :loading="loading.summary"
            />
            <app-metric-card
                label="Administradores"
                :value="totalAdministrador"
                hint="Usuarios con acceso administrativo"
                icon="icons/people.svg"
                tone="blue"
                :loading="loading.summary"
            />
            <app-metric-card
                label="Cajeros"
                :value="totalCajero"
                hint="Usuarios asignados a caja"
                icon="icons/calculator.svg"
                tone="neutral"
                :loading="loading.summary"
            />
        </section>

        <section class="dashboard-insights">
            <app-data-panel
                eyebrow="Ingresos"
                title="Ventas por sucursal"
                :subtitle="`Participación acumulada durante ${anio}.`"
            >
                <div v-if="loading.trends" class="dashboard-list-skeleton">
                    <span v-for="row in 4" :key="`venta-${row}`"></span>
                </div>
                <div v-else-if="salesByStore.length" class="dashboard-progress-list">
                    <div v-for="item in salesByStore" :key="item.label" class="dashboard-progress-item">
                        <div class="dashboard-progress-item__meta">
                            <strong>{{ item.label }}</strong>
                            <span>{{ formatCurrency(item.total) }}</span>
                        </div>
                        <div class="dashboard-progress-item__track" aria-hidden="true">
                            <span :style="{ width: `${progressWidth(item.total, maxSale)}%` }"></span>
                        </div>
                    </div>
                </div>
                <div v-else class="dashboard-empty">
                    <strong>Sin ventas registradas</strong>
                    <span>No existen movimientos para la gestión seleccionada.</span>
                </div>
            </app-data-panel>

            <app-data-panel
                eyebrow="Egresos"
                title="Compras por mes"
                :subtitle="`Evolución mensual de compras durante ${anio}.`"
            >
                <div v-if="loading.trends" class="dashboard-list-skeleton">
                    <span v-for="row in 4" :key="`compra-${row}`"></span>
                </div>
                <div v-else-if="purchasesByMonth.length" class="dashboard-progress-list">
                    <div v-for="item in purchasesByMonth" :key="item.month" class="dashboard-progress-item">
                        <div class="dashboard-progress-item__meta">
                            <strong>{{ item.label }}</strong>
                            <span>{{ formatCurrency(item.total) }}</span>
                        </div>
                        <div class="dashboard-progress-item__track dashboard-progress-item__track--cyan" aria-hidden="true">
                            <span :style="{ width: `${progressWidth(item.total, maxPurchase)}%` }"></span>
                        </div>
                    </div>
                </div>
                <div v-else class="dashboard-empty">
                    <strong>Sin compras registradas</strong>
                    <span>No existen movimientos para la gestión seleccionada.</span>
                </div>
            </app-data-panel>
        </section>

        <section class="dashboard-expiry">
            <app-data-panel
                eyebrow="Atención prioritaria"
                title="Vencimientos en los próximos 90 días"
                subtitle="Productos que requieren revisión, rotación o devolución."
                flush
            >
                <template v-slot:actions>
                    <span class="dashboard-count dashboard-count--warning">
                        {{ arrayProductoMeses.length }} productos
                    </span>
                    <app-button
                        v-if="arrayProductoMeses.length > tableLimit"
                        variant="ghost"
                        @click="showAllUrgent = !showAllUrgent"
                    >
                        {{ showAllUrgent ? `Mostrar ${tableLimit}` : 'Ver todos' }}
                    </app-button>
                    <app-button
                        variant="secondary"
                        icon="icons/print.svg"
                        :loading="pdfLoading.quarter"
                        @click="downloadExpiryReport('quarter')"
                    >
                        Imprimir
                    </app-button>
                </template>

                <app-table
                    :columns="urgentColumns"
                    :rows="visibleUrgentProducts"
                    :loading="loading.expiry"
                    caption="Productos con vencimiento en los próximos 90 días"
                    empty-title="Sin vencimientos próximos"
                    empty-message="No hay productos que venzan durante los próximos 90 días."
                >
                    <template v-slot:cell-fecha_vecimiento="{ row }">
                        <span class="dashboard-date" :class="`dashboard-date--${expiryTone(row.fecha_vecimiento)}`">
                            {{ formatDate(row.fecha_vecimiento) }}
                        </span>
                    </template>
                    <template v-slot:cell-stock="{ value }">
                        <strong class="dashboard-stock">{{ value || 0 }}</strong>
                    </template>
                </app-table>
            </app-data-panel>

            <app-data-panel
                eyebrow="Planificación"
                title="Vencimientos posteriores"
                subtitle="Productos que vencen entre 3 y 12 meses."
                flush
            >
                <template v-slot:actions>
                    <span class="dashboard-count">{{ annualProducts.length }} productos</span>
                    <app-button
                        v-if="annualProducts.length > tableLimit"
                        variant="ghost"
                        @click="showAllAnnual = !showAllAnnual"
                    >
                        {{ showAllAnnual ? `Mostrar ${tableLimit}` : 'Ver todos' }}
                    </app-button>
                    <app-button
                        variant="secondary"
                        icon="icons/print.svg"
                        :loading="pdfLoading.annual"
                        @click="downloadExpiryReport('annual')"
                    >
                        Imprimir
                    </app-button>
                </template>

                <app-table
                    :columns="annualColumns"
                    :rows="visibleAnnualProducts"
                    :loading="loading.expiry"
                    caption="Productos con vencimiento entre 3 y 12 meses"
                    empty-title="Sin vencimientos posteriores"
                    empty-message="No hay productos que venzan dentro de los próximos 12 meses."
                >
                    <template v-slot:cell-fecha_vecimiento="{ row }">
                        <span class="dashboard-date dashboard-date--info">
                            {{ formatDate(row.fecha_vecimiento) }}
                        </span>
                    </template>
                </app-table>
            </app-data-panel>
        </section>
    </main>
</template>

<script>
import Swal from '../utils/appSwal';
import moment from 'moment';

const currencyFormatter = new Intl.NumberFormat('es-BO', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

export default {
    name: 'FrmDashboard',
    data() {
        return {
            anio: moment().format('YYYY'),
            totalVenta: 0,
            totalCompra: 0,
            totalAdministrador: 0,
            totalCajero: 0,
            venta: [],
            compra: [],
            arrayProducto: [],
            arrayProductoMeses: [],
            loading: {
                summary: true,
                trends: true,
                expiry: true,
            },
            requestSequence: 0,
            tableLimit: 50,
            showAllUrgent: false,
            showAllAnnual: false,
            pdfLoading: {
                annual: false,
                quarter: false,
            },
            loadError: '',
            urgentColumns: [
                { key: 'articulo', label: 'Producto' },
                { key: 'fecha_vecimiento', label: 'Vencimiento', className: 'text-nowrap' },
                { key: 'laboratorio', label: 'Laboratorio' },
                { key: 'presentacion', label: 'Presentación' },
                { key: 'stock', label: 'Stock', className: 'text-center' },
            ],
            annualColumns: [
                { key: 'articulo', label: 'Producto' },
                { key: 'fecha_vecimiento', label: 'Vencimiento', className: 'text-nowrap' },
                { key: 'laboratorio', label: 'Laboratorio' },
                { key: 'presentacion', label: 'Presentación' },
            ],
        };
    },
    computed: {
        salesByStore() {
            return this.venta
                .map(item => ({
                    label: item.tienda || 'Sucursal',
                    total: this.toNumber(item.total),
                }))
                .sort((a, b) => b.total - a.total);
        },
        purchasesByMonth() {
            return this.compra
                .map(item => ({
                    month: Number(item.mes),
                    label: this.monthName(item.mes),
                    total: this.toNumber(item.total),
                }))
                .sort((a, b) => a.month - b.month);
        },
        maxSale() {
            return Math.max(...this.salesByStore.map(item => item.total), 0);
        },
        maxPurchase() {
            return Math.max(...this.purchasesByMonth.map(item => item.total), 0);
        },
        annualProducts() {
            const urgentIds = new Set(this.arrayProductoMeses.map(item => item.id));
            return this.arrayProducto.filter(item => !urgentIds.has(item.id));
        },
        visibleUrgentProducts() {
            return this.showAllUrgent
                ? this.arrayProductoMeses
                : this.arrayProductoMeses.slice(0, this.tableLimit);
        },
        visibleAnnualProducts() {
            return this.showAllAnnual
                ? this.annualProducts
                : this.annualProducts.slice(0, this.tableLimit);
        },
        dashboardLoading() {
            return this.loading.summary || this.loading.trends || this.loading.expiry;
        },
    },
    mounted() {
        this.loadDashboard();
    },
    methods: {
        async loadDashboard(isRefresh = false) {
            if (isRefresh && this.dashboardLoading) return;
            const requestId = ++this.requestSequence;
            this.loadError = '';
            this.loading.summary = true;
            this.loading.trends = true;
            this.loading.expiry = true;
            try {
                const response = await axios.get('/dashboard');
                if (requestId !== this.requestSequence) return;

                const payload = response.data || {};
                const summary = payload.resumen || {};
                const expiry = payload.vencimientos || {};

                this.compra = Array.isArray(payload.compra) ? payload.compra : [];
                this.venta = Array.isArray(payload.venta) ? payload.venta : [];
                this.totalVenta = this.toNumber(summary.total_venta);
                this.totalCompra = this.toNumber(summary.total_compra);
                this.totalAdministrador = this.toNumber(summary.administradores);
                this.totalCajero = this.toNumber(summary.cajeros);
                this.arrayProducto = Array.isArray(expiry.anuales) ? expiry.anuales : [];
                this.arrayProductoMeses = Array.isArray(expiry.trimestre) ? expiry.trimestre : [];
                this.showAllUrgent = false;
                this.showAllAnnual = false;
            } catch (error) {
                if (requestId === this.requestSequence) this.captureLoadError(error);
            } finally {
                if (requestId === this.requestSequence) {
                    this.loading.summary = false;
                    this.loading.trends = false;
                    this.loading.expiry = false;
                }
            }
        },
        captureLoadError(error) {
            console.error(error);
            this.loadError = 'Verifica la conexión e intenta actualizar nuevamente.';
        },
        toNumber(value) {
            const number = Number.parseFloat(value);
            return Number.isFinite(number) ? number : 0;
        },
        formatCurrency(value) {
            return `${currencyFormatter.format(this.toNumber(value))} Bs`;
        },
        formatDate(value) {
            return value ? moment(value).format('DD/MM/YYYY') : '—';
        },
        monthName(month) {
            const months = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',
            ];
            return months[Number(month) - 1] || `Mes ${month}`;
        },
        progressWidth(value, maximum) {
            if (!maximum) {
                return 0;
            }
            return Math.max(5, Math.round((this.toNumber(value) / maximum) * 100));
        },
        expiryTone(date) {
            const days = moment(date).startOf('day').diff(moment().startOf('day'), 'days');
            return days <= 30 ? 'danger' : 'warning';
        },
        async downloadExpiryReport(period) {
            const isQuarter = period === 'quarter';
            const url = isQuarter
                ? '/reporte/listarProductoMeses'
                : '/reporte/listarProductoMes';

            this.$set(this.pdfLoading, period, true);

            try {
                const response = await axios.get(url, {
                    params: { anio: this.anio },
                    responseType: 'blob',
                });
                const blob = new Blob([response.data], { type: 'application/pdf' });
                const downloadUrl = URL.createObjectURL(blob);
                window.open(downloadUrl, '_blank');
                window.setTimeout(() => URL.revokeObjectURL(downloadUrl), 60000);
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo generar el reporte',
                    text: 'Intenta nuevamente o comunícate con el administrador.',
                    confirmButtonColor: '#1f8a4c',
                });
            } finally {
                this.$set(this.pdfLoading, period, false);
            }
        },
    },
};
</script>

<style scoped>
.dashboard-module {
    display: grid;
    gap: 1.25rem;
    padding: 0.25rem 0 1.5rem;
}

.dashboard-metrics {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
}

.dashboard-insights,
.dashboard-expiry {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.dashboard-alert {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.85rem 1rem;
    color: #8d2a2a;
    background: #fff1f1;
    border: 1px solid #efb4b4;
    border-radius: 10px;
}

.dashboard-alert div {
    display: flex;
    flex-direction: column;
}

.dashboard-alert span {
    margin-top: 0.15rem;
    font-size: 0.78rem;
}

.dashboard-progress-list {
    display: grid;
    gap: 1rem;
}

.dashboard-progress-item__meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.42rem;
}

.dashboard-progress-item__meta strong {
    overflow: hidden;
    color: #315044;
    font-size: 0.78rem;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dashboard-progress-item__meta span {
    color: #5f716a;
    font-size: 0.76rem;
    font-weight: 700;
    white-space: nowrap;
}

.dashboard-progress-item__track {
    height: 8px;
    overflow: hidden;
    background: #e4eee9;
    border-radius: 999px;
}

.dashboard-progress-item__track span {
    display: block;
    height: 100%;
    background: linear-gradient(90deg, #1f8a4c, #2fae66);
    border-radius: inherit;
}

.dashboard-progress-item__track--cyan span {
    background: linear-gradient(90deg, #0e93b5, #3ec6e0);
}

.dashboard-list-skeleton {
    display: grid;
    gap: 1rem;
}

.dashboard-list-skeleton span {
    display: block;
    width: 100%;
    height: 30px;
    border-radius: 7px;
    background: linear-gradient(90deg, #e4ece8 25%, #f7faf8 50%, #e4ece8 75%);
    background-size: 200% 100%;
    animation: dashboard-loading 1.2s ease-in-out infinite;
}

.dashboard-empty {
    display: flex;
    min-height: 130px;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6f817a;
    text-align: center;
}

.dashboard-empty strong {
    margin-bottom: 0.25rem;
    color: #315044;
}

.dashboard-empty span {
    font-size: 0.76rem;
}

.dashboard-count {
    display: inline-flex;
    align-items: center;
    min-height: 30px;
    padding: 0.3rem 0.65rem;
    color: #17633b;
    font-size: 0.7rem;
    font-weight: 800;
    background: #e5f6ec;
    border: 1px solid #b5dfc5;
    border-radius: 999px;
    white-space: nowrap;
}

.dashboard-count--warning {
    color: #805400;
    background: #fff6dc;
    border-color: #f0d180;
}

.dashboard-date {
    display: inline-flex;
    align-items: center;
    min-height: 25px;
    padding: 0.25rem 0.55rem;
    font-size: 0.71rem;
    font-weight: 800;
    border: 1px solid transparent;
    border-radius: 999px;
}

.dashboard-date--danger {
    color: #a32121;
    background: #fff0f0;
    border-color: #f1b1b1;
}

.dashboard-date--warning {
    color: #805400;
    background: #fff6dc;
    border-color: #f0d180;
}

.dashboard-date--info {
    color: #08708a;
    background: #e7f8fb;
    border-color: #a9dfe8;
}

.dashboard-stock {
    color: #1f6b45;
}

@keyframes dashboard-loading {
    from { background-position: 200% 0; }
    to { background-position: -200% 0; }
}

@media (max-width: 1199.98px) {
    .dashboard-metrics {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 991.98px) {
    .dashboard-insights,
    .dashboard-expiry {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 575.98px) {
    .dashboard-module {
        gap: 1rem;
    }

    .dashboard-metrics {
        grid-template-columns: 1fr;
    }

    .dashboard-alert {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>
