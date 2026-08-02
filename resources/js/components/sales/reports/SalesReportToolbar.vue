<template>
    <div class="sales-report-toolbar">
        <app-input v-if="showDates" label="Desde" type="date" :value="startDate" @input="$emit('update:startDate', $event)" />
        <app-input v-if="showDates" label="Hasta" type="date" :value="endDate" @input="$emit('update:endDate', $event)" />
        <label v-if="criteria.length" class="sales-report-toolbar__field">
            <span>Buscar por</span>
            <select :value="criterion" class="form-select" @change="$emit('update:criterion', $event.target.value)">
                <option v-for="option in criteria" :key="option.value" :value="option.value">{{ option.label }}</option>
            </select>
        </label>
        <app-input
            label="Texto de búsqueda"
            :value="search"
            :placeholder="placeholder"
            @input="$emit('update:search', $event)"
            @keyup.enter.native="$emit('search')"
        />
        <label v-if="providers.length" class="sales-report-toolbar__field">
            <span>Laboratorio</span>
            <select :value="providerId" class="form-select" @change="$emit('update:providerId', Number($event.target.value))">
                <option :value="0">Todos</option>
                <option v-for="provider in providers" :key="provider.id" :value="provider.id">{{ provider.nombre }}</option>
            </select>
        </label>
        <app-button icon="icons/magnifying-glass.svg" :disabled="loading" @click="$emit('search')">Buscar</app-button>
    </div>
</template>

<script>
export default {
    name: 'SalesReportToolbar',
    props: {
        showDates: { type: Boolean, default: false },
        startDate: { type: String, default: '' },
        endDate: { type: String, default: '' },
        criteria: { type: Array, default: () => [] },
        criterion: { type: String, default: '' },
        search: { type: String, default: '' },
        placeholder: { type: String, default: 'Escriba para buscar...' },
        providers: { type: Array, default: () => [] },
        providerId: { type: Number, default: 0 },
        loading: { type: Boolean, default: false },
    },
};
</script>

<style scoped>
.sales-report-toolbar { display: grid; grid-template-columns: repeat(auto-fit, minmax(155px, 1fr)); gap: .65rem; align-items: end; padding: 1rem; background: var(--system-soft-bg, #f8fbf9); border-bottom: 1px solid var(--system-border-color, #d8e5df); }
.sales-report-toolbar__field { display: grid; gap: .35rem; margin: 0; }
.sales-report-toolbar__field > span { color: #315044; font-size: .73rem; font-weight: 800; }
.sales-report-toolbar__field .form-select { min-height: 40px; border-color: #bdd2c9; border-radius: 8px; }
</style>
