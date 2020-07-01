import Vue from 'vue'
import Router from 'vue-router'
import i18n from '@/i18n/index'

import Index from './views/Auth/SignIn'
import SignUp from './views/Auth/SignUp'
import SharedPage from './views/Shared/SharedPage'
import NotFoundShared from './views/Shared/NotFoundShared'
import ForgottenPassword from './views/Auth/ForgottenPassword'
import CreateNewPassword from './views/Auth/CreateNewPassword'

import Settings from './views/Profile'
import Storage from './views/User/Storage'
import Profile from './views/User/Settings'
import Invoice from './views/User/Invoices'
import Password from './views/User/Password'
import Subscription from './views/User/Subscription'
import PaymentMethods from './views/User/PaymentMethods'

import Trash from './views/FilePages/Trash'
import Files from './views/FilePages/Files'
import SharedFiles from './views/FilePages/SharedFiles'

import UpgradePlan from './views/Upgrade/UpgradePlan'
import UpgradeBilling from './views/Upgrade/UpgradeBilling'

import AdminMobileMenu from './views/Mobile/AdminMobileMenu'

import Admin from './views/Admin'
import Invoices from './views/Admin/Invoices'

// Payment Methods
import Gateways from './views/Admin/Gateways'
import Gateway from './views/Admin/Gateways/Gateway'
import GatewaySettings from './views/Admin/Gateways/GatewayTabs/GatewaySettings'
import GatewayTransactions from './views/Admin/Gateways/GatewayTabs/GatewayTransactions'

// Plans
import Plans from './views/Admin/Plans'
import Plan from './views/Admin/Plans/Plan'
import PlanCreate from './views/Admin/Plans/PlanCreate'
import PlanDelete from './views/Admin/Plans/PlanTabs/PlanDelete'
import PlanSettings from './views/Admin/Plans/PlanTabs/PlanSettings'
import PlanSubscribers from './views/Admin/Plans/PlanTabs/PlanSubscribers'

// Users
import Users from './views/Admin/Users'
import User from './views/Admin/Users/User'
import UserCreate from './views/Admin/Users/UserCreate'
import UserDetail from './views/Admin/Users/UserTabs/UserDetail'
import UserDelete from './views/Admin/Users/UserTabs/UserDelete'
import UserStorage from './views/Admin/Users/UserTabs/UserStorage'
import UserPassword from './views/Admin/Users/UserTabs/UserPassword'
import UserInvoices from './views/Admin/Users/UserTabs/UserInvoices'
import UserSubscription from './views/Admin/Users/UserTabs/UserSubscription'

// Setup Wizard
import SetupWizard from './views/SetupWizard'
import Database from './views/SetupWizard/Database'
import AppSetup from './views/SetupWizard/AppSetup'
import PurchaseCode from './views/SetupWizard/PurchaseCode'
import AdminAccount from './views/SetupWizard/AdminAccount'
import BillingsDetail from './views/SetupWizard/BillingsDetail'
import EnvironmentSetup from './views/SetupWizard/EnvironmentSetup'
import StripeCredentials from './views/SetupWizard/StripeCredentials'
import SubscriptionPlans from './views/SetupWizard/SubscriptionPlans'
import SubscriptionService from './views/SetupWizard/SubscriptionService'

Vue.use(Router)

