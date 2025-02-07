<script setup>
import { defineProps } from 'vue'
import InputField from '../ui/InputField.vue'

defineProps({
    account: Object,
    errors: Object
})
</script>

<template>
    <div>
        <InputField
            v-model="account.name"
            :error="errors.name"
            label="Account name"
            id="account_name" />
        <InputField
            v-model="account.website"
            :error="errors.website"
            label="Account website"
            id="account_website" />
        <InputField
            v-model="account.phone"
            :error="errors.phone"
            label="Account phone"
            id="account_phone" />
    </div>
</template>
