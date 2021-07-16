<template>
    <AuthContentWrapper ref="auth">

        <!--Log In by Email-->
        <AuthContent name="log-in" :visible="true">
            <img v-if="config.app_logo" class="logo" :src="$getImage(config.app_logo)" :alt="config.app_name">
            <b v-if="! config.app_logo" class="auth-logo-text">{{ config.app_name }}</b>

            <h1>{{ $t('page_login.title') }}</h1>
            <h2>{{ $t('page_login.subtitle') }}:</h2>

            <ValidationObserver @submit.prevent="logIn" ref="log_in" v-slot="{ invalid }" tag="form"
                                class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                    v-slot="{ errors }">
                    <input v-model="loginEmail" :placeholder="$t('page_login.placeholder_email')" type="email"
                           class="focus-border-theme"
                           :class="{'is-error': errors[0]}" />
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" :text="$t('page_login.button_next')" :loading="isLoading"
                            :disabled="isLoading" />
            </ValidationObserver>

            <span v-if="config.userRegistration" class="additional-link">
                {{ $t('page_login.registration_text') }}
                <router-link class="text-theme" :to="{name: 'SignUp'}">
                    {{ $t('page_login.registration_button') }}
                </router-link>
            </span>
        </AuthContent>

        <!--Log in By Password-->
        <AuthContent name="sign-in" :visible="false">

            <div class="user" v-if="checkedAccount">
                <img class="user-avatar" :src="checkedAccount.avatar" :alt="checkedAccount.name">
                <h1>{{ $t('page_sign_in.title', {name: checkedAccount.name}) }}</h1>
                <h2>{{ $t('page_sign_in.subtitle') }}:</h2>
            </div>

            <ValidationObserver @submit.prevent="singIn" ref="sign_in" v-slot="{ invalid }" tag="form"
                                class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="User Password" rules="required"
                                    v-slot="{ errors }">
                    <input v-model="loginPassword" :placeholder="$t('page_sign_in.placeholder_password')"
                           type="password"
                           class="focus-border-theme"
                           :class="{'is-error': errors[0]}" />
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" :text="$t('page_sign_in.button_log_in')" :loading="isLoading"
                            :disabled="isLoading" />
            </ValidationObserver>

            <span class="additional-link">{{ $t('page_sign_in.password_reset_text') }}
                <router-link :to="{name: 'ForgottenPassword'}" class="text-theme">
                    {{ $t('page_sign_in.password_reset_button') }}
                </router-link>
            </span>
        </AuthContent>

        <!-- Log in by 2fa -->
        <AuthContent name="two-factor-authentication" :visible="false">

           <div class="user" v-if="checkedAccount">
                <img class="user-avatar" :src="checkedAccount.avatar" :alt="checkedAccount.name">
                <h1> {{ $t('page_sign_in_2fa_title', {name: checkedAccount.name}) }} </h1>
                <h2> {{ $t('page_sign_in_2fa_subtitle') }}:</h2>
            </div>

            <ValidationObserver ref="two_factor_authentication" v-slot="{ invalid }" tag="form"
                                class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Two Factor Authentication" rules="required"
                                    v-slot="{ errors }">
                    <input v-model="twoFactorCode" :placeholder="$t('page_sign_in.placeholder_2fa')"
                            @input="twoFactorChallenge(false)"
                           type="text"
                           maxlength="6"
                           class="focus-border-theme"
                           :class="{'is-error': errors[0]}" />
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

            </ValidationObserver>

             <span class="additional-link"> {{ $t('page_sign_in.2fa_recovery_text') }}
                <a @click="goToAuthPage('two-factor-recovery')" class="text-theme">
                   {{ $t('page_sign_in.2fa_recovery_button') }}
                </a>
            </span>

            <div class="spinner-wrapper">
                <Spinner v-if="isLoading" class="spinner"/>
            </div>

        </AuthContent>

        <!-- Log in by 2fa recovery code -->
        <AuthContent name="two-factor-recovery" :visible="false">

           <div class="user" v-if="checkedAccount">
                <img class="user-avatar" :src="checkedAccount.avatar" :alt="checkedAccount.name">
                <h1> {{ checkedAccount.name }} </h1>
                <h2>{{ $t('page_sign_in.2fa_recovery_subtitle') }}:</h2>
            </div>

            <ValidationObserver ref="two_factor_recovery" v-slot="{ invalid }" tag="form"
                                class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Two Factor Recovery" rules="required"
                                    v-slot="{ errors }">
                    <input v-model="twoFactorRecoveryCode" :placeholder="$t('page_sign_in.placeholder_2fa_recovery')"
                            @input="twoFactorChallenge(true)"
                           type="text"
                           maxlength="21"
                           class="focus-border-theme"
                           :class="{'is-error': errors[0]}" />
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

            </ValidationObserver>

             <div class="spinner-wrapper">
                <Spinner v-if="isLoading" class="spinner"/>
            </div>

        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContent from '@/components/Auth/AuthContent'
    import AuthButton from '@/components/Auth/AuthButton'
    import Spinner from '@/components/FilesView/Spinner'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'SignIn',
        components: {
            AuthContentWrapper,
            ValidationProvider,
            ValidationObserver,
            AuthContent,
            AuthButton,
            required,
            Spinner,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
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

                this.$refs.auth.$children.forEach(page => {

                    // Hide current step
                    page.isVisible = false

                    if (page.$props.name === slug) {

                        // Go to next step
                        page.isVisible = true
                    }
                })
            },
            async logIn() {

                // Validate fields
                const isValid = await this.$refs.log_in.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get verify account
                axios
                    .post('/api/user/check', {
                        email: this.loginEmail,
                    })
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        this.checkedAccount = response.data

                        // Show sign in password page
                        this.goToAuthPage('sign-in')
                    })
                    .catch(error => {

                        if (error.response.status == 404) {

                            this.$refs.log_in.setErrors({
                                'E-Mail': [error.response.data]
                            });
                        }

                        if (error.response.status == 500) {

                            events.$emit('alert:open', {
                                emoji: '🤔',
                                title: this.$t('popup_signup_error.title'),
                                message: this.$t('popup_signup_error.message')
                            })
                        }

                        // End loading
                        this.isLoading = false
                    })
            },
            async singIn() {

                // Validate fields
                const isValid = this.validSignIn ? this.validSignIn : await this.$refs.sign_in.validate();

                if (!isValid) return;

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
                        if(response.data.two_factor && ! this.validSignIn) {

                            this.validSignIn = true

                            this.goToAuthPage('two-factor-authentication')
                        } 
                        
                        // If is disabled two factor authentication 
                        if(! response.data.two_factor ) {

                            // Set login state
                            this.$store.commit('SET_AUTHORIZED', true)

                            // Go to files page
                            this.$router.push({name: 'Files'})
                        }
                    })
                    .catch(error => {

                        if (error.response.status == 422) {

                            this.$refs.sign_in.setErrors({
                                'User Password': [this.$t('validation_errors.incorrect_password')]
                            });
                        }

                        // End loading
                        this.isLoading = false
                    })
            },
			async twoFactorChallenge(recovery) {
				// Check if is normal authentication or recovery
				if (!recovery && this.twoFactorCode.length === 6 || recovery && this.twoFactorRecoveryCode.length === 21) {

					this.isLoading = true

					let payload = recovery
						? {recovery_code: this.twoFactorRecoveryCode}
						: {code: this.twoFactorCode}

					axios.post('/two-factor-challenge', payload)
						.then(() => {

							this.isLoading = false

							// Set login state
							this.$store.commit('SET_AUTHORIZED', true)

							// Go to files page
							this.$router.push({name: 'Files'})
						})
						.catch(error => {

							if (error.response.status == 422) {

								//Authentication bad input
								if (!recovery) {

									this.$refs.two_factor_authentication.setErrors({
										'Two Factor Authentication': this.$t('validation_errors.incorrect_2fa_code')
									})
								}

								// Recovery bad input
								if (recovery) {

									this.$refs.two_factor_recovery.setErrors({
										'Two Factor Recovery': this.$t('validation_errors.incorrect_2fa_recovery_code')
									})
								}

							}

							// Repeat the login for next try to type right 2fa code / recovery code
							this.singIn()

							this.isLoading = false
						})
				}

			},
        },
        created() {
            this.$scrollTop()
            this.$store.commit('PROCESSING_POPUP', undefined)

            //if (this.config.isDemo) {
            this.loginEmail = 'howdy@hi5ve.digital'
            this.loginPassword = 'vuefilemanager'
            //}
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_auth-form';
    @import '@assets/vuefilemanager/_auth';

    .spinner-wrapper {
        width: 100%;
        height: 50px;
        position: relative;
        top: 50px;
    }
</style>