const routesAdmin = [
    {
        name: 'Admin',
        path: '/admin',
        component: Admin,
        meta: {
            requiresAuth: true,
            title: 'Admin'
        },
        children: [

            // List Pages
            {
                name: 'Gateways',
                path: '/admin/payment-methods',
                component: Gateways,
                meta: {
                    requiresAuth: true,
                    title: 'Payment Methods'
                },
            },
            {
                name: 'Invoices',
                path: '/admin/invoices',
                component: Invoices,
                meta: {
                    requiresAuth: true,
                    title: 'Invoices'
                },
            },
            {
                name: 'Plans',
                path: '/admin/plans',
                component: Plans,
                meta: {
                    requiresAuth: true,
                    title: 'Pricing Plans'
                },
            },
            {
                name: 'Users',
                path: '/admin/users',
                component: Users,
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.users_list')
                },
            },

            // Create Pages
            {
                name: 'UserCreate',
                path: '/admin/user/create',
                component: UserCreate,
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.user_create')
                },
            },
            {
                name: 'PlanCreate',
                path: '/admin/plan/create',
                component: PlanCreate,
                meta: {
                    requiresAuth: true,
                    title: 'Create Plan'
                },
            },

            // Single pages
            {
                name: 'User',
                path: '/admin/user/:id',
                component: User,
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.users_user')
                },
                children: [
                    {
                        name: 'UserDetail',
                        path: '/admin/user/:id/details',
                        component: UserDetail,
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.users_detail')
                        },
                    },
                    {
                        name: 'UserStorage',
                        path: '/admin/user/:id/storage',
                        component: UserStorage,
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.users_storage_usage')
                        },
                    },
                    {
                        name: 'UserSubscription',
                        path: '/admin/user/:id/subscription',
                        component: UserSubscription,
                        meta: {
                            requiresAuth: true,
                            title: 'Subscription'
                        },
                    },
                    {
                        name: 'UserInvoices',
                        path: '/admin/user/:id/invoices',
                        component: UserInvoices,
                        meta: {
                            requiresAuth: true,
                            title: 'Invoices'
                        },
                    },
                    {
                        name: 'UserPassword',
                        path: '/admin/user/:id/password',
                        component: UserPassword,
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.users_password')
                        },
                    },
                    {
                        name: 'UserDelete',
                        path: '/admin/user/:id/delete',
                        component: UserDelete,
                        meta: {
                            requiresAuth: true,
                            title: i18n.t('routes_title.users_delete')
                        },
                    },
                ]
            },
            {
                name: 'Gateway',
                path: '/admin/payment-method/:slug',
                component: Gateway,
                meta: {
                    requiresAuth: true,
                    title: 'Payment Method'
                },
                children: [
                    {
                        name: 'GatewayTransactions',
                        path: '/admin/payment-methods/:slug/transactions',
                        component: GatewayTransactions,
                        meta: {
                            requiresAuth: true,
                            title: 'Transactions'
                        },
                    },
                    {
                        name: 'GatewaySettings',
                        path: '/admin/payment-methods/:slug/settings',
                        component: GatewaySettings,
                        meta: {
                            requiresAuth: true,
                            title: 'Settings',
                        },
                    },
                ]
            },
            {
                name: 'Plan',
                path: '/admin/plan/:id',
                component: Plan,
                meta: {
                    requiresAuth: true,
                    title: 'Plan'
                },
                children: [
                    {
                        name: 'PlanSubscribers',
                        path: '/admin/plan/:id/subscribers',
                        component: PlanSubscribers,
                        meta: {
                            requiresAuth: true,
                            title: 'Plan Subscribers'
                        },
                    },
                    {
                        name: 'PlanSettings',
                        path: '/admin/plan/:id/settings',
                        component: PlanSettings,
                        meta: {
                            requiresAuth: true,
                            title: 'Plan Settings',
                        },
                    },
                    {
                        name: 'PlanDelete',
                        path: '/admin/plan/:id/delete',
                        component: PlanDelete,
                        meta: {
                            requiresAuth: true,
                            title: 'Plan Delete',
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
]
const routesShared = [
    {
        name: 'SharedPage',
        path: '/shared/:token',
        component: SharedPage,
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'NotFoundShared',
        path: '/shared-not-found',
        component: NotFoundShared,
        meta: {
            requiresAuth: false
        },
    },
]
const routesAuth = [
    {
        name: 'SignIn',
        path: '/',
        component: Index,
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'SignUp',
        path: '/sign-up',
        component: SignUp,
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'ForgottenPassword',
        path: '/forgotten-password',
        component: ForgottenPassword,
        meta: {
            requiresAuth: false
        },
    },
    {
        name: 'CreateNewPassword',
        path: '/create-new-password',
        component: CreateNewPassword,
        meta: {
            requiresAuth: false
        },
    },
]
const routesUser = [
    {
        name: 'Files',
        path: '/files',
        component: Files,
        meta: {
            requiresAuth: true
        },
    },
    {
        name: 'SharedFiles',
        path: '/shared-files',
        component: SharedFiles,
        meta: {
            requiresAuth: true
        },
    },
    {
        name: 'Trash',
        path: '/trash',
        component: Trash,
        meta: {
            requiresAuth: true
        },
    },
    {
        name: 'Settings',
        path: '/settings',
        component: Settings,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                name: 'Profile',
                path: 'profile',
                component: Profile,
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.profile')
                },
            },
            {
                name: 'Password',
                path: '/settings/password',
                component: Password,
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.settings_password')
                },
            },
            {
                name: 'Storage',
                path: '/settings/storage',
                component: Storage,
                meta: {
                    requiresAuth: true,
                    title: i18n.t('routes_title.settings_storage')
                },
            },
            {
                name: 'Invoice',
                path: '/settings/invoices',
                component: Invoice,
                meta: {
                    requiresAuth: true,
                    title: 'Invoices'
                },
            },
            {
                name: 'Subscription',
                path: '/settings/subscription',
                component: Subscription,
                meta: {
                    requiresAuth: true,
                    title: 'Subscription'
                },
            },
            {
                name: 'PaymentMethods',
                path: '/settings/payment-cards',
                component: PaymentMethods,
                meta: {
                    requiresAuth: true,
                    title: 'Payment Cards'
                },
            },
        ]
    },
    {
        name: 'UpgradePlan',
        path: '/upgrade/plan',
        component: UpgradePlan,
        meta: {
            requiresAuth: true,
            title: 'Upgrade Plan'
        },
    },
    {
        name: 'UpgradeBilling',
        path: '/upgrade/billing',
        component: UpgradeBilling,
        meta: {
            requiresAuth: true,
            title: 'Upgrade Billing'
        },
    },
]
const routesMaintenance = [
    {
        name: 'SetupWizard',
        path: '/setup-wizard',
        component: SetupWizard,
        meta: {
            requiresAuth: false
        },
        children: [
            {
                name: 'PurchaseCode',
                path: '/setup-wizard/purchase-code',
                component: PurchaseCode,
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'Database',
                path: '/setup-wizard/database',
                component: Database,
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'SubscriptionService',
                path: '/setup-wizard/subscription-service',
                component: SubscriptionService,
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'StripeCredentials',
                path: '/setup-wizard/stripe-credentials',
                component: StripeCredentials,
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'BillingsDetail',
                path: '/setup-wizard/stripe-billings',
                component: BillingsDetail,
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'SubscriptionPlans',
                path: '/setup-wizard/stripe-plans',
                component: SubscriptionPlans,
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'EnvironmentSetup',
                path: '/setup-wizard/environment-setup',
                component: EnvironmentSetup,
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'AppSetup',
                path: '/setup-wizard/app-setup',
                component: AppSetup,
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'AdminAccount',
                path: '/setup-wizard/admin-setup',
                component: AdminAccount,
                meta: {
                    requiresAuth: false,
                },
            },
        ]
    },
]

const router = new Router({
    mode: 'history',
    routes: [
        ...routesMaintenance,
        ...routesShared,
        ...routesAdmin,
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