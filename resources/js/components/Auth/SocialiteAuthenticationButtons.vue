<template>
    <div
        v-if="config.allowedFacebookLogin || config.allowedGoogleLogin || config.allowedGithubLogin"
        class="mb-10 flex items-center justify-center"
    >
        <div v-if="config.allowedFacebookLogin" class="mx-5 cursor-pointer">
            <facebook-icon @click="socialiteRedirect('facebook')" />
        </div>

        <div v-if="config.allowedGithubLogin" class="mx-5 cursor-pointer">
            <github-icon @click="socialiteRedirect('github')" />
        </div>

        <div v-if="config.allowedGoogleLogin" class="mx-5 cursor-pointer">
			<google-icon @click="socialiteRedirect('google')" class="vue-feather"/>
        </div>
    </div>
</template>

<script>
import GoogleIcon from "../Icons/GoogleIcon";
import { FacebookIcon, GithubIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'SocialiteAuthenticationButtons',
    components: {
        FacebookIcon,
        GoogleIcon,
        GithubIcon,
    },
    computed: {
        ...mapGetters(['config']),
    },
    methods: {
        socialiteRedirect(provider) {
            this.isLoading = true

            this.$store.dispatch('socialiteRedirect', provider)
        },
    },
}
</script>
