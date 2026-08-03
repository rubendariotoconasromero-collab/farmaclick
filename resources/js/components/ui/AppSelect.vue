<template>
    <label class="app-select" :class="{ 'app-select--disabled': disabled, 'app-select--error': error }">
        <span v-if="label" class="app-select__label">
            {{ label }} <b v-if="required" aria-hidden="true">*</b>
        </span>
        <select
            class="app-select__control"
            :value="value"
            :disabled="disabled"
            :required="required"
            @change="$emit('input', $event.target.value)"
        >
            <option v-if="placeholder" :value="placeholderValue" :disabled="disablePlaceholder">{{ placeholder }}</option>
            <option v-for="option in options" :key="option[optionValue]" :value="option[optionValue]">
                {{ option[optionLabel] }}
            </option>
        </select>
        <small v-if="error" class="app-select__error">{{ error }}</small>
    </label>
</template>

<script>
export default {
    name: 'AppSelect',
    props: {
        value: { type: [String, Number], default: '' },
        options: { type: Array, default: () => [] },
        optionValue: { type: String, default: 'value' },
        optionLabel: { type: String, default: 'label' },
        label: { type: String, default: '' },
        placeholder: { type: String, default: '' },
        placeholderValue: { type: [String, Number], default: '' },
        disablePlaceholder: { type: Boolean, default: true },
        disabled: { type: Boolean, default: false },
        required: { type: Boolean, default: false },
        error: { type: String, default: '' },
    },
};
</script>

<style scoped>
.app-select { display: flex; min-width: 0; flex-direction: column; gap: .35rem; margin: 0; color: #315044; }
.app-select__label { font-size: .73rem; font-weight: 800; }
.app-select__label b { color: #d63c3c; }
.app-select__control { width: 100%; min-height: 40px; padding: .48rem 2rem .48rem .65rem; color: #17362b; background-color: #fff; border: 1px solid #bdd2c9; border-radius: 8px; outline: none; transition: border-color .16s ease, box-shadow .16s ease; }
.app-select__control:focus { border-color: #2fae66; box-shadow: 0 0 0 3px rgba(47,174,102,.14); }
.app-select--disabled { opacity: .65; }
.app-select--error .app-select__control { border-color: #d63c3c; }
.app-select__error { color: #d63c3c; font-size: .68rem; font-weight: 600; }
</style>
