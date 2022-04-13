<template>
    <div v-if="!hasPaymentMethod" class="card shadow-card">
        <FormLabel icon="dollar">
            {{ $t('balance') }}
        </FormLabel>

        <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
            {{ user.data.relationships.balance.data.attributes.formatted }}
        </b>

        <!-- Make payment form -->
        <ValidationObserver
            ref="fundAccount"
            @submit.prevent="makePayment"
            v-slot="{ invalid }"
            tag="form"
            class="mt-6"
        >
            <ValidationProvider
                tag="div"
                v-slot="{ errors }"
                mode="passive"
                name="Amount"
                :rules="`required|min_value:${user.data.meta.totalDebt.amount}`"
            >
                <AppInputText
                    :description="
                        $t('amount_increase_description')
                    "
                    :error="errors[0]"
                    :is-last="true"
                >
                    <div class="space-y-4 sm:flex sm:space-x-4 sm:space-y-0">
                        <input
                            v-model="chargeAmount"
                            :placeholder="$t('fund_account_balance')"
                            type="number"
                            min="1"
                            max="999999999"
                            class="focus-border-theme input-dark"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                        <ButtonBase type="submit" button-style="theme" class="w-full sm:w-auto">
                            {{ $t('make_payment') }}
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
import FormLabel from '../UI/Labels/FormLabel'
import AppInputText from '../Forms/Layouts/AppInputText'
import { mapGetters } from 'vuex'

export default {
    name: 'UserBalance',
    components: {
        ValidationObserver,
        ValidationProvider,
        AppInputText,
        ButtonBase,
        FormLabel,
    },
    computed: {
        ...mapGetters(['user']),
        hasPaymentMethod() {
            return this.user.data.relationships.creditCards && this.user.data.relationships.creditCards.data.length > 0
        },
    },
    data() {
        return {
            chargeAmount: undefined,
        }
    },
    methods: {
        async makePayment() {
            // Validate fields
            const isValid = await this.$refs.fundAccount.validate()

            if (!isValid) return

            // Show payment methods popup
            this.$store.dispatch('callSingleChargeProcess', this.chargeAmount)
        },
    },
}
</script>
