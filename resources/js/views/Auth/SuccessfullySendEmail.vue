<template>
    <AuthContentWrapper>        
        <AuthContent :visible="true">
			<Headline
				:title="$t('page_email_successfully_send.title')"
				:description="$t('page_email_successfully_send.subtitle')"
			/>

			<span class="additional-link">
                <router-link :to="{name: 'Homepage'}" class="text-theme">
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
	import Headline from "./Headline";
    import {mapGetters} from 'vuex'

    export default {
        name: 'SuccessfullySendEmail',
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
