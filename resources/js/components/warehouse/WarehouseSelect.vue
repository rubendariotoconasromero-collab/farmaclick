<template>
    <label class="warehouse-select">
        <span>{{ label }} <b v-if="required">*</b></span>
        <select :value="value" @change="$emit('input', Number($event.target.value))">
            <option :value="0" disabled>Selecciona {{ label.toLowerCase() }}</option>
            <option v-for="option in options" :key="option.id" :value="option.id">{{ option.nombre }}</option>
        </select>
        <small v-if="error">{{ error }}</small>
    </label>
</template>

<script>
export default {
    name: 'WarehouseSelect',
    props: {
        value: { type: [String, Number], default: 0 },
        label: { type: String, required: true },
        options: { type: Array, default: () => [] },
        error: { type: String, default: '' },
        required: { type: Boolean, default: false },
    },
};
</script>

<style scoped>
.warehouse-select { display: grid; gap: .35rem; color: #315044; font-size: .72rem; font-weight: 800; }
.warehouse-select b, .warehouse-select small { color: #d63c3c; }
.warehouse-select small { font-size: .7rem; }
.warehouse-select select { min-height: 40px; padding: .5rem .7rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; }
.warehouse-select select:focus { border-color: #0e93b5; box-shadow: 0 0 0 3px rgba(62, 198, 224, .18); outline: 0; }
</style>
