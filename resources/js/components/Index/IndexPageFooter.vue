<template>
    <footer class="page-wrapper medium">
        <router-link :to="{name: 'SaaSLandingPage'}" tag="div" class="logo">
            <img v-if="config.app_logo_horizontal" :src="config.app_logo_horizontal" :alt="config.app_name">
            <b v-if="! config.app_logo_horizontal" class="logo-text">{{ config.app_name }}</b>
        </router-link>
        <ul class="navigation-links">
            <li>
                <a href="/#pricing">
                    Pricing
                </a>
            </li>
            <li>
                <router-link :to="{name: 'ContactUs'}">
                    Contact Us
                </router-link>
            </li>
        </ul>
        <ul class="navigation-links">
            <li>
                <router-link :to="{name: 'DynamicPage', params: {slug: 'terms-of-service'}}">
                    Terms of Service
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'DynamicPage', params: {slug: 'privacy-policy'}}">
                    Privacy Policy
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'DynamicPage', params: {slug: 'cookie-policy'}}">
                    Cookie Policy
                </router-link>
            </li>
        </ul>
        <p class="copyright">
            © 2020 {{ config.app_name }}. All rights reserved.
        </p>
    </footer>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        name: 'IndexPageFooter',
        computed: {
            ...mapGetters(['config']),
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_landing-page';
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    footer {
        text-align: center;
        padding-top: 80px;
    }

    .logo {
        margin-bottom: 15px;
        cursor: pointer;

        img {
            height: 38px;
            width: auto;
        }

        .logo-text {
            font-weight: 800;
            @include font-size(25);
        }
    }

    .navigation-links {
        display: inline-block;

        li {
            display: inline-block;

            a {
                display: block;
                padding: 19px;
                font-weight: 700;
                @include font-size(17);
                @include transition(150ms);

                &:hover {
                    color: $theme;
                }
            }
        }
    }

    .copyright {
        @include font-size(17);
        color: $text-muted;
        padding-top: 50px;
        padding-bottom: 20px;
    }

    @media only screen and (max-width: 960px) {
        .navigation-links {
            display: block;
            
            li {
                display: block;

                a {
                    padding: 10px 0;
                }
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .copyright {
            color: $dark_mode_text_secondary;
        }
    }
</style>
