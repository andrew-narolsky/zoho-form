<script setup>
import { defineProps, defineEmits } from 'vue'

defineProps({
    modelValue: String,
    label: String,
    id: String,
    error: String,
    type: {
        type: String,
        default: 'text'
    }
})

const emit = defineEmits(['update:modelValue', 'clearError'])
</script>

<template>
    <div class="mb-4">
        <label :for="id" class="form-label">{{ label }}</label>
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            :class="['form-control', { 'is-invalid': error }]"
            @input="(event) => { emit('update:modelValue', event.target.value); emit('clearError') }"
        >
        <span v-if="error" class="text-danger">{{ error }}</span>
    </div>
</template>

<style scoped>
.mb-4 {
    position: relative;
}
input {
    border-color: transparent;
    font-size: 18px;
    height: 48px;
    box-sizing: border-box;
}
label {
    font-size: 18px;
    margin-bottom: 5px;
}
.text-danger {
    font-size: 14px;
    position: absolute;
    bottom: -20px;
    right: 0;
}
</style>
