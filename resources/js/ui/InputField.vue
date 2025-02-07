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

const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <div class="mb-3">
        <label :for="id" class="form-label">{{ label }}</label>
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            :class="['form-control', { 'is-invalid': error }]"
            @input="emit('update:modelValue', $event.target.value)"
        >
        <span v-if="error" class="text-danger">{{ error }}</span>
    </div>
</template>
