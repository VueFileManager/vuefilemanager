import axios from 'axios'
import {events} from '@/bus'
import i18n from '@/i18n/index.js'
import router from '@/router'

const defaultState = {
    authorized: undefined,
    permission: 'master', // master | editor | visitor,
    app: undefined,
}

const actions = {
    getAppData: ({commit, dispatch, getters}) => {

        axios
            .get(getters.api + '/user')
            .then((response) => {
                commit('RETRIEVE_APP_DATA', response.data)

            }).catch((error) => {

                if (error.response.status == 401) {
                    commit('SET_AUTHORIZED', false)
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
            .post(context.getters.api + '/add-to-favourites', {unique_id: folder.unique_id})
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
            .post(context.getters.api + '/remove-from-favourites', {unique_id: folder.unique_id})
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    getFolderTree: (context) => {
        return new Promise((resolve, reject) => {
            axios
                .get(context.getters.api + '/folder-tree')
                .then(response => {
                    resolve(response)

                    context.commit('UPDATE_FOLDER_TREE', response.data)
                })
                .catch((error) => {
                    reject(error)

                    // Show error message
                    events.$emit('alert:open', {
                        title: i18n.t('popup_error.title'),
                        message: i18n.t('popup_error.message'),
                    })
                })
        })


    },
}

const mutations = {
    RETRIEVE_APP_DATA(state, app) {
        state.app = app
    },
    UPDATE_FOLDER_TREE(state, tree) {
        state.app.folders = tree
    },
    SET_PERMISSION(state, role) {
        state.permission = role
    },
    SET_AUTHORIZED(state, data) {
        state.authorized = data
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
    REMOVE_ITEM_FROM_RECENT_UPLOAD(state, unique_id) {
        state.app.latest_uploads = state.app.latest_uploads.filter(file => file.unique_id !== unique_id)
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
