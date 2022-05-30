<template>
    <AuthContentWrapper v-if="isVisible" ref="auth" class="h-screen">
        <!--Log In by Email-->
        <AuthContent name="log-in" :visible="true">
            <Headline :title="$t('welcome_back')" :description="$t('page_login.subtitle')" />

            <ValidationObserver
                @submit.prevent="logIn"
                ref="log_in"
                v-slot="{ invalid }"
                tag="form"
                class="mb-12 items-start space-y-4 md:flex md:space-x-4 md:space-y-0"
            >
                <ValidationProvider
                    class="w-full text-left"
                    tag="div"
                    mode="passive"
                    name="E-Mail"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <input
                        class="dark:placeholder:text-gray-600 focus-border-theme w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 font-bold dark:bg-2x-dark-foreground"
                        :class="{ '!border-rose-600': errors[0] }"
                        v-model="loginEmail"
                        :placeholder="$t('page_login.placeholder_email')"
                        type="email"
                    />
                    <span class="text-left text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton
                    class="w-full justify-center md:w-min"
                    icon="chevron-right"
                    :text="$t('next_step')"
                    :loading="isLoading"
                    :disabled="isLoading"
                />
            </ValidationObserver>

            <SocialLoginButtons />

            <span v-if="config.userRegistration" class="block">
                {{ $t('page_login.registration_text') }}
                <router-link class="text-theme font-bold" :to="{ name: 'SignUp' }">
                    {{ $t('page_login.registration_button') }}
                </router-link>
            </span>
        </AuthContent>

        <!--Log in By Password-->
        <AuthContent name="sign-in" :visible="false">
            <Headline
                v-if="checkedAccount"
                :title="$t('page_sign_in.title', { name: checkedAccount.name })"
                :description="$t('page_sign_in.subtitle')"
            >
                <img
					v-if="checkedAccount.avatar"
                    class="user-avatar mx-auto mb-6 w-28 rounded-xl shadow-xl"
                    :src="checkedAccount.avatar.md"
                    :alt="checkedAccount.name"
                />
            </Headline>

            <ValidationObserver
                @submit.prevent="singIn"
                ref="sign_in"
                v-slot="{ invalid }"
                tag="form"
                class="mb-12 items-start space-y-4 md:flex md:space-x-4 md:space-y-0"
            >
                <ValidationProvider
                    tag="div"
                    mode="passive"
                    class="w-full text-left"
                    name="User Password"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <input
                        v-model="loginPassword"
                        :placeholder="$t('page_sign_in.placeholder_password')"
                        type="password"
						ref="inputPassword"
                        class="dark:placeholder:text-gray-600 focus-border-theme h-full w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 font-bold dark:bg-2x-dark-foreground"
                        :class="{ '!border-rose-600': errors[0] }"
                    />
                    <span class="text-left text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton
                    class="w-full justify-center md:w-min"
                    icon="chevron-right"
                    :text="$t('log_in')"
                    :loading="isLoading"
                    :disabled="isLoading"
                />
            </ValidationObserver>

            <span class="block">
                {{ $t('page_sign_in.password_reset_text') }}
                <router-link :to="{ name: 'ForgottenPassword' }" class="text-theme font-bold">
                    {{ $t('page_sign_in.password_reset_button') }}
                </router-link>
            </span>
        </AuthContent>

        <!--Resend verification email-->
        <AuthContent name="not-verified" :visible="false">
            <Headline
                v-if="checkedAccount"
                :title="$t('page_sign_in_2fa_title', { name: checkedAccount.name })"
                :description="$t('page_not_verified.subtitle')"
            >
                <img
					v-if="checkedAccount.avatar"
                    class="user-avatar mx-auto mb-6 w-28 rounded-xl shadow-xl"
                    :src="checkedAccount.avatar.md"
                    :alt="checkedAccount.name"
                />
            </Headline>

            <span class="block">
                {{ $t('page_not_verified.resend_text') }}
                <b @click="resendEmail" class="text-theme cursor-pointer">
                    {{ $t('page_not_verified.resend_button') }}
                </b>
            </span>
        </AuthContent>

        <!-- Log in by 2fa -->
        <AuthContent name="two-factor-authentication" :visible="false">
            <Headline
                v-if="checkedAccount"
                :title="$t('page_sign_in_2fa_title', { name: checkedAccount.name })"
                :description="$t('page_sign_in_2fa_subtitle')"
            >
                <img
					v-if="checkedAccount.avatar"
                    class="user-avatar mx-auto mb-6 w-28 rounded-xl shadow-xl"
                    :src="checkedAccount.avatar.md"
                    :alt="checkedAccount.name"
                />
            </Headline>

            <ValidationObserver ref="two_factor_authentication" v-slot="{ invalid }" tag="form" class="mb-12">
                <ValidationProvider
                    tag="div"
                    mode="passive"
                    class="mx-auto"
                    name="Two Factor Authentication"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <input
                        v-model="twoFactorCode"
                        ref="twoFactorCodeInput"
                        :placeholder="$t('page_sign_in.placeholder_2fa')"
                        @input="twoFactorChallenge(false)"
                        type="text"
                        maxlength="6"
                        class="dark:placeholder:text-gray-600 focus-border-theme h-full w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 text-center font-bold dark:bg-2x-dark-foreground md:w-80"
                        :class="{ '!border-rose-600': errors[0] }"
                    />
                    <span class="mt-2 block text-center text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </ValidationObserver>

            <span class="block">
                {{ $t('page_sign_in.2fa_recovery_text') }}
                <b @click="goToAuthPage('two-factor-recovery')" class="text-theme cursor-pointer cursor-pointer">
                    {{ $t('page_sign_in.2fa_recovery_button') }}
                </b>
            </span>

            <div class="relative mt-10 h-12 w-full">
                <Spinner v-if="isLoading" class="spinner" />
            </div>
        </AuthContent>

        <!-- Log in by 2fa with recovery code -->
        <AuthContent name="two-factor-recovery" :visible="false">
            <Headline
                v-if="checkedAccount"
                :title="$t('page_sign_in_2fa_title', { name: checkedAccount.name })"
                :description="$t('page_sign_in.2fa_recovery_subtitle')"
            >
                <img
					v-if="checkedAccount.avatar"
                    class="user-avatar mx-auto mb-6 w-28 rounded-xl shadow-xl"
                    :src="checkedAccount.avatar.md"
                    :alt="checkedAccount.name"
                />
            </Headline>

            <ValidationObserver ref="two_factor_recovery" v-slot="{ invalid }" tag="form" class="mb-12">
                <ValidationProvider
                    tag="div"
                    mode="passive"
                    class="mx-auto"
                    name="Two Factor Recovery"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <input
                        v-model="twoFactorRecoveryCode"
                        :placeholder="$t('page_sign_in.placeholder_2fa_recovery')"
                        @input="twoFactorChallenge(true)"
                        type="text"
                        maxlength="21"
                        class="dark:placeholder:text-gray-600 focus-border-theme h-full w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 text-center font-bold dark:bg-2x-dark-foreground md:w-80"
                        :class="{ '!border-rose-600': errors[0] }"
                    />
                    <span class="mt-2 block text-center text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </ValidationObserver>

            <b @click="goToAuthPage('two-factor-authentication')" class="text-theme block cursor-pointer">
                {{ $t('2fa.i_have_2fa_app') }}
            </b>

            <div v-if="isLoading" class="relative mt-10 h-12 w-full">
                <Spinner class="spinner" />
            </div>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import AuthContentWrapper from '../../components/Layout/AuthPages/AuthContentWrapper'
