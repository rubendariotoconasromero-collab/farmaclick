<template>
    <app-page-skeleton v-if="loading" :columns="4" label="Cargando configuración de la empresa" />
    <main v-else class="company-settings">
        <app-module-header
            eyebrow="Datos maestros · Configuración"
            title="Mi empresa"
            subtitle="Gestiona la identidad comercial y los recursos visuales utilizados en reportes, menú y acceso."
        >
            <template #actions>
                <app-button v-if="disabled" icon="img/menu/configuracion.png" @click="$emit('edit')">Editar configuración</app-button>
                <template v-else>
                    <app-button variant="ghost" :disabled="saving" @click="$emit('cancel')">Cancelar</app-button>
                    <app-button :loading="saving" @click="$emit('save')">Guardar cambios</app-button>
                </template>
            </template>
        </app-module-header>

        <section class="company-settings__metrics">
            <app-metric-card label="Empresa" :value="data.nombre || 'Sin configurar'" hint="Identidad principal" icon="img/menu/tienda.png" />
            <app-metric-card label="NIT" :value="data.nit || 'Sin registrar'" hint="Identificación tributaria" icon="img/menu/configuracion.png" tone="cyan" />
            <app-metric-card label="Modo" :value="disabled ? 'Solo lectura' : 'Edición activa'" :hint="disabled ? 'Activa edición para cambiar datos' : 'Revisa los cambios antes de guardar'" icon="img/menu/control.png" tone="neutral" />
        </section>

        <div class="company-settings__layout">
            <app-data-panel eyebrow="Identidad" title="Información empresarial" subtitle="Datos mostrados en comprobantes y reportes.">
                <div class="company-settings__form-grid">
                    <app-input v-model="data.nombre" label="Nombre de la empresa" :disabled="disabled" />
                    <app-input v-model="data.nit" label="NIT" :disabled="disabled" />
                    <app-input v-model="data.telefono" label="Teléfono" :disabled="disabled" />
                    <app-input v-model="data.direccion" label="Dirección" :disabled="disabled" />
                </div>
            </app-data-panel>

            <app-data-panel eyebrow="Reportes" title="Logotipo institucional" subtitle="Imagen utilizada en documentos impresos.">
                <asset-card
                    title="Logo de reportes"
                    hint="Resolución recomendada: 300 × 300 px"
                    :src="reportLogo"
                    :disabled="disabled"
                    @change="$emit('report-logo', $event)"
                />
            </app-data-panel>
        </div>

        <app-data-panel
            v-if="isAdministrator"
            eyebrow="Sistema"
            title="Recursos visuales"
            subtitle="Configura las imágenes utilizadas en navegación, usuario e inicio de sesión."
        >
            <div class="company-settings__assets">
                <asset-card title="Logo del sistema" hint="Formato horizontal, aproximadamente 100 px de alto" :src="systemLogo" :disabled="disabled" @change="$emit('system-logo', $event)" @preview="$emit('preview-system-logo')" />
                <asset-card title="Logo de usuario" hint="Imagen utilizada en la barra superior" :src="userLogo" :disabled="disabled" @change="$emit('user-logo', $event)" @preview="$emit('preview-user-logo')" />
                <asset-card title="Logo de acceso" hint="Resolución recomendada: 430 × 310 px" :src="loginLogo" :disabled="disabled" @change="$emit('login-logo', $event)" @preview="$emit('preview-login-logo')" />
                <asset-card title="Fondo de acceso" hint="Resolución recomendada: 1920 × 1080 px" :src="loginBackground" :disabled="disabled" wide @change="$emit('login-background', $event)" @preview="$emit('preview-login-background')" />
            </div>
        </app-data-panel>

        <app-data-panel v-if="isAdministrator" eyebrow="Apariencia" title="Colores del sistema" subtitle="Mantén estos tonos compatibles con la identidad FarmaClick.">
            <div class="company-settings__colors">
                <label>
                    <span>Color del acceso</span>
                    <input v-model="data.colorLogin" type="color" :disabled="disabled">
                    <code>{{ data.colorLogin || 'Sin definir' }}</code>
                </label>
                <label>
                    <span>Color del menú</span>
                    <input v-model="data.colorMenu" type="color" :disabled="disabled">
                    <code>{{ data.colorMenu || 'Sin definir' }}</code>
                </label>
            </div>
        </app-data-panel>

        <footer v-if="!disabled" class="company-settings__footer">
            <span>Los cambios visuales se aplicarán al guardar la configuración.</span>
            <div>
                <app-button variant="ghost" :disabled="saving" @click="$emit('cancel')">Cancelar</app-button>
                <app-button :loading="saving" @click="$emit('save')">Guardar cambios</app-button>
            </div>
        </footer>
    </main>
</template>

<script>
import AssetCard from './CompanyAssetCard.vue';

export default {
    name: 'CompanySettingsWorkspace',
    components: { AssetCard },
    props: {
        data: { type: Object, required: true },
        user: { type: Object, default: () => ({}) },
        reportLogo: { type: String, default: '' },
        systemLogo: { type: String, default: '' },
        userLogo: { type: String, default: '' },
        loginLogo: { type: String, default: '' },
        loginBackground: { type: String, default: '' },
        disabled: { type: Boolean, default: true },
        loading: { type: Boolean, default: false },
        saving: { type: Boolean, default: false },
    },
    computed: {
        isAdministrator() {
            return Number(this.user.id) === 1;
        },
    },
};
</script>

<style scoped>
.company-settings { display: grid; gap: 1rem; padding: 1.25rem; background: #f4f8f6; }
.company-settings__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.company-settings__layout { display: grid; grid-template-columns: minmax(0, 1.5fr) minmax(280px, .75fr); gap: 1rem; }
.company-settings__form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
.company-settings__assets { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
.company-settings__colors { display: flex; flex-wrap: wrap; gap: 1rem; }
.company-settings__colors label { display: grid; min-width: 220px; grid-template-columns: auto 52px; align-items: center; gap: .4rem 1rem; padding: .85rem 1rem; color: #315044; font-size: .73rem; font-weight: 800; background: #f8fbf9; border: 1px solid #d8e5df; border-radius: 10px; }
.company-settings__colors input { width: 52px; height: 40px; padding: .2rem; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; }
.company-settings__colors code { color: #5f716a; font-size: .7rem; }
.company-settings__footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.15rem; color: #5f716a; font-size: .72rem; font-weight: 800; background: #fff; border: 1px solid #d8e5df; border-radius: 14px; box-shadow: 0 6px 18px rgba(23, 54, 43, .065); }
.company-settings__footer div { display: flex; gap: .5rem; }
@media (max-width: 960px) { .company-settings__metrics, .company-settings__layout { grid-template-columns: 1fr; } }
@media (max-width: 720px) { .company-settings__form-grid, .company-settings__assets { grid-template-columns: 1fr; } }
@media (max-width: 640px) { .company-settings { padding: .75rem; } .company-settings__footer { align-items: stretch; flex-direction: column; } }
</style>
