<template>
    <div id="single-page">
        <div v-show="! isLoading" id="page-content" class="large-width center-page">

            <div class="content-page auth-form">
                <div class="plan-title">
                    <img v-if="config.app_logo" class="logo" :src="$getImage(config.app_logo)" :alt="config.app_name">
                    <b v-if="! config.app_logo" class="auth-logo-text">{{ config.app_name }}</b>

                    <h1>Oasis Drive</h1>
                    <h2>Dakujeme, platba bola uspesne zaznamenana. V poslednom kroku si prosim vytvorte heslo pre Vas ucet.</h2>
                </div>

                <ValidationObserver @submit.prevent="signUp" ref="setPassword" v-slot="{ invalid }" tag="form"
                                    class="form block-form password-form">

                    <div class="block-wrapper">
                        <label>{{ $t('page_registration.label_pass') }}</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Your New Password"
                                            rules="required" v-slot="{ errors }">
                            <input v-model="password" :placeholder="$t('page_registration.placeholder_pass')" type="password"
                                   class="focus-border-theme"
                                   :class="{'is-error': errors[0]}" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <div class="block-wrapper">
                        <label>{{ $t('page_registration.label_confirm_pass') }}</label>
                        <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Confirm Your Password"
                                            rules="required" v-slot="{ errors }">
                            <input v-model="password_confirmation" :placeholder="$t('page_registration.placeholder_confirm_pass')"
                                   class="focus-border-theme"
                                   type="password" :class="{'is-error': errors[0]}" />
                            <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                        </ValidationProvider>
                    </div>

                    <AuthButton icon="chevron-right" text="Vytvorit Heslo" :loading="isLoading" :disabled="isLoading" />
                </ValidationObserver>
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
    import AuthButton from '@/components/Auth/AuthButton'
    import Spinner from '@/components/FilesView/Spinner'
    import {CreditCardIcon} from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'
    import ListInfoItem from '@/components/Others/ListInfoItem'
    import ListInfo from '@/components/Others/ListInfo'

    export default {
        name: 'CreatePasswordAfterPayment',
        components: {
            AuthButton,
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
            ...mapGetters([
                'config'
            ]),
        },
        data() {
            return {
                requested: undefined,
                isSubmitted: false,
                isLoading: true,
                isError: false,
                password: undefined,
                password_confirmation: undefined,
            }
        },
        methods: {
            async signUp() {

                // Validate fields
                const isValid = await this.$refs.setPassword.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get user token
                axios
                    .post('/api/oasis/register', this.register)
                    .then(() => {

                        // Go to files page
                        this.$router.push({name: 'Files'})
                    })
                    .catch(error => {

                        if (error.response.status == 422) {

                            if (error.response.data.errors['password']) {

                                this.$refs.setPassword.setErrors({
                                    'Your New Password': error.response.data.errors['password']
                                });
                            }
                        }
                    })
                    .finally(() => {
                        this.isLoading = false
                    })
            },
        },
        mounted() {
            axios.get(`/api/oasis/subscription-request/${this.$route.params.id}`)
                .then(response => {
                    this.requested = response.data
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
    @import '@assets/vuefilemanager/_auth';
    @import '@assets/vuefilemanager/_auth-form';

    .auth-form {
        max-width: 700px;
    }

    .password-form {
        max-width: 550px;
        margin: 0 auto;
        text-align: center;
    }
</style>
