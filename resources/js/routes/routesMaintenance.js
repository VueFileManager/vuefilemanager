const routesMaintenance = [
    {
        name: 'SetupWizard',
        path: '/install',
        component: () => import(/* webpackChunkName: "chunks/setup-wizard" */ '../views/SetupWizard.vue'),
        meta: {
            requiresAuth: false,
        },
        children: [
            {
                name: 'StatusCheck',
                path: '/setup-wizard/status-check',
                component: () =>
                    import(/* webpackChunkName: "chunks/status-check" */ '../views/SetupWizard/StatusCheck.vue'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'Database',
                path: '/setup-wizard/database',
                component: () => import(/* webpackChunkName: "chunks/database" */ '../views/SetupWizard/Database.vue'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'EnvironmentSetup',
                path: '/setup-wizard/environment',
                component: () =>
                    import(/* webpackChunkName: "chunks/environment" */ '../views/SetupWizard/EnvironmentSetup.vue'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'AppSetup',
                path: '/setup-wizard/app-setup',
                component: () => import(/* webpackChunkName: "chunks/app-setup" */ '../views/SetupWizard/AppSetup.vue'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'AdminAccount',
                path: '/setup-wizard/admin-setup',
                component: () =>
                    import(/* webpackChunkName: "chunks/admin-account" */ '../views/SetupWizard/AdminAccount.vue'),
                meta: {
                    requiresAuth: false,
                },
            },
        ],
    },
]

export default routesMaintenance
