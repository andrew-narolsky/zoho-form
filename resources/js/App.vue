<script setup>
import axios from 'axios'
import { ref } from 'vue'
import { useHead } from '@vueuse/head'

import AccountForm from './components/AccountForm.vue'
import DealForm from './components/DealForm.vue'
import SubmitButton from './ui/SubmitButton.vue'

import { validateAccountForm } from './utils/validateAccountForm'
import { validateDealForm } from './utils/validateDealForm'
import Toast from './utils/toast'

useHead({
    title: 'Zoho Form',
    meta: [
        { name: 'description', content: 'Submit your Zoho account and deal details easily.' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { property: 'og:title', content: 'Zoho Form Submission' },
        { property: 'og:description', content: 'Fill out the form to create an account and deal in Zoho.' }
    ]
})

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
            account.value = { name: '', website: '', phone: '' }
            deal.value = { name: '', stage: '' }

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
    <div class="container" data-aos="zoom-in-down"
         data-aos-easing="ease-out-cubic">
        <h1>Zoho Form</h1>
        <form @submit.prevent="submitForm">
            <AccountForm :account="account" :errors="errors" />
            <DealForm :deal="deal" :errors="errors" />
            <p>* Fields marked with an asterisk are required.</p>
            <SubmitButton label="Submit form" />
        </form>
    </div>
</template>
<style scoped>
    h1 {
        margin-bottom: 20px;
        text-align: center;
    }
    form {
        margin: 0 auto;
        max-width: 500px;
        padding: 30px;
        box-sizing: border-box;
        background-color: rgba(255,255,255,.3);
        border: none;
        border-radius: 10px;
        box-shadow: 0 1.411px 2.961px 0 rgba(20, 34, 74, .03), 0 3.392px 7.116px 0 rgba(20, 34, 74, .04), 0 6.386px 13.398px 0 rgba(20, 34, 74, .05), 0 11.392px 23.9px 0 rgba(20, 34, 74, .05);
    }
</style>
