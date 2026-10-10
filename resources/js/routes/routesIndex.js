const routesIndex = [
    {
        name: 'Homepage',
        path: '/',
        component: () => import(/* webpackChunkName: "chunks/homepage" */ '../views/Frontpage/Homepage.vue'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'DynamicPage',
        path: '/page/:slug',
        component: () => import(/* webpackChunkName: "chunks/dynamic-page" */ '../views/Frontpage/DynamicPage.vue'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'ContactUs',
        path: '/contact-us',
        component: () => import(/* webpackChunkName: "chunks/contact-us" */ '../views/Frontpage/ContactUs.vue'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'Demo',
        path: '/demo',
        component: () => import(/* webpackChunkName: "chunks/demo" */ '../views/Demo.vue'),
        meta: {
            requiresAuth: false,
        },
    },
]

export default routesIndex
