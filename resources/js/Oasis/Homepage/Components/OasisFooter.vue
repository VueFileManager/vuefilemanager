<template>
    <div class="oasis-footer container">
        <router-link :to="{name: 'Homepage'}" tag="div" class="logo">
            <img v-if="config.app_logo_horizontal" :src="$getImage(config.app_logo_horizontal)" :alt="config.app_name">
            <b v-if="! config.app_logo_horizontal" class="logo-text">{{ config.app_name }}</b>
        </router-link>
        <nav>
            <ul class="links">
                <li v-for="(item, i) in navigation" :key="i">
                    <a :href="`#${item.href}`">
                        {{ item.title }}
                    </a>
                </li>
            </ul>
            <ul class="links">
                <li v-for="(item, i) in routes" :key="i">
                    <router-link :to="{name: item.route}" :class="{'highlighted': item.route === 'SignUp'}">
                        {{ item.title }}
                    </router-link>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import {CheckIcon} from 'vue-feather-icons'
    import {mapGetters} from "vuex"

    export default {
        name: 'OasisFooter',
        components: {
            CheckIcon,
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
                routes: [
                    {
                        title: 'Prihlásit se',
                        route: 'SignIn',
                    },
                    {
                        title: 'Zaregistrovat se',
                        route: 'SignUp',
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
