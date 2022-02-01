const routesShared = [
    {
        name: 'Shared',
        path: '/share/:token',
        component: () => import(/* webpackChunkName: "chunks/shared" */ '../views/Shared'),
        meta: {
            requiresAuth: false,
        },
        children: [
            {
                name: 'Public',
                path: '/share/:token/files/:id?',
                component: () => import(/* webpackChunkName: "chunks/shared/files" */ '../views/FileView/Public'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'SharedSingleFile',
                path: '/share/:token/file',
                component: () => import(/* webpackChunkName: "chunks/shared/single-file" */ '../views/Shared/SharedSingleFile'),
                meta: {
                    requiresAuth: false,
                },
            },
            {
                name: 'SharedAuthentication',
                path: '/share/:token/authenticate',
                component: () => import(/* webpackChunkName: "chunks/shared/authenticate" */ '../views/Shared/SharedAuthentication'),
                meta: {
                    requiresAuth: false,
                },
            },
        ],
    },
]

export default routesShared
