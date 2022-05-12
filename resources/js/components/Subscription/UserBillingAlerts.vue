<template>
    <div class="card shadow-card">
        <FormLabel icon="bell">
            {{ $t('billing_alert') }}
        </FormLabel>

        <div v-if="user.data.relationships.alert">
            <b class="-mt-3 mb-0.5 block flex items-center text-2xl font-extrabold sm:text-3xl">
                {{ user.data.relationships.alert.data.attributes.formatted }}
                <edit2-icon
                    v-if="!showUpdateBillingAlertForm"
                    @click="showUpdateBillingAlertForm = !showUpdateBillingAlertForm"
                    size="12"
                    class="vue-feather ml-2 -translate-y-0.5 transform cursor-pointer"
                />
                <trash2-icon
                    v-if="showUpdateBillingAlertForm"
                    @click="deleteBillingAlert"
                    size="12"
                    class="vue-feather ml-2 -translate-y-0.5 transform cursor-pointer"
                />
            </b>

            <b class="block text-sm dark:text-gray-500 text-gray-400">
                {{ $t('billing_alert_description') }}
            </b>
        </div>

        <ValidationObserver
            v-if="showUpdateBillingAlertForm"
            ref="updatebillingAlertForm"
            @submit.prevent="updateBillingAlert"
            v-slot="{ invalid }"
            tag="form"
            class="mt-6"
        >
            <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Billing Alert" rules="required">
                <AppInputText
                    :description="
                        $t(
                            'billing_alert_notes'
                        )
                    "
                    :error="errors[0]"
                    :is-last="true"
                >
                    <div class="space-y-4 sm:flex sm:space-x-4 sm:space-y-0">
                        <input
                            v-model="billingAlertAmount"
                            :placeholder="$t('alert_amount_')"
                            type="number"
                            min="1"
                            max="999999999"
                            class="focus-border-theme input-dark"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                        <ButtonBase
                            :loadint="isSendingBillingAlert"
                            :disabled="isSendingBillingAlert"
                            type="submit"
                            button-style="theme"
                            class="w-full sm:w-auto"
                        >
                            {{ $t('update_alert') }}
                        </ButtonBase>
                    </div>
                </AppInputText>
            </ValidationProvider>
        </ValidationObserver>

        <ValidationObserver
            v-if="!user.data.relationships.alert"
            ref="billingAlertForm"
            @submit.prevent="setBillingAlert"
            v-slot="{ invalid }"
            tag="form"
            class="mt-6"
        >
            <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Billing Alert" rules="required">
                <AppInputText
                    :description="
                        $t(
                            'billing_alert_notes'
                        )
                    "
                    :error="errors[0]"
                    :is-last="true"
                >
                    <div class="space-y-4 sm:flex sm:space-x-4 sm:space-y-0">
                        <input
                            v-model="billingAlertAmount"
                            :placeholder="$t('alert_amount_')"
                            type="number"
                            min="1"
                            max="999999999"
                            class="focus-border-theme input-dark"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                        <ButtonBase
                            :loadint="isSendingBillingAlert"
                            :disabled="isSendingBillingAlert"
                            type="submit"
                            button-style="theme"
                            class="w-full sm:w-auto"
                        >
                            {{ $t('set_alert') }}
                        </ButtonBase>
                    </div>
                </AppInputText>
            </ValidationProvider>
        </ValidationObserver>
    </div>
</template>
<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate/dist/vee-validate.full'
import ButtonBase from '../UI/Buttons/ButtonBase'
import AppInputText from '../Forms/Layouts/AppInputText'
import FormLabel from '../UI/Labels/FormLabel'
import { Edit2Icon, Trash2Icon } from 'vue-feather-icons'
import { events } from '../../bus'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'UserBillingAlerts',
    components: {
        ValidationObserver,
        ValidationProvider,
        AppInputText,
        ButtonBase,
        Trash2Icon,
        Edit2Icon,
        FormLabel,
    },
    computed: {
        ...mapGetters(['user']),
    },
    data() {
        return {
            billingAlertAmount: undefined,
            isSendingBillingAlert: false,
            showUpdateBillingAlertForm: false,
        }
    },
    methods: {
        async updateBillingAlert() {
            // Validate fields
            const isValid = await this.$refs.updatebillingAlertForm.validate()

            if (!isValid) return

            this.isSendingBillingAlert = true

            axios
                .put('/api/subscriptions/billing-alert', {
                    amount: this.billingAlertAmount,
                })
                .then(() => {
                    this.$store.dispatch('getAppData')

                    this.showUpdateBillingAlertForm = false

                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('alert_updated'),
                    })
                })
                .catch(() => {
                    events.$emit('toaster', {
                        type: 'danger',
                        message: this.$t('popup_error.title'),
                    })
                })
                .finally(() => {
                    this.isSendingBillingAlert = false
                })
        },
        async setBillingAlert() {
            // Validate fields
            const isValid = await this.$refs.billingAlertForm.validate()

            if (!isValid) return

            this.isSendingBillingAlert = true

            axios
                .post('/api/subscriptions/billing-alert', {
                    amount: this.billingAlertAmount,
                })
                .then(() => {
                    this.$store.dispatch('getAppData')

                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('alert_set_successfully'),
                    })
                })
                .catch(() => {
                    events.$emit('toaster', {
                        type: 'danger',
                        message: this.$t('popup_error.title'),
                    })
                })
                .finally(() => {
                    this.isSendingBillingAlert = false
                })
        },
        deleteBillingAlert() {
            events.$emit('confirm:open', {
                title: this.$t('want_to_delete_alert'),
                message: this.$t(
                    'want_to_delete_alert_description'
                ),
                action: {
                    id: this.user.data.relationships.alert.data.id,
                    operation: 'delete-billing-alert',
                },
            })
        },
    },
    created() {
        events.$on('action:confirmed', (data) => {
            if (data.operation === 'delete-billing-alert')
                axios
                    .delete('/api/subscriptions/billing-alert')
                    .then(() => {
                        this.$store.dispatch('getAppData')

                        this.showUpdateBillingAlertForm = false
                        this.billingAlertAmount = undefined

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('deleted_alert'),
                        })
                    })
                    .catch(() => this.$isSomethingWrong())
        })
    },
    destroyed() {
        events.$off('action:confirmed')
    },
}
</script>
