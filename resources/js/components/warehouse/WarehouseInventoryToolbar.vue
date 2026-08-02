<template>
    <form class="inventory-toolbar" @submit.prevent="$emit('search')">
        <select :value="criterion" :disabled="loading" @change="$emit('update:criterion', $event.target.value)">
            <option value="articulo.nombre_comercial">Producto</option>
            <option value="proveedor.nombre">Laboratorio</option>
            <option value="categoria.nombre">Categoría</option>
            <option value="nombre">Categoría (lote)</option>
        </select>
        <app-input
            :value="search"
            placeholder="Buscar en inventario…"
            :disabled="loading"
            @input="$emit('update:search', $event)"
            @keyup.enter.native="$emit('search')"
        />
        <app-button type="submit" icon="icons/magnifying-glass.svg" :loading="loading">Buscar</app-button>
    </form>
</template>

<script>
export default {
    name: 'WarehouseInventoryToolbar',
    props: {
        search: { type: String, default: '' },
        criterion: { type: String, default: 'articulo.nombre_comercial' },
        loading: { type: Boolean, default: false },
    },
};
</script>

<style scoped>
.inventory-toolbar { display: grid; grid-template-columns: minmax(170px, 230px) minmax(240px, 1fr) auto; gap: .65rem; padding: 1rem; background: #f8fbf9; border-bottom: 1px solid #d8e5df; }
.inventory-toolbar select { min-height: 40px; padding: .5rem .7rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; }
@media (max-width: 700px) { .inventory-toolbar { grid-template-columns: 1fr; } }
</style>
