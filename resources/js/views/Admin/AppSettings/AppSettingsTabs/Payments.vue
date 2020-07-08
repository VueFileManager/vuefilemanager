<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width">

        <!--Personal Information-->
        <PageTabGroup>
            <div class="form block-form">
                <FormLabel>Stripe Payments</FormLabel>
                <InfoBox>
                    <p>Your Stripe credentials is not showed because these values are secret and must not be revealed by stranger. You can change your Stripe credentials in your <b>.env</b> file.</p>
                </InfoBox>
                <div class="block-wrapper">
                    <div class="input-wrapper">
                        <div class="inline-wrapper">
                            <div class="switch-label">
                                <label class="input-label">Allow Subscription Payments:</label>
                            </div>
                            <SwitchInput @input="$updateText('/settings', 'payments_active', payments.status)" v-model="payments.status" class="switch" :state="payments.status"/>
                        </div>
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>Stripe webhook URL:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Webhook URL" rules="required" v-slot="{ errors }">
                        <input :value="stripeWebhookEndpoint" type="text" disabled/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
            </div>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import ImageInput from '@/components/Others/Forms/ImageInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import PageTab from '@/components/Others/Layout/PageTab'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'AppPayments',
        components: {
            ValidationObserver,
            ValidationProvider,
            StorageItemDetail,
            PageTabGroup,
            SwitchInput,
            SelectInput,
            ImageInput,
            ButtonBase,
            FormLabel,
            SetupBox,
            required,
            PageTab,
            InfoBox,
        },
        computed: {
            ...mapGetters(['config']),
            stripeWebhookEndpoint() {
                return this.config.host + '/stripe/webhook'
            }
        },
        data() {
            return {
                isLoading: true,
                payments: {
                    status: undefined,
                    configured: undefined,
                },
            }
        },
        mounted() {
            axios.get('/api/settings', {
                params: {
                    column: 'payments_active|payments_configured'
                }
            })
                .then(response => {
                    this.isLoading = false

                    this.payments.configured = parseInt(response.data.payments_configured)
                    this.payments.status = parseInt(response.data.payments_active)
                })
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
