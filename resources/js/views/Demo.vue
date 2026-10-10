<template>
    <AuthContentWrapper ref="auth" class="h-screen">
        <AuthContent :visible="true">
            <Headline
				title="You Are Nearly Here!"
				description="Just click on the button below."
			/>

			<a :href="`${$store.getters.config.host}/sign-in`" target="_blank">
				<AuthButton
					class="mb-12 w-full justify-center md:w-min"
					icon="chevron-right"
					text="Visit Demo"
				/>
			</a>
        </AuthContent>
    </AuthContentWrapper>
</template>
<script>
import AuthContentWrapper from '../components/Layout/AuthPages/AuthContentWrapper.vue'
import AuthContent from '../components/Layout/AuthPages/AuthContent.vue'
import AuthButton from '../components/UI/Buttons/AuthButton.vue'
import Headline from '../components/UI/Labels/LogoHeadline.vue'

export default {
    name: 'Demo',
    components: {
        AuthContentWrapper,
        AuthContent,
        AuthButton,
        Headline,
    },
}
</script>
