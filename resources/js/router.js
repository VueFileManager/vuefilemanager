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
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
    history: createWebHistory(),
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
            return { left: 0, top: 0 }
        }
    },
})

router.beforeEach((to) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.

        let isAuthenticated = store.getters.config ? store.getters.config.isAuthenticated : config.isAuthenticated

        if (!isAuthenticated) {
            return {
                name: 'SignIn',
                query: { redirect: to.fullPath },
            }
        }
    }
})

export default router
