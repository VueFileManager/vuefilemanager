const routesOthers = [
    {
        name: 'NotFound',
        path: '/:pathMatch(.*)*',
        component: () => import(/* webpackChunkName: "chunks/not-found" */ '../views/NotFound.vue'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'TemporaryUnavailable',
        path: '/temporary-unavailable',
        component: () => import(/* webpackChunkName: "chunks/temporary-unavailable" */ '../views/TemporaryUnavailable.vue'),
        meta: {
            requiresAuth: false,
        },
    },
]

export default routesOthers
