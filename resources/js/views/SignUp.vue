<template>
    <AuthContentWrapper ref="auth">

        <!--Registration-->
        <AuthContent name="sign-up" :visible="true">
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
                register: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
            }
        },
        methods: {
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
