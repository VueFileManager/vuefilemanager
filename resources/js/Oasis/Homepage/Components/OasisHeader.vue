<template>
    <div class="oasis-header">
        <header class="header container">
            <h1 class="main-title">
                Jednoduchý a Bezpečný Cloud vo Vrecku
            </h1>
            <h2 class="sub-title">
                Virtuální šanon, vždy s tebou.
            </h2>
            <router-link :to="{name: 'SignUp'}" class="theme-button">
                Zaregistrujte se
            </router-link>
            <span class="log-in">
                alebo <router-link :to="{name: 'SignIn'}">prihláste se</router-link>
            </span>
        </header>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        name: 'Header',
        components: {

        },
        computed: {
            ...mapGetters([
                'config'
            ]),
        },
        data() {
            return {

            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';
</style>
