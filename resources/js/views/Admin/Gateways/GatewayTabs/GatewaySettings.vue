<template>
    <PageTab>
        <ValidationObserver v-if="gateway.attributes.slug === 'paypal'" ref="personalInformation" v-slot="{ invalid }" tag="form" class="form block-form">

            <PageTabGroup>
                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">Status:</label>
                                <small class="input-help">Status of your payment gateway on website.</small>
                            </div>
                            <SwitchInput @input="$updateText('/gateways/paypal', 'status', paymentGateway.attributes.status)" v-model="paymentGateway.attributes.status" class="switch" :state="paymentGateway.attributes.status"/>
                        </div>
                    </div>
                </div>
                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">Sandbox Mode:</label>
                                <small class="input-help">With sandbox mode on, you can test your payment process on you website.</small>
                            </div>
                            <SwitchInput @input="$updateText('/gateways/paypal', 'sandbox', paymentGateway.attributes.sandbox)" v-model="paymentGateway.attributes.sandbox" class="switch" :state="paymentGateway.attributes.sandbox"/>
                        </div>
                    </div>
                </div>
            </PageTabGroup>
            <PageTabGroup>
                    <b class="form-group-label">PayPal Secrets</b>

                    <!--Paypal Client ID-->
                    <div class="block-wrapper">
                        <label>Paypal Client ID</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ClientID" rules="required"
                                            v-slot="{ errors }">
                            <input @keyup="$updateText('/gateways/paypal', 'client_id', paymentGateway.attributes.client_id)" v-model="paymentGateway.attributes.client_id" placeholder="Paste PayPal client id here" type="text" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!--Paypal Secret-->
                    <div class="block-wrapper">
                        <label>Paypal Secret</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="PayPalSecret" rules="required"
                                            v-slot="{ errors }">
                            <input @keyup="$updateText('/gateways/paypal', 'secret', paymentGateway.attributes.secret)" v-model="paymentGateway.attributes.secret" placeholder="Paste PayPal secret here" type="text" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!--Paypal Webhook ID-->
                    <div class="block-wrapper">
                        <label>Paypal Webhook ID</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="PayPalSecret" rules="required"
                                            v-slot="{ errors }">
                            <input @keyup="$updateText('/gateways/paypal', 'webhook', paymentGateway.attributes.webhook)" v-model="paymentGateway.attributes.webhook" placeholder="Paste PayPal webhook here" type="text" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
            </PageTabGroup>
        </ValidationObserver>

        <ValidationObserver v-if="gateway.attributes.slug === 'stripe'" ref="personalInformation" v-slot="{ invalid }" tag="form" class="form block-form">
            <PageTabGroup>
                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">Status:</label>
                                <small class="input-help">Status of your payment gateway on website.</small>
                            </div>
                            <SwitchInput @input="$updateText('/gateways/stripe', 'status', paymentGateway.attributes.status)" v-model="paymentGateway.attributes.status" class="switch" :state="paymentGateway.attributes.status"/>
                        </div>
                    </div>
                </div>
                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">Sandbox Mode:</label>
                                <small class="input-help">With sandbox mode on, you can test your payment process on you website.</small>
                            </div>
                            <SwitchInput @input="$updateText('/gateways/stripe', 'sandbox', paymentGateway.attributes.sandbox)" v-model="paymentGateway.attributes.sandbox" class="switch" :state="paymentGateway.attributes.sandbox"/>
                        </div>
                    </div>
                </div>
            </PageTabGroup>
            <PageTabGroup>
                    <b class="form-group-label">Stripe Secret</b>

                    <!--Stripe Client ID-->
                    <div class="block-wrapper">
                        <label>Stripe Client ID</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ClientID" rules="required" v-slot="{ errors }">
                            <input @keyup="$updateText('/gateways/stripe', 'client_id', paymentGateway.attributes.client_id)" v-model="paymentGateway.attributes.client_id" placeholder="Paste stripe client id here" type="text" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <!--Stripe Secret-->
                    <div class="block-wrapper">
                        <label>Stripe Secret</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="StripeSecret" rules="required"
                                            v-slot="{ errors }">
                            <input @keyup="$updateText('/gateways/stripe', 'secret', paymentGateway.attributes.secret)" v-model="paymentGateway.attributes.secret" placeholder="Paste stripe secret here" type="text" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>
            </PageTabGroup>
        </ValidationObserver>
    </PageTab>
</template>

<script>
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'

    export default {
        name: 'GatewaySettings',
        props: [
            'gateway'
        ],
        components: {
            SwitchInput,
            PageTabGroup,
            PageTab,
            ValidationProvider,
            ValidationObserver,
            StorageItemDetail,
            SelectInput,
            ButtonBase,
            SetupBox,
            required,
        },
        data() {
            return {
                isLoading: false,
                isSendingRequest: false,
                paymentGateway: undefined,
            }
        },
        created() {
            this.paymentGateway = this.gateway
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .block-form {
        max-width: 100%;
    }

    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
