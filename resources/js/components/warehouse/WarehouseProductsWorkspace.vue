<template>
    <warehouse-page-skeleton v-if="loading" :columns="9" />
    <main v-else class="warehouse-products">
        <app-module-header
            eyebrow="Almacén · Catálogo"
            :title="view === 'form' ? (editing ? 'Editar producto' : 'Nuevo producto') : 'Productos'"
            :subtitle="view === 'form' ? 'Completa la ficha técnica y comercial manteniendo la clasificación del almacén.' : 'Administra el catálogo farmacéutico, precios, presentaciones y condiciones de conservación.'"
        >
            <template #actions>
                <app-button v-if="view === 'form'" variant="ghost" @click="$emit('cancel')">Volver</app-button>
                <app-button v-else icon="img/menu/Almacen.png" @click="$emit('create')">Nuevo producto</app-button>
            </template>
        </app-module-header>

        <section class="warehouse-products__metrics">
            <app-metric-card label="Productos" :value="pagination.total || productCount || rows.length" hint="Catálogo registrado" icon="img/menu/Almacen.png" />
            <app-metric-card label="Categorías" :value="categoryCount" hint="Clasificaciones disponibles" icon="img/menu/configuracion.png" tone="cyan" />
            <app-metric-card label="Laboratorios" :value="providerCount" hint="Proveedores relacionados" icon="img/menu/control.png" tone="neutral" />
        </section>

        <app-data-panel v-if="view === 'list'" eyebrow="Consulta" title="Catálogo de productos" subtitle="Busca por nombre, categoría o laboratorio." flush>
            <warehouse-list-toolbar
                :search="search"
                :criterion="criterion"
                :loading="tableLoading"
                :options="criteria"
                placeholder="Buscar productos…"
                @update:search="$emit('update:search', $event)"
                @update:criterion="$emit('update:criterion', $event)"
                @search="$emit('search')"
            />
            <app-table
                :columns="columns"
                :rows="rows"
                :loading="tableLoading"
                row-key="id"
                min-width="1120px"
                empty-title="Catálogo sin productos"
                empty-message="No se encontraron productos con los filtros actuales."
            >
                <template #cell-nombre_comercial="{ value }"><strong>{{ value }}</strong></template>
                <template #cell-costo_compra="{ value }">{{ money(value) }}</template>
                <template #cell-costo_unitario="{ value }">{{ money(value) }}</template>
                <template #cell-precio_blister="{ value }">{{ money(value) }}</template>
                <template #cell-precio_caja="{ value }">{{ money(value) }}</template>
                <template #cell-estado="{ row }">
                    <span class="warehouse-products__status" :class="{ 'is-inactive': Number(row.estado) !== 1 }">
                        {{ Number(row.estado) === 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                </template>
                <template #cell-actions="{ row }">
                    <div class="warehouse-products__actions">
                        <app-button variant="secondary" @click="$emit('edit', row)">Editar</app-button>
                        <app-button :variant="Number(row.estado) === 1 ? 'danger' : 'secondary'" @click="$emit('toggle', row)">
                            {{ Number(row.estado) === 1 ? 'Desactivar' : 'Activar' }}
                        </app-button>
                    </div>
                </template>
            </app-table>
            <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
        </app-data-panel>

        <template v-else>
            <warehouse-product-form
                :data="data"
                :errors="errors"
                :categories="categories"
                :units="units"
                :providers="providers"
                :lines="lines"
            />
            <footer class="warehouse-products__footer">
                <span>Los campos marcados con * son obligatorios.</span>
                <div>
                    <app-button variant="ghost" @click="$emit('cancel')">Cancelar</app-button>
                    <app-button :loading="saving" @click="$emit('save')">{{ editing ? 'Guardar cambios' : 'Crear producto' }}</app-button>
                </div>
            </footer>
        </template>
    </main>
</template>

<script>
import WarehouseListToolbar from './WarehouseListToolbar.vue';
import WarehouseProductForm from './WarehouseProductForm.vue';

export default {
    name: 'WarehouseProductsWorkspace',
    components: { WarehouseListToolbar, WarehouseProductForm },
    props: {
        rows: { type: Array, default: () => [] },
        data: { type: Object, required: true },
        errors: { type: Object, default: () => ({}) },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        categories: { type: Array, default: () => [] },
        units: { type: Array, default: () => [] },
        providers: { type: Array, default: () => [] },
        lines: { type: Array, default: () => [] },
        productCount: { type: [String, Number], default: 0 },
        categoryCount: { type: [String, Number], default: 0 },
        providerCount: { type: [String, Number], default: 0 },
        search: { type: String, default: '' },
        criterion: { type: String, default: 'articulo.nombre_comercial' },
        view: { type: String, default: 'list' },
        editing: { type: Boolean, default: false },
        loading: { type: Boolean, default: false },
        tableLoading: { type: Boolean, default: false },
        saving: { type: Boolean, default: false },
    },
    data() {
        return {
            criteria: [
                { value: 'articulo.nombre_comercial', label: 'Nombre comercial' },
                { value: 'articulo.nombre_generico', label: 'Nombre genérico' },
                { value: 'categoria.nombre', label: 'Categoría' },
                { value: 'proveedor.nombre', label: 'Laboratorio' },
            ],
            columns: [
                { key: 'nombre_comercial', label: 'Producto' }, { key: 'nombre_generico', label: 'Genérico' },
                { key: 'unidad', label: 'Presentación' }, { key: 'costo_compra', label: 'Costo' },
                { key: 'proveedor', label: 'Laboratorio' }, { key: 'costo_unitario', label: 'P. unitario' },
                { key: 'precio_blister', label: 'P. blíster' }, { key: 'precio_caja', label: 'P. caja' },
                { key: 'estado', label: 'Estado' }, { key: 'actions', label: 'Acciones' },
            ],
        };
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
    },
};
</script>

<style scoped>
.warehouse-products { display: grid; gap: 1rem; padding: 1.25rem; background: #f4f8f6; }
.warehouse-products__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.warehouse-products__status { display: inline-flex; padding: .28rem .55rem; color: #17693c; font-size: .68rem; font-weight: 800; background: #e5f7ed; border-radius: 999px; }
.warehouse-products__status.is-inactive { color: #7c4a10; background: #fff1d8; }
.warehouse-products__actions { display: flex; gap: .4rem; }
.warehouse-products__footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.15rem; color: #5f716a; font-size: .72rem; font-weight: 800; background: #fff; border: 1px solid #d8e5df; border-radius: 14px; box-shadow: 0 6px 18px rgba(23, 54, 43, .065); }
.warehouse-products__footer div { display: flex; gap: .5rem; }
@media (max-width: 900px) { .warehouse-products__metrics { grid-template-columns: 1fr; } }
@media (max-width: 650px) { .warehouse-products { padding: .75rem; } .warehouse-products__footer { align-items: stretch; flex-direction: column; } }
</style>
