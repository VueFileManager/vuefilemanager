<template>
    <nav class="oasis-navigation navigation" :class="{'white': isWhite}">
        <div class="container">
            <router-link :to="{name: 'Homepage'}" tag="div" class="logo">
                <img v-if="config.app_logo_horizontal" :src="$getImage(config.app_logo_horizontal)" :alt="config.app_name">
                <b v-if="! config.app_logo_horizontal" class="logo-text">{{ config.app_name }}</b>
            </router-link>
            <ul class="links">
                <li v-for="(item, i) in navigation" :key="i">
                    <a v-if="$router.currentRoute.name !== 'DynamicPage'" @click="scrollToSection(item.href)">{{ item.title }}</a>

                    <router-link v-if="$router.currentRoute.name === 'DynamicPage'" :to="{path: '/', hash: `#${item.href}`}">
                        {{ item.title }}
                    </router-link>
                </li>
            </ul>
            <div v-if="config.isAuthenticated" class="log-in">
                <router-link :to="{name: 'Files'}" class="base-button theme-color">
                    {{ $t('go_to_files') }}
                </router-link>
            </div>
            <div v-if="! config.isAuthenticated" class="log-in">
                <router-link :to="{name: 'SignIn'}" class="base-button theme-color">
                    {{ $t('page_index.menu.log_in') }}
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
                isWhite: false,
                navigation: [
                    {
                        title: this.$t('navigation.price'),
                        href: 'cenik',
                    },
                    {
                        title: this.$t('navigation.about-us'),
                        href: 'o-nas',
                    },
                    {
                        title: this.$t('navigation.contact-and-support'),
                        href: 'kontakt-a-podpora',
                    },
                ],
            }
        },
        methods: {
            scrollToSection(anchor) {
                var el = document.getElementById('vuefilemanager')

                const section = document.getElementById(anchor),
                    position = section.offsetTop;

                el.scrollTo({top: position, behavior: 'smooth'});
            }
        },
        created() {

            // Perform scroll to loaded location
            if (document.location.hash) {
                setTimeout(() => {
                    this.scrollToSection(document.location.hash.substring(1))
                }, 300)
            }

            // Set white bar after user scrolling down
            var el = document.getElementById('vuefilemanager')

            el.addEventListener('scroll', () => {
                if (el.scrollTop > 35 ) {
                    this.isWhite = true
                } else {
                    this.isWhite = false
                }
            })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';

    .oasis-navigation {
        position: sticky;
        top: 0;
    }

    .navigation {
        padding: 5px 0;
        z-index: 10;
        @include transition(150ms);
        margin-top: 35px;

        &.white {
            background: white;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .log-in, .logo {
            width: 235px;
        }

        .logo img {
            width: 100%;
        }

        .log-in {
            text-align: right;
        }
    }

    @media only screen and (max-width: 1370px) {
        .navigation {
            margin-top: 5px;
        }
    }

    @media only screen and (max-width: 960px) {
        .oasis-navigation.navigation {
            margin-top: 10px;

            .base-button {
                padding: 12px 24px;
            }
        }

        .links {
            display: none;
        }
    }


    @media (prefers-color-scheme: dark) {
        .navigation {

            .links li a {
                color: $dark-mode-text;
            }

            &.white {
                background: rgba(darken($theme-bg-dark, 10%), 0.9);
                backdrop-filter: blur(18px);
            }
        }
    }
</style>
