const routesMaintenance = [
    {
        name: 'SetupWizard',
        path: '/install',
        component: () => import(/* webpackChunkName: "chunks/setup-wizard" */ '../views/SetupWizard'),
        meta: {
            requiresAuth: false,
        },
        children: [
            {
                name: 'StatusCheck',
                path: '/setup-wizard/status-check',
                component: () => import(/* webpackChunkName: "chunks/status-check" */ '../views/SetupWizard/StatusCheck'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'PurchaseCode',
                path: '/setup-wizard/purchase-code',
                component: () => import(/* webpackChunkName: "chunks/purchase-code" */ '../views/SetupWizard/PurchaseCode'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'Database',
                path: '/setup-wizard/database',
                component: () => import(/* webpackChunkName: "chunks/database" */ '../views/SetupWizard/Database'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'InstallationDisclaimer',
                path: '/setup-wizard/installation-disclaimer',
                component: () => import(/* webpackChunkName: "chunks/installation-disclaimer" */ '../views/SetupWizard/InstallationDisclaimer'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'SubscriptionService',
                path: '/setup-wizard/subscription-service',
                component: () => import(/* webpackChunkName: "chunks/subscription-service" */ '../views/SetupWizard/SubscriptionService'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'StripeCredentials',
                path: '/setup-wizard/stripe-credentials',
                component: () => import(/* webpackChunkName: "chunks/stripe-credentials" */ '../views/SetupWizard/StripeCredentials'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'BillingsDetail',
                path: '/setup-wizard/stripe-billings',
                component: () => import(/* webpackChunkName: "chunks/billings-detail" */ '../views/SetupWizard/BillingsDetail'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'SubscriptionPlans',
                path: '/setup-wizard/stripe-plans',
                component: () => import(/* webpackChunkName: "chunks/subscription-plans" */ '../views/SetupWizard/SubscriptionPlans'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'EnvironmentSetup',
                path: '/setup-wizard/environment-setup',
                component: () => import(/* webpackChunkName: "chunks/environment-setup" */ '../views/SetupWizard/EnvironmentSetup'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'AppSetup',
                path: '/setup-wizard/app-setup',
                component: () => import(/* webpackChunkName: "chunks/app-setup" */ '../views/SetupWizard/AppSetup'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'AdminAccount',
                path: '/setup-wizard/admin-setup',
                component: () => import(/* webpackChunkName: "chunks/admin-account" */ '../views/SetupWizard/AdminAccount'),
                meta: {
                    requiresAuth: false,
                },
            },
        ],
    },
]

export default routesMaintenance
