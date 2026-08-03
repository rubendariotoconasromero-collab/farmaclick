<template>
    <article class="report-group">
        <header>
            <span class="report-group__icon"><img :src="icon" alt="" aria-hidden="true"></span>
            <div>
                <h3>{{ title }}</h3>
                <p>{{ description }}</p>
            </div>
            <span class="report-group__count">{{ reports.length }}</span>
        </header>
        <div class="report-group__items">
            <button
                v-for="report in reports"
                :key="report.label"
                type="button"
                class="report-group__item"
                @click="$emit('run', report)"
            >
                <span><img :src="report.icon" alt="" aria-hidden="true"></span>
                <strong>{{ report.label }}</strong>
                <small>{{ reportHint(report) }}</small>
                <img class="report-group__arrow" src="/icons/arrow-right.svg" alt="" aria-hidden="true">
            </button>
        </div>
    </article>
</template>

<script>
export default {
    name: 'ReportGroupCard',
    props: {
        title: { type: String, required: true },
        description: { type: String, default: 'Genera y consulta la información del módulo.' },
        icon: { type: String, default: '/icons/chart-line.svg' },
        reports: { type: Array, default: () => [] },
    },
    methods: {
        reportHint(report) {
            return report.mode === 'excel'
                ? 'Archivo Excel'
                : 'Documento PDF';
        },
    },
};
</script>

<style scoped>
.report-group {
    overflow: hidden;
    border: 1px solid #dce9e3;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 10px 28px rgba(23, 54, 43, 0.06);
}

.report-group > header {
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-bottom: 1px solid #e2ece7;
    background: linear-gradient(135deg, #f2faf6, #f7fcfd);
}

.report-group__icon {
    display: grid;
    width: 38px;
    height: 38px;
    place-items: center;
    border-radius: 11px;
    color: #fff;
    background: linear-gradient(145deg, #1f965e, #1ca3ba);
}
.report-group__icon img { width: 20px; height: 20px; filter: brightness(0) invert(1); }

.report-group h3 {
    margin: 0;
    color: #17362b;
    font-size: 0.95rem;
}

.report-group p {
    margin: 0.18rem 0 0;
    color: #6a7f77;
    font-size: 0.7rem;
}

.report-group__count {
    display: grid;
    min-width: 30px;
    height: 30px;
    place-items: center;
    border-radius: 999px;
    color: #197650;
    background: #e3f5eb;
    font-size: 0.72rem;
    font-weight: 900;
}

.report-group__items {
    display: grid;
    padding: 0.45rem;
}

.report-group__item {
    display: grid;
    grid-template-columns: 32px 1fr auto auto;
    align-items: center;
    gap: 0.65rem;
    padding: 0.65rem;
    border: 0;
    border-radius: 10px;
    color: #25473a;
    background: transparent;
    text-align: left;
}

.report-group__item:hover {
    background: #edf8f2;
    transform: translateX(2px);
}

.report-group__item > span {
    display: grid;
    width: 30px;
    height: 30px;
    place-items: center;
    border-radius: 8px;
    color: #19865a;
    background: #e5f5ed;
}
.report-group__item > span img { width: 17px; height: 17px; opacity: 0.82; }

.report-group__item strong { font-size: 0.77rem; }
.report-group__item small { color: #81918b; font-size: 0.64rem; }
.report-group__arrow { width: 13px; height: 13px; opacity: 0.62; }
</style>
