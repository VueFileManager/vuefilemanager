import Vue from 'vue'
import Router from 'vue-router'
import i18n from '@/i18n/index'

import AdminMobileMenu from './views/Mobile/AdminMobileMenu'
import UserProfileMobileMenu from './views/Mobile/UserProfileMobileMenu'

Vue.use(Router)

const routesAdmin = [
    {
        name: 'Admin',
        path: '/admin',
        component: () =>
            import(/* webpackChunkName: "admin" */ './views/Admin'),
        meta: {
            requiresAuth: true,
            title: 'Admin'
        },
        children: [
            {
                name: 'Dashboard',
                path: '/admin/dashboard',
                component: () =>
                    import(/* webpackChunkName: "dashboard" */ './views/Admin/Dashboard'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.dashboard')
                },
            },
            {
                name: 'Invoices',
                path: '/admin/invoices',
                component: () =>
                    import(/* webpackChunkName: "invoices" */ './views/Admin/Invoices'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.invoices')
                },
            },
            {
                name: 'Pages',
                path: '/admin/pages',
                component: () =>
                    import(/* webpackChunkName: "pages" */ './views/Admin/Pages'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.pages')
                },
            },
            {
                name: 'PageEdit',
                path: '/admin/pages/:slug',
                component: () =>
                    import(/* webpackChunkName: "page-edit" */ './views/Admin/Pages/PageEdit'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.page_edit')
                },
            },
            {
                name: 'Plans',
                path: '/admin/plans',
                component: () =>
                    import(/* webpackChunkName: "plans" */ './views/Admin/Plans'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.pricing_plans')
                },
            },
            {
                name: 'Users',
                path: '/admin/users',
                component: () =>
                    import(/* webpackChunkName: "users" */ './views/Admin/Users'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.users_list')
                },
            },
            {
                name: 'UserCreate',
                path: '/admin/user/create',
                component: () =>
                    import(/* webpackChunkName: "user-create" */ './views/Admin/Users/UserCreate'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.user_create')
                },
            },
            {
                name: 'PlanCreate',
                path: '/admin/plan/create',
                component: () =>
                    import(/* webpackChunkName: "plan-create" */ './views/Admin/Plans/PlanCreate'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.plan_create')
                },
            },
            {
                name: 'User',
                path: '/admin/user/:id',
                component: () =>
                    import(/* webpackChunkName: "user" */ './views/Admin/Users/User'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.users_user')
                },
                children: [
                    {
                        name: 'UserDetail',
                        path: '/admin/user/:id/details',
                        component: () =>
                            import(/* webpackChunkName: "user-detail" */ './views/Admin/Users/UserTabs/UserDetail'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.users_detail')
                        },
                    },
                    {
                        name: 'UserStorage',
                        path: '/admin/user/:id/storage',
                        component: () =>
                            import(/* webpackChunkName: "user-storage" */ './views/Admin/Users/UserTabs/UserStorage'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.users_storage_usage')
                        },
                    },
                    {
                        name: 'UserSubscription',
                        path: '/admin/user/:id/subscription',
                        component: () =>
                            import(/* webpackChunkName: "user-subscription" */ './views/Admin/Users/UserTabs/UserSubscription'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.subscription')
                        },
                    },
                    {
                        name: 'UserInvoices',
                        path: '/admin/user/:id/invoices',
                        component: () =>
                            import(/* webpackChunkName: "user-invoices" */ './views/Admin/Users/UserTabs/UserInvoices'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.invoices')
                        },
                    },
                    {
                        name: 'UserPassword',
                        path: '/admin/user/:id/password',
                        component: () =>
                            import(/* webpackChunkName: "user-password" */ './views/Admin/Users/UserTabs/UserPassword'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.users_password')
                        },
                    },
                    {
                        name: 'UserDelete',
                        path: '/admin/user/:id/delete',
                        component: () =>
                            import(/* webpackChunkName: "user-delete" */ './views/Admin/Users/UserTabs/UserDelete'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.users_delete')
                        },
                    },
                ]
            },
            {
                name: 'Plan',
                path: '/admin/plan/:id',
                component: () =>
                    import(/* webpackChunkName: "plan" */ './views/Admin/Plans/Plan'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.plan')
                },
                children: [
                    {
                        name: 'PlanSubscribers',
                        path: '/admin/plan/:id/subscribers',
                        component: () =>
                            import(/* webpackChunkName: "plan-subscribers" */ './views/Admin/Plans/PlanTabs/PlanSubscribers'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.subscribers')
                        },
                    },
                    {
                        name: 'PlanSettings',
                        path: '/admin/plan/:id/settings',
                        component: () =>
                            import(/* webpackChunkName: "plan-settings" */ './views/Admin/Plans/PlanTabs/PlanSettings'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.plan_settings'),
                        },
                    },
                    {
                        name: 'PlanDelete',
                        path: '/admin/plan/:id/delete',
                        component: () =>
                            import(/* webpackChunkName: "plan-delete" */ './views/Admin/Plans/PlanTabs/PlanDelete'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.plan_delete'),
                        },
                    },
                ]
            },
            {
                name: 'AppSettings',
                path: '/admin/settings',
                component: () =>
                    import(/* webpackChunkName: "app-settings" */ './views/Admin/AppSettings/AppSettings'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.settings')
                },
                children: [
                    {
                        name: 'AppAppearance',
                        path: '/admin/settings/appearance',
                        component: () =>
                            import(/* webpackChunkName: "app-appearance" */ './views/Admin/AppSettings/AppSettingsTabs/Appearance'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.appearance')
                        },
                    },
                    {
                        name: 'AppIndex',
                        path: '/admin/settings/index',
                        component: () =>
                            import(/* webpackChunkName: "app-index" */ './views/Admin/AppSettings/AppSettingsTabs/Index'),
                        meta: {
                            requiresAuth: true,
                            title: 'Index'
                        },
                    },
                    {
                        name: 'AppBillings',
                        path: '/admin/settings/billings',
                        component: () =>
                            import(/* webpackChunkName: "app-billings" */ './views/Admin/AppSettings/AppSettingsTabs/Billings'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.billings')
                        },
                    },
                    {
                        name: 'AppEmail',
                        path: '/admin/settings/email',
                        component: () =>
                            import(/* webpackChunkName: "app-email" */ './views/Admin/AppSettings/AppSettingsTabs/Email'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.email')
                        },
                    },
                    {
                        name: 'AppPayments',
                        path: '/admin/settings/payments',
                        component: () =>
                            import(/* webpackChunkName: "app-payments" */ './views/Admin/AppSettings/AppSettingsTabs/Payments'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.payments')
                        },
                    },
                    {
                        name: 'AppOthers',
                        path: '/admin/settings/others',
                        component: () =>
                            import(/* webpackChunkName: "app-others" */ './views/Admin/AppSettings/AppSettingsTabs/Others'),
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.others')
                        },
                    },
                ]
            },
        ]
    },
    {
        name: 'AdminMobileMenu',
        path: '/admin-menu',
        component: AdminMobileMenu,
        meta: {
            requiresAuth: true,
            title: i18n.t('routes_title.settings_mobile')
        },
    },
    {
        name: 'UserProfileMobileMenu',
        path: '/user-menu',
        component: UserProfileMobileMenu,
        meta: {
            requiresAuth: true,
            title: i18n.t('routes_title.profile_settings')
        },
    },
]
const routesShared = [
    {
        name: 'SharedPage',
        path: '/shared/:token',
        component: () =>
            import(/* webpackChunkName: "shared-page" */ './views/Shared/SharedPage'),
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'NotFoundShared',
        path: '/shared-not-found',
        component: () =>
            import(/* webpackChunkName: "not-found-shared" */ './views/Shared/NotFoundShared'),
        meta: {
            requiresAuth: false
        },
    },
]
const routesAuth = [
    {
        name: 'SignIn',
        path: '/sign-in',
        component: () =>
            import(/* webpackChunkName: "sign-in" */ './views/Auth/SignIn'),
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'SignUp',
        path: '/sign-up',
        component: () =>
            import(/* webpackChunkName: "sign-up" */ './views/Auth/SignUp'),
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'ForgottenPassword',
        path: '/forgotten-password',
        component: () =>
            import(/* webpackChunkName: "forgotten-password" */ './views/Auth/ForgottenPassword'),
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'CreateNewPassword',
        path: '/create-new-password',
        component: () =>
            import(/* webpackChunkName: "create-new-password" */ './views/Auth/CreateNewPassword'),
        meta: {
            requiresAuth: false
        },
    },
]
const routesUser = [
    {
        name: 'Files',
        path: '/files',
        component: () =>
            import(/* webpackChunkName: "files" */ './views/FilePages/Files'),
        meta: {
            requiresAuth: true
        },
    },
    {
        name: 'SharedFiles',
        path: '/shared-files',
        component: () =>
            import(/* webpackChunkName: "shared-files" */ './views/FilePages/SharedFiles'),
        meta: {
            requiresAuth: true
        },
    },
    {
        name: 'Trash',
        path: '/trash',
        component: () =>
            import(/* webpackChunkName: "trash" */ './views/FilePages/Trash'),
        meta: {
            requiresAuth: true
        },
    },
    {
        name: 'Settings',
        path: '/settings',
        component: () =>
            import(/* webpackChunkName: "settings" */ './views/Profile'),
        meta: {
            requiresAuth: true
        },
        children: [
            {
                name: 'Profile',
                path: 'profile',
                component: () =>
                    import(/* webpackChunkName: "profile" */ './views/User/Settings'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.profile')
                },
            },
            {
                name: 'Password',
                path: '/settings/password',
                component: () =>
                    import(/* webpackChunkName: "settings-password" */ './views/User/Password'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.settings_password')
                },
            },
            {
                name: 'Storage',
                path: '/settings/storage',
                component: () =>
                    import(/* webpackChunkName: "settings-storage" */ './views/User/Storage'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.settings_storage')
                },
            },
            {
                name: 'Invoice',
                path: '/settings/invoices',
                component: () =>
                    import(/* webpackChunkName: "settings-invoices" */ './views/User/Invoices'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.invoices')
                },
            },
            {
                name: 'Subscription',
                path: '/settings/subscription',
                component: () =>
                    import(/* webpackChunkName: "settings-subscription" */ './views/User/Subscription'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.subscription')
                },
            },
            {
                name: 'PaymentMethods',
                path: '/settings/payment-methods',
                component: () =>
                    import(/* webpackChunkName: "settings-payment-methods" */ './views/User/PaymentMethods'),
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.payment_methods')
                },
            },
            {
                name: 'CreatePaymentMethod',
                path: '/settings/create-payment-method',
                component: () =>
                    import(/* webpackChunkName: "settings-create-payment-methods" */ './views/User/CreatePaymentMethod'),
                meta: {
                    requiresAuth: true,
                    title: 'Create Payment Method'
                },
            },
        ]
    },
    {
        name: 'UpgradePlan',
        path: '/upgrade/plan',
        component: () =>
            import(/* webpackChunkName: "upgrade-plan" */ './views/Upgrade/UpgradePlan'),
        meta: {
            requiresAuth: true,
            title: i18n.t('routes_title.upgrade_plan')
        },
    },
    {
        name: 'UpgradeBilling',
        path: '/upgrade/billing',
        component: () =>
            import(/* webpackChunkName: "upgrade-billing" */ './views/Upgrade/UpgradeBilling'),
        meta: {
            requiresAuth: true,
            title: i18n.t('routes_title.upgrade_billing')
        },
    },
]
const routesMaintenance = [
    {
        name: 'Upgrade',
        path: '/upgrade',
        component: () =>
            import(/* webpackChunkName: "upgrade" */ './views/Upgrade'),
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'SetupWizard',
        path: '/install',
        component: () =>
            import(/* webpackChunkName: "setup-wizard" */ './views/SetupWizard'),
        meta: {
            requiresAuth: false
        },
        children: [
            {
                name: 'PurchaseCode',
                path: '/setup-wizard/purchase-code',
                component: () =>
                    import(/* webpackChunkName: "purchase-code" */ './views/SetupWizard/PurchaseCode'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'Database',
                path: '/setup-wizard/database',
                component: () =>
                    import(/* webpackChunkName: "database" */ './views/SetupWizard/Database'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'InstallationDisclaimer',
                path: '/setup-wizard/installation-disclaimer',
                component: () =>
                    import(/* webpackChunkName: "installation-disclaimer" */ './views/SetupWizard/InstallationDisclaimer'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'SubscriptionService',
                path: '/setup-wizard/subscription-service',
                component: () =>
                    import(/* webpackChunkName: "subscription-service" */ './views/SetupWizard/SubscriptionService'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'StripeCredentials',
                path: '/setup-wizard/stripe-credentials',
                component: () =>
                    import(/* webpackChunkName: "stripe-credentials" */ './views/SetupWizard/StripeCredentials'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'BillingsDetail',
                path: '/setup-wizard/stripe-billings',
                component: () =>
                    import(/* webpackChunkName: "billings-detail" */ './views/SetupWizard/BillingsDetail'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'SubscriptionPlans',
                path: '/setup-wizard/stripe-plans',
                component: () =>
                    import(/* webpackChunkName: "subscription-plans" */ './views/SetupWizard/SubscriptionPlans'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'EnvironmentSetup',
                path: '/setup-wizard/environment-setup',
                component: () =>
                    import(/* webpackChunkName: "environment-setup" */ './views/SetupWizard/EnvironmentSetup'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'AppSetup',
                path: '/setup-wizard/app-setup',
                component: () =>
                    import(/* webpackChunkName: "app-setup" */ './views/SetupWizard/AppSetup'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'AdminAccount',
                path: '/setup-wizard/admin-setup',
                component: () =>
                    import(/* webpackChunkName: "admin-account" */ './views/SetupWizard/AdminAccount'),
                meta: {
                    requiresAuth: false,
                },
            },
        ]
    },
]
const routesIndex = [
    {
        name: 'SaaSLandingPage',
        path: '/',
        component: () =>
            import(/* webpackChunkName: "landing-page" */ './views/index/SaaSLandingPage'),
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'DynamicPage',
        path: '/page/:slug',
        component: () =>
            import(/* webpackChunkName: "dynamic-page" */ './views/Index/DynamicPage'),
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'ContactUs',
        path: '/contact-us',
        component: () =>
            import(/* webpackChunkName: "contact-us" */ './views/Index/ContactUs'),
        meta: {
            requiresAuth: false
        },
    },
]

const router = new Router({
    mode: 'history',
    routes: [
        ...routesMaintenance,
        ...routesShared,
        ...routesAdmin,
        ...routesIndex,
        ...routesAuth,
        ...routesUser,
    ],
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return {x: 0, y: 0}
        }
    },
})

router.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.

        //if ( ! store.getters.isLogged) {
        if (false) {
            next({
                name: 'SignIn',
                query: {redirect: to.fullPath}
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
})

export default router