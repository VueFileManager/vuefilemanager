const routesIndex = [
    {
        name: 'Demo',
        path: '/demo',
        component: () => import(/* webpackChunkName: "chunks/demo" */ '../views/Demo'),
        meta: {
            requiresAuth: false,
        },
    },
]

export default routesIndex
