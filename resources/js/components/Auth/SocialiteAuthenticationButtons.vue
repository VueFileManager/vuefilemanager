<template>
    <div class="wrapper flex flex-row w-1/2 ml-2">
        <div class="w-1/3 grid justify-items-center items-center cursor-pointer">
            <facebook-icon  @click="socialiteRedirect('facebook')" />
        </div>

        <div class="w-1/3 grid justify-items-center items-center cursor-pointer">
            <github-icon @click="socialiteRedirect('github')" />
        </div>

        <div class="w-1/3 grid justify-items-center items-center cursor-pointer">
            <h1 @click="socialiteRedirect('google')">G</h1>
        </div>
    </div>
</template>

<script>
import { FacebookIcon, GithubIcon  } from 'vue-feather-icons'

export default {
    name:'SocialiteAuthenticationButtons',
    components: {
        FacebookIcon,
        GithubIcon,
    },
    methods: {
        socialiteRedirect(provider) {

            this.isLoading = true

            this.$store.dispatch('socialiteRedirect', provider)
        },
    }
    
}
</script>

<style lang="scss" scoped>

.wrapper {
    margin: 50px auto 0px auto;
}

</style>
