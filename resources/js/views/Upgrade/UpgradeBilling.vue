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
                    <div class="billing" v-if="billing">
                        <b class="form-group-label">Billing Information:</b>
                        <ValidationObserver ref="order" v-slot="{ invalid }" tag="form" class="form block-form">
                            <div class="form block-form">

                                <div class="block-wrapper">
                                    <label>Name:</label>
                                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required"
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
                                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required"
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
                                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required"
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
                                                            rules="required" name="billing_city" v-slot="{ errors }">
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
                                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required"
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
                                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required"
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
                    <div class="summary">
                        <b class="form-group-label">Order Summary:</b>

                        <div class="summary-list" v-if="requestedPlan">
                            <div class="row">
                                <div class="cell">
                                    <b>{{ requestedPlan.data.attributes.name }}</b>
                                    <small>Billed monthly</small>
                                </div>
                                <div class="cell">
                                    <b>{{ requestedPlan.data.attributes.price }} USD</b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="cell">
                                    <b>Total</b>
                                </div>
                                <div class="cell">
                                    <b>{{ requestedPlan.data.attributes.price }} USD</b>
                                </div>
                            </div>

                            <ButtonBase :disabled="isSubmitted" :loading="isSubmitted" @click.native="submitOrder"
                                        type="submit" button-style="theme-solid" class="next-submit">
                                Pay Order
                            </ButtonBase>

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
    import PageHeader from '@/components/Others/PageHeader'
    import Spinner from '@/components/FilesView/Spinner'
    import {CreditCardIcon} from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import { mapGetters } from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

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
            required,
            Spinner,
        },
        computed: {
            ...mapGetters(['requestedPlan']),
        },
        data() {
            return {
                isLoading: true,
                isSubmitted: false,
                billing: undefined,
            }
        },
        methods: {
            async submitOrder() {

                // Validate fields
                const isValid = await this.$refs.order.validate();

                if (!isValid) return;

                // Start loading
                this.isSubmitted = true

                // Send order request
                axios
                    .post('/api/upgrade', {
                        billing: this.billing,
                        plan: this.requestedPlan,
                    })
                    .then(response => {

                        // End loading
                        this.isSubmitted = false

                        // Show toaster
                        events.$emit('toaster', {
                            type: 'success',
                            message: 'Your account was successfully upgraded.',
                        })

                        // Go to User page
                        this.$router.push({name: 'Storage'})
                    })
                    .catch(error => {

                        // End loading
                        this.isSubmitted = false
                    })
            }
        },
        created() {
            axios.get('/api/profile')
                .then(response => {

                    if (! this.requestedPlan) {
                        this.$router.push({name: 'UpgradePlan'})
                    }

                    this.billing = {
                        billing_name: response.data.relationships.settings.data.attributes.billing_name,
                        billing_address: response.data.relationships.settings.data.attributes.billing_address,
                        billing_state: response.data.relationships.settings.data.attributes.billing_state,
                        billing_city: response.data.relationships.settings.data.attributes.billing_city,
                        billing_postal_code: response.data.relationships.settings.data.attributes.billing_postal_code,
                        billing_country: response.data.relationships.settings.data.attributes.billing_country,
                        billing_phone_number: response.data.relationships.settings.data.attributes.billing_phone_number,
                    }

                    this.isLoading = false
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .summary-list {
        box-shadow: 0 7px 20px 5px hsla(220, 36%, 16%, 0.06);
        border-radius: 8px;
        position: sticky;
        padding: 25px;
        top: 30px;

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

        .billing {
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
