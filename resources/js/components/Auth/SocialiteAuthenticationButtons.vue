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
            <span @click="socialiteRedirect('google')" class="text-3xl font-semibold">G</span>
        </div>
    </div>
</template>

<script>
import { FacebookIcon, GithubIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'SocialiteAuthenticationButtons',
    components: {
        FacebookIcon,
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
