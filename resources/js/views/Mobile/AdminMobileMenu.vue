<template>
    <div id="single-page">
        <div id="page-content">

            <!--Header-->
            <MobileHeader :title="$router.currentRoute.meta.title"/>

            <!--Content-->
            <div class="content-page">
                <nav class="mobile-navigation">

                    <!--SaaS menu-->
                    <b class="mobile-menu-label">SaaS</b>
                    <MenuItemList :navigation="SassNavigation" />

                    <!--Admin menu-->
                    <b class="mobile-menu-label">Admin</b>
                    <MenuItemList :navigation="AdminNavigation" />
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
    import MenuItemList from '@/components/Mobile/MenuItemList'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import { mapGetters } from 'vuex'

    export default {
        name: 'AdminMobileMenu',
        components: {
            MenuItemList,
            MobileHeader,
        },
        data() {
            return {
                AdminNavigation: [
                    {
                        icon: 'users',
                        title: 'Users',
                        routeName: 'Users',
                        isVisible: true,
                    },
                    {
                        icon: 'settings',
                        title: 'Settings',
                        routeName: 'User',
                        isVisible: true,
                    },
                ],
                SassNavigation: [
                    {
                        icon: 'database',
                        title: 'Plans',
                        routeName: 'Plans',
                        isVisible: true,
                    },
                    {
                        icon: 'credit-card',
                        title: 'Payment Methods',
                        routeName: 'Gateways',
                        isVisible: true,
                    },
                    {
                        icon: 'file-text',
                        title: 'Invoices',
                        routeName: 'Invoices',
                        isVisible: true,
                    },
                ]
            }
        },
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

        .mobile-menu-label {
            margin-top: 30px;
            margin-bottom: 5px;
            @include font-size(11);
            color: $text-muted;
            display: block;

            &:first-child {
                margin-top: 0;
            }
        }
    }
</style>
