<template>
    <AuthContentWrapper ref="auth" class="h-screen">
        <!--Create new password-->
        <AuthContent name="create-new-password" :visible="true">
            <Headline :title="$t('page_create_password.title')" :description="$t('page_create_password.subtitle')" />

            <ValidationObserver
                @submit.prevent="createNewPassword"
                ref="create_new_password"
                v-slot="{ invalid }"
                tag="form"
                class="mb-12 space-y-4 text-left"
            >
                <div class="mx-auto mb-5 md:flex md:max-w-lg md:items-center">
                    <label class="mb-1.5 block font-bold md:mb-0 md:w-72 md:pr-4 md:text-right">
                        {{ $t('email') }}:
                    </label>
                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        class="w-full text-left"
                        name="E-Mail"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <input
                            v-model="recoverPassword.email"
                            :placeholder="$t('page_login.placeholder_email')"
                            type="email"
                            class="dark:placeholder:text-gray-600 focus-border-theme w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 font-bold dark:bg-2x-dark-foreground"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                        <span class="text-left text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="mx-auto mb-5 md:flex md:max-w-lg md:items-center">
                    <label class="mb-1.5 block font-bold md:mb-0 md:w-72 md:pr-4 md:text-right">
                        {{ $t('new_password') }}:
                    </label>
                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        class="w-full text-left"
                        name="New Password"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <input
                            v-model="recoverPassword.newPassword"
                            :placeholder="$t('new_password')"
                            type="password"
                            class="dark:placeholder:text-gray-600 focus-border-theme w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 font-bold dark:bg-2x-dark-foreground"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                        <span class="text-left text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="mx-auto mb-5 md:flex md:max-w-lg md:items-center">
                    <label class="mb-1.5 block font-bold md:mb-0 md:w-72 md:pr-4 md:text-right">
                        {{ $t('confirm_password') }}:
                    </label>
                    <ValidationProvider
                        tag="div"
                        mode="passive"
                        class="w-full text-left"
                        name="Confirm Password"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <input
                            v-model="recoverPassword.newPasswordConfirm"
                            :placeholder="$t('confirm_password')"
                            type="password"
                            class="dark:placeholder:text-gray-600 focus-border-theme w-full appearance-none rounded-lg border border-transparent bg-light-background px-5 py-3.5 font-bold dark:bg-2x-dark-foreground"
                            :class="{ '!border-rose-600': errors[0] }"
                        />
                        <span class="text-left text-xs text-red-600" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div>
                    <AuthButton
                        class="mt-12 w-full justify-center md:w-min"
                        icon="chevron-right"
                        :text="$t('update_password')"
                        :loading="isLoading"
                        :disabled="isLoading"
                    />
                </div>
            </ValidationObserver>

            <span class="block">
                {{ $t('page_forgotten_password.password_remember_text') }}
                <router-link :to="{ name: 'SignIn' }" class="text-theme font-bold">
                    {{ $t('log_in') }}
                </router-link>
            </span>
        </AuthContent>

        <!--Password reset successfully-->
        <AuthContent name="password-reset-successfully" :visible="false">
			<Headline :title="$t('page_forgotten_password.pass_reseted_title')" :description="$t('page_forgotten_password.pass_reseted_subtitle')" />

            <router-link :to="{ name: 'SignIn' }">
                <AuthButton icon="chevron-right" :text="$t('sign_in')" />
            </router-link>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import AuthContentWrapper from '../../components/Layout/AuthPages/AuthContentWrapper'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import AuthContent from '../../components/Layout/AuthPages/AuthContent'
import AuthButton from '../../components/UI/Buttons/AuthButton'
import { required } from 'vee-validate/dist/rules'
import Headline from '../../components/UI/Labels/LogoHeadline'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'CreateNewPassword',
    components: {
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
    },
    data() {
        return {
            isLoading: false,
            recoverPassword: {
                token: undefined,
                email: '',
                newPassword: '',
                newPasswordConfirm: '',
            },
        }
    },
    methods: {
        goToAuthPage(slug) {
            this.$refs.auth.$children.forEach((page) => {
                // Hide current step
                page.isVisible = false

                if (page.$props.name === slug) {
                    // Go to next step
                    page.isVisible = true
                }
            })
        },
        async createNewPassword() {
            // Validate fields
            const isValid = await this.$refs.create_new_password.validate()

            if (!isValid) return

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
                .catch((error) => {
                    if (error.response.status === 422) {
                        if (error.response.data.error) {
                            this.$refs.create_new_password.setErrors({
                                'E-Mail': error.response.data.error,
                            })
                        }

                        if (error.response.data.errors['password']) {
                            this.$refs.create_new_password.setErrors({
                                'New Password': error.response.data.errors['password'],
                            })
                        }
                    }

                    // End loading
                    this.isLoading = false
                })
        },
    },
    created() {
        // Get token
        this.recoverPassword.token = this.$route.query.token
    },
}
</script>
