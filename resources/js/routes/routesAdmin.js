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
