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
