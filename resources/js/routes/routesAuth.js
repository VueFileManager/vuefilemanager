const routesAuth = [
    {
        name: 'SuccessfullyVerified',
        path: '/successfully-verified',
        component: () =>
            import(/* webpackChunkName: "chunks/successfully-email-verified" */ '../views/Auth/SuccessfullyEmailVerified'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'SuccessfullySend',
        path: '/successfully-send',
        component: () => import(/* webpackChunkName: "chunks/successfully-email-send" */ '../views/Auth/SuccessfullySendEmail'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'SignIn',
        path: '/sign-in',
        component: () => import(/* webpackChunkName: "chunks/sign-in" */ '../views/Auth/SignIn'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'SignUp',
        path: '/sign-up',
        component: () => import(/* webpackChunkName: "chunks/sign-up" */ '../views/Auth/SignUp'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'ForgottenPassword',
        path: '/forgotten-password',
        component: () => import(/* webpackChunkName: "chunks/forgotten-password" */ '../views/Auth/ForgottenPassword'),
        meta: {
            requiresAuth: false,
        },
    },
    {
        name: 'CreateNewPassword',
        path: '/create-new-password',
        component: () => import(/* webpackChunkName: "chunks/create-new-password" */ '../views/Auth/CreateNewPassword'),
        meta: {
            requiresAuth: false,
        },
    },
]

export default routesAuth
