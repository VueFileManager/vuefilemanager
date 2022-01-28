<template>
    <AuthContentWrapper ref="auth" class="h-screen">

        <!--Forgotten your password?-->
        <AuthContent name="forgotten-password" :visible="true">
			<Headline
				:title="$t('page_forgotten_password.title')"
				:description="$t('page_forgotten_password.subtitle')"
			/>

            <ValidationObserver @submit.prevent="forgottenPassword" ref="forgotten_password" v-slot="{ invalid }" tag="form" class="md:flex items-start md:space-x-4 md:space-y-0 space-y-4 mb-12">
                <ValidationProvider tag="div" mode="passive" class="w-full text-left relative" name="E-Mail" rules="required" v-slot="{ errors }">
                    <input v-model="recoverEmail" :placeholder="$t('page_login.placeholder_email')" type="email" class="font-bold px-5 py-3.5 dark:bg-2x-dark-foreground bg-light-background w-full rounded-lg focus-border-theme appearance-none border border-transparent" :class="{'border-red': errors[0]}"/>
                    <span class="text-red-600 text-xs text-left" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton class="md:w-min w-full justify-center" icon="chevron-right" :text="$t('page_forgotten_password.button_get_link')" :loading="isLoading" :disabled="isLoading"/>
            </ValidationObserver>

            <span class="block">
				{{ $t('page_forgotten_password.password_remember_text') }}
                <router-link :to="{name: 'SignIn'}" class="font-bold text-theme">
                    {{ $t('page_forgotten_password.password_remember_button') }}
                </router-link>
            </span>
        </AuthContent>

        <!--Password reset link send-->
        <AuthContent name="password-reset-link-sended" :visible="false">
			<Headline
				:title="$t('page_forgotten_password.pass_sennded_title')"
				:description="$t('page_forgotten_password.pass_sennded_subtitle')"
			/>

            <span class="block">
				{{ $t('page_forgotten_password.password_remember_text') }}
                <router-link :to="{name: 'SignIn'}" class="font-bold text-theme">
                    {{ $t('page_forgotten_password.password_remember_button') }}
                </router-link>
            </span>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContentWrapper from '/resources/js/components/Auth/AuthContentWrapper'
    import AuthContent from '/resources/js/components/Auth/AuthContent'
    import AuthButton from '/resources/js/components/Auth/AuthButton'
    import {required} from 'vee-validate/dist/rules'
	import Headline from "./Headline";
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
			Headline,
        },
        computed: {
            ...mapGetters([
				'config',
			]),
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

                        this.goToAuthPage('password-reset-link-sended')
                    }).catch(error => {

                        if (error.response.status === 422) {
                            this.$refs.forgotten_password.setErrors({
                                'E-Mail': error.response.data.message
                            });
                        }
                    }).finally(() => this.isLoading = false)
            },
        }
    }
</script>
