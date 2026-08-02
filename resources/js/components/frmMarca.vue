<template>
    <warehouse-catalog-workspace
        title="Líneas"
        singular="Línea"
        subtitle="Organiza las líneas o laboratorios comerciales asociados al catálogo de productos."
        endpoint="/marca"
    />
</template>

<script>
import WarehouseCatalogWorkspace from './warehouse/WarehouseCatalogWorkspace.vue';

export default {
    name: 'FrmMarca',
    components: { WarehouseCatalogWorkspace },
};
</script>
