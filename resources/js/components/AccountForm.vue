<script setup>
import { defineProps, defineEmits } from 'vue'
import InputField from '../ui/InputField.vue'

const props = defineProps({
    account: Object,
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
            v-model="account.name"
            :error="errors.name"
            @clearError="clearError('name')"
            label="Account name*"
            id="account_name" />
        <InputField
            v-model="account.website"
            :error="errors.website"
            @clearError="clearError('website')"
            label="Account website*"
            id="account_website" />
        <InputField
            v-model="account.phone"
            :error="errors.phone"
            @clearError="clearError('phone')"
            label="Account phone*"
            id="account_phone" />
    </div>
</template>
