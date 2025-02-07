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
    <button type="submit" class="btn btn-primary">
        {{ label }}
    </button>
</template>
