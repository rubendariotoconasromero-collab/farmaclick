<template>
    <main class="report-center">
        <app-page-skeleton v-if="loading" />

        <template v-else>
            <app-module-header
                eyebrow="Análisis · Documentos"
                title="Centro de reportes"
                subtitle="Genera documentos operativos, financieros e inventarios desde un catálogo organizado."
            >
                <template #actions>
                    <div class="report-center__tabs" role="tablist" aria-label="Secciones de reportes">
                        <button type="button" :class="{ active: activeTab === 'library' }" @click="activeTab = 'library'">
                            Biblioteca
                        </button>
                        <button type="button" :class="{ active: activeTab === 'cash' }" @click="activeTab = 'cash'">
                            Arqueos
                        </button>
                    </div>
                </template>
            </app-module-header>

            <section class="report-center__metrics">
                <app-metric-card
                    label="Reportes disponibles"
                    :value="reportCount"
                    hint="Documentos configurados"
                    icon="/icons/chart-line.svg"
                />
                <app-metric-card
                    label="Categorías"
                    :value="allSections.length + 3"
                    hint="Información organizada por área"
                    icon="/icons/storage.svg"
                    tone="cyan"
                />
                <app-metric-card
                    label="Período seleccionado"
                    :value="rangeDays"
                    hint="Días incluidos en la consulta"
                    icon="/icons/calendar.svg"
                    tone="neutral"
                />
            </section>

            <template v-if="activeTab === 'library'">
                <app-data-panel
                    eyebrow="Parámetros globales"
                    title="Período de análisis"
                    subtitle="Estas fechas se aplican a compras, ventas, caja y reportes personalizados."
                >
                    <div class="report-center__filters">
                        <app-input v-model="dates.fecha_inicio" type="date" label="Desde" />
                        <span class="report-center__range-line"></span>
                        <app-input v-model="dates.fecha_fin" type="date" label="Hasta" />
                        <app-input v-model.trim="reportSearch" class="report-center__search" type="search" label="Buscar reporte" placeholder="Ej. ventas, inventario, cuotas…" />
                    </div>
                </app-data-panel>

                <section v-if="filteredSections.length" class="report-center__grid">
                    <report-group-card
                        v-for="section in filteredSections"
                        :key="section.title"
                        :title="section.title"
                        :description="sectionDescription(section.title)"
                        :icon="sectionIcon(section.title)"
                        :reports="section.reports"
                        @run="runReport"
                    />
                </section>
                <app-data-panel
                    v-else
                    eyebrow="Sin coincidencias"
                    title="No encontramos reportes"
                    subtitle="Prueba con otro término de búsqueda."
                />

                <app-data-panel
                    eyebrow="Reportes segmentados"
                    title="Consultas por entidad"
                    subtitle="Selecciona una entidad antes de generar reportes personalizados."
                >
                    <div class="report-center__segments">
                        <article v-for="segment in segments" :key="segment.type">
                            <header>
                                <span><img :src="segment.icon" alt="" aria-hidden="true"></span>
                                <div>
                                    <strong>{{ segment.title }}</strong>
                                    <small>{{ selectedName(segment.type) || 'Ninguna selección' }}</small>
                                </div>
                                <app-button variant="secondary" @click="openSelector(segment.type)">
                                    {{ selectedName(segment.type) ? 'Cambiar' : 'Seleccionar' }}
                                </app-button>
                            </header>
                            <div>
                                <button
                                    v-for="report in segment.reports"
                                    :key="report.label"
                                    type="button"
                                    @click="runReport(report)"
                                >
                                    <img :src="report.icon" alt="" aria-hidden="true">
                                    <span>{{ report.label }}</span>
                                    <img src="/icons/file.svg" alt="PDF" aria-hidden="true">
                                </button>
                            </div>
                        </article>
                    </div>
                </app-data-panel>
            </template>

            <template v-else>
                <app-data-panel
                    eyebrow="Cierres de caja"
                    title="Reportes de arqueo"
                    subtitle="Filtra los cierres y genera documentos por forma de pago o un consolidado general."
                    flush
                >
                    <div class="report-center__cash-filters">
                        <app-input v-model="cashFilters.fecha_inicio" type="date" label="Desde" />
                        <app-input v-model="cashFilters.fecha_fin" type="date" label="Hasta" />
                        <app-select
                            v-model="cashFilters.id_usuario"
                            :options="userOptions"
                            label="Usuario"
                            placeholder="Todos los usuarios"
                            :disable-placeholder="false"
                        />
                        <app-button :loading="cashLoading" @click="$emit('load-cash')">Aplicar filtros</app-button>
                    </div>
                    <app-table
                        :columns="cashColumns"
                        :rows="cashRecords"
                        :loading="cashLoading"
                        min-width="900px"
                        empty-title="Sin arqueos"
                        empty-message="No existen cierres de caja para el período seleccionado."
                    >
                        <template #cell-fecha_apertura="{ value }">{{ formatDate(value) }}</template>
                        <template #cell-fecha_cierre="{ value }">{{ value ? formatDate(value) : 'Caja abierta' }}</template>
                        <template #cell-estado="{ row }">
                            <span class="report-center__status" :class="{ 'is-open': row.estado === 'Abierta' }">
                                {{ row.estado || 'Sin estado' }}
                            </span>
                        </template>
                        <template #cell-actions="{ row }">
                            <div v-if="row.estado === 'Cerrada'" class="report-center__cash-actions">
                                <button
                                    v-for="type in cashReportTypes"
                                    :key="type.value"
                                    type="button"
                                    @click="$emit('download-cash', { id: row.id, type: type.value })"
                                >
                                    {{ type.label }}
                                </button>
                            </div>
                            <span v-else class="report-center__pending">Disponible al cerrar caja</span>
                        </template>
                    </app-table>
                </app-data-panel>
            </template>
        </template>

        <div v-if="selectorOpen" class="report-center__backdrop" @click.self="closeSelector">
            <section class="report-center__dialog" role="dialog" aria-modal="true" :aria-label="selectorTitle">
                <header>
                    <div>
                        <span>Filtro específico</span>
                        <h2>{{ selectorTitle }}</h2>
                    </div>
                    <button type="button" aria-label="Cerrar" @click="closeSelector">×</button>
                </header>
                <label class="report-center__selector-search">
                    <span>Buscar</span>
                    <input v-model.trim="selectorSearch" type="search" :placeholder="selectorPlaceholder">
                </label>
                <p v-if="selectorMultiple" class="report-center__selector-hint">Puedes seleccionar más de un laboratorio.</p>
                <div class="report-center__selector-list">
                    <button
                        v-for="item in filteredSelectorItems"
                        :key="item.id"
                        type="button"
                        :class="{ selected: isRowSelected(item) }"
                        @click="toggleSelection(item)"
                    >
                        <span>{{ itemInitials(item) }}</span>
                        <div>
                            <strong>{{ item.nombre }}</strong>
                            <small v-if="item.matricula">Matrícula: {{ item.matricula }}</small>
                        </div>
                        <img class="report-center__check" src="/icons/check.svg" alt="Seleccionado">
                    </button>
                    <p v-if="remoteSelectorLoading" class="report-center__selector-loading">Buscando…</p>
                    <p v-else-if="!filteredSelectorItems.length && selectorType === 'client' && selectorSearch.trim().length < 2">Escribe al menos 2 letras para buscar un cliente.</p>
                    <p v-else-if="!filteredSelectorItems.length">No existen coincidencias.</p>
                </div>
                <footer>
                    <app-button variant="ghost" @click="closeSelector">Cancelar</app-button>
                    <app-button :disabled="!hasTemporarySelection" @click="confirmSelection">Confirmar selección</app-button>
                </footer>
            </section>
        </div>
    </main>
