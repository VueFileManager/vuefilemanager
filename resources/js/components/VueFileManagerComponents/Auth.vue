<template>
    <AuthContentWrapper ref="auth">

        <!--Log In by Email-->
        <AuthContent name="log-in" :visible="false">
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

                <AuthButton icon="chevron-right" :text="$t('page_login.button_next')" :loading="isLoading" :disabled="isLoading"/>
            </ValidationObserver>

            <span v-if="config.userRegistration" class="additional-link">{{ $t('page_login.registration_text') }} <b
                    @click="goToAuthPage('sign-up')">{{ $t('page_login.registration_button') }}</b></span>
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
                    <input v-model="loginPassword" :placeholder="$t('page_sign_in.placeholder_password')" type="password"
                           :class="{'is-error': errors[0]}"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" :text="$t('page_sign_in.button_log_in')" :loading="isLoading" :disabled="isLoading"/>
            </ValidationObserver>

            <span class="additional-link">{{ $t('page_sign_in.password_reset_text') }} <b @click="goToAuthPage('forgotten-password')">{{ $t('page_sign_in.password_reset_button') }}</b></span>
        </AuthContent>

        <!--Forgotten your password?-->
        <AuthContent name="forgotten-password" :visible="false">
            <img class="logo" :src="config.app_logo" :alt="config.app_name">
            <h1>{{ $t('page_forgotten_password.title') }}</h1>
            <h2>{{ $t('page_forgotten_password.subtitle') }}</h2>

            <ValidationObserver @submit.prevent="forgottenPassword" ref="forgotten_password" v-slot="{ invalid }"
                                tag="form" class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                    v-slot="{ errors }">
                    <input v-model="recoverEmail" :placeholder="$t('page_login.placeholder_email')" type="email"
                           :class="{'is-error': errors[0]}"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" :text="$t('page_forgotten_password.button_get_link')" :loading="isLoading" :disabled="isLoading"/>
            </ValidationObserver>

            <span class="additional-link">{{ $t('page_forgotten_password.password_remember_text') }} <b @click="goToAuthPage('log-in')">{{ $t('page_forgotten_password.password_remember_button') }}</b></span>
        </AuthContent>

        <!--Create new password-->
        <AuthContent name="create-new-password" :visible="false">
            <img class="logo" :src="config.app_logo" :alt="config.app_name">
            <h1>{{ $t('page_create_password.title') }}</h1>
            <h2>{{ $t('page_create_password.subtitle') }}</h2>

            <ValidationObserver @submit.prevent="createNewPassword" ref="create_new_password" v-slot="{ invalid }"
                                tag="form" class="form block-form create-new-password">

                <div class="block-wrapper">
                    <label>{{ $t('page_create_password.label_email') }}</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                        v-slot="{ errors }">
                        <input v-model="recoverPassword.email" :placeholder="$t('page_login.placeholder_email')" type="email"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('page_create_password.label_new_pass') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="New Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="recoverPassword.newPassword" :placeholder="$t('page_create_password.label_new_pass')" type="password"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('page_create_password.label_confirm_pass') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Confirm Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="recoverPassword.newPasswordConfirm" :placeholder="$t('page_create_password.label_confirm_pass')"
                               type="password" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div>
                    <AuthButton icon="chevron-right" :text="$t('page_create_password.button_update')" :loading="isLoading" :disabled="isLoading"/>
                </div>
            </ValidationObserver>

            <span class="additional-link">{{ $t('page_forgotten_password.password_remember_text') }} <b @click="goToAuthPage('log-in')">{{ $t('page_forgotten_password.password_remember_button') }}</b></span>
        </AuthContent>

        <!--Registration-->
        <AuthContent name="sign-up" :visible="false">
            <img class="logo" :src="config.app_logo" :alt="config.app_name">
            <h1>{{ $t('page_registration.title') }}</h1>
            <h2>{{ $t('page_registration.subtitle') }}</h2>

            <ValidationObserver @submit.prevent="signUp" ref="sign_up" v-slot="{ invalid }" tag="form"
                                class="form block-form">

                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_email') }}</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                        v-slot="{ errors }">
                        <input v-model="register.email" :placeholder="$t('page_registration.placeholder_email')" type="email"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_name') }}</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Full Name" rules="required"
                                        v-slot="{ errors }">
                        <input v-model="register.name" :placeholder="$t('page_registration.placeholder_name')" type="text"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_pass') }}</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Your New Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="register.password" :placeholder="$t('page_registration.placeholder_pass')" type="password"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_confirm_pass') }}</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Confirm Your Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="register.password_confirmation" :placeholder="$t('page_registration.placeholder_confirm_pass')"
                               type="password" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div>
                    <AuthButton icon="chevron-right" :text="$t('page_registration.button_create_account')" :loading="isLoading" :disabled="isLoading"/>
                </div>
            </ValidationObserver>

            <span class="additional-link">{{ $t('page_registration.have_an_account') }} <b @click="goToAuthPage('log-in')">{{ $t('page_forgotten_password.password_remember_button') }}</b></span>
        </AuthContent>

        <!--Password reset link sended-->
        <AuthContent name="password-reset-link-sended" :visible="false">
            <img class="logo" :src="config.app_logo" :alt="config.app_name">
            <h1>{{ $t('page_forgotten_password.pass_sennded_title') }}</h1>
            <h2>{{ $t('page_forgotten_password.pass_sennded_subtitle') }}</h2>

            <span class="additional-link">{{ $t('page_forgotten_password.password_remember_text') }} <b @click="goToAuthPage('log-in')">{{ $t('page_forgotten_password.password_remember_button') }}</b></span>
        </AuthContent>

        <!--Password reset successfully-->
        <AuthContent name="password-reset-successfully" :visible="false">
            <img class="logo" :src="config.app_logo" :alt="config.app_name">
            <h1>{{ $t('page_forgotten_password.pass_reseted_title') }}</h1>
            <h2>{{ $t('page_forgotten_password.pass_reseted_subtitle') }}</h2>

            <AuthButton icon="chevron-right" @click.native="goToAuthPage('log-in')" :text="$t('page_forgotten_password.pass_reseted_signin')"/>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '@/components/VueFileManagerComponents/Auth/AuthContentWrapper'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContent from '@/components/VueFileManagerComponents/Auth/AuthContent'
    import AuthButton from '@/components/VueFileManagerComponents/Auth/AuthButton'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Auth',
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
        watch: {
            loginEmail(val) {
                this.recoverEmail = val
            }
        },
        data() {
            return {
                isLoading: false,
                checkedAccount: undefined,
                loginPassword: '',
                loginEmail: '',
                recoverEmail: '',
                recoverPassword: {
                    token: undefined,
                    email: '',
                    newPassword: '',
                    newPasswordConfirm: '',
                },
                register: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
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
                    .post(this.$store.getters.api + '/user/check', {
                        email: this.loginEmail,
                    })
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        this.checkedAccount = response.data
                        this.goToAuthPage('sign-in')
                    })
                    .catch(error => {

                        if (error.response.status == 404) {

                            this.$refs.log_in.setErrors({
                                'E-Mail': [error.response.data.message]
                            });
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
                    .post(this.$store.getters.api + '/user/login', {
                        email: this.loginEmail,
                        password: this.loginPassword,
                    })
                    .then(() => {

                        // End loading
                        this.isLoading = false

                        // Set login state
                        this.$store.commit('SET_AUTHORIZED', true)
                    })
                    .catch(error => {

                        if (error.response.status == 400) {

                            this.$refs.sign_in.setErrors({
                                'User Password': [this.$t('validation_errors.incorrect_password')]
                            });
                        }

                        // End loading
                        this.isLoading = false
                    })
            },
            async forgottenPassword() {

                // Validate fields
                const isValid = await this.$refs.forgotten_password.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get user reset link
                axios
                    .post(this.$store.getters.api + '/password/email', {
                        email: this.recoverEmail
                    })
                    .then(() => {

                        // End loading
                        this.isLoading = false

                        this.goToAuthPage('password-reset-link-sended')
                    }).catch(error => {

                        if (error.response.status == 422) {
                            this.$refs.forgotten_password.setErrors({
                                'E-Mail': error.response.data.message
                            });
                        }

                        // End loading
                        this.isLoading = false
                    })
            },
            async createNewPassword() {

                // Validate fields
                const isValid = await this.$refs.create_new_password.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get user reset link
                axios
                    .post(this.$store.getters.api + '/password/reset', {
                        email: this.recoverPassword.email,
                        token: this.recoverPassword.token,
                        password: this.recoverPassword.newPassword,
                        password_confirmation: this.recoverPassword.newPasswordConfirm,
                    })
                    .then(() => {

                        // End loading
                        this.isLoading = false

                        this.goToAuthPage('password-reset-successfully')
                    })
                    .catch(error => {

                        if (error.response.status == 422) {

                            if (error.response.data.error) {

                                this.$refs.create_new_password.setErrors({
                                    'E-Mail': error.response.data.error
                                });
                            }

                            if (error.response.data.errors['password']) {

                                this.$refs.create_new_password.setErrors({
                                    'New Password': error.response.data.errors['password']
                                });
                            }
                        }

                        // End loading
                        this.isLoading = false
                    })
            },
            async signUp() {

                // Validate fields
                const isValid = await this.$refs.sign_up.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get user token
                axios
                    .post(this.$store.getters.api + '/user/register', this.register)
                    .then(() => {

                        // End loading
                        this.isLoading = false

                        // Set login state
                        this.$store.commit('SET_AUTHORIZED', true)
                    })
                    .catch(error => {

                        if (error.response.status == 422) {

                            if (error.response.data.errors['email']) {

                                this.$refs.sign_up.setErrors({
                                    'E-Mail': error.response.data.errors['email']
                                });
                            }

                            if (error.response.data.errors['password']) {

                                this.$refs.sign_up.setErrors({
                                    'Your New Password': error.response.data.errors['password']
                                });
                            }
                        }

                        // End loading
                        this.isLoading = false
                    })
            }
        },
        mounted() {

            // Check if user try to get reset page
            let pathname = location.pathname.split('/')[1]
            let token = location.search.split('token=')[1]

            if (pathname === this.$t('routes.create_new_password')) {
                this.recoverPassword.token = token
                this.goToAuthPage('create-new-password')
            } else {
                this.goToAuthPage('log-in')
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";
    @import '@assets/vue-file-manager/_forms';

    .auth-form {
        text-align: center;
        max-width: 600px;
        padding: 25px 20px;
        display: table-cell;
        vertical-align: middle;

        .user-avatar {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(25, 54, 60, 0.2);
        }

        .logo {
            width: 120px;
            margin-bottom: 20px;
        }

        h1 {
            @include font-size(34);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 2px;
            color: $text;
        }

        h2 {
            @include font-size(23);
            font-weight: 500;
            margin-bottom: 50px;
            color: $text;
        }

        .block-form {
            margin-left: auto;
            margin-right: auto;

            .block-wrapper label {
                text-align: right;
            }
        }
    }

    @media only screen and (min-width: 690px) and (max-width: 960px) {

        .auth-form {
            padding-left: 20%;
            padding-right: 20%;
        }
    }

    @media only screen and (max-width: 690px) {

        .auth-form {
            width: 100%;

            h1 {
                @include font-size(30);
            }

            h2 {
                @include font-size(21);
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .auth-form {

            h1, h2, .additional-link {
                color: $dark_mode_text_primary;
            }
        }
    }

</style>
