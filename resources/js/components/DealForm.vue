<script setup>
import { defineProps, defineEmits } from 'vue'
import InputField from '../ui/InputField.vue'

const props = defineProps({
    deal: Object,
    errors: Object
})

defineEmits(['clearError'])

const clearError = (field) => {
    if (props.errors[field]) {
        props.errors[field] = ''
    }
}
</script>

<template>
    <div>
        <InputField
            v-model="deal.name"
            :error="errors.deal_name"
            @clearError="clearError('deal_name')"
            label="Deal name*"
            id="deal_name" />
        <InputField
            v-model="deal.stage"
            :error="errors.deal_stage"
            @clearError="clearError('deal_stage')"
            label="Deal stage*"
            id="deal_stage" />
    </div>
</template>
