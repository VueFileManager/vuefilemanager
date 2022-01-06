<template>
    <AuthContentWrapper ref="auth">

        <AuthContent :visible="true">
			<Headline
				:title="$t('Temporary Unavailable')"
				:description="$t('Unfortunately, this shared link is temporary unavailable. Please try it later.')"
			/>

            <span class="additional-link">{{ $t('page_registration.have_an_account') }}
                <router-link :to="{name: 'SignIn'}">
                    {{ $t('page_forgotten_password.password_remember_button') }}
                </router-link>
            </span>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '/resources/js/components/Auth/AuthContentWrapper'
    import AuthContent from '/resources/js/components/Auth/AuthContent'
    import AuthButton from '/resources/js/components/Auth/AuthButton'
	import Headline from "./Auth/Headline"

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
    @import '/resources/sass/vuefilemanager/_auth';
</style>
