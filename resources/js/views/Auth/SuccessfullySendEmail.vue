<template>
    <AuthContentWrapper>        
        <AuthContent :visible="true">
			<Headline
				:title="$t('page_email_successfully_send.title')"
				:description="$t('page_email_successfully_send.subtitle')"
			/>

			<span class="block">
                <router-link :to="{name: 'Homepage'}" class="font-bold text-theme">
                    {{ $t('go_home') }}
                </router-link>
            </span>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '/resources/js/components/Auth/AuthContentWrapper'
    import AuthContent from '/resources/js/components/Auth/AuthContent'
    import AuthButton from '/resources/js/components/Auth/AuthButton'
	import Headline from "./Headline"

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
