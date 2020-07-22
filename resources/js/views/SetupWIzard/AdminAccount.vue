<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">
            <div class="content-headline">
                <settings-icon size="40" class="title-icon"></settings-icon>
                <h1>Setup Wizard</h1>
                <h2>Create your admin account.</h2>
            </div>

            <ValidationObserver @submit.prevent="adminAccountSubmit" ref="adminAccount" v-slot="{ invalid }" tag="form" class="form block-form">
                <FormLabel>Create Admin Account</FormLabel>

                <div class="block-wrapper">
                    <label>Avatar (optional):</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Avatar" v-slot="{ errors }">
                        <ImageInput v-model="admin.avatar" :error="errors[0]" />
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Full Name:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Full Name" rules="required" v-slot="{ errors }">
                        <input v-model="admin.name" placeholder="Type your full name" type="text" :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Email:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Email" rules="required" v-slot="{ errors }">
                        <input v-model="admin.email" placeholder="Type your email" type="email" :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Password" rules="required|confirmed:confirmation" v-slot="{ errors }">
                        <input v-model="admin.password" placeholder="Type your password" type="password" :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Password Confirmation:</label>
                    <ValidationProvider tag="div" class="input-wrapper" name="confirmation" rules="required" vid="confirmation" v-slot="{ errors }">
                        <input v-model="admin.password_confirmation" placeholder="Confirm your password" type="password" :class="{'is-error': errors[0]}" />
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="submit-wrapper">
                    <AuthButton icon="chevron-right" text="Create Admin and Login" :loading="isLoading" :disabled="isLoading"/>
                </div>

            </ValidationObserver>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import ImageInput from '@/components/Others/Forms/ImageInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import AuthContent from '@/components/Auth/AuthContent'
    import AuthButton from '@/components/Auth/AuthButton'
    import { SettingsIcon } from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'EnvironmentSetup',
        components: {
            AuthContentWrapper,
            ValidationProvider,
            ValidationObserver,
            SettingsIcon,
            SelectInput,
            SwitchInput,
            AuthContent,
            ImageInput,
            AuthButton,
            FormLabel,
            required,
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
                const isValid = await this.$refs.adminAccount.validate();

                if (!isValid) return;

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

                if (this.admin.avatar)
                    formData.append('avatar', this.admin.avatar)

                axios
                    .post('/api/setup/admin-setup', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    })
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        // Set login state
                        this.$store.commit('SET_AUTHORIZED', true)

                        if (localStorage.getItem('license') === 'Extended') {
                            this.$store.commit('SET_SAAS', true)
                        }

                        // Go to files page
                        this.$router.push({name: 'Dashboard'})

                        // Remove license from localStorage
                        localStorage.removeItem('purchase_code')
                        localStorage.removeItem('license')
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

                                this.$refs.adminAccount.setErrors({
                                    'Email': error.response.data.errors['email']
                                });
                            }

                            if (error.response.data.errors['password']) {

                                this.$refs.adminAccount.setErrors({
                                    'Password': error.response.data.errors['password']
                                });
                            }
                        }

                        // End loading
                        this.isLoading = false
                    })
            },
        },
        created() {
            this.$scrollTop()
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_forms';
    @import '@assets/vue-file-manager/_auth';
    @import '@assets/vue-file-manager/_setup_wizard';
</style>
