import Vue from 'vue'
import Router from 'vue-router'

import Index from './views/Index'
import SignUp from './views/SignUp'
import ForgottenPassword from './views/ForgottenPassword'
//import ForgottenPassword from './views/ForgottenPassword'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    routes: [
        {
            name: 'index',
            path: '/',
            component: Index,
        },
        {
            name: 'SignUp',
            path: '/sign-up',
            component: SignUp,
        },
        {
            name: 'ForgottenPassword',
            path: '/forgotten-password',
            component: ForgottenPassword,
        },
        /*{
            name: 'CreateNewPassword',
            path: '/create-new-password',
            component: CreateNewPassword,
        },*/
    ],
    scrollBehavior(to, from, savedPosition) {

        if (savedPosition) {
            return savedPosition
        } else {
            return {x: 0, y: 0}
        }
    }
})

export default router