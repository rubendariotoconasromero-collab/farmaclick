<template>
    <section class="reasons-page">
        <app-module-header
            eyebrow="Gastos"
            title="Motivos de gasto"
            subtitle="Administre el catálogo utilizado para clasificar y analizar los egresos."
        >
            <template #actions>
                <app-button icon="icons/plus.svg" @click="$emit('create')">Nuevo motivo</app-button>
            </template>
        </app-module-header>

        <div class="reasons-overview">
            <app-metric-card
                label="Motivos registrados"
                :value="count"
                hint="Categorías disponibles"
                icon="icons/tags.svg"
                tone="green"
            />
            <div class="reasons-help">
                <img :src="asset('icons/info.svg')" alt="" aria-hidden="true">
                <div>
                    <strong>Catálogo de clasificación</strong>
                    <span>Use nombres breves y descripciones específicas para mantener reportes de gastos consistentes.</span>
                </div>
            </div>
        </div>

        <app-data-panel
            title="Catálogo de motivos"
            subtitle="Busque y actualice las categorías existentes."
            eyebrow="Administración"
            flush
        >
            <div class="reasons-toolbar">
                <app-input
                    :value="search"
                    label="Buscar por nombre"
                    placeholder="Ej. Servicios, transporte…"
                    @input="$emit('update:search', $event)"
                    @keyup.enter="$emit('search')"
                />
                <app-button icon="icons/magnifying-glass.svg" @click="$emit('search')">Buscar</app-button>
            </div>
            <app-table
                :columns="columns"
                :rows="rows"
                min-width="640px"
                empty-title="Sin motivos registrados"
                empty-message="Cree el primer motivo para comenzar a clasificar gastos."
            >
                <template #cell-nombre="{ value }">
                    <span class="reason-name"><img :src="asset('icons/tag.svg')" alt="" aria-hidden="true"><strong>{{ value }}</strong></span>
                </template>
                <template #cell-descripcion="{ value }"><span class="reason-description">{{ value || 'Sin descripción' }}</span></template>
                <template #cell-actions="{ row }">
                    <button class="reason-edit" type="button" @click="$emit('edit', row)">
                        <img :src="asset('icons/pencil.svg')" alt="" aria-hidden="true"> Modificar
                    </button>
                </template>
            </app-table>
            <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
        </app-data-panel>

        <div v-if="modal" class="expense-dialog-backdrop" role="presentation" @click.self="$emit('close')">
            <section class="expense-dialog" role="dialog" aria-modal="true" aria-labelledby="reason-dialog-title">
                <header>
                    <div>
                        <span>Catálogo de gastos</span>
                        <h2 id="reason-dialog-title">{{ action === 1 ? 'Nuevo motivo' : 'Modificar motivo' }}</h2>
                    </div>
                    <button type="button" aria-label="Cerrar" @click="$emit('close')">×</button>
                </header>
                <div class="expense-dialog__body">
                    <app-input
                        v-model="datos.nombre"
                        label="Nombre del motivo"
                        placeholder="Nombre breve y reconocible"
                        required
                        :error="fieldError('nombre')"
                    />
                    <app-input
                        v-model="datos.descripcion"
                        label="Descripción"
                        placeholder="Indique cuándo debe utilizarse este motivo"
                        multiline
                        :rows="3"
                    />
                    <div v-if="validationErrors.length" class="reason-errors">
                        <span v-for="error in validationErrors" :key="error">{{ error }}</span>
                    </div>
                </div>
                <footer>
                    <app-button variant="secondary" @click="$emit('close')">Cancelar</app-button>
                    <app-button icon="icons/save.svg" :loading="saving" @click="$emit(action === 1 ? 'save' : 'update')">
                        {{ action === 1 ? 'Guardar motivo' : 'Guardar cambios' }}
                    </app-button>
                </footer>
            </section>
        </div>
    </section>
</template>

