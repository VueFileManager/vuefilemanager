<template>
    <AuthContentWrapper ref="auth">

        <AuthContent name="not-found" :visible="true">
			<Headline
				:title="$t('page_shared_404.title')"
				:description="$t('page_shared_404.subtitle')"
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
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContent from '/resources/js/components/Auth/AuthContent'
    import AuthButton from '/resources/js/components/Auth/AuthButton'
    import {required} from 'vee-validate/dist/rules'
	import Headline from "./Auth/Headline"
    import {mapGetters} from 'vuex'

    export default {
        name: 'NotFound',
        components: {
            AuthContentWrapper,
            ValidationProvider,
            ValidationObserver,
            AuthContent,
            AuthButton,
			Headline,
            required,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: false,
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_auth';
</style>
