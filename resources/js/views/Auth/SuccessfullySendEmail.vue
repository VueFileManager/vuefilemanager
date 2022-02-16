<template>
    <AuthContentWrapper class="h-screen">
        <AuthContent :visible="true">
            <Headline
                :title="$t('page_email_successfully_send.title')"
                :description="$t('page_email_successfully_send.subtitle')"
            />

            <span class="block">
                <router-link :to="{ name: 'Homepage' }" class="text-theme font-bold">
                    {{ $t('go_home') }}
                </router-link>
            </span>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
import AuthContentWrapper from '../../components/Auth/AuthContentWrapper'
import AuthContent from '../../components/Auth/AuthContent'
import AuthButton from '../../components/Auth/AuthButton'
import Headline from './Headline'

export default {
    name: 'SuccessfullySendEmail',
    components: {
        AuthContentWrapper,
        AuthContent,
        AuthButton,
        Headline,
    },
}
</script>
