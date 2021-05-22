<template>
    <AuthContentWrapper>
        <AuthContent :visible="true">
            <img v-if="config.app_logo" class="logo" :src="$getImage(config.app_logo)" :alt="config.app_name">
            <b v-if="! config.app_logo" class="auth-logo-text">{{ config.app_name }}</b>
          
            <h1>{{ $t('page_login.title') }}</h1>
            <h2>{{ $t('page_email_successfully_verified') }}</h2>
            
            <a href="/sign-in">
                <AuthButton icon="chevron-right" :text="$t('page_sign_in.button_log_in')"/>
            </a>

        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '@/components/Auth/AuthContentWrapper'
    import AuthContent from '@/components/Auth/AuthContent'
    import AuthButton from '@/components/Auth/AuthButton'
    import {mapGetters} from 'vuex'

    export default {
        name: 'SuccessfullyEmailVerified',
        components: {
            AuthContentWrapper,
            AuthContent,
            AuthButton,
        },
        computed: {
            ...mapGetters(['config']),
        },
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_auth-form';
    @import '@assets/vuefilemanager/_auth';
</style>