</template>

<script>
export default {
    name: 'ReportCenterWorkspace',
    props: {
        loading: { type: Boolean, default: false },
        dates: { type: Object, required: true },
        cashFilters: { type: Object, required: true },
        baseSections: { type: Array, default: () => [] },
        datedSections: { type: Array, default: () => [] },
        userReports: { type: Array, default: () => [] },
        clientReports: { type: Array, default: () => [] },
        laboratoryReports: { type: Array, default: () => [] },
        users: { type: Array, default: () => [] },
        selectedUser: { type: Object, default: null },
        selectedClient: { type: Object, default: null },
        selectedLaboratories: { type: Array, default: () => [] },
        cashRecords: { type: Array, default: () => [] },
        cashLoading: { type: Boolean, default: false },
    },
    data() {
        return {
            activeTab: 'library',
            reportSearch: '',
            selectorOpen: false,
            selectorType: '',
            selectorSearch: '',
            temporarySelection: null,
            remoteSelectorItems: [],
            remoteSelectorLoading: false,
            remoteSearchTimer: null,
            remoteSearchSequence: 0,
            remoteEndpoints: {
                client: '/cliente/selectCliente',
                laboratory: '/proveedor/selectProveedor',
            },
        };
    },
    computed: {
        allSections() {
            return [...this.baseSections, ...this.datedSections];
        },
        filteredSections() {
            const term = this.normalize(this.reportSearch);
            if (!term) return this.allSections;
            return this.allSections
                .map(section => ({
                    ...section,
                    reports: section.reports.filter(report =>
                        this.normalize(`${section.title} ${report.label}`).includes(term)
                    ),
                }))
                .filter(section => section.reports.length);
        },
        reportCount() {
            return this.allSections.reduce((total, section) => total + section.reports.length, 0)
                + this.userReports.length + this.clientReports.length + this.laboratoryReports.length;
        },
        rangeDays() {
            if (!this.dates.fecha_inicio || !this.dates.fecha_fin) return 0;
            const start = new Date(`${this.dates.fecha_inicio}T00:00:00`);
            const end = new Date(`${this.dates.fecha_fin}T00:00:00`);
            return Math.max(0, Math.round((end - start) / 86400000) + 1);
        },
        segments() {
            return [
                { type: 'user', title: 'Por usuario', icon: '/icons/people.svg', reports: this.userReports },
                { type: 'client', title: 'Por cliente', icon: '/icons/people.svg', reports: this.clientReports },
                { type: 'laboratory', title: 'Por laboratorio', icon: '/icons/storage.svg', reports: this.laboratoryReports },
            ];
        },
        selectorMultiple() {
            return this.selectorType === 'laboratory';
        },
        hasTemporarySelection() {
            return this.selectorMultiple
                ? Boolean(this.temporarySelection && this.temporarySelection.length)
                : Boolean(this.temporarySelection);
        },
        filteredSelectorItems() {
            if (this.selectorType === 'user') {
                const term = this.normalize(this.selectorSearch);
                if (!term) return this.users;
                return this.users.filter(item => this.normalize(item.nombre || '').includes(term));
            }
            return this.remoteSelectorItems;
        },
        selectorTitle() {
            const titles = {
                user: 'Seleccionar usuario',
                client: 'Seleccionar cliente',
                laboratory: 'Seleccionar laboratorio',
            };
            return titles[this.selectorType] || 'Seleccionar';
        },
        selectorPlaceholder() {
            return this.selectorType === 'client'
                ? 'Buscar por nombre o matrícula…'
                : 'Buscar por nombre…';
        },
        cashColumns() {
            return [
                { key: 'usuario', label: 'Usuario' },
                { key: 'fecha_apertura', label: 'Apertura' },
                { key: 'fecha_cierre', label: 'Cierre' },
                { key: 'estado', label: 'Estado' },
                { key: 'actions', label: 'Reportes disponibles' },
            ];
        },
        userOptions() {
            return this.users.map(user => ({ value: user.id, label: user.nombre }));
        },
        cashReportTypes() {
            return [
                { value: 'efectivo', label: 'Efectivo' },
                { value: 'transferencia', label: 'Transferencia' },
                { value: 'deposito', label: 'Depósito' },
                { value: 'qr', label: 'QR' },
                { value: 'mixta', label: 'Mixta' },
                { value: 'general', label: 'General' },
                { value: 'general_detallada', label: 'Detallado' },
            ];
        },
    },
    watch: {
        selectorSearch(termino) {
            if (!this.remoteEndpoints[this.selectorType]) return;
            if (this.selectorType === 'client' && termino.trim().length < 2) {
                this.remoteSelectorItems = [];
                return;
            }
            this.buscarEntidadesRemotas(termino);
        },
    },
    methods: {
        normalize(value) {
            return String(value || '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase();
        },
        sectionDescription(title) {
            const descriptions = {
                Productos: 'Inventario, existencias y vencimientos.',
                Datos: 'Directorios y configuración operativa.',
                Compras: 'Movimientos, pagos y gastos relacionados.',
                Ventas: 'Operaciones, anulaciones y devoluciones.',
                'Forma de Pago Venta': 'Detalle de ingresos por medio de pago.',
            };
            return descriptions[title] || 'Documentos disponibles para esta categoría.';
        },
        sectionIcon(title) {
            const icons = {
                Productos: '/icons/basket.svg',
                Datos: '/icons/storage.svg',
                Compras: '/icons/truck.svg',
                Ventas: '/icons/cart.svg',
                'Forma de Pago Venta': '/icons/money.svg',
            };
            return icons[title] || '/icons/chart-line.svg';
        },
        runReport(report) {
            if (report) this.$emit('run-report', report);
        },
        selectedName(type) {
            if (type === 'user') return this.selectedUser && this.selectedUser.nombre;
            if (type === 'client') return this.selectedClient && this.selectedClient.nombre;
            if (type === 'laboratory') {
                const labs = this.selectedLaboratories || [];
                if (!labs.length) return '';
                return labs.length === 1 ? labs[0].nombre : `${labs.length} laboratorios seleccionados`;
            }
            return '';
        },
        currentSelection(type) {
            if (type === 'user') return this.selectedUser;
            if (type === 'client') return this.selectedClient;
            if (type === 'laboratory') return [...(this.selectedLaboratories || [])];
            return null;
        },
        openSelector(type) {
            this.selectorType = type;
            this.selectorSearch = '';
            this.temporarySelection = this.currentSelection(type);
            this.remoteSelectorItems = [];
            this.selectorOpen = true;
            // El catálogo de laboratorios es pequeño: se puede listar completo de una vez.
            // El de clientes es muy grande: solo se consulta cuando el usuario escribe una búsqueda.
            if (type === 'laboratory') this.buscarEntidadesRemotas('');
        },
        closeSelector() {
            this.selectorOpen = false;
            this.selectorType = '';
            this.selectorSearch = '';
            this.temporarySelection = null;
            this.remoteSelectorItems = [];
            clearTimeout(this.remoteSearchTimer);
        },
        toggleSelection(item) {
            if (!this.selectorMultiple) {
                this.temporarySelection = item;
                return;
            }
            const seleccion = this.temporarySelection || [];
            const existe = seleccion.some(actual => actual.id === item.id);
            this.temporarySelection = existe
                ? seleccion.filter(actual => actual.id !== item.id)
                : [...seleccion, item];
        },
        isRowSelected(item) {
            if (this.selectorMultiple) {
                return (this.temporarySelection || []).some(actual => actual.id === item.id);
            }
            return this.temporarySelection && this.temporarySelection.id === item.id;
        },
        buscarEntidadesRemotas(termino) {
            const endpoint = this.remoteEndpoints[this.selectorType];
            if (!endpoint) return;

            const sequence = ++this.remoteSearchSequence;
            clearTimeout(this.remoteSearchTimer);

            this.remoteSearchTimer = setTimeout(async () => {
                this.remoteSelectorLoading = true;
                try {
                    const { data } = await axios.get(endpoint, {
                        params: termino ? { filtro: termino } : {},
                    });
                    if (sequence === this.remoteSearchSequence) {
                        this.remoteSelectorItems = data.data || data || [];
                    }
                } catch (error) {
                    if (sequence === this.remoteSearchSequence) this.remoteSelectorItems = [];
                } finally {
                    if (sequence === this.remoteSearchSequence) this.remoteSelectorLoading = false;
                }
            }, 250);
        },
        confirmSelection() {
            if (!this.hasTemporarySelection) return;
            this.$emit('select-entity', {
                type: this.selectorType,
                item: this.temporarySelection,
            });
            this.closeSelector();
        },
        itemInitials(item) {
            return String(item.nombre || '?')
                .split(/\s+/)
                .slice(0, 2)
                .map(part => part.charAt(0))
                .join('')
                .toUpperCase();
        },
        formatDate(value) {
            if (!value) return '—';
            const date = new Date(value);
            return date.toLocaleDateString('es-BO', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        },
    },
};
</script>

<style scoped>
.report-center {
    display: grid;
    gap: 1.1rem;
    padding: 1.35rem;
}

.report-center__tabs {
    display: inline-flex;
    gap: 0.25rem;
    padding: 0.25rem;
    border: 1px solid #cfe0d8;
    border-radius: 10px;
    background: #fff;
}

.report-center__tabs button {
    padding: 0.45rem 0.75rem;
    border: 0;
    border-radius: 7px;
    color: #557067;
    background: transparent;
    font-size: 0.74rem;
    font-weight: 800;
}

.report-center__tabs button.active { color: #fff; background: #1f925b; }

.report-center__metrics {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
}

.report-center__filters {
    display: grid;
    grid-template-columns: minmax(160px, 220px) auto minmax(160px, 220px) minmax(260px, 1fr);
    align-items: end;
    gap: 0.8rem;
}

.report-center label {
    display: grid;
    gap: 0.35rem;
    margin: 0;
}

.report-center label > span {
    color: #526b62;
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.report-center input,
.report-center select {
    min-height: 42px;
    padding: 0.55rem 0.75rem;
    border: 1px solid #cfe0d8;
    border-radius: 10px;
    outline: 0;
    color: #17362b;
    background: #fff;
}

.report-center input:focus,
.report-center select:focus {
    border-color: #22a368;
    box-shadow: 0 0 0 3px rgba(34, 163, 104, 0.12);
}

.report-center__range-line { width: 28px; height: 1px; margin-bottom: 21px; background: #9cb5aa; }
.report-center__grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }

.report-center__segments {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
}

.report-center__segments > article {
    overflow: hidden;
    border: 1px solid #dce9e3;
    border-radius: 14px;
}

.report-center__segments article > header {
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 0.65rem;
    padding: 0.85rem;
    background: #f1faf5;
}

.report-center__segments header > span {
    display: grid;
    width: 34px;
    height: 34px;
    place-items: center;
    border-radius: 9px;
    color: #1b8c5b;
    background: #dcf2e6;
}
.report-center__segments header > span img { width: 18px; height: 18px; opacity: 0.8; }

.report-center__segments header div { display: grid; min-width: 0; }
.report-center__segments header strong { color: #17362b; font-size: 0.78rem; }
.report-center__segments header small { overflow: hidden; color: #778a83; font-size: 0.66rem; text-overflow: ellipsis; white-space: nowrap; }
.report-center__segments article > div { display: grid; gap: 0.35rem; padding: 0.5rem; }

.report-center__segments article > div button {
    display: grid;
    grid-template-columns: 24px 1fr auto;
    align-items: center;
    gap: 0.45rem;
    padding: 0.55rem;
    border: 0;
    border-radius: 8px;
    color: #315448;
    background: transparent;
    font-size: 0.72rem;
    text-align: left;
}

.report-center__segments article > div button:hover { background: #eff8f3; }
.report-center__segments article > div button > img:first-child { width: 16px; height: 16px; opacity: 0.78; }
.report-center__segments article > div button > img:last-child { width: 15px; height: 15px; opacity: 0.55; }

.report-center__cash-filters {
    display: grid;
    grid-template-columns: 180px 180px minmax(220px, 1fr) auto;
    align-items: end;
    gap: 0.75rem;
    padding: 1rem;
    border-bottom: 1px solid #e0ebe6;
    background: #f8fbf9;
}

.report-center__status {
    display: inline-flex;
    padding: 0.3rem 0.65rem;
    border-radius: 999px;
    color: #36594c;
    background: #e9efec;
    font-size: 0.69rem;
    font-weight: 800;
}

.report-center__status.is-open { color: #14784e; background: #e2f6eb; }
.report-center__cash-actions { display: flex; flex-wrap: wrap; gap: 0.3rem; }

.report-center__cash-actions button {
    padding: 0.28rem 0.45rem;
    border: 1px solid #c9ddd4;
    border-radius: 6px;
    color: #27614b;
    background: #fff;
    font-size: 0.62rem;
    font-weight: 700;
}

.report-center__cash-actions button:hover { border-color: #2ca56c; background: #edf8f2; }
.report-center__pending { color: #84938e; font-size: 0.68rem; }

.report-center__backdrop {
    position: fixed;
    z-index: 1100;
    inset: 0;
    display: grid;
    place-items: center;
    padding: 1rem;
    background: rgba(10, 35, 27, 0.58);
}

.report-center__dialog {
    width: min(700px, 100%);
    max-height: 88vh;
    overflow: hidden;
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 24px 70px rgba(8, 38, 27, 0.26);
}

.report-center__dialog > header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.2rem;
    border-bottom: 1px solid #deebe5;
}

.report-center__dialog header span { color: #20905f; font-size: 0.67rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; }
.report-center__dialog h2 { margin: 0.15rem 0 0; color: #17362b; font-size: 1.15rem; }
.report-center__dialog header button { border: 0; color: #557067; background: transparent; font-size: 1.8rem; }
.report-center__selector-search { padding: 0.9rem 1rem; border-bottom: 1px solid #e2ece7; background: #f8fbf9; }

.report-center__selector-list {
    display: grid;
    gap: 0.35rem;
    max-height: 430px;
    overflow: auto;
    padding: 0.65rem;
}

.report-center__selector-list button {
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 0.7rem;
    padding: 0.65rem;
    border: 1px solid transparent;
    border-radius: 10px;
    color: #315448;
    background: #fff;
    text-align: left;
}

.report-center__selector-list button:hover,
.report-center__selector-list button.selected { border-color: #84c9a7; background: #eff9f4; }
.report-center__selector-list button > span {
    display: grid;
    width: 34px;
    height: 34px;
    place-items: center;
    border: 2px solid #fff;
    outline: 2px solid #1f965e;
    outline-offset: 1px;
    border-radius: 9px;
    color: #fff;
    background: linear-gradient(145deg, #1f965e, #1ca3ba);
    font-size: 0.66rem;
    font-weight: 900;
}
.report-center__selector-list button div { display: grid; }
.report-center__selector-list button small { color: #788b83; font-size: 0.66rem; }
.report-center__check { visibility: hidden; width: 17px; height: 17px; }
.report-center__selector-list button.selected .report-center__check { visibility: visible; }
.report-center__selector-list > p { padding: 2rem; color: #778a83; text-align: center; }
.report-center__selector-hint { margin: 0; padding: 0.6rem 1rem 0; color: #557067; font-size: 0.68rem; }
.report-center__selector-loading { padding: 1.2rem; color: #778a83; text-align: center; }
.report-center__dialog footer { display: flex; justify-content: flex-end; gap: 0.65rem; padding: 0.9rem 1rem; border-top: 1px solid #e2ece7; }

@media (max-width: 1050px) {
    .report-center__segments { grid-template-columns: 1fr; }
}

@media (max-width: 850px) {
    .report-center__metrics,
    .report-center__grid { grid-template-columns: 1fr; }
    .report-center__filters,
    .report-center__cash-filters { grid-template-columns: 1fr; }
    .report-center__range-line { display: none; }
}

@media (max-width: 720px) {
    .report-center { padding: 0.9rem; }
}
</style>
