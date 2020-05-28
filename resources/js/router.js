import Vue from 'vue'
import Router from 'vue-router'
import i18n from '@/i18n/index'

import Index from './views/Auth/SignIn'
import SignUp from './views/Auth/SignUp'
import SharedPage from './views/Shared/SharedPage'
import NotFoundShared from './views/Shared/NotFoundShared'
import ForgottenPassword from './views/Auth/ForgottenPassword'
import CreateNewPassword from './views/Auth/CreateNewPassword'

import Settings from './views/Settings'
import Profile from './views/User/Profile'
import Storage from './views/User/Storage'
import Trash from './views/FilePages/Trash'
import Files from './views/FilePages/Files'
import Password from './views/User/Password'
import SharedFiles from './views/FilePages/SharedFiles'

import MobileSettings from './views/Mobile/MobileSettings'

import Admin from './views/Admin'
import Users from './views/Admin/Users'

import User from './views/Admin/Users/User'
import UserCreate from './views/Admin/Users/UserCreate'
import UserDetail from './views/Admin/Users/UserTabs/UserDetail'
import UserDelete from './views/Admin/Users/UserTabs/UserDelete'
import UserStorage from './views/Admin/Users/UserTabs/UserStorage'
import UserPassword from './views/Admin/Users/UserTabs/UserPassword'

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
            name: 'SharedPage',
            path: '/shared/:token',
            component: SharedPage,
            meta: {
                requiresAuth: false
            },
        },
        {
            name: 'NotFoundShared',
            path: '/shared-not-found',
            component: NotFoundShared,
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
            name: 'SharedFiles',
            path: '/shared-files',
            component: SharedFiles,
            meta: {
                requiresAuth: true
            },
        },
        {
            name: 'Trash',
            path: '/trash',
            component: Trash,
            meta: {
                requiresAuth: true
            },
        },
        {
            name: 'Admin',
            path: '/admin',
            component: Admin,
            meta: {
                requiresAuth: true,
                title: 'Admin'
            },
            children: [
                {
                    name: 'Users',
                    path: '/admin/users',
                    component: Users,
                    meta: {
                        requiresAuth: true,
                        title: i18n.t('routes_title.users_list')
                    },
                },
                {
                    name: 'UserCreate',
                    path: '/admin/user/create',
                    component: UserCreate,
                    meta: {
                        requiresAuth: true,
                        title: i18n.t('routes_title.user_create')
                    },
                },
                {
                    name: 'User',
                    path: '/admin/user/:id',
                    component: User,
                    meta: {
                        requiresAuth: true,
                        title: i18n.t('routes_title.users_user')
                    },
                    children: [
                        {
                            name: 'UserDetail',
                            path: '/admin/user/:id/details',
                            component: UserDetail,
                            meta: {
                                requiresAuth: true,
                                title: i18n.t('routes_title.users_detail')
                            },
                        },
                        {
                            name: 'UserStorage',
                            path: '/admin/user/:id/storage',
                            component: UserStorage,
                            meta: {
                                requiresAuth: true,
                                title: i18n.t('routes_title.users_storage_usage')
                            },
                        },
                        {
                            name: 'UserPassword',
                            path: '/admin/user/:id/password',
                            component: UserPassword,
                            meta: {
                                requiresAuth: true,
                                title: i18n.t('routes_title.users_password')
                            },
                        },
                        {
                            name: 'UserDelete',
                            path: '/admin/user/:id/delete',
                            component: UserDelete,
                            meta: {
                                requiresAuth: true,
                                title: i18n.t('routes_title.users_delete')
                            },
                        },
                    ]
                },
            ]
        },
        {
            name: 'Settings',
            path: '/settings',
            component: Settings,
            meta: {
                requiresAuth: true
            },
            children: [
                {
                    name: 'Profile',
                    path: 'profile',
                    component: Profile,
                    meta: {
                        requiresAuth: true,
                        title: i18n.t('routes_title.profile')
                    },
                },
                {
                    name: 'Password',
                    path: '/settings/password',
                    component: Password,
                    meta: {
                        requiresAuth: true,
                        title: i18n.t('routes_title.settings_password')
                    },
                },
                {
                    name: 'Storage',
                    path: '/settings/storage',
                    component: Storage,
                    meta: {
                        requiresAuth: true,
                        title: i18n.t('routes_title.settings_storage')
                    },
                },
            ]
        },
        {
            name: 'MobileSettings',
            path: '/settings-mobile',
            component: MobileSettings,
            meta: {
                requiresAuth: true,
                title: i18n.t('routes_title.settings_mobile')
            },
        }
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