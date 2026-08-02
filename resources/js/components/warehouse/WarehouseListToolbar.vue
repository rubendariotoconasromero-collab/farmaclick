<template>
    <form class="warehouse-list-toolbar" @submit.prevent="$emit('search')">
        <select :value="criterion" :disabled="loading" @change="$emit('update:criterion', $event.target.value)">
            <option v-for="option in options" :key="option.value" :value="option.value">{{ option.label }}</option>
        </select>
        <app-input
            :value="search"
            :placeholder="placeholder"
            :disabled="loading"
            @input="$emit('update:search', $event)"
            @keyup.enter.native="$emit('search')"
        />
        <app-button type="submit" icon="icons/magnifying-glass.svg" :loading="loading">Buscar</app-button>
    </form>
</template>

<script>
export default {
    name: 'WarehouseListToolbar',
    props: {
        search: { type: String, default: '' },
        criterion: { type: String, required: true },
        options: { type: Array, required: true },
        placeholder: { type: String, default: 'Buscar…' },
        loading: { type: Boolean, default: false },
    },
};
</script>

<style scoped>
.warehouse-list-toolbar { display: grid; grid-template-columns: minmax(170px, 230px) minmax(240px, 1fr) auto; gap: .65rem; padding: 1rem; background: #f8fbf9; border-bottom: 1px solid #d8e5df; }
.warehouse-list-toolbar select { min-height: 40px; padding: .5rem .7rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; }
@media (max-width: 700px) { .warehouse-list-toolbar { grid-template-columns: 1fr; } }
</style>
