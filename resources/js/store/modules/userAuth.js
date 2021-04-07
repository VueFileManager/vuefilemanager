import axios from 'axios'
import router from '@/router'
import Vue from 'vue'

const defaultState = {
    permission: 'master', // master | editor | visitor
    user: undefined,
}

const actions = {
    getAppData: ({commit, getters}) => {

        return new Promise((resolve, reject) => {
            axios
                .get(getters.api + '/user' + getters.sorting.URI)
                .then((response) => {
                    resolve(response)

                    commit('RETRIEVE_USER', response.data)

                }).catch((error) => {
                    reject(error)

                    // Redirect if unauthenticated
                    if ([401, 403].includes(error.response.status)) {

                        commit('SET_AUTHORIZED', false)
                    }
                }
            )
        })
    },
    logOut: ({commit}) => {

        let popup = setTimeout(() => {
            commit('PROCESSING_POPUP', {
                title: 'Logging Out',
                message: 'Wait a second...',
            })
        }, 300)

        axios
            .post('/logout')
            .then(() => {
                clearTimeout(popup)
                commit('DESTROY_DATA')

                router.push({name: 'SignIn'})
            })
    },
    addToFavourites: (context, folder) => {
        let addFavourites = []
        let items = [folder]

        // If dont coming single folder get folders to add to favourites from fileInfoDetail
        if (!folder)
            items = context.getters.fileInfoDetail

        items.forEach((data) => {
            if (data.type === 'folder') {

                if (context.getters.user.data.relationships.favourites.data.attributes.folders.find(folder => folder.id === data.id)) return

                addFavourites.push({
                    id: data.id
                })
            }
        })

        // If dont coming single folder clear the selected folders in fileInfoDetail
        if (!folder) {
            context.commit('CLEAR_FILEINFO_DETAIL')
        }

        let pushToFavorites = []

        // Check is favorites already don't include some of pushed folders
        items.map(data => {
            if (!context.getters.user.data.relationships.favourites.data.attributes.folders.find(folder => folder.id === data.id)) {
                pushToFavorites.push(data)
            }
        })

        // Add to storage
        context.commit('ADD_TO_FAVOURITES', pushToFavorites)

        axios
            .post(context.getters.api + '/folders/favourites', {
                folders: addFavourites
            })
            .catch(() => {
                Vue.prototype.$isSomethingWrong()
            })
    },
    removeFromFavourites: ({commit, getters, dispatch}, folder) => {

        // Remove from storage
        commit('REMOVE_ITEM_FROM_FAVOURITES', folder)

        axios
            .post(getters.api + '/folders/favourites/' + folder.id, {
                _method: 'delete'
            })
            .catch(() => {
                Vue.prototype.$isSomethingWrong()
            })
    },
}

const mutations = {
    RETRIEVE_USER(state, user) {
        state.user = user
    },
    SET_PERMISSION(state, role) {
        state.permission = role
    },
    DESTROY_DATA(state) {
        state.app = undefined
    },
    ADD_TO_FAVOURITES(state, folder) {
        folder.forEach(item => {
            state.user.data.relationships.favourites.data.attributes.folders.push({
                id: item.id,
                name: item.name,
                type: item.type,
            })
        })
    },
    UPDATE_NAME(state, name) {
        state.user.data.relationships.settings.data.attributes.name = name
    },
    UPDATE_AVATAR(state, avatar) {
        state.user.data.relationships.settings.data.attributes.avatar = avatar
    },
    REMOVE_ITEM_FROM_FAVOURITES(state, item) {
        state.user.data.relationships.favourites.data.attributes.folders = state.user.data.relationships.favourites.data.attributes.folders.filter(folder => folder.id !== item.id)
    },
    UPDATE_NAME_IN_FAVOURITES(state, data) {
        state.user.data.relationships.favourites.data.attributes.folders.find(folder => {
            if (folder.id === data.id) {
                folder.name = data.name
            }
        })
    }
}

const getters = {
    permission: state => state.permission,
    user: state => state.user,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations
}
