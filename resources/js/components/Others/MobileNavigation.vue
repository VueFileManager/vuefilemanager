<template>
    <div class="mobile-main-navigation" v-if="user">
        <transition name="context-menu">
            <nav v-if="isVisible" class="mobile-navigation">

                <!--User Info-->
                <UserHeadline class="user-info"/>

                <!--Navigation-->
                <MenuItemList :navigation="navigation" @menu="action"/>
            </nav>
        </transition>
    </div>
</template>

<script>
    import UserHeadline from '@/components/Sidebar/UserHeadline'
    import MenuItemList from '@/components/Mobile/MenuItemList'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'UserMobileNavigation',
        components: {
            MenuItemList,
            UserHeadline,
        },
        computed: {
            ...mapGetters(['user', 'homeDirectory']),
            navigation() {
                return [
                    {
                        icon: 'hard-drive',
                        title: this.$t('menu.files'),
                        routeName: 'Files',
                        isVisible: true,
                    },
                    /*{
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
                        routeName: 'Files',
                        isVisible: true,
                    },*/
                    {
                        icon: 'user',
                        title: this.$t('menu.settings'),
                        routeName: 'UserProfileMobileMenu',
                        isVisible: true,
                    },
                    {
                        icon: 'settings',
                        title: this.$t('menu.admin'),
                        routeName: 'AdminMobileMenu',
                        isVisible: this.user.data.attributes.role === 'admin',
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

                /*if (name === 'latest') {
                    this.$store.dispatch('getLatest')
                }

                if (name === 'trash') {
                    this.$store.dispatch('getTrash')
                }*/

                if (name === 'hard-drive') {
                    this.$store.dispatch('getFolder', [{folder: this.homeDirectory, back: false, init: true}])
                }

                if (name === 'power') {
                    this.$store.dispatch('logOut')
                }

                events.$emit('mobile-navigation:hide')
            },
        },
        created() {
            events.$on('mobile-navigation:show', () => this.isVisible = true)
            events.$on('mobile-navigation:hide', () => this.isVisible = false)
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

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
        max-height: 80%;
        overflow-y: auto;
    }

    .user-info {
        margin-bottom: 10px;
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
