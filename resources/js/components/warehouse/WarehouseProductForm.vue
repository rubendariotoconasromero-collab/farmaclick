<template>
    <div class="warehouse-product-form">
        <app-data-panel eyebrow="Identificación" title="Información del producto" subtitle="Datos utilizados para búsquedas y documentos.">
            <div class="warehouse-product-form__grid">
                <app-input v-model="data.nombre_comercial" label="Nombre comercial" :error="errorFor('nombre_comercial')" required />
                <app-input v-model="data.nombre_generico" label="Nombre genérico" :error="errorFor('nombre_generico')" />
                <app-input v-model="data.cod_proveedor" label="Código del proveedor" />
                <app-input v-model="data.cod_barra" label="Código de barras" />
                <app-input v-model="data.composicion" label="Concentración / composición" />
                <app-input v-model="data.ubicacion" label="Ubicación" placeholder="Estante o sector" />
                <app-input v-model="data.descripcion" label="Acción terapéutica" multiline :rows="3" />
            </div>
        </app-data-panel>

        <app-data-panel eyebrow="Clasificación" title="Catálogos relacionados" subtitle="Relaciona presentación, categoría, laboratorio y línea.">
            <div class="warehouse-product-form__grid">
                <warehouse-select v-model="data.id_unidad" label="Presentación" :options="units" :error="errorFor('id_unidad')" required />
                <warehouse-select v-model="data.id_categoria" label="Categoría" :options="categories" :error="errorFor('id_categoria')" required />
                <warehouse-select v-model="data.id_proveedor" label="Laboratorio" :options="providers" :error="errorFor('id_proveedor')" required />
                <warehouse-select v-model="data.id_marca" label="Línea" :options="lines" :error="errorFor('id_marca')" required />
            </div>
        </app-data-panel>

        <app-data-panel eyebrow="Precios y empaque" title="Configuración comercial" subtitle="Valores de compra, venta y equivalencias por empaque.">
            <div class="warehouse-product-form__grid warehouse-product-form__grid--numbers">
                <app-input v-model.number="data.costo_compra" type="number" label="Precio de compra" min="0" step="0.01" />
                <app-input v-model.number="data.costo_compra_caja" type="number" label="Compra por caja" min="0" step="0.01" />
                <app-input v-model.number="data.venta_presentacion" type="number" label="Unidades por presentación" min="0" />
                <app-input v-model.number="data.costo_unitario" type="number" label="Precio unitario" min="0" step="0.01" />
                <app-input v-model.number="data.cantidad_blister" type="number" label="Cantidad de blísteres" min="0" />
                <app-input v-model.number="data.precio_blister" type="number" label="Precio por blíster" min="0" step="0.01" />
                <app-input v-model.number="data.cantidad_caja" type="number" label="Cantidad por caja" min="0" />
                <app-input v-model.number="data.precio_caja" type="number" label="Precio por caja" min="0" step="0.01" />
                <app-input v-model.number="data.stock_minimo" type="number" label="Stock mínimo" min="0" />
            </div>
        </app-data-panel>

        <app-data-panel eyebrow="Control" title="Condiciones y estado" subtitle="Características especiales para almacenamiento y dispensación.">
            <div class="warehouse-product-form__checks">
                <label><input v-model="data.psicotropico" type="checkbox" :true-value="1" :false-value="0"> Psicotrópico</label>
                <label><input v-model="data.refrigerado" type="checkbox" :true-value="1" :false-value="0"> Requiere refrigeración</label>
                <label class="warehouse-product-form__state">
                    <span>Estado</span>
                    <select v-model="data.estado"><option value="1">Activo</option><option value="0">Inactivo</option></select>
                </label>
            </div>
        </app-data-panel>
    </div>
</template>

<script>
import WarehouseSelect from './WarehouseSelect.vue';

export default {
    name: 'WarehouseProductForm',
    components: { WarehouseSelect },
    props: {
        data: { type: Object, required: true },
        errors: { type: Object, default: () => ({}) },
        categories: { type: Array, default: () => [] },
        units: { type: Array, default: () => [] },
        providers: { type: Array, default: () => [] },
        lines: { type: Array, default: () => [] },
    },
    methods: {
        errorFor(field) {
            const value = this.errors[field];
            return Array.isArray(value) ? value[0] : (value || '');
        },
    },
};
</script>

<style scoped>
.warehouse-product-form { display: grid; gap: 1rem; }
.warehouse-product-form__grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
.warehouse-product-form__grid--numbers { grid-template-columns: repeat(3, minmax(0, 1fr)); }
.warehouse-product-form__checks { display: flex; flex-wrap: wrap; align-items: center; gap: 1rem 1.5rem; }
.warehouse-product-form__checks > label:not(.warehouse-product-form__state) { display: flex; align-items: center; gap: .5rem; color: #315044; font-size: .76rem; font-weight: 800; }
.warehouse-product-form__checks input { width: 17px; height: 17px; accent-color: #1f8a4c; }
.warehouse-product-form__state { display: grid; min-width: 180px; gap: .35rem; color: #315044; font-size: .72rem; font-weight: 800; }
.warehouse-product-form__state select { min-height: 40px; padding: .5rem .7rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; }
@media (max-width: 850px) { .warehouse-product-form__grid, .warehouse-product-form__grid--numbers { grid-template-columns: 1fr; } }
</style>
