<template>
    <AuthContentWrapper ref="auth">
        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true" class="!max-w-2xl mt-6 mb-12">
			<Headline class="mx-auto max-w-screen-sm !mb-10" title="Setup Wizard" description="Create your admin account.">
                <settings-icon size="40" class="vue-feather text-theme mx-auto animate-[spin_5s_linear_infinite] mb-3" />
            </Headline>

            <ValidationObserver @submit.prevent="adminAccountSubmit" ref="adminAccount" v-slot="{ invalid }" tag="form" class="card shadow-card text-left">
                <FormLabel>
					Create Admin Account
				</FormLabel>

				<ValidationProvider tag="div" mode="passive" name="Avatar" v-slot="{ errors }">
					<AppInputText title="Avatar (optional)" :error="errors[0]">
                        <ImageInput v-model="admin.avatar" :error="errors[0]" />
					</AppInputText>
				</ValidationProvider>

				<ValidationProvider tag="div" mode="passive" name="Full Name" rules="required" v-slot="{ errors }">
					<AppInputText title="Full Name" :error="errors[0]">
						<input v-model="admin.name" class="focus-border-theme input-dark" placeholder="Type your full name" type="text" :class="{ 'border-red': errors[0] }" />
					</AppInputText>
				</ValidationProvider>

				<ValidationProvider tag="div" mode="passive" name="Email" rules="required" v-slot="{ errors }">
					<AppInputText title="Email" :error="errors[0]">
						<input v-model="admin.email" class="focus-border-theme input-dark" placeholder="Type your email" type="email" :class="{ 'border-red': errors[0] }" />
					</AppInputText>
				</ValidationProvider>

				<ValidationProvider tag="div" mode="passive" name="Password" rules="required|confirmed:confirmation" v-slot="{ errors }">
					<AppInputText title="Password" :error="errors[0]">
                        <input v-model="admin.password" class="focus-border-theme input-dark" placeholder="Type your password" type="password" :class="{ 'border-red': errors[0] }" />
					</AppInputText>
				</ValidationProvider>

				<ValidationProvider tag="div" name="confirmation" rules="required" vid="confirmation" v-slot="{ errors }">
					<AppInputText title="Password Confirmation" :error="errors[0]" :is-last="true">
                        <input v-model="admin.password_confirmation" class="focus-border-theme input-dark" placeholder="Confirm your password" type="password" :class="{ 'border-red': errors[0] }" />
					</AppInputText>
				</ValidationProvider>
            </ValidationObserver>

			<AuthButton @click.native="adminAccountSubmit" class="w-full justify-center" icon="chevron-right" text="Create Admin and Login" :loading="isLoading" :disabled="isLoading" />
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import AppInputText from "../../components/Admin/AppInputText";
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import AuthContentWrapper from '../../components/Auth/AuthContentWrapper'
import SelectInput from '../../components/Others/Forms/SelectInput'
import SwitchInput from '../../components/Others/Forms/SwitchInput'
import ImageInput from '../../components/Others/Forms/ImageInput'
import FormLabel from '../../components/Others/Forms/FormLabel'
import InfoBox from '../../components/Others/Forms/InfoBox'
import AuthContent from '../../components/Auth/AuthContent'
import AuthButton from '../../components/Auth/AuthButton'
import { required } from 'vee-validate/dist/rules'
import { SettingsIcon } from 'vue-feather-icons'
import Headline from "../Auth/Headline"
import { events } from '../../bus'
import axios from 'axios'

export default {
    name: 'EnvironmentSetup',
    components: {
        AuthContentWrapper,
        ValidationProvider,
        ValidationObserver,
		AppInputText,
        SettingsIcon,
        SelectInput,
        SwitchInput,
        AuthContent,
        ImageInput,
        AuthButton,
        FormLabel,
        required,
		Headline,
        InfoBox,
    },
    data() {
        return {
            isLoading: false,
            admin: {
                name: '',
                email: '',
                avatar: undefined,
                password: '',
                password_confirmation: '',
            },
        }
    },
    methods: {
        async adminAccountSubmit() {
            // Validate fields
            const isValid = await this.$refs.adminAccount.validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true

            // Create form
            let formData = new FormData()

            // Add image to form
            formData.append('name', this.admin.name)
            formData.append('email', this.admin.email)
            formData.append('password', this.admin.password)
            formData.append('password_confirmation', this.admin.password_confirmation)

            formData.append('license', localStorage.getItem('license'))
            formData.append('purchase_code', localStorage.getItem('purchase_code'))

            if (this.admin.avatar) formData.append('avatar', this.admin.avatar)

            axios
                .post('/api/setup/admin-setup', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((response) => {
                    // End loading
                    this.isLoading = false

                    // Set login state
                    this.$store.commit('SET_AUTHORIZED', true)

                    if (localStorage.getItem('license') === 'Extended') {
                        this.$store.commit('SET_SAAS', true)
                    }

                    // Go to files page
                    this.$router.push({ name: 'Dashboard' })

                    // Remove license from localStorage
                    localStorage.removeItem('purchase_code')
                    localStorage.removeItem('license')
                })
                .catch((error) => {
                    if (error.response.status == 401) {
                        if (error.response.data.error === 'invalid_client') {
                            events.$emit('alert:open', {
                                emoji: '🤔',
                                title: this.$t('popup_passport_error.title'),
                                message: this.$t('popup_passport_error.message'),
                            })
                        }
                    }

                    if (error.response.status == 500) {
                        events.$emit('alert:open', {
                            emoji: '🤔',
                            title: this.$t('popup_signup_error.title'),
                            message: this.$t('popup_signup_error.message'),
                        })
                    }

                    if (error.response.status == 422) {
                        if (error.response.data.errors['email']) {
                            this.$refs.adminAccount.setErrors({
                                Email: error.response.data.errors['email'],
                            })
                        }

                        if (error.response.data.errors['password']) {
                            this.$refs.adminAccount.setErrors({
                                Password: error.response.data.errors['password'],
                            })
                        }
                    }

                    // End loading
                    this.isLoading = false
                })
        },
    },
    created() {
        this.$scrollTop()
    },
}
</script>
