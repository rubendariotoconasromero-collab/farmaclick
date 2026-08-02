<template>
    <div v-if="open" class="report-format" role="dialog" aria-modal="true" aria-labelledby="purchase-report-title" @click.self="$emit('close')">
        <section class="report-format__card">
            <header class="report-format__header">
                <div class="report-format__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v7H6zM18 12h1"/></svg>
                </div>
                <div>
                    <small>Compra registrada</small>
                    <h2 id="purchase-report-title">¿Cómo desea generar la nota?</h2>
                    <p>Seleccione el formato adecuado para su impresora.</p>
                </div>
                <button type="button" class="report-format__close" aria-label="Cerrar" :disabled="Boolean(loadingFormat)" @click="$emit('close')">×</button>
            </header>

            <div class="report-format__options">
                <button type="button" class="report-option" :disabled="Boolean(loadingFormat)" @click="$emit('select', 'carta')">
                    <span class="report-option__preview report-option__preview--letter"><i></i><i></i><i></i><i></i></span>
                    <span class="report-option__copy">
                        <strong>Formato carta</strong>
                        <small>Documento completo para archivo, administración o impresora convencional.</small>
                        <b>{{ loadingFormat === 'carta' ? 'Generando…' : 'Generar PDF carta' }} <span>→</span></b>
                    </span>
                </button>

                <button type="button" class="report-option report-option--ticket" :disabled="Boolean(loadingFormat)" @click="$emit('select', 'ticket')">
                    <span class="report-option__preview report-option__preview--ticket"><i></i><i></i><i></i><i></i><i></i></span>
                    <span class="report-option__copy">
                        <strong>Ticket térmico</strong>
                        <small>Versión compacta de 80 mm, optimizada para impresión rápida y bajo consumo.</small>
                        <b>{{ loadingFormat === 'ticket' ? 'Generando…' : 'Generar ticket' }} <span>→</span></b>
                    </span>
                </button>
            </div>

            <footer>
                <span>Puede volver a imprimir la última nota desde el encabezado del módulo.</span>
                <button type="button" :disabled="Boolean(loadingFormat)" @click="$emit('close')">Ahora no</button>
            </footer>
        </section>
    </div>
</template>

<script>
export default {
    name: 'PurchaseReportFormatModal',
    props: {
        open: { type: Boolean, default: false },
        loadingFormat: { type: String, default: '' },
    },
};
</script>

<style scoped>
.report-format { position: fixed; z-index: 1085; inset: 0; display: grid; place-items: center; padding: 1rem; background: rgba(12, 42, 33, .68); backdrop-filter: blur(4px); }
.report-format__card { width: min(680px, 100%); overflow: hidden; background: #fff; border: 1px solid rgba(31, 146, 84, .18); border-radius: 18px; box-shadow: 0 28px 70px rgba(8, 38, 29, .3); }
.report-format__header { position: relative; display: grid; grid-template-columns: 52px 1fr auto; gap: .9rem; align-items: start; padding: 1.25rem 1.35rem 1rem; background: linear-gradient(135deg, #edf9f2 0%, #effbfe 100%); border-bottom: 1px solid #dcebe5; }
.report-format__icon { display: grid; width: 52px; height: 52px; place-items: center; background: #1f9254; border-radius: 14px; box-shadow: 0 8px 20px rgba(31, 146, 84, .22); }
.report-format__icon svg { width: 24px; fill: none; stroke: #fff; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.report-format__header small { color: #1688a2; font-size: .68rem; font-weight: 900; letter-spacing: .08em; text-transform: uppercase; }
.report-format__header h2 { margin: .16rem 0 .25rem; color: #173f32; font-size: 1.25rem; }
.report-format__header p { margin: 0; color: #60786e; font-size: .82rem; }
.report-format__close { padding: 0; color: #789087; background: transparent; border: 0; font-size: 1.65rem; line-height: 1; cursor: pointer; }
.report-format__options { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; padding: 1.15rem 1.35rem; }
.report-option { display: grid; grid-template-columns: 76px 1fr; gap: .85rem; min-height: 150px; padding: 1rem; color: inherit; text-align: left; background: #fff; border: 1px solid #cfe0d9; border-radius: 14px; cursor: pointer; transition: .18s ease; }
.report-option:hover { transform: translateY(-2px); border-color: #1f9254; box-shadow: 0 10px 24px rgba(23, 63, 50, .11); }
.report-option--ticket:hover { border-color: #1a9ebe; }
.report-option:disabled { cursor: wait; opacity: .68; transform: none; }
.report-option__preview { position: relative; display: block; width: 64px; height: 86px; padding: 12px 9px; background: #fff; border: 2px solid #1f9254; border-radius: 5px; box-shadow: 0 7px 14px rgba(23, 63, 50, .12); }
.report-option__preview--ticket { width: 55px; height: 94px; border-color: #1a9ebe; border-radius: 3px 3px 8px 8px; }
.report-option__preview i { display: block; height: 3px; margin-bottom: 7px; background: #d7e7e0; border-radius: 2px; }
.report-option__preview i:first-child { width: 62%; background: #1f9254; }
.report-option__preview--ticket i:first-child { background: #1a9ebe; }
.report-option__copy { display: flex; flex-direction: column; min-width: 0; }
.report-option__copy strong { color: #173f32; font-size: .92rem; }
.report-option__copy small { margin-top: .35rem; color: #667c73; font-size: .72rem; line-height: 1.45; }
.report-option__copy b { margin-top: auto; padding-top: .65rem; color: #1f9254; font-size: .72rem; }
.report-option--ticket .report-option__copy b { color: #1688a2; }
.report-option__copy b span { font-size: 1rem; }
.report-format footer { display: flex; justify-content: space-between; gap: 1rem; align-items: center; padding: .75rem 1.35rem; color: #70847c; background: #f8fbfa; border-top: 1px solid #e0ebe7; font-size: .68rem; }
.report-format footer button { color: #315c4d; background: transparent; border: 0; font-size: .72rem; font-weight: 800; cursor: pointer; }
@media (max-width: 620px) {
    .report-format__header { grid-template-columns: 44px 1fr auto; padding: 1rem; }
    .report-format__icon { width: 44px; height: 44px; }
    .report-format__options { grid-template-columns: 1fr; padding: 1rem; }
    .report-option { min-height: 130px; }
    .report-format footer { align-items: flex-start; padding: .75rem 1rem; }
}
</style>
