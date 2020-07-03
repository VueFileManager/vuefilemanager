<template>
    <div id="single-page">
        <div id="page-content">

            <!--Header-->
            <MobileHeader :title="$router.currentRoute.meta.title"/>

            <!--Content-->
            <div class="content-page">
                <nav class="mobile-navigation">

                    <!--Admin menu-->
                    <b class="mobile-menu-label">Menu</b>
                    <MenuItemList :navigation="ProfileNavigation" />

                    <!--SaaS menu-->
                    <b class="mobile-menu-label">Subscription</b>
                    <MenuItemList :navigation="SubscriptionNavigation" />
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
    import MenuItemList from '@/components/Mobile/MenuItemList'
    import MobileHeader from '@/components/Mobile/MobileHeader'

    export default {
        name: 'UserProfileMobileMenu',
        components: {
            MenuItemList,
            MobileHeader,
        },
        data() {
            return {
                ProfileNavigation: [
                    {
                        icon: 'user',
                        title: 'Profile',
                        routeName: 'Profile',
                        isVisible: true,
                    },
                    {
                        icon: 'hard-drive',
                        title: 'Storage',
                        routeName: 'Storage',
                        isVisible: true,
                    },
                    {
                        icon: 'lock',
                        title: 'Password',
                        routeName: 'Password',
                        isVisible: true,
                    },
                ],
                SubscriptionNavigation: [
                    {
                        icon: 'cloud',
                        title: 'Subscription',
                        routeName: 'Subscription',
                        isVisible: true,
                    },
                    {
                        icon: 'credit-card',
                        title: 'Payment Cards',
                        routeName: 'PaymentMethods',
                        isVisible: true,
                    },
                    {
                        icon: 'file-text',
                        title: 'Invoices',
                        routeName: 'Invoice',
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
