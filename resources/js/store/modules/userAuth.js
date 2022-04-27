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
    logOut: ({ commit }) => {

        let popup = setTimeout(() => {
            commit('PROCESSING_POPUP', {
                title: 'Logging Out',
                message: 'Wait a second...',
            })
        }, 300)

        axios
            .post('/logout')
            .catch(() => this.$isSomethingWrong())
            .finally(() => {
                clearTimeout(popup)

                commit('DESTROY_DATA')
                commit('SET_AUTHORIZED', false)

                router.push({name: 'Homepage'})
            })
    },
    addToFavourites: (context, folder) => {
        let addFavourites = []
        let items = [folder]

        // If dont coming single folder get folders to add to favourites from clipboard
        if (!folder) items = context.getters.clipboard

        items.forEach((item) => {
            if (item.data.type === 'folder') {
                if (
                    context.getters.user.data.relationships.favourites.data.find((folder) => folder.id === item.data.id)
                )
                    return

                addFavourites.push({ id: item.data.id })
            }
        })

        // If dont coming single folder clear the selected folders in clipboard
        if (!folder) {
            context.commit('CLIPBOARD_CLEAR')
        }

        let pushToFavorites = []

        // Check is favorites already don't include some of pushed folders
        items.map((item) => {
            if (!context.getters.user.data.relationships.favourites.data.find((folder) => folder.data.id === item.id)) {
                pushToFavorites.push(item)
            }
        })

        // Add to storage
        context.commit('ADD_TO_FAVOURITES', pushToFavorites)

        axios
            .post(context.getters.api + '/folders/favourites', {
                folders: addFavourites,
            })
            .catch(() => {
                Vue.prototype.$isSomethingWrong()
            })
    },
    removeFromFavourites: ({ commit, getters, dispatch }, folder) => {
        // Remove from storage
        commit('REMOVE_ITEM_FROM_FAVOURITES', folder)

        axios
            .post(getters.api + '/folders/favourites/' + folder.data.id, {
                _method: 'delete',
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
            state.user.data.relationships.favourites.data.push(item)
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
        state.user.data.relationships.favourites.data = state.user.data.relationships.favourites.data.filter(
            (folder) => folder.data.id !== item.data.id
        )
    },
    UPDATE_NAME_IN_FAVOURITES(state, data) {
        state.user.data.relationships.favourites.data.find((folder) => {
            if (folder.id === data.id) {
                folder.name = data.name
            }
        })
    },
}

const getters = {
    permission: (state) => state.permission,
    user: (state) => state.user,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations,
}
