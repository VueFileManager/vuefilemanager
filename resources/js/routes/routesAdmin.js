const routesAdmin = [
    {
        name: 'Admin',
        path: '/admin',
        component: () => import(/* webpackChunkName: "chunks/admin" */ '../views/Admin'),
        meta: {
            requiresAuth: true,
            title: 'Admin',
        },
        children: [
            {
                name: 'Dashboard',
                path: '/admin/dashboard',
                component: () => import(/* webpackChunkName: "chunks/dashboard" */ '../views/Admin/Dashboard'),
                meta: {
                    requiresAuth: true,
                    title: 'dashboard',
                },
            },
            {
                name: 'Invoices',
                path: '/admin/invoices',
                component: () => import(/* webpackChunkName: "chunks/invoices" */ '../views/Admin/Invoices'),
                meta: {
                    requiresAuth: true,
                    title: 'transactions',
                },
            },
            {
                name: 'Subscriptions',
                path: '/admin/subscriptions',
                component: () => import(/* webpackChunkName: "chunks/subscriptions" */ '../views/Admin/Subscriptions'),
                meta: {
                    requiresAuth: true,
                    title: 'subscriptions',
                },
            },
            {
                name: 'Pages',
                path: '/admin/pages',
                component: () => import(/* webpackChunkName: "chunks/pages" */ '../views/Admin/Pages'),
                meta: {
                    requiresAuth: true,
                    title: 'pages',
                },
            },
            {
                name: 'PageEdit',
                path: '/admin/pages/:slug',
                component: () => import(/* webpackChunkName: "chunks/page-edit" */ '../views/Admin/Pages/PageEdit'),
                meta: {
                    requiresAuth: true,
                    title: 'edit_page',
                },
            },
            {
                name: 'Plans',
                path: '/admin/plans',
                component: () => import(/* webpackChunkName: "chunks/plans" */ '../views/Admin/Plans'),
                meta: {
                    requiresAuth: true,
                    title: 'pricing_plans',
                },
            },
            {
                name: 'Users',
                path: '/admin/users',
                component: () => import(/* webpackChunkName: "chunks/users" */ '../views/Admin/Users'),
                meta: {
                    requiresAuth: true,
                    title: 'routes_title.users_list',
                },
            },
            {
                name: 'UserCreate',
                path: '/admin/user/create',
                component: () => import(/* webpackChunkName: "chunks/user-create" */ '../views/Admin/Users/UserCreate'),
                meta: {
                    requiresAuth: true,
                    title: 'create_user',
                },
            },
            {
                name: 'CreateFixedPlan',
                path: '/admin/plan/create/fixed',
                component: () =>
                    import(
                        /* webpackChunkName: "chunks/plan-create/fixed" */ '../views/Admin/Plans/Create/CreateFixedPlan'
                    ),
                meta: {
                    requiresAuth: true,
                    title: 'create_plan',
                },
            },
            {
                name: 'CreateMeteredPlan',
                path: '/admin/plan/create/metered',
                component: () =>
                    import(
                        /* webpackChunkName: "chunks/plan-create/metered" */ '../views/Admin/Plans/Create/CreateMeteredPlan'
                    ),
                meta: {
                    requiresAuth: true,
                    title: 'create_plan',
                },
            },
            {
                path: '/admin/user/:id',
                component: () => import(/* webpackChunkName: "chunks/user" */ '../views/Admin/Users/User'),
                meta: {
                    requiresAuth: true,
                    title: 'routes_title.users_user',
                },
                children: [
                    {
                        name: 'UserDetail',
                        path: '/admin/user/:id/details',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/user-detail" */ '../views/Admin/Users/UserTabs/UserDetail'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'detail',
                        },
                    },
                    {
                        name: 'UserStorage',
                        path: '/admin/user/:id/storage',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/user-storage" */ '../views/Admin/Users/UserTabs/UserStorage'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'routes_title.users_storage_usage',
                        },
                    },
                    {
                        name: 'UserSubscription',
                        path: '/admin/user/:id/subscription',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/user-subscription" */ '../views/Admin/Users/UserTabs/UserSubscription'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'subscription',
                        },
                    },
                    {
                        name: 'UserPassword',
                        path: '/admin/user/:id/password',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/user-password" */ '../views/Admin/Users/UserTabs/UserPassword'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'routes_title.users_password',
                        },
                    },
                    {
                        name: 'UserDelete',
                        path: '/admin/user/:id/delete',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/user-delete" */ '../views/Admin/Users/UserTabs/UserDelete'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'delete_user',
                        },
                    },
                ],
            },
            {
                name: 'PlanFixed',
                path: '/admin/plan/:id',
                component: () => import(/* webpackChunkName: "chunks/plan" */ '../views/Admin/Plans/FixedPlan'),
                meta: {
                    requiresAuth: true,
                    title: 'plan',
                },
                children: [
                    {
                        name: 'PlanFixedSubscribers',
                        path: '/admin/plan/:id/fixed/subscribers',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/plan-subscribers" */ '../views/Admin/Plans/Tabs/PlanSubscribers'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'subscribers',
                        },
                    },
                    {
                        name: 'PlanFixedSettings',
                        path: '/admin/plan/:id/fixed/settings',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/plan-settings" */ '../views/Admin/Plans/Tabs/PlanFixedSettings'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'plan_settings',
                        },
                    },
                    {
                        name: 'PlanFixedDelete',
                        path: '/admin/plan/:id/fixed/delete',
                        component: () =>
                            import(/* webpackChunkName: "chunks/plan-delete" */ '../views/Admin/Plans/Tabs/PlanDelete'),
                        meta: {
                            requiresAuth: true,
                            title: 'plan_delete',
                        },
                    },
                ],
            },
            {
                name: 'PlanMetered',
                path: '/admin/plan/:id',
                component: () => import(/* webpackChunkName: "chunks/plan" */ '../views/Admin/Plans/MeteredPlan'),
                meta: {
                    requiresAuth: true,
                    title: 'plan',
                },
                children: [
                    {
                        name: 'PlanMeteredSubscribers',
                        path: '/admin/plan/:id/metered/subscribers',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/plan-subscribers" */ '../views/Admin/Plans/Tabs/PlanSubscribers'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'subscribers',
                        },
                    },
                    {
                        name: 'PlanMeteredSettings',
                        path: '/admin/plan/:id/metered/settings',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/plan-settings" */ '../views/Admin/Plans/Tabs/PlanMeteredSettings'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'plan_settings',
                        },
                    },
                    {
                        name: 'PlanMeteredDelete',
                        path: '/admin/plan/:id/metered/delete',
                        component: () =>
                            import(/* webpackChunkName: "chunks/plan-delete" */ '../views/Admin/Plans/Tabs/PlanDelete'),
                        meta: {
                            requiresAuth: true,
                            title: 'plan_delete',
                        },
                    },
                ],
            },
            {
                name: 'PaymentSettings',
                path: '/admin/payments',
                component: () =>
                    import(/* webpackChunkName: "chunks/payments" */ '../views/Admin/PaymentSettings/PaymentSettings'),
                meta: {
                    requiresAuth: true,
                    title: 'Payment Settings',
                },
                children: [
                    {
                        name: 'AppBillings',
                        path: '/admin/payments/billings',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/payments/billings" */ '../views/Admin/PaymentSettings/PaymentSettingsTab/Billings'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'billings',
                        },
                    },
                    {
                        name: 'AppPayments',
                        path: '/admin/payments/payments',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/payments/settings" */ '../views/Admin/PaymentSettings/PaymentSettingsTab/Payments'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'payments',
                        },
                    },
                ],
            },
            {
                name: 'AppSettings',
                path: '/admin/settings',
                component: () =>
                    import(/* webpackChunkName: "chunks/app-settings" */ '../views/Admin/Settings/AppSettings'),
                meta: {
                    requiresAuth: true,
                    title: 'settings',
                },
                children: [
                    {
                        name: 'AppAppearance',
                        path: '/admin/settings/appearance',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/app-appearance" */ '../views/Admin/Settings/AppSettingsTabs/Appearance'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'appearance',
                        },
                    },
                    {
                        name: 'AppIndex',
                        path: '/admin/settings/index',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/app-index" */ '../views/Admin/Settings/AppSettingsTabs/Index'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'Index',
                        },
                    },
                    {
                        name: 'AppEnvironment',
                        path: '/admin/settings/environment',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/app-environment" */ '../views/Admin/Settings/AppSettingsTabs/Environment'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'environment',
                        },
                    },
                    {
                        name: 'AppOthers',
                        path: '/admin/settings/others',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/app-others" */ '../views/Admin/Settings/AppSettingsTabs/Others'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'others',
                        },
                    },
                    {
                        name: 'AppSignInUp',
                        path: '/admin/settings/sign-in',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/app-sign-in-out" */ '../views/Admin/Settings/AppSettingsTabs/SignInUp'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'Sign In/Up',
                        },
                    },
                    {
                        name: 'AppAdsense',
                        path: '/admin/settings/adsense',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/app-adsense" */ '../views/Admin/Settings/AppSettingsTabs/Adsense'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'Adsense',
                        },
                    },
                    {
                        name: 'AppServer',
                        path: '/admin/settings/server',
                        component: () =>
                            import(
                                /* webpackChunkName: "chunks/app-server" */ '../views/Admin/Settings/AppSettingsTabs/Server'
                            ),
                        meta: {
                            requiresAuth: true,
                            title: 'Server',
                        },
                    },
                ],
            },
            {
                name: 'Language',
                path: '/admin/language',
                component: () =>
                    import(/* webpackChunkName: "chunks/app-language" */ '../views/Admin/Languages/Language'),
                meta: {
                    requiresAuth: true,
                    title: 'Language Editor',
                },
            },
        ],
    },
]

export default routesAdmin
