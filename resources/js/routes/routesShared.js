const routesShared = [
    {
        name: 'Shared',
        path: '/share/:token',
        component: () => import(/* webpackChunkName: "chunks/shared" */ '../views/Shared.vue'),
        meta: {
            requiresAuth: false,
        },
        children: [
            {
                name: 'Public',
                path: '/share/:token/files/:id?',
                component: () => import(/* webpackChunkName: "chunks/shared/browser" */ '../views/FileView/Public.vue'),
                meta: {
                    requiresAuth: false,
                },
            },
        ],
    },
    {
        name: 'SharedSingleFile',
        path: '/share/:token/file',
        component: () => import(/* webpackChunkName: "chunks/shared/single-file" */ '../views/SharedSingleFile.vue'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'SharedAuthentication',
        path: '/share/:token/authenticate',
        component: () => import(/* webpackChunkName: "chunks/shared/authenticate" */ '../views/SharedAuthentication.vue'),
        meta: {
            requiresAuth: false,
        },
    },
]

export default routesShared
