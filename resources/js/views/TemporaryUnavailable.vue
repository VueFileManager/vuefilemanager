<template>
    <AuthContentWrapper ref="auth" class="h-screen">
        <AuthContent :visible="true">
            <Headline :title="$t('Temporary Unavailable')" :description="$t('Unfortunately, this shared link is temporary unavailable. Please try it later.')" />

            <span class="additional-link"
                >{{ $t('page_registration.have_an_account') }}
                <router-link :to="{ name: 'SignIn' }" class="text-theme font-bold">
                    {{ $t('page_forgotten_password.password_remember_button') }}
                </router-link>
            </span>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import AuthContentWrapper from '../components/Auth/AuthContentWrapper'
import AuthContent from '../components/Auth/AuthContent'
import Headline from './Auth/Headline'

export default {
    name: 'TemporaryUnavailable',
    components: {
        AuthContentWrapper,
        AuthContent,
        Headline,
    },
}
</script>
