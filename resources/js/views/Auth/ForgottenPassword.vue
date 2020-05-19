<template>
    <AuthContentWrapper ref="auth">

        <!--Forgotten your password?-->
        <AuthContent name="forgotten-password" :visible="true">
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

            <span class="additional-link">{{ $t('page_forgotten_password.password_remember_text') }}
                <router-link :to="{name: 'SignIn'}">
                    {{ $t('page_forgotten_password.password_remember_button') }}
                </router-link>
            </span>
        </AuthContent>

        <!--Password reset link sended-->
        <AuthContent name="password-reset-link-sended" :visible="false">
            <img class="logo" :src="config.app_logo" :alt="config.app_name">
            <h1>{{ $t('page_forgotten_password.pass_sennded_title') }}</h1>
            <h2>{{ $t('page_forgotten_password.pass_sennded_subtitle') }}</h2>

            <span class="additional-link">{{ $t('page_forgotten_password.password_remember_text') }}
                <router-link :to="{name: 'SignIn'}">
                    {{ $t('page_forgotten_password.password_remember_button') }}
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
    import axios from 'axios'

    export default {
        name: 'ForgottenPassword',
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
                recoverEmail: '',
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
            async forgottenPassword() {

                // Validate fields
                const isValid = await this.$refs.forgotten_password.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get user reset link
                axios
                    .post('/api/password/email', {
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
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_auth-form';
    @import '@assets/vue-file-manager/_auth';
</style>
