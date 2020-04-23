import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

import Index from './views/Auth/SignIn'
import SignUp from './views/Auth/SignUp'
import SharedContent from './views/Shared/SharedContent'
import VerifyByPassword from './views/Shared/VerifyByPassword'
import ForgottenPassword from './views/Auth/ForgottenPassword'
import CreateNewPassword from './views/Auth/CreateNewPassword'

import Files from './views/Files'
import Profile from './views/Profile'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    routes: [
        {
            name: 'SignIn',
            path: '/',
            component: Index,
            meta: {
                requiresAuth: false
            },
        },
        {
            name: 'SignUp',
            path: '/sign-up',
            component: SignUp,
            meta: {
                requiresAuth: false
            },
        },
        {
            name: 'ForgottenPassword',
            path: '/forgotten-password',
            component: ForgottenPassword,
            meta: {
                requiresAuth: false
            },
        },
        {
            name: 'CreateNewPassword',
            path: '/create-new-password',
            component: CreateNewPassword,
            meta: {
                requiresAuth: false
            },
        },
        {
            name: 'SharedContent',
            path: '/shared/:token',
            component: SharedContent,
            meta: {
                requiresAuth: false
            },
        },
        {
            name: 'Files',
            path: '/files',
            component: Files,
            meta: {
                requiresAuth: true
            },
        },
        {
            name: 'Profile',
            path: '/profile',
            component: Profile,
            meta: {
                requiresAuth: true
            },
        },
    ],
    scrollBehavior(to, from, savedPosition) {

        if (savedPosition) {
            return savedPosition
        } else {
            return {x: 0, y: 0}
        }
    },
})

router.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.

        //if ( ! store.getters.isLogged) {
        if ( false ) {
            next({
                name: 'SignIn',
                query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
})

export default router