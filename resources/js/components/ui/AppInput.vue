<template>
    <div class="app-input" :class="{ 'app-input--invalid': hasError }">
        <label v-if="label" class="app-input__label" :for="fieldId">
            {{ label }} <span v-if="required" class="app-input__required" aria-hidden="true">*</span>
        </label>
        <div class="app-input__control">
            <span v-if="$slots.prefix" class="app-input__affix"><slot name="prefix"></slot></span>
            <textarea
                v-if="multiline"
                ref="field"
                v-bind="$attrs"
                :id="fieldId"
                class="app-input__field app-input__field--textarea"
                :value="value"
                :rows="rows"
                :placeholder="placeholder"
                :disabled="disabled"
                :readonly="readonly"
                :required="required"
                :aria-invalid="hasError ? 'true' : 'false'"
                :aria-describedby="descriptionId"
                v-on="inputListeners"
            ></textarea>
            <input
                v-else
                ref="field"
                v-bind="$attrs"
                :id="fieldId"
                class="app-input__field"
                :type="type"
                :value="value"
                :placeholder="placeholder"
                :disabled="disabled"
                :readonly="readonly"
                :required="required"
                :aria-invalid="hasError ? 'true' : 'false'"
                :aria-describedby="descriptionId"
                v-on="inputListeners"
            >
            <span v-if="$slots.suffix" class="app-input__affix"><slot name="suffix"></slot></span>
        </div>
        <small v-if="hasError" :id="descriptionId" class="app-input__message app-input__message--error">{{ error }}</small>
        <small v-else-if="hint" :id="descriptionId" class="app-input__message">{{ hint }}</small>
    </div>
</template>

<script>
export default {
    name: 'AppInput',
    inheritAttrs: false,
    props: {
        value: { type: [String, Number], default: '' },
        id: { type: String, default: '' },
        type: { type: String, default: 'text' },
        label: { type: String, default: '' },
        placeholder: { type: String, default: '' },
        hint: { type: String, default: '' },
        error: { type: [String, Array], default: '' },
        disabled: { type: Boolean, default: false },
        readonly: { type: Boolean, default: false },
        required: { type: Boolean, default: false },
        multiline: { type: Boolean, default: false },
        rows: { type: Number, default: 3 },
    },
    computed: {
        fieldId() {
            return this.id || `app-input-${this._uid}`;
        },
        hasError() {
            return Array.isArray(this.error) ? this.error.length > 0 : Boolean(this.error);
        },
        descriptionId() {
            return this.hasError || this.hint ? `${this.fieldId}-description` : null;
        },
        inputListeners() {
            return {
                ...this.$listeners,
                input: event => this.$emit('input', event.target.value),
            };
        },
    },
};
</script>

<style scoped>
.app-input { width: 100%; text-align: left; }
.app-input__label { display: block; margin-bottom: .35rem; color: #315044; font-size: .73rem; font-weight: 800; }
.app-input__required { color: #d63c3c; }
.app-input__control { display: flex; align-items: stretch; overflow: hidden; min-height: 40px; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; transition: border-color 150ms ease, box-shadow 150ms ease; }
.app-input__control:focus-within { border-color: #0e93b5; box-shadow: 0 0 0 3px rgba(62, 198, 224, .18); }
.app-input__field { width: 100%; min-width: 0; min-height: 38px; padding: .52rem .72rem; color: #17362b; font: inherit; font-size: .82rem; background: transparent; border: 0; outline: 0; }
.app-input__field:disabled { color: #7d8e87; background: #edf3f0; cursor: not-allowed; }
.app-input__field--textarea { min-height: 5.5rem; resize: vertical; }
.app-input__affix { display: inline-flex; align-items: center; padding: 0 .7rem; color: #315044; font-size: .78rem; font-weight: 800; background: #effaf4; }
.app-input__message { display: block; margin-top: .3rem; color: #6f817a; font-size: .7rem; }
.app-input__message--error { color: #d63c3c; }
.app-input--invalid .app-input__control { border-color: #d63c3c; }
</style>
