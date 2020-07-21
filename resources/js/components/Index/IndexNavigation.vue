<template>
    <nav class="main-navigation">
        <router-link :to="{name: 'SaaSLandingPage'}" tag="div" class="logo">
            <img v-if="config.app_logo_horizontal" :src="$getImage(config.app_logo_horizontal)" :alt="config.app_name">
            <b v-if="! config.app_logo_horizontal" class="logo-text">{{ config.app_name }}</b>
        </router-link>
        <div class="navigation">
            <ul class="navigation-links">
                <!--<li v-if="config.stripe_public_key">
                    <a href="/#pricing">
                        {{ $t('page_index.menu.pricing') }}
                    </a>
                </li>-->
                <li>
                    <router-link :to="{name: 'ContactUs'}">
                        {{ $t('page_index.menu.contact_us') }}
                    </router-link>
                </li>
            </ul>
            <ul class="navigation-links">
                <li>
                    <router-link :to="{name: 'SignIn'}">
                        {{ $t('page_index.menu.log_in') }}
                    </router-link>
                </li>
                <li v-if="config.userRegistration">
                    <router-link class="cta-button" :to="{name: 'SignUp'}">
                        {{ $t('page_index.menu.sign_in') }}
                    </router-link>
                </li>
            </ul>
        </div>
        <router-link class="cta-button log-in" :to="{name: 'SignIn'}">
            {{ $t('page_index.menu.log_in') }}
        </router-link>
    </nav>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        name: 'IndexNavigation',
        computed: {
            ...mapGetters(['config', 'index']),
        },
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
        cursor: pointer;

        img {
            cursor: pointer;
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
