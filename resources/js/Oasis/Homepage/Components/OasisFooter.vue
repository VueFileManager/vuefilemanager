<template>
    <div class="oasis-footer container">
        <router-link :to="{name: 'Homepage'}" tag="div" class="logo">
            <img v-if="config.app_logo_horizontal" :src="$getImage(config.app_logo_horizontal)" :alt="config.app_name">
            <b v-if="! config.app_logo_horizontal" class="logo-text">{{ config.app_name }}</b>
        </router-link>
        <nav>
            <ul class="links">
                <li v-if="legal.visibility" v-for="(legal, index) in config.legal" :key="index">
                    <router-link :to="{name: 'DynamicPage', params: {slug: legal.slug }}">
                        {{ legal.title }}
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
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';

    @media only screen and (max-width: 960px) {
        .oasis-footer.container {
            display: block;
            text-align: center;
            padding: 10px 0;

            .logo {
                margin: 0 auto;
                display: block;
            }

            .links {
                display: block;

                li {
                    display: block;

                    a {
                        display: block;
                        padding: 10px;
                    }
                }
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .oasis-footer.container {

            .links li a {
                color: $dark-mode-text;
            }
        }
    }
</style>
