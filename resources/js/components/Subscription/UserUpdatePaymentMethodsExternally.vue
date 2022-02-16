<template>
    <div v-if="canShowForSubscription" class="card shadow-card">
        <FormLabel>
            {{ $t('Update Payments') }}
        </FormLabel>

        <AppInputButton
            :title="$t('Update your Payment Method')"
            :description="$t('You will be redirected to your payment provider to edit your payment method.')"
            :is-last="true"
        >
            <ButtonBase
                @click.native="updatePaymentMethod"
                :loading="isGeneratedUpdateLink"
                class="w-full sm:w-auto"
                button-style="theme"
            >
                {{ $t('Update Payments') }}
            </ButtonBase>
        </AppInputButton>
    </div>
</template>

<script>
import AppInputSwitch from '../Admin/AppInputSwitch'
import ButtonBase from '../FilesView/ButtonBase'
import FormLabel from '../Others/Forms/FormLabel'
import axios from 'axios'
import { events } from '../../bus'
import AppInputButton from '../Admin/AppInputButton'

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
