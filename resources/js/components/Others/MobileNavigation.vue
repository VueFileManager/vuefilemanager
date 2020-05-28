<template>
    <div class="mobile-main-navigation" v-if="app">
        <transition name="context-menu">
            <nav v-if="isVisible" class="mobile-navigation">

                <!--User Info-->
                <div class="user-info">
                    <UserAvatar size="large"/>
                    <UserHeadline/>
                </div>

                <!--Navigation-->
                <MenuItemList :navigation="navigation" @menu="action"/>
            </nav>
        </transition>
        <transition name="fade">
            <div v-show="isVisible" class="vignette" @click="closeAndResetContextMenu"></div>
        </transition>
    </div>
</template>

<script>
    import UserHeadline from '@/components/Sidebar/UserHeadline'
    import MenuItemList from '@/components/Mobile/MenuItemList'
    import UserAvatar from '@/components/Others/UserAvatar'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'MenuBar',
        components: {
            MenuItemList,
            UserHeadline,
            UserAvatar,
        },
        computed: {
            ...mapGetters(['app', 'homeDirectory']),
            navigation() {
                return [
                    {
                        icon: 'hard-drive',
                        title: this.$t('menu.files'),
                        routeName: 'Files',
                        isVisible: true,
                    },
                    {
                        icon: 'latest',
                        title: this.$t('menu.latest'),
                        routeName: 'Files',
                        isVisible: true,
                    },
                    {
                        icon: 'share',
                        title: this.$t('menu.shared'),
                        routeName: 'SharedFiles',
                        isVisible: true,
                    },
                    {
                        icon: 'trash',
                        title: this.$t('menu.trash'),
                        routeName: 'Trash',
                        isVisible: true,
                    },
                    {
                        icon: 'settings',
                        title: this.$t('menu.settings'),
                        routeName: 'Profile',
                        isVisible: true,
                    },
                    {
                        icon: 'users',
                        title: this.$t('menu.admin'),
                        routeName: 'Users',
                        isVisible: this.app.user.role === 'admin',
                    },
                    {
                        icon: 'power',
                        title: this.$t('menu.logout'),
                        routeName: 'LogOut',
                        isVisible: true,
                    },
                ]
            },
        },
        data() {
            return {
                isVisible: false,
            }
        },
        methods: {
            action(name) {

                if (name === 'latest') {
                    this.$store.dispatch('getLatest')
                }

                if (name === 'hard-drive') {
                    this.$store.dispatch('getFolder', [{folder: this.homeDirectory, back: false, init: true}])
                }

                if (name === 'power') {
                    this.$store.dispatch('logOut')
                }

                this.closeAndResetContextMenu()
            },
            closeAndResetContextMenu() {
                this.isVisible = false

                events.$emit('hide:mobile-navigation')
            }
        },
        created() {
            events.$on('show:mobile-navigation', () => {
                this.isVisible = true
            })
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .mobile-navigation {
        padding: 20px;
        width: 100%;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 99;
        background: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        min-height: 440px;
        max-height: 80%;
        overflow-y: auto;
    }

    .vignette {
        background: rgba(0, 0, 0, 0.35);
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 9;
        cursor: pointer;
        opacity: 1;
    }

    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    @media only screen and (max-width: 690px) {

    }

    @media (prefers-color-scheme: dark) {
        .mobile-navigation {
            background: $dark_mode_background;
        }
    }

    // Transition
    .context-menu-enter-active,
    .fade-enter-active {
        transition: all 200ms;
    }

    .context-menu-leave-active,
    .fade-leave-active {
        transition: all 200ms;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .context-menu-enter,
    .context-menu-leave-to {
        opacity: 0;
        transform: translateY(100%);
    }

    .context-menu-leave-active {
        position: absolute;
    }

</style>
