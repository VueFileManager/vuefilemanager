import axios from 'axios'
import router from '../../router'
import Vue from 'vue'

const defaultState = {
    permission: 'master', // master | editor | visitor
    user: undefined,
}

const actions = {
    getAppData: ({ commit, getters, dispatch }) => {
        return new Promise((resolve, reject) => {
            axios
                .get(getters.api + '/user' + getters.sorting.URI)
                .then((response) => {
                    resolve(response)

                    commit('RETRIEVE_USER', response.data)
                    commit('UPDATE_NOTIFICATION_COUNT', response.data.data.relationships.unreadNotifications.data.length)

                    if (! getters.isBroadcasting && getters.config.broadcasting === 'pusher') {
                        dispatch('runConnection')
                    }
                })
                .catch((error) => {
                    reject(error)

                    // Redirect if unauthenticated
                    if ([401, 403].includes(error.response.status)) {
                        commit('SET_AUTHORIZED', false)
                    }
                })
        })
    },
    logOut: ({ commit, getters }) => {

        let popup = setTimeout(() => {
            commit('PROCESSING_POPUP', {
                title: 'Logging Out',
                message: 'Wait a second...',
            })
        }, 300)

        axios
            .post('/logout')
            .catch((error) => {
                if (error.response.status === 500) {
                    Vue.prototype.$isSomethingWrong()
                }
            })
            .finally(() => {
                clearTimeout(popup)

                commit('PROCESSING_POPUP', undefined)
                commit('DESTROY_DATA')
                commit('SET_AUTHORIZED', false)

                // Get redirect location
                let redirectLocation = getters.config.allowHomepage ? 'Homepage' : 'SignIn'

                // Redirect user
                router.push({name: redirectLocation})
            })
    },
    socialiteRedirect: ({ commit }, provider) => {
        axios
            .get(`/api/socialite/${provider}/redirect`)
            .then((response) => {
                if (response.data.data.url) {
                    window.location.href = response.data.data.url
                }
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    addToFavourites: (context, folder) => {
        let items = [folder]

        // If dont coming single folder get folders to add to favourites from clipboard
        if (!folder) items = context.getters.clipboard

        let itemsToFavourites = items.map((item) => {
            if (item.data.type === 'folder') {
                if (context.getters.user.data.relationships.favourites.find((folder) => folder.id === item.data.id))
                    return

                return item.data.id;
            }
        })

        // Check is favorites already don't include some of pushed folders
        let favouritesWidget = items.map((item) => {
            if (!context.getters.user.data.relationships.favourites.find((folder) => folder.data.id === item.id)) {
                return item
            }
        })

        // Add to favourites UI widget
        context.commit('ADD_TO_FAVOURITES', favouritesWidget)

        axios
            .post(context.getters.api + '/favourites', {
                ids: itemsToFavourites,
            })
            .catch(() => {
                Vue.prototype.$isSomethingWrong()
            })
    },
    removeFromFavourites: ({ commit, getters, dispatch }, folder) => {
        // Remove from storage
        commit('REMOVE_ITEM_FROM_FAVOURITES', folder)

        axios
            .post(getters.api + '/favourites/' + folder.data.id, {
                _method: 'delete',
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    readAllNotifications: ({ commit }) => {
        axios.post('/api/notifications/read')
            .then(() => {
                commit('UPDATE_NOTIFICATION_COUNT', 0)
            })
    },
    deleteAllNotifications: ({ commit }) => {
        axios.delete('/api/notifications')
            .then(() => {
                commit('FLUSH_NOTIFICATIONS')
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
}

const mutations = {
    CHANGE_TWO_FACTOR_AUTHENTICATION_STATE(state, condition) {
        state.user.data.attributes.two_factor_confirmed_at = condition
    },
    RETRIEVE_USER(state, user) {
        state.user = user
    },
    SET_PERMISSION(state, role) {
        state.permission = role
    },
    DESTROY_DATA(state) {
        state.currentFolder = undefined
        state.user = undefined
        state.app = undefined

        state.clipboard = []
    },
    ADD_TO_FAVOURITES(state, folder) {
        folder.forEach((item) => {
            state.user.data.relationships.favourites.push(item)
        })
    },
    UPDATE_FIRST_NAME(state, name) {
        state.user.data.relationships.settings.data.attributes.first_name = name
    },
    UPDATE_LAST_NAME(state, name) {
        state.user.data.relationships.settings.data.attributes.last_name = name
    },
    UPDATE_AVATAR(state, avatar) {
        state.user.data.attributes.avatar = {
            xs: avatar,
            sm: avatar,
            md: avatar,
        }
    },
    REMOVE_ITEM_FROM_FAVOURITES(state, item) {
        state.user.data.relationships.favourites.data = state.user.data.relationships.favourites.filter(
            (folder) => folder.data.id !== item.data.id
        )
    },
    UPDATE_NAME_IN_FAVOURITES(state, data) {
        state.user.data.relationships.favourites.find((folder) => {
            if (folder.id === data.id) {
                folder.name = data.name
            }
        })
    },
    FLUSH_NOTIFICATIONS(state) {
        state.user.data.relationships.readNotifications.data = []
        state.user.data.relationships.unreadNotifications.data = []
    },
    CLEAR_NOTIFICATION_ACTION_DATA(state, notificationId) {
        if (state.user.data.relationships.readNotifications.data.length) {
            state.user.data.relationships.readNotifications.data.map(notification => {
                if (notification.data.id === notificationId) {
                    notification.data.attributes.action = undefined
                }
            })
        }

        if (state.user.data.relationships.unreadNotifications.data.length) {
            state.user.data.relationships.unreadNotifications.data.map(notification => {
                if (notification.data.id === notificationId) {
                    notification.data.attributes.action = undefined
                }
            })
        }
    },
}

const getters = {
    userLimitationReason: (state) => state.user && state.user.data.meta.restrictions.reason,
    permission: (state) => state.permission,
    user: (state) => state.user,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations,
}
