<template>
    <div class="warehouse-page-skeleton" role="status" aria-live="polite">
        <span class="visually-hidden">{{ label }}</span>
        <div class="warehouse-page-skeleton__header">
            <div>
                <span></span>
                <strong></strong>
                <small></small>
            </div>
            <i></i>
        </div>
        <div class="warehouse-page-skeleton__metrics">
            <article v-for="metric in 3" :key="metric">
                <i></i>
                <div><span></span><strong></strong><small></small></div>
            </article>
        </div>
        <div class="warehouse-page-skeleton__panel">
            <header><div><span></span><strong></strong></div><i></i></header>
            <div class="warehouse-page-skeleton__toolbar"><span></span><span></span><i></i></div>
            <div class="warehouse-page-skeleton__rows">
                <div
                    v-for="row in rows"
                    :key="row"
                    :style="{ gridTemplateColumns: `repeat(${columns}, minmax(80px, 1fr))` }"
                >
                    <span v-for="column in columns" :key="column"></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'WarehousePageSkeleton',
    props: {
        rows: { type: Number, default: 5 },
        columns: { type: Number, default: 6 },
        label: { type: String, default: 'Cargando módulo de almacén' },
    },
};
</script>

<style scoped>
.warehouse-page-skeleton { display: grid; gap: 1rem; padding: 1.25rem; background: #f4f8f6; }
.warehouse-page-skeleton__header { display: flex; min-height: 132px; align-items: center; justify-content: space-between; padding: 1.4rem 1.5rem; background: linear-gradient(115deg, #163f32, #1f8a4c); border-bottom: 3px solid #3ec6e0; border-radius: 14px; }
.warehouse-page-skeleton__header div { display: grid; width: min(560px, 72%); gap: .6rem; }
.warehouse-page-skeleton__header span { width: 22%; height: .55rem; }
.warehouse-page-skeleton__header strong { width: 52%; height: 1.4rem; }
.warehouse-page-skeleton__header small { width: 88%; height: .7rem; }
.warehouse-page-skeleton__header i { width: 118px; height: 38px; border-radius: 8px; }
.warehouse-page-skeleton__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.warehouse-page-skeleton__metrics article { display: flex; min-height: 118px; align-items: center; gap: 1rem; padding: 1.15rem; background: #fff; border: 1px solid #d8e5df; border-radius: 14px; }
.warehouse-page-skeleton__metrics article > i { flex: 0 0 48px; height: 48px; border-radius: 12px; }
.warehouse-page-skeleton__metrics article div { display: grid; width: 100%; gap: .45rem; }
.warehouse-page-skeleton__metrics span { width: 48%; height: .55rem; }
.warehouse-page-skeleton__metrics strong { width: 68%; height: 1.35rem; }
.warehouse-page-skeleton__metrics small { width: 80%; height: .55rem; }
.warehouse-page-skeleton__panel { overflow: hidden; background: #fff; border: 1px solid #d8e5df; border-radius: 14px; }
.warehouse-page-skeleton__panel header { display: flex; min-height: 78px; align-items: center; justify-content: space-between; padding: 1rem 1.15rem; border-bottom: 1px solid #d8e5df; }
.warehouse-page-skeleton__panel header div { display: grid; width: 42%; gap: .5rem; }
.warehouse-page-skeleton__panel header span { width: 30%; height: .5rem; }
.warehouse-page-skeleton__panel header strong { width: 72%; height: .9rem; }
.warehouse-page-skeleton__panel header i { width: 76px; height: 30px; border-radius: 7px; }
.warehouse-page-skeleton__toolbar { display: grid; grid-template-columns: 190px 1fr 88px; gap: .65rem; padding: 1rem; background: #f8fbf9; border-bottom: 1px solid #d8e5df; }
.warehouse-page-skeleton__toolbar span, .warehouse-page-skeleton__toolbar i { height: 40px; border-radius: 8px; }
.warehouse-page-skeleton__rows > div { display: grid; gap: 1rem; padding: .88rem 1rem; border-bottom: 1px solid #e3ece8; }
.warehouse-page-skeleton__rows span { height: .72rem; border-radius: 4px; }
.warehouse-page-skeleton span, .warehouse-page-skeleton strong, .warehouse-page-skeleton small, .warehouse-page-skeleton i { display: block; background: linear-gradient(90deg, rgba(222,235,229,.58) 25%, rgba(255,255,255,.78) 50%, rgba(222,235,229,.58) 75%); background-size: 200% 100%; animation: warehouse-skeleton 1.2s ease-in-out infinite; }
@keyframes warehouse-skeleton { from { background-position: 200% 0; } to { background-position: -200% 0; } }
@media (max-width: 820px) { .warehouse-page-skeleton__metrics { grid-template-columns: 1fr; } }
@media (max-width: 640px) { .warehouse-page-skeleton { padding: .75rem; } .warehouse-page-skeleton__toolbar { grid-template-columns: 1fr; } }
</style>
