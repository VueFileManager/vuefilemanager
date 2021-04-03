<template>
    <div class="oasis-header">
        <nav class="navigation container">
            <router-link :to="{name: 'Homepage'}" tag="div" class="logo">
                <img v-if="config.app_logo_horizontal" :src="$getImage(config.app_logo_horizontal)" :alt="config.app_name">
                <b v-if="! config.app_logo_horizontal" class="logo-text">{{ config.app_name }}</b>
            </router-link>
            <ul class="links">
                <li v-for="(item, i) in navigation" :key="i">
                    <a :href="`#${item.href}`">{{ item.title }}</a>
                </li>
            </ul>
            <div class="log-in">
                <router-link :to="{name: 'SignIn'}" class="base-button theme-color">
                    Prihlásit se
                </router-link>
            </div>
        </nav>

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
                navigation: [
                    {
                        title: 'O Nás',
                        href: 'o-nas',
                    },
                    /*{
                        title: 'Kariéra',
                        href: 'kariera',
                    },*/
                    {
                        title: 'Ceník',
                        href: 'cenik',
                    },
                    {
                        title: 'Podpora',
                        href: 'podpora',
                    },
                ],
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';
</style>
