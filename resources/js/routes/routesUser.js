const routesUser = [
    {
        name: 'User',
        path: '/user',
        component: () => import(/* webpackChunkName: "chunks/settings" */ '../views/Profile'),
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                name: 'Profile',
                path: '/user/profile',
                component: () => import(/* webpackChunkName: "chunks/profile" */ '../views/User/Settings'),
                meta: {
                    requiresAuth: true,
                    title: 'my_profile',
                },
            },
            {
                name: 'Password',
                path: '/user/settings/password',
                component: () => import(/* webpackChunkName: "chunks/settings-password" */ '../views/User/Password'),
                meta: {
                    requiresAuth: true,
                    title: 'change_password',
                },
            },
            {
                name: 'Storage',
                path: '/user/settings/storage',
                component: () => import(/* webpackChunkName: "chunks/settings-storage" */ '../views/User/Storage'),
                meta: {
                    requiresAuth: true,
                    title: 'storage',
                },
            },
            {
                name: 'Billing',
                path: '/user/settings/billing',
                component: () => import(/* webpackChunkName: "chunks/billing" */ '../views/User/Billing'),
                meta: {
                    requiresAuth: true,
                    title: 'billing',
                },
            },
        ],
    },
]

export default routesUser
