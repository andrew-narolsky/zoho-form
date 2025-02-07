<script setup>
import { defineProps } from 'vue'
import InputField from '../ui/InputField.vue'

defineProps({
    deal: Object,
    errors: Object
})
</script>

<template>
    <div>
        <InputField
            v-model="deal.name"
            :error="errors.deal_name"
            label="Deal name"
            id="deal_name" />
        <InputField
            v-model="deal.stage"
            :error="errors.deal_stage"
            label="Deal stage"
            id="deal_stage" />
    </div>
</template>
