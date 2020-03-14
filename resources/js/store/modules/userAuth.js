import axios from 'axios'
import {events} from '@/bus'

const defaultState = {
    authorized: undefined,
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
                commit('SET_CURRENT_VIEW', 'files')
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
                    title: 'Whooops, something went wrong :(',
                    message:
                        "Something went wrong and we can't continue. Please contact us."
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
                    title: 'Whooops, something went wrong :(',
                    message:
                        "Something went wrong and we can't continue. Please contact us."
                })
            })
    },
}
const mutations = {
    RETRIEVE_APP_DATA(state, app) {
        state.app = app
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
    isLogged: state => state.authorized,
    isGuest: state => ! state.authorized,
    app: state => state.app,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations
}
