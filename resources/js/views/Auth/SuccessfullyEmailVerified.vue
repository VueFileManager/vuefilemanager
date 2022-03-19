<template>
    <AuthContentWrapper class="h-screen">
        <AuthContent :visible="true">
            <Headline
                :title="$t('page_email_successfully_verified.title')"
                :description="$t('page_email_successfully_verified.subtitle')"
            />

            <router-link :to="{ name: 'SignIn' }">
                <AuthButton icon="chevron-right" :text="$t('log_in')" />
            </router-link>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import AuthContentWrapper from '../../components/Auth/AuthContentWrapper'
import AuthContent from '../../components/Auth/AuthContent'
import AuthButton from '../../components/Auth/AuthButton'
import Headline from './Headline'

export default {
    name: 'SuccessfullyEmailVerified',
    components: {
        AuthContentWrapper,
        AuthContent,
        AuthButton,
        Headline,
    },
}
</script>
