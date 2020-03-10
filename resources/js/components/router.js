import Router from 'vue-router'
import Vue from 'vue'

import ProfileSettings from './views/ProfileSettings'

import store from './store/index'

Vue.use(Router)

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/finish-your-registration',
            name: 'finish-your-registration',
            component: FinishRegistration,
            meta: {
                requiresAuth: true,
            }
        },
        {
            path: '/admin/login',
            name: 'LogIn',
            component: Login,
            meta: {
                requiresVisitor: true,
            }
        },
        {
            path: '/admin/logout',
            name: 'LogOut',
            component: LogOut,
            meta: {
                requiresAuth: false
            }
        },
        {
            path: '/admin/services',
            name: 'Ads',
            component: Services,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/add-service',
            name: 'Add Service',
            component: ThumbnailPicker,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/edit-service/:id',
            name: 'Edit Service',
            component: EditService,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/trips',
            name: 'Trips',
            component: Trips,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/add-trip',
            name: 'Add Trip',
            component: AddTrip,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/edit-trip/:id',
            name: 'Edit Trip',
            component: EditTrip,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/hotels',
            name: 'Hotels',
            component: Hotels,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/add-hotel',
            name: 'Add Hotel',
            component: AddHotel,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/edit-hotel/:id',
            name: 'Edit Hotel',
            component: EditHotel,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/gallery',
            name: 'Gallery',
            component: Gallery,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/add-gallery/',
            name: 'Add Gallery',
            component: AddGallery,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/edit-gallery/:id',
            name: 'Edit Gallery',
            component: EditGallery,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/team',
            name: 'Team',
            component: Team,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/add-member',
            name: 'Add Team Member',
            component: AddTeamMember,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/edit-member/:id',
            name: 'Edit Team Member',
            component: EditTeamMember,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/references',
            name: 'References',
            component: References,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/orders',
            name: 'Orders',
            component: Orders,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/order/:id',
            name: 'Order',
            component: Order,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/payouts',
            name: 'Payouts',
            component: Payouts,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/settings',
            name: 'Settings',
            component: Settings,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/admin/profile-settings',
            name: 'Profile Settings',
            component: ProfileSettings,
            meta: {
                requiresAuth: true
            }
        },
    ],
    scrollBehavior(to, from, savedPosition) {

        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    }
})

router.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!store.getters.isLogged) {
            next({
                path: '/admin/login',
                query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (store.getters.isLogged) {
            next({
                path: '/admin/services',
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

