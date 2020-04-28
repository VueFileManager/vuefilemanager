<template>
    <div class="user-headline-wrapper" v-if="app">
        <div class="user-headline" @click="openMenu">
            <div class="user-avatar">
                <img :src="app.user.avatar" :alt="app.user.name">
            </div>
            <div class="user-name">
                <b class="name">{{ app.user.name }}</b>
                <span class="email">{{ app.user.email }}</span>
            </div>
        </div>
        <transition name="user-menu">
            <div class="user-menu" v-if="isOpenedMenu">
                <ul class="menu-options" @click="closeMenu">
                    <li class="menu-option">
                        <router-link :to="{name: 'Profile'}">
                            {{ $t('context_menu.profile_settings') }}
                        </router-link>
                    </li>
                    <li class="menu-option" @click="$store.dispatch('logOut')">{{ $t('context_menu.log_out') }}</li>
                </ul>
            </div>
        </transition>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'UserHeadline',
        computed: {
            ...mapGetters(['app', 'appSize']),
            isSmallAppSize() {
                return this.appSize === 'small'
            }
        },
        data() {
            return {
                isOpenedMenu: false,
            }
        },
        methods: {
            openMenu() {

                // If is mobile, then go to user settings page, else, open menu
                if ( this.isSmallAppSize ) {

                    this.$router.push({name: 'Profile'})
                } else {

                    this.isOpenedMenu = !this.isOpenedMenu
                }

            },
            closeMenu() {
                this.isOpenedMenu = !this.isOpenedMenu
            },
        },
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .user-headline-wrapper {
        position: relative;
    }

    .user-headline {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        margin: 15px;
        padding: 5px;
        user-select: none;
        border-radius: 8px;
        display: flex;
        align-items: center;
        cursor: pointer;
        @include transition(150ms);
        background: darken($light_background, 3%);

        &:active {
            transform: scale(0.95);
        }
    }

    .user-name {

        .name, .email {
            white-space: nowrap;
            width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .name {
            display: block;
            @include font-size(17);
            line-height: 1;
            color: $text;
        }

        .email {
            @include font-size(13);
            color: $theme;
            display: block;
            margin-top: 2px;
            font-weight: 600;
        }
    }

    .user-avatar {
        line-height: 0;
        margin-right: 15px;

        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
    }

    .user-menu {
        position: absolute;
        top: 75px;
        left: 0;
        right: 0;
        margin: 15px;
        z-index: 9;
    }

    .menu-options {
        list-style: none;
        width: 100%;
        margin: 0;
        padding: 0;
        box-shadow: $shadow;
        background: white;
        border-radius: 8px;

        .menu-option {
            font-weight: 700;
            @include font-size(15);
            padding: 15px 30px;
            cursor: pointer;
            width: 100%;
            color: $text;

            a {
                text-decoration: none;
                color: $text;
            }

            &:hover {
                background: $light_background;
                color: $theme;
            }
        }
    }

    @media only screen and (max-width: 690px) {

        .user-headline {
            position: relative;
            margin-bottom: 40px;
            background: transparent;
            padding: 0;
        }
    }

    @media (prefers-color-scheme: dark) {

        .user-headline {
            background: $dark_mode_foreground;
            padding: 0;

            &:hover {
                background: transparent;
            }
        }

        .user-name {

            .name {
                color: $dark_mode_text_primary;
            }
        }

        .menu-options {
            background: $dark_mode_background;

            .menu-option {
                color: $dark_mode_text_primary;

                &:hover {
                    background: $dark_mode_foreground;
                }
            }
        }
    }

    // Transition
    .user-menu-enter-active {
        transition: all 150ms ease;
    }

    .user-menu-leave-active {
        transition: all 150ms ease;
    }

    .user-menu-enter,
    .user-menu-leave-to {
        opacity: 0;
        transform: translateY(-35%);
    }

</style>
