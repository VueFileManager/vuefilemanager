import routesUploadRequest from './routes/routesUploadRequest'
import routesMaintenance from './routes/routesMaintenance'
import routesShared from './routes/routesShared'
import routesOthers from './routes/routesOthers'
import routesAdmin from './routes/routesAdmin'
import routesIndex from './routes/routesIndex'
import routesAuth from './routes/routesAuth'
import routesUser from './routes/routesUser'
import routesFile from './routes/routesFile'
import routesTeam from './routes/routesTeam'
import store from './store/index'
import Router from 'vue-router'
import Vue from 'vue'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    routes: [
        ...routesUploadRequest,
        ...routesMaintenance,
        ...routesShared,
        ...routesOthers,
        ...routesAdmin,
        ...routesIndex,
        ...routesAuth,
        ...routesUser,
        ...routesFile,
        ...routesTeam,
    ],
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    },
})

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.

        let isAuthenticated = store.getters.config ? store.getters.config.isAuthenticated : config.isAuthenticated

        if (!isAuthenticated) {
            next({
                name: 'SignIn',
                query: { redirect: to.fullPath },
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
})

export default router
