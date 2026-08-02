<template>
    <div id="salesCustomerModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content picker-modal">
                <div class="modal-header">
                    <div><small>Directorio de clientes</small><h5>Seleccionar cliente</h5></div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="picker-modal__search">
                        <app-input :value="search" placeholder="Nombre, NIT o CI…" @input="$emit('update:search', $event)" @keyup.enter="$emit('search')" />
                        <app-button icon="icons/magnifying-glass.svg" @click="$emit('search')">Buscar</app-button>
                    </div>
                    <app-table :columns="columns" :rows="customers" min-width="720px" empty-title="Sin clientes" empty-message="No se encontraron clientes con ese término.">
                        <template #cell-nombre="{ row }"><strong>{{ row.nombre }}</strong><small>{{ row.direccion || 'Sin dirección registrada' }}</small></template>
                        <template #cell-descuento="{ value }"><span class="picker-modal__rate">{{ rate(value) }}</span></template>
                        <template #cell-action="{ row }"><app-button variant="secondary" data-bs-dismiss="modal" @click="$emit('select', row)">Seleccionar</app-button></template>
                    </app-table>
                </div>
                <div class="modal-footer"><app-button variant="ghost" data-bs-dismiss="modal" @click="$emit('close')">Cerrar</app-button></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SalesCustomerPickerModal',
    props: {
        customers: { type: Array, default: () => [] },
        search: { type: String, default: '' },
    },
    data() {
        return {
            columns: [
                { key: 'nombre', label: 'Cliente' }, { key: 'matricula', label: 'NIT / CI' },
                { key: 'telefono', label: 'Teléfono' }, { key: 'descuento', label: 'Tarifa' },
                { key: 'action', label: '' },
            ],
        };
    },
    methods: {
        rate(value) {
            return ({ 1: 'Unitaria', 2: 'Mayorista', 3: 'Preferencial' })[Number(value)] || 'Unitaria';
        },
    },
};
</script>

<style scoped>
.picker-modal { overflow: hidden; border: 0; border-radius: var(--system-radius-lg, 14px); box-shadow: var(--system-shadow-lg, 0 24px 70px rgba(10,56,42,.26)); }
.picker-modal .modal-header { color: #fff; background: linear-gradient(110deg, var(--system-sidebar-bg, #163f32), var(--fc-green-600, #1f8a4c)); border-bottom: 3px solid var(--fc-cyan-500, #3ec6e0); }
.picker-modal .modal-header h5 { margin: .1rem 0 0; font-weight: 800; }
.picker-modal .modal-header small { color: #80dcec; font-size: .65rem; font-weight: 900; text-transform: uppercase; letter-spacing: .07em; }
.picker-modal__search { display: grid; grid-template-columns: 1fr auto; gap: .55rem; margin-bottom: 1rem; }
.picker-modal ::v-deep .app-table td strong { display: block; color: var(--fc-ink, #17362b); }
.picker-modal ::v-deep .app-table td small { color: var(--system-text-muted, #5f716a); font-size: .65rem; }
.picker-modal__rate { display: inline-flex; padding: .25rem .5rem; color: var(--fc-blue-600, #0e93b5); font-size: .67rem; font-weight: 800; background: var(--fc-cyan-50, #effbfd); border-radius: 999px; }
@media (max-width: 600px) { .picker-modal__search { grid-template-columns: 1fr; } }
</style>
