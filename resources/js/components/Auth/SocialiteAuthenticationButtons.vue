<template>
    <div class="wrapper">
        <div class="icon-wrapper">
            <facebook-icon  @click="socialite('facebook')" class="icon"/>
            <github-icon @click="socialite('github')" class="icon"/>
            <h1 @click="socialite('google')" class="icon">G</h1>
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
        socialite(provider) {

            this.isLoading = true

            axios
                .get(`/api/socialite/${provider}/redirect`)
                .then((response) => {
                    if(response.data.url) {
                        window.location.href = response.data.url
                    }                
                })
                .catch(() => this.$isSomethingWrong())
        },
    }
    
}
</script>

<style lang="scss" scoped>

.wrapper {
    display: flex;
    justify-content: center;
    margin: 50px 0px 0px 0px;

    .icon-wrapper {
        width: 200px;
        display: flex;
        align-content: center;
        justify-content: space-between;

        .icon { 
            align-self: center;
            cursor: pointer;
        }
    }
}

</style>
