<script setup>
import { defineProps } from 'vue'

defineProps({
    label: {
        type: String,
        default: 'Submit'
    }
})
</script>

<template>
    <button type="submit" class="btn btn-primary w-100 btn-lg">
        {{ label }}
    </button>
</template>
