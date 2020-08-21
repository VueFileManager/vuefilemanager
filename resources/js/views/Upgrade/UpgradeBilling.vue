<template>
    <div id="single-page">
        <div id="page-content" class="large-width center-page" v-show="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <div class="content-page">
                <div class="plan-title">
                    <credit-card-icon size="42" class="title-icon"></credit-card-icon>
                    <h1>{{ $t('page_upgrade_account.title') }}</h1>
                    <h2>{{ $t('page_upgrade_account.desription') }}</h2>
                </div>
                <div class="order">
                    <div class="steps">

                        <div class="payment-card">
                            <FormLabel>{{ $t('page_upgrade_account.section_card') }}</FormLabel>

                            <!-- Pay by new credit card -->
                            <div class="register-card" v-show="! defaultPaymentMethod || payByNewCard">
                                <InfoBox v-if="config.isDemo">
                                    <p>For test your payment please use <b>4242 4242 4242 4242</b> or <b>5555 5555 5555 4444</b> as a card number, <b>11/22</b>
                                        as the expiration date and <b>123</b> as CVC number and ZIP <b>12345</b>.</p>
                                </InfoBox>

                                <div ref="stripeCard" class="stripe-card" :class="{'is-error': isError }"></div>

                                <div class="card-error-message" v-if="isError">
                                    <span>{{ errorMessage }}</span>
                                </div>
                            </div>

                            <!--User registered payment card-->
                            <div class="registered-cards" v-if="defaultPaymentMethod && ! payByNewCard">

                                <div class="credit-card" :class="{'is-error': isError}">
                                    <div class="card-number">
                                        <img class="credit-card-icon"
                                             :src="$getCreditCardBrand(defaultPaymentMethod.data.attributes.brand)"
                                             :alt="defaultPaymentMethod.data.attributes.brand">
                                        <div class="credit-card-numbers">
                                            •••• {{ defaultPaymentMethod.data.attributes.last4 }}
                                        </div>
                                        <ColorLabel color="purple">{{ $t('global.default') }}</ColorLabel>
                                    </div>
                                    <div class="expiration-date">
                                        <span>{{ defaultPaymentMethod.data.attributes.exp_month }} / {{ defaultPaymentMethod.data.attributes.exp_year }}</span>
                                    </div>
                                </div>

                                <!--Change payment-->
                                <div class="change-payment" v-if="! isError">
                                    <span>
                                        {{ $t('page_upgrade_account.change_payment.you_can') }}
                                    </span>

                                    <router-link v-if="PaymentMethods.data.length > 0" :to="{name: 'PaymentMethods'}">
                                        {{ $t('page_upgrade_account.change_payment.change_payment') }}
                                    </router-link>

                                    <span v-if="PaymentMethods.data.length > 0">
                                        {{ $t('global.or') }}
                                    </span>

                                    <a @click="payByNewCardForm">
                                        {{ $t('page_upgrade_account.change_payment.pay_by_new_card') }}
                                    </a>
                                </div>

                                <!--Card error-->
                                <div class="card-error-message" v-if="isError">
                                    <span>{{ errorMessage }}</span>
                                    <span @click="payByNewCardForm" class="link">
                                        {{ $t('page_upgrade_account.errors.pay_by_another_card') }}
                                    </span>
                                    <span>
                                        {{ $t('global.or') }}
                                    </span>
                                    <router-link :to="{name: 'PaymentMethods'}" class="link">
                                        {{ $t('page_upgrade_account.change_payment.change_payment') }}
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="billing" v-if="billing">
                            <FormLabel>{{ $t('page_upgrade_account.section_billing') }}</FormLabel>

                            <ValidationObserver ref="order" v-slot="{ invalid }" tag="form" class="form block-form">
                                <div class="form block-form">

                                    <div class="block-wrapper">
                                        <label>{{ $t('user_settings.name') }}:</label>
                                        <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                            rules="required"
                                                            name="billing_name" v-slot="{ errors }">
                                            <input v-model="billing.billing_name"
                                                   :placeholder="$t('user_settings.name_plac')"
                                                   type="text"
                                                   :class="{'is-error': errors[0]}"
                                            />
                                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>

                                    <div class="block-wrapper">
                                        <label>{{ $t('user_settings.address') }}:</label>
                                        <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                            rules="required"
                                                            name="billing_address" v-slot="{ errors }">
                                            <input v-model="billing.billing_address"
                                                   :placeholder="$t('user_settings.address_plac')"
                                                   type="text"
                                                   :class="{'is-error': errors[0]}"
                                            />
                                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>

                                    <div class="wrapper-inline">
                                        <div class="block-wrapper">
                                            <label>{{ $t('user_settings.city') }}:</label>
                                            <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                                rules="required" name="billing_city"
                                                                v-slot="{ errors }">
                                                <input v-model="billing.billing_city"
                                                       :placeholder="$t('user_settings.city_plac')"
                                                       type="text"
                                                       :class="{'is-error': errors[0]}"
                                                />
                                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </div>

                                        <div class="block-wrapper">
                                            <label>{{ $t('user_settings.postal_code') }}:</label>
                                            <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                                rules="required" name="billing_postal_code"
                                                                v-slot="{ errors }">
                                                <input v-model="billing.billing_postal_code"
                                                       :placeholder="$t('user_settings.postal_code_plac')"
                                                       type="text"
                                                       :class="{'is-error': errors[0]}"
                                                />
                                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </div>
                                    </div>

                                    <div class="block-wrapper">
                                        <label>{{ $t('user_settings.country') }}:</label>
                                        <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                            rules="required"
                                                            name="billing_country" v-slot="{ errors }">
                                            <SelectInput v-model="billing.billing_country"
                                                         :default="billing.billing_country"
                                                         :options="countries"
                                                         :placeholder="$t('user_settings.country_plac')"
                                                         :isError="errors[0]" />
                                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>

                                    <div class="block-wrapper">
                                        <label>{{ $t('user_settings.state') }}:</label>
                                        <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                            rules="required"
                                                            name="billing_state" v-slot="{ errors }">
                                            <input v-model="billing.billing_state"
                                                   :placeholder="$t('user_settings.state_plac')"
                                                   type="text"
                                                   :class="{'is-error': errors[0]}"
                                            />
                                            <small class="input-help">
                                                State, county, province, or region.
                                            </small>
                                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>

                                    <div class="block-wrapper">
                                        <label>{{ $t('user_settings.phone_number') }}:</label>
                                        <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                            rules="required"
                                                            name="billing_phone_number" v-slot="{ errors }">
                                            <input v-model="billing.billing_phone_number"
                                                   :placeholder="$t('user_settings.phone_number_plac')"
                                                   type="text"
                                                   :class="{'is-error': errors[0]}"
                                            />
                                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>
                                </div>
                            </ValidationObserver>
                        </div>
                    </div>
                    <div class="summary">
                        <FormLabel>{{ $t('page_upgrade_account.section_summary') }}</FormLabel>
                        <div class="summary-list" :class="{'is-error': isError}" v-if="requestedPlan">
                            <div class="row">
                                <div class="cell">
                                    <b>{{ requestedPlan.data.attributes.name }}</b>
                                    <small>{{ $t('page_upgrade_account.summary.period') }}</small>
                                </div>
                                <div class="cell">
                                    <b>{{ requestedPlan.data.attributes.price }}</b>
                                </div>
                            </div>
                            <div class="row" v-if="taxRates">
                                <div class="cell">
                                    <b>{{ $t('page_upgrade_account.summary.vat') }} - ({{ taxRates.jurisdiction }} {{ taxRates.percentage }}%)</b>
                                </div>
                                <div class="cell">
                                    <b>{{ taxRates.plan_price_formatted }}</b>
                                </div>
                            </div>

                            <!--Show total when tax rates is not specified-->
                            <div class="row" v-if="! taxRates">
                                <div class="cell">
                                    <b>{{ $t('global.total') }}</b>
                                </div>
                                <div class="cell">
                                    <b>{{ requestedPlan.data.attributes.price }}</b>
                                </div>
                            </div>

                            <!--Show total when is tax rates-->
                            <div class="row" v-if="taxRates">
                                <div class="cell">
                                    <b>{{ $t('page_upgrade_account.summary.total_with_vat') }}</b>
                                </div>
                                <div class="cell">
                                    <b>{{ taxRates.plan_price_formatted }}</b>
                                </div>
                            </div>

                            <ButtonBase :disabled="isSubmitted" :loading="isSubmitted" @click.native="submitOrder"
                                        type="submit" button-style="theme-solid" class="next-submit">
                                {{ $t('page_upgrade_account.summary.submit_button') }}
                            </ButtonBase>
                            <p class="error-message" v-if="isError">{{ errorMessage }}</p>
                            <small class="disclaimer">
                                {{ $t('page_upgrade_account.summary.submit_disclaimer', {app: config.app_name}) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import PlanPricingTables from '@/components/Others/PlanPricingTables'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import ColorLabel from '@/components/Others/ColorLabel'
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'
    import {CreditCardIcon} from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    let [stripe, card] = [undefined, undefined];

    export default {
        name: 'UpgradePlan',
        components: {
            ValidationProvider,
            ValidationObserver,
            PlanPricingTables,
            CreditCardIcon,
            MobileHeader,
            SelectInput,
            ButtonBase,
            PageHeader,
            ColorLabel,
            FormLabel,
            required,
            Spinner,
            InfoBox,
        },
        computed: {
            ...mapGetters(['requestedPlan', 'config', 'countries']),
            billing() {
                return this.$store.getters.user.relationships.settings.data.attributes
            },
            taxRates() {
                return this.requestedPlan.data.attributes.tax_rates.find(taxRate => {
                   return taxRate.jurisdiction === this.billing.billing_country
                })
            }
        },
        data() {
            return {
                complete: false,
                stripeOptions: {
                    hidePostalCode: false
                },
                isLoading: true,
                isSubmitted: false,
                PaymentMethods: undefined,
                defaultPaymentMethod: undefined,

                errorMessage: undefined,
                isError: false,

                payByNewCard: false,

                clientSecret: undefined
            }
        },
        methods: {
            initStripe() {
                stripe = Stripe(this.config.stripe_public_key)

                let elements = stripe.elements();

                card = elements.create('card');

                card.mount(this.$refs.stripeCard);
            },
            payByNewCardForm() {
                this.payByNewCard = true
                this.isError = false
            },
            successOrder() {
                // Update user data
                this.$store.dispatch('getAppData')

                // Show toaster
                events.$emit('toaster', {
                    type: 'success',
                    message: this.$t('toaster.account_upgraded'),
                })

                // Go to User page
                this.$router.push({name: 'Subscription'})
            },
            errorOrder(error) {

                // Redirect user to confirmation payment page
                if (error.response.status === 402) {
                    window.location.href = error.response.data.message;
                }

                // Show user error message
                if (error.response.status === 400) {
                    this.isError = true
                    this.errorMessage = error.response.data.message
                }

                // Show server error
                if (error.response.status === 500) {
                    this.isError = true
                    this.errorMessage = error.response.data.message

                    events.$emit('alert:open', {
                        title: this.$t('popup_error.title'),
                        message: this.$t('popup_error.message'),
                    })
                }
            },
            async submitOrder() {

                // Validate fields
                const isValid = await this.$refs.order.validate();

                if (!isValid) return;

                // Remove error
                this.isError = false

                // Start loading
                this.isSubmitted = true

                // If user don't have credit card, register new
                if (!this.defaultPaymentMethod || this.payByNewCard) {

                    const {setupIntent, error} = await stripe.confirmCardSetup(this.clientSecret, {
                        payment_method: {
                            card: card,
                        }
                    })

                    if (error) {

                        // Set error on
                        this.isError = true

                        // End button spinner
                        this.isSubmitted = false

                        // Show error message
                        this.errorMessage = error.message

                    } else {

                        axios
                            .post('/api/subscription/upgrade', {
                                billing: this.billing,
                                plan: this.requestedPlan,
                                payment: {
                                    type: 'stripe',
                                    meta: {
                                        pm: setupIntent.payment_method,
                                    }
                                }
                            })
                            .then(() => this.successOrder())
                            .catch((error) => this.errorOrder(error))
                            .finally(() => {
                                this.isSubmitted = false
                            })
                    }
                }

                // if user has credit card
                if (this.defaultPaymentMethod && !this.payByNewCard) {

                    axios
                        .post('/api/subscription/upgrade', {
                            billing: this.billing,
                            plan: this.requestedPlan,
                            payment: {
                                type: 'stripe',
                            }
                        })
                        .then(() => this.successOrder())
                        .catch((error) => this.errorOrder(error))
                        .finally(() => {
                            this.isSubmitted = false
                        })
                }
            },
        },
        mounted: function () {
            if (!this.requestedPlan) {
                this.$router.push({name: 'UpgradePlan'})
            } else {
                this.initStripe()
            }
        },
        created() {

            // Get setup intent for stripe
            axios.get('/api/stripe/setup-intent')
                .then(response => {
                    this.clientSecret = response.data.client_secret
                })
                .catch(() => this.$isSomethingWrong())

            axios.get('/api/user/payments')
                .then(response => {

                    this.defaultPaymentMethod = response.data.default
                    this.PaymentMethods = response.data.others
                })
                .catch(() => this.$isSomethingWrong())
                .finally(() => {
                        this.isLoading = false
                    }
                )
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .change-payment {
        padding-top: 10px;

        span {
            font-weight: 600;
        }

        a {
            cursor: pointer;
            font-weight: 700;

            &:hover {
                text-decoration: underline;
            }
        }

        span, a {
            color: $text-muted;
            @include font-size(14);
        }
    }

    .card-error-message {
        padding-top: 10px;

        span, a {
            @include font-size(14);
            font-weight: 600;
            color: $danger;
        }

        .link, a {
            text-decoration: underline;
            cursor: pointer;

            &:hover {
                text-decoration: none;
            }
        }
    }

    .registered-cards {
        margin-bottom: 50px;
    }

    .register-card {
        margin-bottom: 55px;
    }

    .credit-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        background: $light_background;
        border-radius: 8px;
        margin-top: 20px;

        &.is-error {
            box-shadow: 0 0 7px rgba($danger, 0.3);
            border: 2px solid $danger;
            border-radius: 4px;
        }

        span {
            font-weight: 700;
        }

        .card-number {
            display: flex;
        }

        .credit-card-numbers {
            vertical-align: middle;
            margin-right: 10px;
        }

        .credit-card-icon {
            vertical-align: middle;
            max-height: 20px;
            margin-right: 8px;
        }
    }

    .stripe-card {
        box-sizing: border-box;
        padding: 13px 20px;

        border: 1px solid transparent;
        border-radius: 4px;
        background-color: white;

        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;

        &.is-error {
            box-shadow: 0 0 7px rgba($danger, 0.3);
            border: 2px solid $danger;
            border-radius: 4px;
        }

        &.StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        &.StripeElement--invalid {
            border-color: #fa755a;
        }

        &.StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }

        iframe .InputContainer .InputElement {
            color: white;
        }
    }

    .summary-list {
        box-shadow: 0 7px 20px 5px hsla(220, 36%, 16%, 0.06);
        border-radius: 8px;
        position: sticky;
        padding: 25px;
        top: 30px;

        &.is-error {
            border: 2px solid $danger;
            box-shadow: 0 7px 20px 5px rgba($danger, 0.06);
        }

        .error-message {
            font-weight: 600;
        }

        .next-submit {
            width: 100%;
            margin-top: 20px;
        }

        .disclaimer {
            @include font-size(12);
            line-height: 1.6;
            display: block;
            margin-top: 12px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;

            &:first-child {
                padding-top: 0;
            }

            &:last-of-type {
                border-top: 1px solid $light_mode_border;
                padding-bottom: 0;

                b {
                    font-weight: 800;
                }
            }
        }

        .cell {
            b {
                display: block;
                @include font-size(18);
            }

            small {
                color: $text-muted;
                @include font-size(12);
            }
        }
    }

    .order {
        display: flex;
        margin-bottom: 30px;

        .steps {
            flex: 0 0 65%;
            padding-right: 30px;

            .form {
                max-width: 100%;
            }
        }

        .summary {
            flex: 0 0 34%;
        }
    }

    .plan-title {
        text-align: center;
        max-width: 600px;
        margin: 0 auto 80px;

        path, line, polyline, rect, circle {
            color: $theme;
        }

        h1 {
            @include font-size(38);
            font-weight: 800;
            margin-bottom: 5px;
        }

        h2 {
            @include font-size(20);
            font-weight: 500;
        }
    }

    @media (prefers-color-scheme: dark) {

        .plan-title {

            h1 {
                color: $dark_mode_text_primary;
            }

            h2 {
                color: $dark_mode_text_secondary;
            }
        }

        .credit-card {
            background: $dark_mode_foreground;

            span, .credit-card-numbers {
                color: $dark_mode_text_primary;
            }
        }

        .change-payment {

            span {
                color: $dark_mode_text_secondary;
            }

            a {
                color: $theme;
            }
        }

        .summary-list {
            background: $dark_mode_foreground;

            .disclaimer {
                color: $dark_mode_text_secondary;
            }

            .row {

                &:last-of-type {
                    border-top: 1px solid $dark_mode_border_color;

                    b {
                        color: $dark_mode_text_primary;
                    }
                }
            }

            .cell {
                b {
                    color: $dark_mode_text_primary;
                }

                small {
                    color: $dark_mode_text_secondary;
                }
            }
        }

        .stripe-card {
            border: 1px solid transparent;
            //background-color: $dark_mode_foreground;
            box-shadow: none;

            &.StripeElement--webkit-autofill {
                background-color: $dark_mode_foreground !important;
            }

            &.StripeElement--focus {
                box-shadow: none;
                border-color: $theme;
                box-shadow: 0 1px 5px rgba($theme, 0.3);
            }
        }
    }

    @media only screen and (max-width: 960px) {
        .order {
            display: block;

            .steps {
                margin-bottom: 70px;
            }
        }
    }

</style>
