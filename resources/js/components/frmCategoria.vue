<template>
    <warehouse-catalog-workspace
        title="Categorías"
        singular="Categoría"
        subtitle="Clasifica los productos para agilizar búsquedas, reportes y operaciones de almacén."
        endpoint="/categoria"
    />
</template>

<script>
import WarehouseCatalogWorkspace from './warehouse/WarehouseCatalogWorkspace.vue';

export default {
    name: 'FrmCategoria',
    components: { WarehouseCatalogWorkspace },
};
</script>
