<template>
    <AuthContentWrapper class="h-screen">
        <AuthContent :visible="true">
			<Headline
				:title="$t('page_email_successfully_verified.title')"
				:description="$t('page_email_successfully_verified.subtitle')"
			/>
            
            <router-link :to="{name: 'SignIn'}">
                <AuthButton icon="chevron-right" :text="$t('page_sign_in.button_log_in')"/>
            </router-link>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '/resources/js/components/Auth/AuthContentWrapper'
    import AuthContent from '/resources/js/components/Auth/AuthContent'
    import AuthButton from '/resources/js/components/Auth/AuthButton'
	import Headline from "./Headline";

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
