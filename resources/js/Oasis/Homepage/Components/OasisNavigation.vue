<template>
    <nav class="oasis-navigation navigation">
        <div class="container">
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
        </div>
    </nav>
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
