<template>
    <nav v-if="pagination && pagination.last_page > 1" class="purchase-pagination" aria-label="Paginación">
        <button
            type="button"
            :disabled="pagination.current_page <= 1"
            @click="$emit('change', pagination.current_page - 1)"
        >
            Anterior
        </button>
        <button
            v-for="page in pages"
            :key="page"
            type="button"
            :class="{ 'is-active': page === pagination.current_page }"
            :aria-current="page === pagination.current_page ? 'page' : null"
            @click="$emit('change', page)"
        >
            {{ page }}
        </button>
        <button
            type="button"
            :disabled="pagination.current_page >= pagination.last_page"
            @click="$emit('change', pagination.current_page + 1)"
        >
            Siguiente
        </button>
    </nav>
</template>

<script>
export default {
    name: 'PurchasePagination',
    props: {
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
    },
};
</script>

<style scoped>
.purchase-pagination { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: .3rem; padding: .85rem 1rem; background: #f8fbf9; border-top: 1px solid #d8e5df; }
.purchase-pagination button { min-width: 34px; min-height: 34px; padding: .35rem .58rem; color: #315044; font-size: .73rem; font-weight: 800; background: #fff; border: 1px solid #cbdcd4; border-radius: 7px; }
.purchase-pagination button:hover:not(:disabled) { color: #17693c; background: #effaf4; border-color: #2fae66; }
.purchase-pagination button.is-active { color: #fff; background: #1f8a4c; border-color: #1f8a4c; }
.purchase-pagination button:disabled { cursor: not-allowed; opacity: .45; }
</style>
