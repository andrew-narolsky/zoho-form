<script setup>
import axios from 'axios'
import { ref } from 'vue'

import AccountForm from './components/AccountForm.vue'
import DealForm from './components/DealForm.vue'
import SubmitButton from './ui/SubmitButton.vue'

import { validateAccountForm } from './utils/validateAccountForm'
import { validateDealForm } from './utils/validateDealForm'
import Toast from './utils/toast'

const account = ref({
    name: '',
    website: '',
    phone: ''
})

const deal = ref({
    name: '',
    stage: ''
})

const errors = ref({})

const validateForm = () => {
    errors.value = {}

    errors.value = {
        ...validateAccountForm(account.value),
        ...validateDealForm(deal.value)
    }

    return Object.keys(errors.value).length === 0
}

const submitForm = async () => {

    if (!validateForm()) return

    axios.post('/api/create-account', account.value)
        .then(accountResponse => {
            errors.value = {}

            const dealData = {
                name: deal.value.name,
                account_id: accountResponse.data.data[0].details.id,
                stage: deal.value.stage
            }

            return axios.post('/api/create-deal', dealData)
        })
        .then(dealResponse => {
            account.value = deal.value = {}

            Toast.fire({
                icon: 'success',
                title: 'Your form has been successfully submitted!'
            })
        })
        .catch(error => {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Something went wrong: ', error
                })
            }
        })
}
</script>

<template>
    <div class="container">
        <h1>Zoho Form</h1>
        <form @submit.prevent="submitForm">
            <AccountForm :account="account" :errors="errors" />
            <DealForm :deal="deal" :errors="errors" />
            <SubmitButton label="Submit form" />
        </form>
    </div>
</template>
<style scoped>
    h1 {
        margin-top: 50px;
    }
</style>
