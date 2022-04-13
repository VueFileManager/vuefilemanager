<template>
    <div>
        <!--Balance-->
        <div class="card shadow-card">
            <FormLabel icon="hard-drive">
                {{ $t('balance') }}
            </FormLabel>

            <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                {{ user.data.relationships.balance.data.attributes.formatted }}
            </b>

            <ValidationObserver
                ref="creditUserBalance"
                @submit.prevent="increaseBalance"
                v-slot="{ invalid }"
                tag="form"
                class="mt-6"
            >
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Balance Amount" rules="required">
                    <AppInputText
                        :description="$t('balance_will_be_increased')"
                        :error="errors[0]"
                        :is-last="true"
                    >
                        <div class="space-y-4 sm:flex sm:space-x-4 sm:space-y-0">
                            <input
                                v-model="balanceAmount"
                                :placeholder="$t('increase_for')"
                                type="number"
                                min="1"
                                max="999999999"
                                class="focus-border-theme input-dark"
                                :class="{ '!border-rose-600': errors[0] }"
                            />
                            <ButtonBase
                                type="submit"
                                button-style="theme"
                                class="w-full sm:w-auto"
                                :loading="isUpdatingBalanceAmount"
                                :disabled="isUpdatingBalanceAmount"
                            >
                                {{ $t('increase_balance') }}
                            </ButtonBase>
                        </div>
                    </AppInputText>
                </ValidationProvider>
            </ValidationObserver>
        </div>

        <!--Usage Estimates-->
        <div class="card shadow-card">
            <FormLabel icon="hard-drive">
                {{ $t('usage_estimates') }}
            </FormLabel>

            <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                {{ user.data.meta.usages.costEstimate }}
            </b>

            <b class="mb-3 mb-5 block text-sm text-gray-400">
                {{ user.data.relationships.subscription.data.attributes.updated_at }}
                {{ $t('till_now') }}
            </b>

            <div>
                <div
                    class="flex items-center justify-between border-b border-dashed border-light py-2 dark:border-opacity-5"
                    v-for="(usage, i) in user.data.meta.usages.featureEstimates"
                    :key="i"
                >
                    <div class="w-2/4 leading-none">
                        <b class="text-sm font-bold leading-none">
                            {{ $t(usage.feature) }}
                        </b>
                        <small class="block pt-2 text-xs leading-none dark:text-gray-500 text-gray-500">
                            {{ $t(`feature_usage_desc_${usage.feature}`) }}
                        </small>
                    </div>
                    <div class="w-1/4 text-left">
                        <span class="text-gray-560 text-sm font-bold">
                            {{ usage.usage }}
                        </span>
                    </div>
                    <div class="w-1/4 text-right">
                        <span class="text-theme text-sm font-bold">
                            {{ usage.cost }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import ColorLabel from '../../../../components/UI/Labels/ColorLabel'
import { mapGetters } from 'vuex'
import axios from 'axios'
import { events } from '../../../../bus'

export default {
    name: 'UserMeteredSubscription',
    props: ['subscription', 'user'],
    components: {
        ValidationProvider,
        ValidationObserver,
        AppInputText,
        ButtonBase,
        ColorLabel,
        FormLabel,
    },
    computed: {
        ...mapGetters([]),
    },
    data() {
        return {
            balanceAmount: undefined,
            isUpdatingBalanceAmount: false,
        }
    },
    methods: {
        async increaseBalance() {
            // Validate fields
            const isValid = await this.$refs.creditUserBalance.validate()

            if (!isValid) return

            this.isUpdatingBalanceAmount = true

            axios
                .post(`/api/subscriptions/admin/users/${this.user.data.id}/credit`, {
                    amount: this.balanceAmount,
                })
                .then(() => {
                    events.$emit('reload:user')

                    this.balanceAmount = undefined

                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('balance_was_increased'),
                    })
                })
                .catch(() => {
                    events.$emit('toaster', {
                        type: 'danger',
                        message: this.$t('popup_error.title'),
                    })
                })
                .finally(() => {
                    this.isUpdatingBalanceAmount = false
                })
        },
    },
    created() {},
}
</script>