<script>
export default {
    name: 'ExpenseReasonsWorkspace',
    props: {
        rows: { type: Array, default: () => [] },
        datos: { type: Object, required: true },
        count: { type: [Number, String], default: 0 },
        modal: { type: [Number, Boolean], default: false },
        action: { type: Number, default: 1 },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        search: { type: String, default: '' },
        serverErrors: { type: Object, default: () => ({}) },
        validationErrors: { type: Array, default: () => [] },
        saving: { type: Boolean, default: false },
    },
    data() {
        return {
            columns: [
                { key: 'nombre', label: 'Motivo' },
                { key: 'descripcion', label: 'Descripción' },
                { key: 'actions', label: 'Acciones' },
            ],
        };
    },
    methods: {
        fieldError(field) {
            const value = this.serverErrors[field];
            return Array.isArray(value) ? value[0] : (value || '');
        },
        asset(path) {
            const index = window.location.pathname.indexOf('/main');
            const base = index >= 0 ? window.location.pathname.substring(0, index) : '';
            return `${base}/${path}`;
        },
    },
};
</script>

<style scoped>
.reasons-page { display: grid; gap: 1rem; padding: 1.15rem; background: #f4f8f6; }
.reasons-overview { display: grid; grid-template-columns: minmax(260px, .35fr) minmax(0, 1fr); gap: 1rem; }
.reasons-help { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.2rem; color: #315044; background: linear-gradient(110deg, #e8f9fc, #fff); border: 1px solid #cae9ef; border-radius: 14px; box-shadow: 0 6px 18px rgba(23,54,43,.05); }
.reasons-help img { width: 34px; height: 34px; opacity: .7; filter: invert(47%) sepia(72%) saturate(653%) hue-rotate(147deg); }
.reasons-help strong, .reasons-help span { display: block; }
.reasons-help strong { color: #17362b; font-size: .85rem; }
.reasons-help span { margin-top: .24rem; color: #6f817a; font-size: .75rem; line-height: 1.45; }
.reasons-toolbar { display: grid; grid-template-columns: minmax(260px, 1fr) auto; align-items: end; gap: .6rem; max-width: 720px; padding: 1rem; background: #f8fbf9; border-bottom: 1px solid #d8e5df; }
.reason-name { display: inline-flex; align-items: center; gap: .5rem; color: #17362b; }
.reason-name img { width: 16px; height: 16px; filter: invert(42%) sepia(48%) saturate(691%) hue-rotate(94deg); }
.reason-description { color: #5f716a; }
.reason-edit { display: inline-flex; align-items: center; gap: .35rem; min-height: 32px; padding: .35rem .55rem; color: #315044; font-size: .7rem; font-weight: 800; background: #fff; border: 1px solid #cbdcd4; border-radius: 6px; }
.reason-edit:hover { color: #17693c; background: #effaf4; border-color: #2fae66; }
.reason-edit img { width: 14px; height: 14px; filter: invert(42%) sepia(18%) saturate(647%) hue-rotate(100deg); }
.expense-dialog-backdrop { position: fixed; inset: 0; z-index: 1055; display: grid; padding: 1rem; place-items: center; background: rgba(9,33,26,.58); backdrop-filter: blur(3px); }
.expense-dialog { overflow: hidden; width: min(620px, 100%); background: #fff; border-radius: 14px; box-shadow: 0 24px 70px rgba(0,0,0,.25); }
.expense-dialog header { display: flex; align-items: flex-start; justify-content: space-between; padding: 1rem 1.15rem; color: #fff; background: linear-gradient(110deg, #173f32, #1f8a4c); border-bottom: 3px solid #3ec6e0; }
.expense-dialog header span { color: #71d5e8; font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
.expense-dialog h2 { margin: .1rem 0 0; font-size: 1.05rem; font-weight: 800; }
.expense-dialog header button { color: #fff; font-size: 1.5rem; line-height: 1; background: transparent; border: 0; }
.expense-dialog__body { display: grid; gap: .85rem; padding: 1.15rem; }
.expense-dialog footer { display: flex; justify-content: flex-end; gap: .5rem; padding: .85rem 1.15rem; background: #f4f8f6; border-top: 1px solid #d8e5df; }
.reason-errors { display: flex; flex-direction: column; gap: .2rem; padding: .65rem .75rem; color: #a52b2b; font-size: .72rem; background: #fff0f0; border: 1px solid #f0caca; border-radius: 8px; }
@media (max-width: 650px) { .reasons-page { padding: .75rem; } .reasons-overview, .reasons-toolbar { grid-template-columns: 1fr; } .expense-dialog footer { flex-direction: column-reverse; } }
</style>
