<template>
    <AuthContentWrapper ref="auth">

        <!--Log In by Email-->
        <AuthContent name="log-in" :visible="true">
            <img class="logo" :src="config.app_logo" :alt="config.app_name">
            <h1>{{ $t('page_login.title') }}</h1>
            <h2>{{ $t('page_login.subtitle') }}</h2>

            <ValidationObserver @submit.prevent="logIn" ref="log_in" v-slot="{ invalid }" tag="form"
                                class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                    v-slot="{ errors }">
                    <input v-model="loginEmail" :placeholder="$t('page_login.placeholder_email')" type="email"
                           :class="{'is-error': errors[0]}"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" :text="$t('page_login.button_next')" :loading="isLoading"
                            :disabled="isLoading"/>
            </ValidationObserver>

            <span v-if="config.userRegistration" class="additional-link">{{ $t('page_login.registration_text') }}
                <router-link :to="{name: 'SignUp'}">
                    {{ $t('page_login.registration_button') }}
                </router-link>
            </span>
        </AuthContent>

        <!--Log in By Password-->
        <AuthContent name="sign-in" :visible="false">

            <div class="user" v-if="checkedAccount">
                <img class="user-avatar" :src="checkedAccount.avatar" :alt="checkedAccount.name">
                <h1>{{ $t('page_sign_in.title', {name: checkedAccount.name}) }}</h1>
                <h2>{{ $t('page_sign_in.subtitle') }}</h2>
            </div>

            <ValidationObserver @submit.prevent="singIn" ref="sign_in" v-slot="{ invalid }" tag="form"
                                class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="User Password" rules="required"
                                    v-slot="{ errors }">
                    <input v-model="loginPassword" :placeholder="$t('page_sign_in.placeholder_password')"
                           type="password"
                           :class="{'is-error': errors[0]}"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" :text="$t('page_sign_in.button_log_in')" :loading="isLoading"
                            :disabled="isLoading"/>
            </ValidationObserver>

            <span class="additional-link">{{ $t('page_sign_in.password_reset_text') }}
                <router-link :to="{name: 'ForgottenPassword'}">
                    {{ $t('page_sign_in.password_reset_button') }}
                </router-link>
            </span>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContent from '@/components/Auth/AuthContent'
    import AuthButton from '@/components/Auth/AuthButton'
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
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: false,
                checkedAccount: undefined,
                loginPassword: '',
                loginEmail: '',
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
                                'E-Mail': [error.response.data.message]
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
                const isValid = await this.$refs.sign_in.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get user token
                axios
                    .post('/api/user/login', {
                        email: this.loginEmail,
                        password: this.loginPassword,
                    })
                    .then(() => {

                        // End loading
                        this.isLoading = false

                        // Set login state
                        this.$store.commit('SET_AUTHORIZED', true)

                        // Go to files page
                        this.$router.push({name: 'Files'})
                    })
                    .catch(error => {

                        if (error.response.status == 400) {

                            this.$refs.sign_in.setErrors({
                                'User Password': [this.$t('validation_errors.incorrect_password')]
                            });
                        }

                        if (error.response.status == 401) {

                            if (error.response.data.error === 'invalid_client') {
                                events.$emit('alert:open', {
                                    emoji: '🤔',
                                    title: this.$t('popup_passport_error.title'),
                                    message: this.$t('popup_passport_error.message')
                                })
                            }
                        }

                        // End loading
                        this.isLoading = false
                    })
            },
        },
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_auth-form';
    @import '@assets/vue-file-manager/_auth';
</style>
