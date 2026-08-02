<template>
    <form class="access-toolbar" @submit.prevent="$emit('search')">
        <label v-if="criteria.length" class="access-toolbar__criterion">
            <span>Buscar por</span>
            <select :value="criterion" @change="$emit('update:criterion', $event.target.value)">
                <option v-for="option in criteria" :key="option.value" :value="option.value">
                    {{ option.label }}
                </option>
            </select>
        </label>
        <label class="access-toolbar__search">
            <span>Término de búsqueda</span>
            <input
                :value="value"
                type="search"
                :placeholder="placeholder"
                @input="$emit('input', $event.target.value); $emit('typing')"
            >
        </label>
        <app-button type="submit" variant="secondary">Buscar</app-button>
        <app-button v-if="value" variant="ghost" @click="$emit('clear')">Limpiar</app-button>
    </form>
</template>

<script>
export default {
    name: 'AccessToolbar',
    props: {
        value: { type: String, default: '' },
        criterion: { type: String, default: '' },
        criteria: { type: Array, default: () => [] },
        placeholder: { type: String, default: 'Escribe para buscar…' },
    },
};
</script>

<style scoped>
.access-toolbar {
    display: flex;
    align-items: flex-end;
    gap: 0.75rem;
    padding: 1rem;
    border-bottom: 1px solid #e1ece7;
    background: #f8fbf9;
}

.access-toolbar label {
    display: grid;
    gap: 0.35rem;
    margin: 0;
}

.access-toolbar label > span {
    color: #577067;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.access-toolbar__search { flex: 1; }
.access-toolbar__criterion { min-width: 180px; }

.access-toolbar input,
.access-toolbar select {
    min-height: 42px;
    padding: 0.55rem 0.75rem;
    border: 1px solid #cfe0d8;
    border-radius: 10px;
    outline: none;
    color: #17362b;
    background: #fff;
}

.access-toolbar input:focus,
.access-toolbar select:focus {
    border-color: #21a669;
    box-shadow: 0 0 0 3px rgba(33, 166, 105, 0.12);
}

@media (max-width: 720px) {
    .access-toolbar { align-items: stretch; flex-direction: column; }
    .access-toolbar label { width: 100%; }
}
</style>
