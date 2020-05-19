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

            <span class="additional-link">{{ $t('page_registration.have_an_account') }}
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
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'SignUp',
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
                    .post('/api/user/register', this.register)
                    .then(() => {

                        // End loading
                        this.isLoading = false

                        // Set login state
                        this.$store.commit('SET_AUTHORIZED', true)

                        // Go to files page
                        this.$router.push({name: 'Files'})
                    })
                    .catch(error => {

                        if (error.response.status == 401) {

                            if (error.response.data.error === 'invalid_client') {
                                events.$emit('alert:open', {
                                    emoji: '🤔',
                                    title: this.$t('popup_passport_error.title'),
                                    message: this.$t('popup_passport_error.message')
                                })
                            }
                        }

                        if (error.response.status == 500) {

                            events.$emit('alert:open', {
                                emoji: '🤔',
                                title: this.$t('popup_signup_error.title'),
                                message: this.$t('popup_signup_error.message')
                            })
                        }

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
    @import '@assets/vue-file-manager/_auth-form';
    @import '@assets/vue-file-manager/_auth';
</style>