import { ValidationObserver, ValidationProvider } from 'vee-validate/dist/vee-validate.full'
import SocialLoginButtons from '../../components/UI/Buttons/SocialLoginButtons'
import AuthContent from '../../components/Layout/AuthPages/AuthContent'
import AuthButton from '../../components/UI/Buttons/AuthButton'
import Spinner from '../../components/UI/Others/Spinner'
import { mapGetters } from 'vuex'
import { events } from '../../bus'
import axios from 'axios'
import Headline from '../../components/UI/Labels/LogoHeadline'

export default {
    name: 'SignIn',
    components: {
        SocialLoginButtons,
        AuthContentWrapper,
        ValidationProvider,
        ValidationObserver,
        AuthContent,
        AuthButton,
        Headline,
        Spinner,
    },
    computed: {
        ...mapGetters(['config']),
    },
    data() {
        return {
			isVisible: false,
            isLoading: false,
            validSignIn: false,
            checkedAccount: undefined,
            loginPassword: '',
            loginEmail: '',
            twoFactorCode: '',
            twoFactorRecoveryCode: '',
        }
    },
    methods: {
        goToAuthPage(slug) {
            this.$refs.auth.$children.forEach((page) => {
                // Hide current step
                page.isVisible = page.$props.name === slug
            })
        },
        resendEmail() {
            axios
                .post('/api/user/verify', {
                    email: this.loginEmail,
                })
                .then(() => {
                    this.$router.push({ name: 'SuccessfullySend' })
                })
                .catch(() => {
                    this.$isSomethingWrong()
                })
        },
        async logIn() {
            // Validate fields
            const isValid = await this.$refs.log_in.validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true

            // Send request to get verify account
            axios
                .post('/api/user/check', {
                    email: this.loginEmail,
                })
                .then((response) => {
                    // End loading
                    this.isLoading = false

                    this.checkedAccount = response.data

                    if (response.data.oauth_provider) {
                        // Redirect user to socialite login if he's accout is registered by socialite
                        this.$store.dispatch('socialiteRedirect', response.data.oauth_provider)
                    } else {
                        // Show sign in password page
                        this.goToAuthPage('sign-in')

						this.$nextTick(() => {
							this.$refs.inputPassword.focus()
						})
                    }
                })
                .catch((error) => {
                    if (error.response.status == 404) {
                        this.$refs.log_in.setErrors({
                            'E-Mail': [error.response.data.message],
                        })
                    }

                    if (error.response.status == 500) {
                        events.$emit('alert:open', {
                            emoji: '🤔',
                            title: this.$t('popup_signup_error.title'),
                            message: this.$t('popup_signup_error.message'),
                        })
                    }

                    // End loading
                    this.isLoading = false
                })
        },
        async singIn() {
            // Validate fields
            const isValid = this.validSignIn ? this.validSignIn : await this.$refs.sign_in.validate()

            if (!isValid) return

            if (!this.checkedAccount.verified) {
                this.goToAuthPage('not-verified')

                return
            }

            // Start loading
            this.isLoading = true

            // Send request to get user token
            axios
                .post('/login', {
                    email: this.loginEmail,
                    password: this.loginPassword,
                })
                .then((response) => {
                    // End loading
                    this.isLoading = false

                    // If is enabled two factor authentication
                    if (response.data.two_factor && !this.validSignIn) {
                        this.validSignIn = true

                        this.goToAuthPage('two-factor-authentication')

                        // Autofocus to input
                        this.$nextTick(() => this.$refs.twoFactorCodeInput.focus())
                    }

                    // If is disabled two factor authentication
                    if (!response.data.two_factor) {
                        // Set login state
                        this.$store.commit('SET_AUTHORIZED', true)

                        // Go to files page
                        this.proceedToAccount()
                    }
                })
                .catch((error) => {
                    if (error.response.status == 422) {
                        this.$refs.sign_in.setErrors({
                            'User Password': [this.$t('validation_errors.incorrect_password')],
                        })
                    }

                    // End loading
                    this.isLoading = false
                })
        },
        async twoFactorChallenge(recovery) {
            // Check if is normal authentication or recovery
            if (
                (!recovery && this.twoFactorCode.length === 6) ||
                (recovery && this.twoFactorRecoveryCode.length === 21)
            ) {
                this.isLoading = true

                let payload = recovery ? { recovery_code: this.twoFactorRecoveryCode } : { code: this.twoFactorCode }

                axios
                    .post('/two-factor-challenge', payload)
                    .then(() => {
                        this.isLoading = false

                        // Set login state
                        this.$store.commit('SET_AUTHORIZED', true)

                        // Go to files page
                        this.proceedToAccount()
                    })
                    .catch((error) => {
                        if (error.response.status == 422) {
                            //Authentication bad input
                            if (!recovery) {
                                this.$refs.two_factor_authentication.setErrors({
                                    'Two Factor Authentication': this.$t('validation_errors.incorrect_2fa_code'),
                                })
                            }

                            // Recovery bad input
                            if (recovery) {
                                this.$refs.two_factor_recovery.setErrors({
                                    'Two Factor Recovery': this.$t('validation_errors.incorrect_2fa_recovery_code'),
                                })
                            }
                        }

                        // Repeat the login for next try to type right 2fa code / recovery code
                        this.singIn()

                        this.isLoading = false
                    })
            }
        },
        proceedToAccount() {
            if (this.$route.query.redirect) {
                this.$router.push(this.$route.query.redirect)
            } else {
                this.$router.push({ name: 'Files' })
            }
        },
    },
	mounted() {
		// Redirect if user is authenticated
		if (this.$root.$data.config.isAuthenticated) {
			this.$router.push({name: 'Files'})
		}
	},
	created() {
		// Show the page when user is not authenticated
		if (! this.$root.$data.config.isAuthenticated) {
			this.isVisible = true
		}

        this.$scrollTop()
        this.$store.commit('PROCESSING_POPUP', undefined)

        if (this.config.isPrefilledUsers) {
            this.loginEmail = 'howdy@hi5ve.digital'
            this.loginPassword = 'vuefilemanager'
        }
    },
}
</script>
