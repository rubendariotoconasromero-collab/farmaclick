<template>
    <section class="warehouse-module-intro">
        <app-module-header eyebrow="Almacén" :title="title" :subtitle="subtitle">
            <template v-if="$slots.actions" #actions>
                <slot name="actions"></slot>
            </template>
        </app-module-header>
        <div class="warehouse-module-intro__metrics">
            <app-metric-card
                :label="primaryLabel"
                :value="primaryValue"
                :hint="primaryHint"
                icon="img/menu/Almacen.png"
            />
            <app-metric-card
                :label="secondaryLabel"
                :value="secondaryValue"
                :hint="secondaryHint"
                icon="img/menu/control.png"
                tone="cyan"
            />
            <app-metric-card
                :label="tertiaryLabel"
                :value="tertiaryValue"
                :hint="tertiaryHint"
                icon="img/menu/historial.png"
                tone="neutral"
            />
        </div>
    </section>
</template>

<script>
export default {
    name: 'WarehouseModuleIntro',
    props: {
        title: { type: String, required: true },
        subtitle: { type: String, required: true },
        primaryLabel: { type: String, default: 'Registros' },
        primaryValue: { type: [String, Number], default: 0 },
        primaryHint: { type: String, default: 'Información disponible' },
        secondaryLabel: { type: String, default: 'En esta vista' },
        secondaryValue: { type: [String, Number], default: 0 },
        secondaryHint: { type: String, default: 'Elementos mostrados' },
        tertiaryLabel: { type: String, default: 'Estado' },
        tertiaryValue: { type: [String, Number], default: 'Actualizado' },
        tertiaryHint: { type: String, default: 'Datos listos para operar' },
    },
};
</script>

<style>
.warehouse-module-intro { display: grid; gap: 1rem; margin-bottom: 1rem; }
.warehouse-module-intro__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.warehouse-legacy { padding: 1.25rem !important; background: #f4f8f6; }
.warehouse-legacy > .row { margin: 0; }
.warehouse-legacy > .row > .col { padding: 0; }
.warehouse-legacy > .row > .col > .card { overflow: hidden; margin: 0; background: #fff; border: 1px solid #d8e5df; border-radius: 14px; box-shadow: 0 6px 18px rgba(23, 54, 43, .065); }
.warehouse-legacy > .row > .col > .card > .card-header.text-center.text-white { display: none; }
.warehouse-legacy .card-header { color: #17362b; background: #f8fbf9 !important; border-color: #d8e5df; }
.warehouse-legacy .table-responsive { margin: 0; padding: 0 1rem 1rem; }
.warehouse-legacy table.table { width: 100% !important; margin: 0 !important; color: #425c52; border-color: #e3ece8; }
.warehouse-legacy table.table thead { background: #edf7f1 !important; }
.warehouse-legacy table.table thead th { padding: .72rem .75rem; color: #315044 !important; font-size: .68rem; text-transform: uppercase; letter-spacing: .035em; border-color: #cfe0d8; }
.warehouse-legacy table.table tbody td { padding: .72rem .75rem; font-size: .78rem; vertical-align: middle; }
.warehouse-legacy table.table tbody tr:hover { background: #f8fbf9; }
.warehouse-legacy .form-control, .warehouse-legacy .form-select, .warehouse-legacy .form-controls { min-height: 40px; color: #17362b; border-color: #bdd2c9; border-radius: 8px; }
.warehouse-legacy .form-control:focus, .warehouse-legacy .form-select:focus, .warehouse-legacy .form-controls:focus { border-color: #0e93b5; box-shadow: 0 0 0 3px rgba(62, 198, 224, .18); }
.warehouse-legacy .btn-info { color: #fff !important; background: #1f8a4c !important; border-color: #1f8a4c !important; }
.warehouse-legacy .btn-info:hover { background: #17693c !important; border-color: #17693c !important; }
.warehouse-legacy .nav-tabs { gap: .35rem; padding: .8rem 1rem 0; border-color: #d8e5df; }
.warehouse-legacy .nav-tabs .nav-link { color: #5f716a; font-size: .78rem; font-weight: 800; border-radius: 8px 8px 0 0; }
.warehouse-legacy .nav-tabs .nav-link.active { color: #17693c; background: #effaf4; border-color: #b9d6ca #b9d6ca #effaf4; }
.warehouse-legacy .modal-content { overflow: hidden; border: 0; border-radius: 14px; box-shadow: 0 24px 70px rgba(10, 35, 27, .28); }
.warehouse-legacy .modal-header { color: #fff; background: linear-gradient(115deg, #163f32, #1f8a4c) !important; border-bottom: 3px solid #3ec6e0; }
.warehouse-legacy .pagination .page-link { color: #315044; border-color: #cbdcd4; }
.warehouse-legacy .pagination .active .page-link { color: #fff; background: #1f8a4c; border-color: #1f8a4c; }
@media (max-width: 820px) { .warehouse-module-intro__metrics { grid-template-columns: 1fr; } }
@media (max-width: 640px) { .warehouse-legacy { padding: .75rem !important; } }
</style>
