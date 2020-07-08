<template>
    <nav class="main-navigation">
        <router-link :to="{name: 'SaaSLandingPage'}" tag="div" class="logo">
            <img src="/assets/images/vuefilemanager-horizontal-logo.svg" alt="VueFileManager">
        </router-link>
        <div class="navigation">
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
                    <router-link :to="{name: 'SignIn'}">
                        Log In
                    </router-link>
                </li>
                <li>
                    <router-link class="cta-button" :to="{name: 'SignUp'}">
                        Sign Up
                    </router-link>
                </li>
            </ul>
        </div>
        <router-link class="cta-button log-in" :to="{name: 'SignIn'}">
            Log In
        </router-link>
    </nav>
</template>

<script>
    export default {
        name: 'IndexNavigation',
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_landing-page';
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .main-navigation {
        justify-content: space-between;
        padding-bottom: 25px;
        align-items: center;
        padding-top: 25px;
        display: flex;
    }

    .logo {

        img {
            cursor: pointer;
            height: 38px;
            width: auto;
        }
    }

    .navigation-links {
        display: inline-block;
        margin-left: 50px;

        &:first-child {
            margin-left: 0;
        }

        li {
            display: inline-block;

            a {
                padding: 14px;
                font-weight: 700;
                @include font-size(17);
                @include transition(150ms);

                &:hover {
                    color: $theme;
                }
            }
        }
    }

    .cta-button {
        background: rgba($theme, 0.1);
        border-radius: 6px;
        padding: 8px 23px;
        color: $theme;
        @include font-size(17);
        font-weight: 700;

        &.log-in {
            display: none;
        }
    }

    @media only screen and (max-width: 690px) {

        .navigation {
            display: none;
        }

        .logo {

            img {
                height: auto;
                width: 190px;
            }
        }

        .cta-button.log-in {
            display: block;
        }
    }
</style>
