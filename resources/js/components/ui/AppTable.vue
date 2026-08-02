<template>
    <div class="app-table" :aria-busy="loading ? 'true' : 'false'">
        <div v-if="loading" class="app-table__loading" role="status">
            <div
                v-for="row in 5"
                :key="row"
                class="app-table__skeleton-row"
                :style="{ gridTemplateColumns: `repeat(${columns.length}, minmax(90px, 1fr))` }"
            >
                <span v-for="column in columns" :key="column.key"></span>
            </div>
        </div>

        <div v-else class="app-table__scroll">
            <table class="table table-hover" :style="{ minWidth }">
                <caption v-if="caption" class="visually-hidden">{{ caption }}</caption>
                <thead>
                    <tr>
                        <th v-for="column in columns" :key="column.key" scope="col" :class="column.className">
                            {{ column.label }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, index) in rows"
                        :key="row[rowKey] !== undefined ? row[rowKey] : index"
                        :class="resolveRowClass(row, index)"
                    >
                        <td v-for="column in columns" :key="column.key" :class="column.className">
                            <slot :name="`cell-${column.key}`" :row="row" :value="row[column.key]">
                                {{ displayValue(row[column.key]) }}
                            </slot>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="rows.length === 0" class="app-table__empty">
                <strong>{{ emptyTitle }}</strong>
                <span>{{ emptyMessage }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AppTable',
    props: {
        columns: {
            type: Array,
            required: true,
        },
        rows: {
            type: Array,
            default: () => [],
        },
        rowKey: {
            type: String,
            default: 'id',
        },
        loading: {
            type: Boolean,
            default: false,
        },
        caption: {
            type: String,
            default: '',
        },
        emptyTitle: {
            type: String,
            default: 'No hay registros',
        },
        emptyMessage: {
            type: String,
            default: 'No se encontraron datos para este período.',
        },
        minWidth: {
            type: String,
            default: '720px',
        },
        rowClass: {
            type: [String, Function],
            default: '',
        },
    },
    methods: {
        resolveRowClass(row, index) {
            return typeof this.rowClass === 'function'
                ? this.rowClass(row, index)
                : this.rowClass;
        },
        displayValue(value) {
            return value === null || value === undefined || value === '' ? '—' : value;
        },
    },
};
</script>

<style scoped>
.app-table {
    background: #fff;
}

.app-table__scroll {
    max-height: 330px;
    overflow: auto;
}

.app-table .table {
    margin: 0;
}

.app-table .table th,
.app-table .table td {
    padding: 0.72rem 0.9rem;
    border-color: #e2ebe7;
    font-size: 0.78rem;
    vertical-align: middle;
}

.app-table .table thead {
    position: sticky;
    top: 0;
    z-index: 1;
}

.app-table .table thead th {
    color: #315044 !important;
    background: #effaf4 !important;
    font-size: 0.66rem;
    letter-spacing: 0.055em;
}

.app-table__empty {
    display: flex;
    min-height: 160px;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    color: #6f817a;
    text-align: center;
}

.app-table__empty strong {
    margin-bottom: 0.25rem;
    color: #17362b;
}

.app-table__empty span {
    font-size: 0.76rem;
}

.app-table__loading {
    padding: 0.25rem 0;
}

.app-table__skeleton-row {
    display: grid;
    grid-template-columns: repeat(5, minmax(90px, 1fr));
    gap: 1rem;
    padding: 0.82rem 0.9rem;
    border-bottom: 1px solid #e2ebe7;
}

.app-table__skeleton-row span {
    height: 0.75rem;
    border-radius: 4px;
    background: linear-gradient(90deg, #e4ece8 25%, #f7faf8 50%, #e4ece8 75%);
    background-size: 200% 100%;
    animation: app-table-loading 1.2s ease-in-out infinite;
}

@keyframes app-table-loading {
    from { background-position: 200% 0; }
    to { background-position: -200% 0; }
}
</style>
