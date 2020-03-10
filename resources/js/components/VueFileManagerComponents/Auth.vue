<template>
    <AuthContentWrapper ref="auth">

        <!--Log In by Email-->
        <AuthContent name="log-in" :visible="false">
            <img class="logo" src="/assets/images/hero.svg" alt="Vue File Manager logo">
            <h1>Welcome Back!</h1>
            <h2>Please type your email to log in:</h2>

            <ValidationObserver @submit.prevent="logIn" ref="log_in" v-slot="{ invalid }" tag="form"
                                class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                    v-slot="{ errors }">
                    <input v-model="loginEmail" placeholder="Type your E-mail" type="email"
                           :class="{'is-error': errors[0]}"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" text="Next Step" :loading="isLoading" :disabled="isLoading"/>
            </ValidationObserver>

            <span class="additional-link">Don’t have an account? <b
                    @click="goToAuthPage('sign-up')">Register account.</b></span>
        </AuthContent>

        <!--Log in By Password-->
        <AuthContent name="sign-in" :visible="false">

            <div class="user" v-if="checkedAccount">
                <img class="user-avatar" :src="checkedAccount.avatar" :alt="checkedAccount.name">
                <h1>Are You {{ checkedAccount.name }}?</h1>
                <h2>Confirm you by your password:</h2>
            </div>

            <ValidationObserver @submit.prevent="singIn" ref="sign_in" v-slot="{ invalid }" tag="form"
                                class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="User Password" rules="required"
                                    v-slot="{ errors }">
                    <input v-model="loginPassword" placeholder="Type your password" type="password"
                           :class="{'is-error': errors[0]}"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" text="Log In" :loading="isLoading" :disabled="isLoading"/>
            </ValidationObserver>

            <span class="additional-link">Forgotten your password? <b @click="goToAuthPage('forgotten-password')">Reset Password.</b></span>
        </AuthContent>

        <!--Forgotten your password?-->
        <AuthContent name="forgotten-password" :visible="false">
            <img class="logo" src="/assets/images/hero.svg" alt="Vue File Manager logo">
            <h1>Forgotten Password?</h1>
            <h2>Get reset link with your email:</h2>

            <ValidationObserver @submit.prevent="forgottenPassword" ref="forgotten_password" v-slot="{ invalid }"
                                tag="form" class="form inline-form">
                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                    v-slot="{ errors }">
                    <input v-model="recoverEmail" placeholder="Type your E-mail" type="email"
                           :class="{'is-error': errors[0]}"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" text="Get Link" :loading="isLoading" :disabled="isLoading"/>
            </ValidationObserver>

            <span class="additional-link">Remember your password? <b @click="goToAuthPage('log-in')">Log In.</b></span>
        </AuthContent>

        <!--Create new password-->
        <AuthContent name="create-new-password" :visible="false">
            <img class="logo" src="/assets/images/hero.svg" alt="Vue File Manager logo">
            <h1>Only One Step to Log In</h1>
            <h2>Create your new password here:</h2>

            <ValidationObserver @submit.prevent="createNewPassword" ref="create_new_password" v-slot="{ invalid }"
                                tag="form" class="form block-form create-new-password">

                <div class="block-wrapper">
                    <label>Email:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                        v-slot="{ errors }">
                        <input v-model="recoverPassword.email" placeholder="Type your E-mail" type="email"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Your new password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="New Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="recoverPassword.newPassword" placeholder="Your new password" type="password"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Confirm your new password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Confirm Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="recoverPassword.newPasswordConfirm" placeholder="Confirm your new password"
                               type="password" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div>
                    <AuthButton icon="chevron-right" text="Update Password" :loading="isLoading" :disabled="isLoading"/>
                </div>
            </ValidationObserver>

            <span class="additional-link">Remember your password? <b @click="goToAuthPage('log-in')">Log In.</b></span>
        </AuthContent>

        <!--Registration-->
        <AuthContent name="sign-up" :visible="false">
            <img class="logo" src="/assets/images/hero.svg" alt="Vue File Manager logo">
            <h1>Create New Account</h1>
            <h2>Please fill registration to create account:</h2>

            <ValidationObserver @submit.prevent="signUp" ref="sign_up" v-slot="{ invalid }" tag="form"
                                class="form block-form">

                <div class="block-wrapper">
                    <label>Email:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required"
                                        v-slot="{ errors }">
                        <input v-model="register.email" placeholder="Type your E-mail" type="email"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Full Name:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Full Name" rules="required"
                                        v-slot="{ errors }">
                        <input v-model="register.name" placeholder="Type your full name" type="text"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Create password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Your New Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="register.password" placeholder="Your new password" type="password"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Confirm password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Confirm Your Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="register.password_confirmation" placeholder="Confirm your new password"
                               type="password" :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div>
                    <AuthButton icon="chevron-right" text="Create Account" :loading="isLoading" :disabled="isLoading"/>
                </div>
            </ValidationObserver>

            <span class="additional-link">Do you have an account? <b @click="goToAuthPage('log-in')">Log In.</b></span>
        </AuthContent>

        <!--Password reset link sended-->
        <AuthContent name="password-reset-link-sended" :visible="false">
            <img class="logo" src="/assets/images/hero.svg" alt="Vue File Manager logo">
            <h1>Thank you!</h1>
            <h2>We have e-mailed your password reset link!</h2>

            <span class="additional-link">Remember your password? <b @click="goToAuthPage('log-in')">Log In.</b></span>
        </AuthContent>

        <!--Password reset successfully-->
        <AuthContent name="password-reset-successfully" :visible="false">
            <img class="logo" src="/assets/images/hero.svg" alt="Vue File Manager logo">
            <h1>Awesome!</h1>
            <h2>Your password was reset successfully.</h2>

            <AuthButton icon="chevron-right" @click.native="goToAuthPage('log-in')" text="Sign In"/>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '@/components/VueFileManagerComponents/Auth/AuthContentWrapper'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContent from '@/components/VueFileManagerComponents/Auth/AuthContent'
    import AuthButton from '@/components/VueFileManagerComponents/Auth/AuthButton'
    import {required} from 'vee-validate/dist/rules'
    import {events} from '@/bus'
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
        data() {
            return {
                isLoading: false,
                checkedAccount: undefined,
                loginPassword: 'vuefilemanager',
                loginEmail: 'peterpapp@makingcg.com',
                recoverEmail: 'peterpapp@makingcg.com',
                recoverPassword: {
                    token: undefined,
                    email: 'peterpapp@makingcg.com',
                    newPassword: 'vuefilemanager',
                    newPasswordConfirm: 'vuefilemanager',
                },
                register: {
                    name: 'Hi5Ve Digital',
                    email: 'peterpapp@makingcg.com',
                    password: 'vuefilemanager',
                    password_confirmation: 'vuefilemanager',
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
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        // Set login state
                        this.$store.commit('SET_AUTHORIZED', true)
                    })
                    .catch(error => {

                        if (error.response.status == 400) {

                            this.$refs.sign_in.setErrors({
                                'User Password': ['Sorry, you passed incorrect password :(']
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
                    .then(response => {

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
                    .then(response => {

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
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        // Store token to localstorage
                        localStorage.setItem('access_token', response.data.access_token)

                        // Store token to vuex
                        this.$store.commit('RETRIEVE_TOKEN', response.data.access_token)
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

            if (pathname === 'create-new-password') {
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
    //@import "@/assets/scss/_forms.scss";

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
        }

        h2 {
            @include font-size(23);
            font-weight: 500;
            margin-bottom: 50px;
        }

        .block-form {
            margin-left: auto;
            margin-right: auto;
        }

        .additional-link {
            @include font-size(16);
            margin-top: 50px;
            display: block;

            b {
                color: $theme;
                cursor: pointer;

                &:hover {
                    text-decoration: underline;
                }
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
            //font-size: 90%;
            width: 100%;

            h1 {
                @include font-size(30);
            }

            h2 {
                @include font-size(21);
            }
        }
    }

</style>
