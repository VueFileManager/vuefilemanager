<template>
    <div id="single-page">
        <div v-show="! isLoading" id="page-content" class="large-width center-page">

            <div class="content-page">
                <div class="plan-title">
                    <img v-if="config.app_logo" class="logo" :src="$getImage(config.app_logo)" :alt="config.app_name">
                    <b v-if="! config.app_logo" class="auth-logo-text">{{ config.app_name }}</b>

                    <h1>Oasis Drive</h1>
                    <h2>Zaplacenim objednavky se Vas ucet automaticky zaktivuje a vytvori se Vam digitalni prostor pro Vase dulezite dokumenty.</h2>
                </div>

                <div class="order">
                    <div class="steps">
                        <div class="payment-card">
                            <FormLabel>{{ $t('page_upgrade_account.section_card') }}</FormLabel>

                            <!-- Pay by new credit card -->
                            <div class="register-card form block-form">
                                <InfoBox v-if="config.isDemo">
                                    <p>For test your payment please use <b>4242 4242 4242 4242</b> or <b>5555 5555 5555 4444</b> as a card number, <b>11/22</b>
                                        as the expiration date and <b>123</b> as CVC number and ZIP <b>12345</b>.</p>
                                </InfoBox>


                                <div class="block-wrapper">
                                    <label>Platebni karta:</label>
                                    <div ref="stripeCard" class="stripe-card" :class="{'is-error': isError }"></div>

                                    <div class="card-error-message" v-if="isError">
                                        <span>{{ errorMessage }}</span>
                                    </div>
                                </div>

                                <InfoBox>
                                    <ListInfo class="billing">
                                        <ListInfoItem class="billing-item" title="Spolecnost" content="CMPortal, s.r.o." />
                                        <ListInfoItem class="billing-item" title="Adresa" content="Korytná 47/3, Praha 10000, Česká Republika" />
                                    </ListInfo>
                                </InfoBox>
                            </div>
                        </div>
                    </div>

                    <div class="summary">
                        <FormLabel>
                            {{ $t('page_upgrade_account.section_summary') }}
                        </FormLabel>

                        <div class="summary-list" :class="{'is-error': isError}" v-if="requestedPlan">
                            <div class="row">
                                <div class="cell">
                                    <b>{{ requestedPlan.data.relationships.plan.data.attributes.name }}</b>
                                    <small>{{ $t('page_upgrade_account.summary.period') }}</small>
                                </div>
                                <div class="cell">
                                    <b>{{ requestedPlan.data.relationships.plan.data.attributes.price }}</b>
                                </div>
                            </div>
                            <div class="row" v-if="taxRates">
                                <div class="cell">
                                    <b>{{ $t('page_upgrade_account.summary.vat') }} - ({{ taxRates.country }} {{ taxRates.percentage }}%)</b>
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
                                    <b>{{ requestedPlan.data.relationships.plan.data.attributes.price }}</b>
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
    import ListInfoItem from '@/components/Others/ListInfoItem'
    import ListInfo from '@/components/Others/ListInfo'

    let [stripe, card] = [undefined, undefined];

    export default {
        name: 'SubscriptionRequestPayment',
        components: {
            ListInfoItem,
            ListInfo,
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
            ...mapGetters(['config', 'countries']),
            taxRates() {
                return this.requestedPlan.data.relationships.plan.data.attributes.tax_rates.find(taxRate => {
                    return taxRate.country === this.requestedPlan.data.relationships.user.data.attributes.country
                })
            }
        },
        data() {
            return {
                requestedPlan: undefined,
                errorMessage: undefined,
                clientSecret: undefined,
                isSubmitted: false,
                isPayed: false,
                isLoading: true,
                isError: false,
                stripeOptions: {
                    hidePostalCode: false
                },
            }
        },
        methods: {
            async submitOrder() {
                // Remove error
                this.isError = false

                // Start loading
                this.isSubmitted = true

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
                        .post(`/api/oasis/subscribe/${this.$route.params.id}`, {
                            plan: this.requestedPlan,
                            payment: {
                                type: 'stripe',
                                meta: {
                                    pm: setupIntent.payment_method,
                                }
                            }
                        })
                        .then(() => {
                            this.successOrder()
                        })
                        .catch((error) => {
                            this.errorOrder(error)
                        })
                        .finally(() => {
                            this.isSubmitted = false
                        })
                }
            },
            successOrder() {

                // Show toaster
                events.$emit('toaster', {
                    type: 'success',
                    message: this.$t('toaster.account_upgraded'),
                })

                this.$router.push({name: 'CreatePasswordAfterPayment'})
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
            initStripe() {
                stripe = Stripe(this.config.stripe_public_key)

                let elements = stripe.elements();

                card = elements.create('card');

                card.mount(this.$refs.stripeCard);
            },
        },
        mounted() {
            let StripeElementsScript = document.createElement('script')

            StripeElementsScript.setAttribute('src', 'https://js.stripe.com/v3/')
            document.head.appendChild(StripeElementsScript)

            // Get setup intent for stripe
            axios.get(`/api/oasis/subscribe/${this.$route.params.id}/setup-intent`)
                .then(response => {
                    this.clientSecret = response.data.client_secret

                    this.initStripe()
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })

            axios.get(`/api/oasis/subscribe/${this.$route.params.id}`)
                .then(response => {
                    this.requestedPlan = response.data

                    if (response.data.data.attributes.status === 'payed') {
                        this.$router.push({name: 'CreatePasswordAfterPayment'})
                    }

                    if (response.data.data.attributes.status === 'logged') {
                        this.$router.push({name: 'Files'})
                    }
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
                .finally(() => {
                    this.isLoading = false
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';
    @import '@assets/vuefilemanager/_forms';

    .billing {
        margin-top: 0 !important;

        .billing-item {
            margin-right: 30px;

            &:last-child {
                padding-bottom: 0;
            }
        }
    }

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
        margin-top: 60px;

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
        margin: 0 auto;

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
