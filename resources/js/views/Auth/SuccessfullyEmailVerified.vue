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
import AuthContentWrapper from '../../components/Layout/AuthPages/AuthContentWrapper.vue'
import AuthContent from '../../components/Layout/AuthPages/AuthContent.vue'
import AuthButton from '../../components/UI/Buttons/AuthButton.vue'
import Headline from '../../components/UI/Labels/LogoHeadline.vue'

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
