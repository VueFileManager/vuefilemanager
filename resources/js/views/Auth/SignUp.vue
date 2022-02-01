<template>
    <AuthContentWrapper ref="auth">
        <!--Registration-->
        <AuthContent name="sign-up" :visible="true" class="mt-4 mb-12">
            <Headline :title="$t('page_registration.title')" :description="$t('page_registration.subtitle')" />

            <ValidationObserver @submit.prevent="signUp" ref="sign_up" v-slot="{ invalid }" tag="form" class="mb-12 space-y-4 text-left">
                <div class="mx-auto mb-5 md:flex md:max-w-lg md:items-center">
                    <label class="mb-1.5 block font-bold md:mb-0 md:w-72 md:pr-4 md:text-right"> {{ $t('page_registration.label_email') }}: </label>
                    <ValidationProvider tag="div" mode="passive" class="w-full text-left" name="E-Mail" rules="required" v-slot="{ errors }">
                        <input
                            v-model="register.email"
                            :placeholder="$t('page_registration.placeholder_email')"
                            type="email"
                            class="focus-border-theme w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 font-bold dark:bg-2x-dark-foreground"
                            :class="{ 'border-red': errors[0] }"
                        />
                        <span class="text-left text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="mx-auto mb-5 md:flex md:max-w-lg md:items-center">
                    <label class="mb-1.5 block font-bold md:mb-0 md:w-72 md:pr-4 md:text-right"> {{ $t('page_registration.label_name') }}: </label>
                    <ValidationProvider tag="div" mode="passive" class="w-full text-left" name="Full Name" rules="required" v-slot="{ errors }">
                        <input
                            v-model="register.name"
                            :placeholder="$t('page_registration.placeholder_name')"
                            type="text"
                            class="focus-border-theme w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 font-bold dark:bg-2x-dark-foreground"
                            :class="{ 'border-red': errors[0] }"
                        />
                        <span class="text-left text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="mx-auto mb-5 md:flex md:max-w-lg md:items-center">
                    <label class="mb-1.5 block font-bold md:mb-0 md:w-72 md:pr-4 md:text-right"> {{ $t('page_registration.label_pass') }}: </label>
                    <ValidationProvider tag="div" mode="passive" class="w-full text-left" name="Your New Password" rules="required" v-slot="{ errors }">
                        <input
                            v-model="register.password"
                            :placeholder="$t('page_registration.placeholder_pass')"
                            type="password"
                            class="focus-border-theme w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 font-bold dark:bg-2x-dark-foreground"
                            :class="{ 'border-red': errors[0] }"
                        />
                        <span class="text-left text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="mx-auto mb-5 md:flex md:max-w-lg md:items-center">
                    <label class="mb-1.5 block font-bold md:mb-0 md:w-72 md:pr-4 md:text-right"> {{ $t('page_registration.label_confirm_pass') }}: </label>
                    <ValidationProvider tag="div" mode="passive" class="w-full text-left" name="Confirm Your Password" rules="required" v-slot="{ errors }">
                        <input
                            v-model="register.password_confirmation"
                            :placeholder="$t('page_registration.placeholder_confirm_pass')"
                            class="focus-border-theme w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 font-bold dark:bg-2x-dark-foreground"
                            type="password"
                            :class="{ 'border-red': errors[0] }"
                        />
                        <span class="text-left text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="text-center">
                    <i18n path="page_registration.agreement" tag="p" class="mx-auto mt-12 mb-6 w-96 font-bold">
                        <router-link
                            :to="{
                                name: 'DynamicPage',
                                params: { slug: 'terms-of-service' },
                            }"
                            target="_blank"
                            class="text-theme"
                        >
                            {{ termsOfService.title }}
                        </router-link>
                        <router-link
                            :to="{
                                name: 'DynamicPage',
                                params: { slug: 'privacy-policy' },
                            }"
                            target="_blank"
                            class="text-theme"
                        >
                            {{ privacyPolicy.title }}
                        </router-link>
                    </i18n>
                    <AuthButton
                        class="w-full justify-center md:w-min"
                        icon="chevron-right"
                        :text="$t('page_registration.button_create_account')"
                        :loading="isLoading"
                        :disabled="isLoading"
                    />
                </div>
            </ValidationObserver>

            <SocialiteAuthenticationButtons />

            <span class="block"
                >{{ $t('page_registration.have_an_account') }}
                <router-link :to="{ name: 'SignIn' }" class="text-theme font-bold">
                    {{ $t('page_forgotten_password.password_remember_button') }}
                </router-link>
            </span>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import Headline from './Headline'
import AuthContentWrapper from '../../components/Auth/AuthContentWrapper'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import AuthContent from '../../components/Auth/AuthContent'
import AuthButton from '../../components/Auth/AuthButton'
import SocialiteAuthenticationButtons from '../../components/Auth/SocialiteAuthenticationButtons'
import { required } from 'vee-validate/dist/rules'
import { mapGetters } from 'vuex'
import { events } from '../../bus'
import axios from 'axios'

export default {
    name: 'SignUp',
    components: {
        SocialiteAuthenticationButtons,
        AuthContentWrapper,
        ValidationProvider,
        ValidationObserver,
        AuthContent,
        AuthButton,
        Headline,
        required,
    },
    computed: {
        ...mapGetters(['config']),
        privacyPolicy() {
            return this.config.legal.find((legal) => {
                return legal.slug === 'privacy-policy'
            })
        },
        termsOfService() {
            return this.config.legal.find((legal) => {
                return legal.slug === 'terms-of-service'
            })
        },
    },
    data() {
        return {
            isLoading: false,
            register: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                reCaptcha: null,
            },
        }
    },
    methods: {
        async signUp() {
            // Validate fields
            const isValid = await this.$refs.sign_up.validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true

            // Get ReCaptcha token
            if (config.allowedRecaptcha) {
                this.register.reCaptcha = await this.$reCaptchaToken('register').then((response) => {
                    return response
                })
            }

            // Send request to get user token
            axios
                .post('/api/register', this.register)
                .then(() => {
                    // End loading
                    this.isLoading = false

                    if (!this.config.userVerification) {
                        // Set login state
                        this.$store.commit('SET_AUTHORIZED', true)

                        // Go to files page
                        this.$router.push({ name: 'Files' })
                    } else {
                        // Go to SuccessfullySend page
                        this.$router.push({ name: 'SuccessfullySend' })
                    }
                })
                .catch((error) => {
                    if (error.response.status == 500) {
                        events.$emit('alert:open', {
                            emoji: '🤔',
                            title: this.$t('popup_signup_error.title'),
                            message: this.$t('popup_signup_error.message'),
                        })
                    }

                    if (error.response.status == 422) {
                        if (error.response.data.errors['email']) {
                            this.$refs.sign_up.setErrors({
                                'E-Mail': error.response.data.errors['email'],
                            })
                        }

                        if (error.response.data.errors['password']) {
                            this.$refs.sign_up.setErrors({
                                'Your New Password': error.response.data.errors['password'],
                            })
                        }
                    }

                    // End loading
                    this.isLoading = false
                })
        },
    },
    created() {
        this.$scrollTop()
    },
}
</script>
