<template>
    <div v-if="hasSubscription" class="card shadow-card">
        <FormLabel>
            {{ $t('edit_your_subscription') }}
        </FormLabel>

        <AppInputButton
            v-if="subscription.attributes.status !== 'cancelled'"
            :title="$t('cancel_subscription')"
            :description="
                $t(
                    'cancel_subscription_description'
                )
            "
        >
            <ButtonBase
                @click.native="cancelSubscriptionConfirmation"
                :loading="isCancelling"
                class="w-full sm:w-auto"
                button-style="secondary"
            >
                {{ $t('cancel_now') }}
            </ButtonBase>
        </AppInputButton>

        <AppInputButton
            :title="$t('upgrade_downgrade_plan')"
            :description="$t('upgrade_downgrade_plan_description')"
            :is-last="true"
        >
            <ButtonBase @click.native="$changeSubscriptionOptions" class="w-full sm:w-auto" button-style="secondary">
                {{ $t('change_plan') }}
            </ButtonBase>
        </AppInputButton>
    </div>
</template>

<script>
import AppInputButton from '../Forms/Layouts/AppInputButton'
import AppInputText from '../Forms/Layouts/AppInputText'
import AppInputSwitch from '../Forms/Layouts/AppInputSwitch'
import ButtonBase from '../UI/Buttons/ButtonBase'
import FormLabel from '../UI/Labels/FormLabel'
import { events } from '../../bus'
import axios from 'axios'

export default {
    name: 'UserEditSubscription',
    components: {
        AppInputButton,
        AppInputSwitch,
        AppInputText,
        ButtonBase,
        FormLabel,
    },
    computed: {
        subscription() {
            return this.$store.getters.user.data.relationships.subscription.data
        },
        hasSubscription() {
            return this.$store.getters.user.data.relationships.subscription
        },
    },
    data() {
        return {
            isCancelling: false,
        }
    },
    methods: {
        cancelSubscriptionConfirmation() {
            events.$emit('confirm:open', {
                title: this.$t('want_cancel_subscription'),
                message: this.$t(
                    "popup_subscription_cancel.message"
                ),
                action: {
                    operation: 'cancel-subscription',
                },
            })
        },
    },
    created() {
        events.$on('action:confirmed', (data) => {
            if (data.operation === 'cancel-subscription') {
                // Start deleting spinner button
                this.isCancelling = true

                // Send post request
                axios
                    .post('/api/subscriptions/cancel')
                    .then(() => {
                        // Update user data
                        this.$store.dispatch('getAppData')

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('popup_subscription_cancel.title'),
                        })
                    })
                    .catch(() => {
                        events.$emit('toaster', {
                            type: 'danger',
                            message: this.$t('popup_error.title'),
                        })
                    })
                    .finally(() => {
                        this.isCancelling = false
                    })
            }
        })
    },
    destroyed() {
        events.$off('action:confirmed')
    },
}
</script>
