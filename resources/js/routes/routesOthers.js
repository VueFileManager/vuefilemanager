const routesOthers = [
    {
        name: 'NotFound',
        path: '*',
        component: () => import(/* webpackChunkName: "chunks/not-found" */ '../views/NotFound'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'TemporaryUnavailable',
        path: '/temporary-unavailable',
        component: () => import(/* webpackChunkName: "chunks/temporary-unavailable" */ '../views/TemporaryUnavailable'),
        meta: {
            requiresAuth: false,
        },
    },
]

export default routesOthers
