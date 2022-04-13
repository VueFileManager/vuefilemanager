<template>
    <div v-if="canShowForSubscription" class="card shadow-card">
        <FormLabel>
            {{ $t('update_payments') }}
        </FormLabel>

        <AppInputButton
            :title="$t('update_payment_method')"
            :description="$t('payment_method_update_redirect_description')"
            :is-last="true"
        >
            <ButtonBase
                @click.native="updatePaymentMethod"
                :loading="isGeneratedUpdateLink"
                class="w-full sm:w-auto"
                button-style="theme"
            >
                {{ $t('update_payments') }}
            </ButtonBase>
        </AppInputButton>
    </div>
</template>

<script>
import AppInputSwitch from '../Forms/Layouts/AppInputSwitch'
import ButtonBase from '../UI/Buttons/ButtonBase'
import FormLabel from '../UI/Labels/FormLabel'
import axios from 'axios'
import { events } from '../../bus'
import AppInputButton from '../Forms/Layouts/AppInputButton'

export default {
    name: 'UserUpdatePaymentMethodsExternally',
    components: {
        AppInputButton,
        AppInputSwitch,
        ButtonBase,
        FormLabel,
    },
    computed: {
        canShowForSubscription() {
            return (
                this.hasSubscription &&
                !this.subscription.attributes.is_cancelled &&
                ['paystack', 'paypal'].includes(this.subscription.attributes.driver)
            )
        },
        subscription() {
            return this.$store.getters.user.data.relationships.subscription.data
        },
        hasSubscription() {
            return this.$store.getters.user.data.relationships.subscription
        },
    },
    data() {
        return {
            isGeneratedUpdateLink: false,
        }
    },
    methods: {
        updatePaymentMethod() {
            this.isGeneratedUpdateLink = true

            axios
                .post(`/api/subscriptions/edit/${this.subscription.id}`)
                .then((response) => {
                    window.location = response.data.url
                })
                .catch(() => {
                    events.$emit('toaster', {
                        type: 'danger',
                        message: this.$t('popup_error.title'),
                    })

                    this.isGeneratedUpdateLink = false
                })
        },
    },
}
</script>
