<template>
    <AuthContentWrapper ref="auth">

        <!--Password reset link sended-->
        <AuthContent name="not-found" :visible="true">
            <img class="logo" :src="config.app_logo" :alt="config.app_name">
            <h1>{{ $t('page_shared_404.title') }}</h1>
            <h2>{{ $t('page_shared_404.subtitle') }}</h2>

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
    import axios from 'axios'

    export default {
        name: 'NotFoundShared',
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
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_auth';
</style>
