<template>
    <AuthContentWrapper ref="auth" class="h-screen">
        <AuthContent :visible="true">
            <Headline :title="$t('page_shared_404.title')" :description="$t('page_shared_404.subtitle')" />
            <span class="additional-link"
                >{{ $t('page_registration.have_an_account') }}
                <router-link :to="{ name: 'SignIn' }" class="text-theme font-bold">
                    {{ $t('log_in') }}
                </router-link>
            </span>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import AuthContentWrapper from '../components/Auth/AuthContentWrapper'
import AuthContent from '../components/Auth/AuthContent'
import AuthButton from '../components/Auth/AuthButton'
import Headline from './Auth/Headline'

export default {
    name: 'NotFound',
    components: {
        AuthContentWrapper,
        AuthContent,
        AuthButton,
        Headline,
    },
}
</script>

<style scoped lang="scss">
@import '../../sass/vuefilemanager/auth';
</style>
