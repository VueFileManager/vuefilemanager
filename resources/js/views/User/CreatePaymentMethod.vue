<template>
    <PageTab>
        <PageTabGroup>
            <div class="form block-form">
                <FormLabel>{{ $t('user_payments.add_card') }}</FormLabel>

                <!-- Register new credit card -->
                <div class="register-card">
                    <InfoBox v-show="config.isDemo">
                        <p>For test your payment please use <b>4242 4242 4242 4242</b> or <b>5555 5555 5555 4444</b> as a card number, <b>11/22</b>
                            as the expiration date and <b>123</b> as CVC number and ZIP <b>12345</b>.</p>
                    </InfoBox>

                    <div class="block-wrapper">
                        <label>{{ $t('user_payments.card_field_title') }}:</label>
                        <div ref="stripeCard" class="stripe-card" :class="{'is-error': isError }">
                            <span class="loading">
                                {{ $t('user_payments.field_loading') }}
                            </span>
                        </div>
                        <div class="card-error-message" v-if="isError">
                            <span>{{ errorMessage }}</span>
                        </div>
                    </div>
                </div>

                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">{{ $t('user_add_card.default_title') }}:</label>
                                <small class="input-help">{{ $t('user_add_card.default_description') }}</small>
                            </div>
                            <SwitchInput v-model="defaultPaymentMethod" class="switch" :state="defaultPaymentMethod"/>
                        </div>
                    </div>
                </div>

                <ButtonBase @click.native="registerCard" :loading="isSubmitted" :button-style="isDisabledSubmit ? 'secondary' : 'theme'" type="submit">
                    {{ $t('user_payments.store_card') }}
                </ButtonBase>
            </div>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    let [stripe, card] = [undefined, undefined];

    export default {
        name: 'CreatePaymentMethod',
        components: {
            PageTabGroup,
            SwitchInput,
            ButtonBase,
            FormLabel,
            PageTab,
            InfoBox,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                errorMessage: undefined,
                isError: false,

                stripeOptions: {
                    hidePostalCode: false
                },

                isSubmitted: false,
                isDisabledSubmit: true,

                defaultPaymentMethod: true,
                clientSecret: undefined,
            }
        },
        methods: {
            async registerCard() {

                // Prevent empty submit
                if (! stripe && !card && ! this.$refs.stripeCard.classList.contains('StripeElement')) return

                // Start loading
                this.isSubmitted = true

                const {setupIntent, error} = await stripe.confirmCardSetup(this.clientSecret, {
                    payment_method: {
                        card: card,
                    }
                })

                if (setupIntent) {

                    axios
                        .post('/api/user/payment-cards', {
                            token: setupIntent.payment_method,
                            default: this.defaultPaymentMethod,
                        })
                        .then(() => {

                            // Show toaster
                            events.$emit('toaster', {
                                type: 'success',
                                message: this.$t('toaster.card_new_add'),
                            })

                            // Go to payment methods page
                            this.$router.push({name: 'PaymentMethods'})
                        })
                        .catch(() => {
                            events.$emit('alert:open', {
                                title: this.$t('popup_error.title'),
                                message: this.$t('popup_error.message'),
                            })
                        })
                        .finally(() => {
                            this.isSubmitted = false
                        })
                }

                if (error) {

                    // Set error on
                    this.isError = true

                    // End button spinner
                    this.isSubmitted = false

                    // Show error message
                    this.errorMessage = error.message
                }
            },
            initStripe() {
                stripe = Stripe(this.config.stripe_public_key)

                let elements = stripe.elements();

                card = elements.create('card');

                card.mount(this.$refs.stripeCard);

                this.isDisabledSubmit = false
            },
        },
        mounted() {
            let StripeElementsScript = document.createElement('script')

            StripeElementsScript.setAttribute('src', 'https://js.stripe.com/v3/')
            document.head.appendChild(StripeElementsScript)

            // Get setup intent for stripe
            axios.get('/api/stripe/setup-intent')
                .then(response => {
                    this.clientSecret = response.data.client_secret

                    this.initStripe()
                })
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .register-card {
        margin-bottom: 25px;
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


        &:not(.StripeElement) {
            background: $light_background;
            padding: 14px 20px;

            .loading {
                @include font-size(14);
                font-weight: 700;
            }
        }

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
</style>
