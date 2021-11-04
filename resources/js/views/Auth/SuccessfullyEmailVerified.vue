<template>
    <AuthContentWrapper>
        <AuthContent :visible="true">
			<Headline
				:title="$t('page_email_successfully_verified.title')"
				:description="$t('page_email_successfully_verified.subtitle')"
			/>
            
            <a href="/sign-in">
                <AuthButton icon="chevron-right" :text="$t('page_sign_in.button_log_in')"/>
            </a>

        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '/resources/js/components/Auth/AuthContentWrapper'
    import AuthContent from '/resources/js/components/Auth/AuthContent'
    import AuthButton from '/resources/js/components/Auth/AuthButton'
	import Headline from "./Headline";
	import {mapGetters} from 'vuex'

    export default {
        name: 'SuccessfullyEmailVerified',
        components: {
            AuthContentWrapper,
            AuthContent,
            AuthButton,
			Headline,
        },
        computed: {
            ...mapGetters(['config']),
        },
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_auth-form';
    @import '/resources/sass/vuefilemanager/_auth';
</style>
