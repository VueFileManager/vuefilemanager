<template>
    <footer class="page-wrapper medium">
        <div class="logo">
            <img src="/assets/images/vuefilemanager-horizontal-logo.svg" alt="VueFileManager">
        </div>
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
            <li>
                <router-link :to="{name: 'DynamicPage', params: {slug: 'terms'}}">
                    Terms
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'DynamicPage', params: {slug: 'privacy'}}">
                    Privacy
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'DynamicPage', params: {slug: 'cookies'}}">
                    Cookies
                </router-link>
            </li>
        </ul>
        <p class="copyright">
            © 2020 VueFileManager. All rights reserved.
        </p>
    </footer>
</template>

<script>
    export default {
        name: 'IndexPageFooter',
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_landing-page';
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    footer {
        text-align: center;
    }

    .logo {
        margin-bottom: 15px;

        img {
            height: 38px;
            width: auto;
        }
    }

    .navigation-links {
        display: inline-block;

        li {
            display: inline-block;

            a {
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

    @media only screen and (max-width: 690px) {

    }
</style>
