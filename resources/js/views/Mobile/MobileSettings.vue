<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <div class="content-page">
                <nav class="mobile-navigation">

                    <!--Navigation-->
                    <MenuItemList :navigation="navigation" />
                </nav>
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import MenuItemList from '@/components/Mobile/MenuItemList'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import { mapGetters } from 'vuex'

    export default {
        name: 'MobileSettings',
        components: {
            MenuItemList,
            MobileHeader,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                navigation: undefined
            }
        },
        created() {

            this.navigation = [
                {
                    icon: 'user',
                    title: this.$t('menu.profile'),
                    routeName: 'Profile',
                    isVisible: true,
                },
                {
                    icon: 'lock',
                    title: this.$t('menu.password'),
                    routeName: 'Password',
                    isVisible: true,
                },
                {
                    icon: 'hard-drive',
                    title: this.$t('menu.storage'),
                    routeName: 'Storage',
                    isVisible: this.config.storageLimit,
                },
            ]
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .mobile-navigation {
        width: 100%;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 99;
    }

    @media only screen and (max-width: 690px) {

    }

    @media (prefers-color-scheme: dark) {

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
