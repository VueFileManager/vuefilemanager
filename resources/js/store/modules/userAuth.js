import axios from 'axios'
import {events} from '@/bus'
import i18n from '@/i18n/index.js'
import router from '@/router'

const defaultState = {
    authorized: undefined,
    permission: 'master', // master | editor | visitor
    app: undefined,
}

const actions = {
    getAppData: ({commit, getters}) => {

        axios
            .get(getters.api + '/user')
            .then((response) => {
                commit('RETRIEVE_APP_DATA', response.data)

            }).catch((error) => {

                // Redirect if unauthenticated
                if ([401, 403].includes(error.response.status)) {

                    commit('SET_AUTHORIZED', false)
                    router.push({name: 'SignIn'})
                }
            }
        )
    },
    logOut: ({getters, commit}) => {
        axios
            .get(getters.api + '/logout')
            .then(() => {

                // Commit Remove Access Token from vuex storage
                commit('DESTROY_DATA')

                router.push({name: 'SignIn'})
            })
    },
    addToFavourites: (context, folder) => {

        // Add to storage
        context.commit('ADD_TO_FAVOURITES', folder)

        axios
            .post(context.getters.api + '/folders/favourites', {
                unique_id: folder.unique_id
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    removeFromFavourites: (context, folder) => {

        // Remove from storage
        context.commit('REMOVE_ITEM_FROM_FAVOURITES', folder)

        axios
            .delete(context.getters.api + '/folders/favourites/' + folder.unique_id)
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
}

const mutations = {
    RETRIEVE_APP_DATA(state, app) {
        state.app = app
    },
    SET_PERMISSION(state, role) {
        state.permission = role
    },
    DESTROY_DATA(state) {
        state.authorized = false
        state.app = undefined
    },
    ADD_TO_FAVOURITES(state, folder) {
        state.app.favourites.push({
            unique_id: folder.unique_id,
            name: folder.name,
            type: folder.type,
        })
    },
    UPDATE_NAME(state, name) {
        state.app.user.name = name
    },
    UPDATE_AVATAR(state, avatar) {
        state.app.user.avatar = avatar
    },
    UPDATE_RECENT_UPLOAD(state, file) {
        // Remove last file from altest uploads
        if (state.app.latest_uploads.length === 7) state.app.latest_uploads.pop()

        // Add new file to latest uploads
        state.app.latest_uploads.unshift(file)
    },
    REMOVE_ITEM_FROM_FAVOURITES(state, item) {
        state.app.favourites = state.app.favourites.filter(folder => folder.unique_id !== item.unique_id)
    },
    UPDATE_NAME_IN_FAVOURITES(state, data) {
        state.app.favourites.find(folder => {
            if (folder.unique_id == data.unique_id) folder.name = data.name
        })
    }
}

const getters = {
    permission: state => state.permission,
    isGuest: state => ! state.authorized,
    isLogged: state => state.authorized,
    app: state => state.app,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations
}
