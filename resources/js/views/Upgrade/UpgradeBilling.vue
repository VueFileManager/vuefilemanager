<template>
    <div id="single-page">
        <div id="page-content" class="large-width center-page" v-show="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">

                <div class="plan-title">
                    <credit-card-icon size="42" class="title-icon"></credit-card-icon>
                    <h1>Choose Payment Method</h1>
                    <h2>Choose plan witch perfect fit your needs. All plans is billed monthly automatically via your
                        credit card.</h2>
                </div>

                <div class="order">

                    <div class="steps">

                        <div class="payment-card">
                            <b class="form-group-label">Payment Card:</b>

                            <!-- Pay by new credit card -->
                            <div class="register-card" v-show="! defaultPaymentCard || payByNewCard">
                                <p class="payment-demo-disclaimer">
                                    For test your payment please use <b>4242 4242 4242 4242</b> as a card number, <b>11/22</b>
                                    as the expiration date and <b>123</b> as CVC number and ZIP <b>12345</b>.
                                </p>

                                <div ref="stripeCard" class="stripe-card" :class="{'is-error': isError }"></div>

                                <div class="card-error-message" v-if="isError">
                                    <span>{{ errorMessage }}</span>
                                </div>
                            </div>

                            <!--User registered payment card-->
                            <div class="registered-cards" v-if="defaultPaymentCard && ! payByNewCard">

                                <div class="credit-card" :class="{'is-error': isError}">
                                    <div class="card-number">
                                        <img class="credit-card-icon"
                                             :src="$getCreditCardBrand(defaultPaymentCard.data.attributes.brand)"
                                             :alt="defaultPaymentCard.data.attributes.brand">
                                        <div class="credit-card-numbers">
                                            •••• {{ defaultPaymentCard.data.attributes.last4 }}
                                        </div>
                                        <ColorLabel color="purple">Default</ColorLabel>
                                    </div>
                                    <div class="expiration-date">
                                        <span>{{ defaultPaymentCard.data.attributes.exp_month }} / {{ defaultPaymentCard.data.attributes.exp_year }}</span>
                                    </div>
                                </div>

                                <!--Change payment-->
                                <div class="change-payment" v-if="! isError">
                                    <span>Also you can</span>

                                    <router-link v-if="paymentCards.data.length > 0" :to="{name: 'PaymentCards'}">change your
                                        default payment method
                                    </router-link>
                                    <span v-if="paymentCards.data.length > 0">or</span>

                                    <a @click="payByNewCardForm">pay by new credit card.</a>
                                </div>

                                <!--Card error-->
                                <div class="card-error-message" v-if="isError">
                                    <span>{{ errorMessage }}</span>
                                    <span @click="payByNewCardForm"
                                          class="link">Please pay by another payment card</span>
                                    <span> or </span>
                                    <router-link :to="{name: 'PaymentCards'}" class="link">Change your default payment
                                        method
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="billing" v-if="billing">
                            <b class="form-group-label">Billing Information:</b>
                            <ValidationObserver ref="order" v-slot="{ invalid }" tag="form" class="form block-form">
                                <div class="form block-form">

                                    <div class="block-wrapper">
                                        <label>Name:</label>
                                        <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                            rules="required"
                                                            name="billing_name" v-slot="{ errors }">
                                            <input v-model="billing.billing_name"
                                                   placeholder="Type your billing name"
                                                   type="text"
                                                   :class="{'is-error': errors[0]}"
                                            />
                                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>

                                    <div class="block-wrapper">
                                        <label>Address:</label>
                                        <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                            rules="required"
                                                            name="billing_address" v-slot="{ errors }">
                                            <input v-model="billing.billing_address"
                                                   placeholder="Type your billing address"
                                                   type="text"
                                                   :class="{'is-error': errors[0]}"
                                            />
                                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>

                                    <div class="block-wrapper">
                                        <label>State:</label>
                                        <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                            rules="required"
                                                            name="billing_state" v-slot="{ errors }">
                                            <input v-model="billing.billing_state"
                                                   placeholder="Type your billing state"
                                                   type="text"
                                                   :class="{'is-error': errors[0]}"
                                            />
                                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>

                                    <div class="wrapper-inline">
                                        <div class="block-wrapper">
                                            <label>City:</label>
                                            <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                                rules="required" name="billing_city"
                                                                v-slot="{ errors }">
                                                <input v-model="billing.billing_city"
                                                       placeholder="Type your billing city"
                                                       type="text"
                                                       :class="{'is-error': errors[0]}"
                                                />
                                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </div>

                                        <div class="block-wrapper">
                                            <label>Postal Code:</label>
                                            <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                                rules="required" name="billing_postal_code"
                                                                v-slot="{ errors }">
                                                <input v-model="billing.billing_postal_code"
                                                       placeholder="Type your billing postal code"
                                                       type="text"
                                                       :class="{'is-error': errors[0]}"
                                                />
                                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </div>
                                    </div>

                                    <div class="block-wrapper">
                                        <label>Country:</label>
                                        <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                            rules="required"
                                                            name="billing_country" v-slot="{ errors }">
                                            <input v-model="billing.billing_country"
                                                   placeholder="Type your billing country"
                                                   type="text"
                                                   :class="{'is-error': errors[0]}"
                                            />
                                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </div>

                                    <div class="block-wrapper">
                                        <label>Phone Number:</label>
                                        <ValidationProvider tag="div" mode="passive" class="input-wrapper"
                                                            rules="required"
                                                            name="billing_phone_number" v-slot="{ errors }">
                                            <input v-model="billing.billing_phone_number"
                                                   placeholder="Type your billing phone number"
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
                        <b class="form-group-label">Order Summary:</b>
                        <div class="summary-list" :class="{'is-error': isError}" v-if="requestedPlan">
                            <div class="row">
                                <div class="cell">
                                    <b>{{ requestedPlan.data.attributes.name }}</b>
                                    <small>Billed monthly</small>
                                </div>
                                <div class="cell">
                                    <b>{{ requestedPlan.data.attributes.price }}</b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="cell">
                                    <b>Total</b>
                                </div>
                                <div class="cell">
                                    <b>{{ requestedPlan.data.attributes.price }}</b>
                                </div>
                            </div>
                            <ButtonBase :disabled="isSubmitted" :loading="isSubmitted" @click.native="submitOrder"
                                        type="submit" button-style="theme-solid" class="next-submit">
                                Pay with credit card
                            </ButtonBase>
                            <p class="error-message" v-if="isError">{{ errorMessage }}</p>
                            <small class="disclaimer">
                                By submit form, you agree to save the payment method and billing information in your
                                VueFileManager account.
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
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import ColorLabel from '@/components/Others/ColorLabel'
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'
    import {CreditCardIcon} from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    let stripe = Stripe(`pk_test_51GsACaCBETHMUxzVsYkeApHtqb85paMuye7G77PDDQ28kXqDJ5HTmqLi13aM6xee81OQK1fhkTZ7vmDiWLStU9160061Yb2MtL`),
        elements = stripe.elements(),
        card = undefined;

    export default {
        name: 'UpgradePlan',
        components: {
            ValidationProvider,
            ValidationObserver,
            PlanPricingTables,
            CreditCardIcon,
            MobileHeader,
            ButtonBase,
            PageHeader,
            ColorLabel,
            required,
            Spinner,
        },
        computed: {
            ...mapGetters(['requestedPlan']),
            billing() {
                return this.$store.getters.user.relationships.settings.data.attributes
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
                paymentCards: undefined,
                defaultPaymentCard: undefined,

                errorMessage: undefined,
                isError: false,

                payByNewCard: false,

                clientSecret: undefined
            }
        },
        methods: {
            payByNewCardForm() {
                this.payByNewCard = true
                this.isError = false
            },
            successOrder() {
                // Update user data
                //this.$store.dispatch('getAppData')

                // End loading
                this.isSubmitted = false

                // Show toaster
                events.$emit('toaster', {
                    type: 'success',
                    message: 'Your account was successfully upgraded.',
                })

                // Go to User page
                //this.$router.push({name: 'Subscription'})
            },
            errorOrder(error) {

                if (error.response.status = 402) {
                    this.isError = true
                    this.errorMessage = error.response.data.message
                }

                // End loading
                this.isSubmitted = false
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
                if (!this.defaultPaymentCard || this.payByNewCard) {

                    console.log('Payment by new card');

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
                    }
                }

                // if user has credit card
                if (this.defaultPaymentCard && !this.payByNewCard) {

                    console.log('Payment by default card');

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
                }
            },
        },
        mounted: function () {
            card = elements.create('card');
            card.mount(this.$refs.stripeCard);
        },
        created() {

            // Get setup intent for stripe
            axios.get('/api/stripe/setup-intent')
                .then(response => this.clientSecret = response.data.client_secret)

            axios.get('/api/user/payments')
                .then(response => {

                    if (!this.requestedPlan) {
                        this.$router.push({name: 'UpgradePlan'})
                    }

                    this.defaultPaymentCard = response.data.default
                    this.paymentCards = response.data.others

                    this.isLoading = false
                })
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

    .payment-demo-disclaimer {
        padding: 15px;
        background: $light_background;
        border-radius: 8px;
        margin-bottom: 20px;
        line-height: 1.6;

        b {
            color: $danger;
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

    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
