<template>
    <div id="single-page">
        <div id="page-content">

            <!--Header-->
            <MobileHeader :title="$router.currentRoute.meta.title"/>

            <!--Content-->
            <div class="content-page">
                <nav class="mobile-navigation">

                    <!--Admin menu-->
                    <b class="mobile-menu-label">{{ $t('global.admin') }}</b>
                    <MenuItemList :navigation="AdminNavigation" />

                    <!--SaaS menu-->
                    <b class="mobile-menu-label">{{ $t('global.saas') }}</b>
                    <MenuItemList :navigation="SassNavigation" />
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
                        icon: 'box',
                        title: this.$t('admin_menu.dashboard'),
                        routeName: 'Dashboard',
                        isVisible: true,
                    },
                    {
                        icon: 'users',
                        title: this.$t('admin_menu.users'),
                        routeName: 'Users',
                        isVisible: true,
                    },
                    {
                        icon: 'settings',
                        title: this.$t('admin_menu.settings'),
                        routeName: 'AppOthers',
                        isVisible: true,
                    },
                ],
                SassNavigation: [
                    {
                        icon: 'database',
                        title: this.$t('admin_menu.plans'),
                        routeName: 'Plans',
                        isVisible: true,
                    },
                    {
                        icon: 'file-text',
                        title: this.$t('admin_menu.invoices'),
                        routeName: 'Invoices',
                        isVisible: true,
                    },
                    {
                        icon: 'monitor',
                        title: this.$t('admin_menu.pages'),
                        routeName: 'Pages',
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

    @media (prefers-color-scheme: dark) {
        .mobile-navigation {

            .mobile-menu-label {
                color: $dark_mode_text_secondary;
            }
        }
    }
</style>
