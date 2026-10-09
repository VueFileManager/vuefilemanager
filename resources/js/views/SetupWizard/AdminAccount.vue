<template>
    <AuthContentWrapper ref="auth">
        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true" class="mt-6 mb-12 max-w-2xl!">
            <Headline
                class="mx-auto mb-10! max-w-(--breakpoint-sm)"
                title="Setup Wizard"
                description="Create your admin account."
            >
                <settings-icon
                    size="40"
                    class="vue-feather text-theme mx-auto mb-3 animate-[spin_5s_linear_infinite]"
                />
            </Headline>

            <ValidationObserver
                @submit.prevent="adminAccountSubmit"
                ref="adminAccount"
                v-slot="{ invalid }"
                tag="form"
                class="card text-left shadow-card"
            >
                <FormLabel> Create Admin Account </FormLabel>

                <ValidationProvider tag="div" mode="passive" name="Avatar" v-slot="{ errors }">
                    <AppInputText title="Avatar (optional)" :error="errors[0]">
                        <ImageInput v-model="admin.avatar" :error="errors[0]" />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Full Name" rules="required" v-slot="{ errors }">
                    <AppInputText title="Full Name" :error="errors[0]">
                        <input
                            v-model="admin.name"
                            class="focus-border-theme input-dark"
                            placeholder="Type your full name"
                            type="text"
                            :class="{ 'border-rose-600!': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider tag="div" mode="passive" name="Email" rules="required" v-slot="{ errors }">
                    <AppInputText title="Email" :error="errors[0]">
                        <input
                            v-model="admin.email"
                            class="focus-border-theme input-dark"
                            placeholder="Type your email"
                            type="email"
                            :class="{ 'border-rose-600!': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="Password"
                    rules="required|confirmed:confirmation"
                    v-slot="{ errors }"
                >
                    <AppInputText title="Password" :error="errors[0]">
                        <input
                            v-model="admin.password"
                            class="focus-border-theme input-dark"
                            placeholder="Type your password"
                            type="password"
                            :class="{ 'border-rose-600!': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>

                <ValidationProvider
                    tag="div"
                    name="confirmation"
                    rules="required"
                    vid="confirmation"
                    v-slot="{ errors }"
                >
                    <AppInputText title="Password Confirmation" :error="errors[0]" :is-last="true">
                        <input
                            v-model="admin.password_confirmation"
                            class="focus-border-theme input-dark"
                            placeholder="Confirm your password"
                            type="password"
                            :class="{ 'border-rose-600!': errors[0] }"
                        />
                    </AppInputText>
                </ValidationProvider>
            </ValidationObserver>

            <AuthButton
                @click="adminAccountSubmit"
                class="w-full justify-center"
                icon="chevron-right"
                text="Create Admin and Login"
                :loading="isLoading"
                :disabled="isLoading"
            />
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import AppInputText from '../../components/Forms/Layouts/AppInputText.vue'
import { ValidationProvider, ValidationObserver } from '../../components/Validation/index.js'
import AuthContentWrapper from '../../components/Layout/AuthPages/AuthContentWrapper.vue'
import SelectInput from '../../components/Inputs/SelectInput.vue'
import SwitchInput from '../../components/Inputs/SwitchInput.vue'
import ImageInput from '../../components/Inputs/ImageInput.vue'
import FormLabel from '../../components/UI/Labels/FormLabel.vue'
import InfoBox from '../../components/UI/Others/InfoBox.vue'
import AuthContent from '../../components/Layout/AuthPages/AuthContent.vue'
import AuthButton from '../../components/UI/Buttons/AuthButton.vue'
import { SettingsIcon } from '../../icons/feather.js'
import Headline from '../../components/UI/Labels/LogoHeadline.vue'
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

            if (this.admin.avatar) formData.append('avatar', this.admin.avatar)

            axios
                .post('/admin-setup', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((response) => {
                    // Go to sign page
                    window.location = '/sign-in'
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        events.$emit('alert:open', {
                            emoji: '🤔',
                            title: this.$t('popup_signup_error.title'),
                            message: this.$t('popup_signup_error.message'),
                        })
                    }

                    if (error.response.status === 422) {
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
                })
                .finally(() => (this.isLoading = false))
        },
    },
    created() {
        this.$scrollTop()

        if (config.isSetupWizardDebug) {
            this.admin = {
                name: 'Jane Doe',
                email: 'howdy@hi5ve.digital',
                avatar: undefined,
                password: 'vuefilemanager',
                password_confirmation: 'vuefilemanager',
            }
        }
    },
}
</script>
