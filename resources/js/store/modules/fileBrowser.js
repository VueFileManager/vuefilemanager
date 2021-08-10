import Vue from "vue"
import axios from 'axios'
import {events} from '@/bus'
import router from '@/router'
import i18n from '@/i18n/index'

const defaultState = {
    currentFolder: undefined,
    navigation: undefined,

    isSearching: false,
    isLoading: true,

    browseHistory: [],
    clipboard: [],
    entries: [],
}

const actions = {
    getFolder: ({commit, getters}, [payload]) => {
        commit('LOADING_STATE', {loading: true, data: []})

        if (payload.init)
            commit('FLUSH_FOLDER_HISTORY')

        // Clear search
        if (getters.isSearching) {
            commit('CHANGE_SEARCHING_STATE', false)
            events.$emit('clear-query')
        }

        // Set folder location
        payload.folder.location = payload.folder.deleted_at || payload.folder.location === 'trash' ? 'trash' : 'base'

        if (!payload.back && !payload.sorting)
            commit('STORE_PREVIOUS_FOLDER', getters.currentFolder)

        let url = payload.folder.location === 'trash'
            ? '/browse/folders/' + payload.folder.id + getters.sorting.URI + '&trash=true'
            : '/browse/folders/' + payload.folder.id + getters.sorting.URI

        axios
            .get(getters.api + url)
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})
                commit('STORE_CURRENT_FOLDER', payload.folder)

                if (payload.back && !payload.sorting)
                    commit('REMOVE_BROWSER_HISTORY')

                events.$emit('scrollTop')
            })
            .catch(error => {

                // Redirect if unauthenticated
                if ([401, 403].includes(error.response.status)) {

                    commit('SET_AUTHORIZED', false)
                    router.push({name: 'SignIn'})

                } else {

                    // Show error message
                    events.$emit('alert:open', {
                        title: i18n.t('popup_error.title'),
                        message: i18n.t('popup_error.message'),
                    })
                }
            })
    },
    getLatest: ({commit, getters}) => {
        commit('LOADING_STATE', {loading: true, data: []})

        commit('STORE_PREVIOUS_FOLDER', getters.currentFolder)
        commit('STORE_CURRENT_FOLDER', {
            name: i18n.t('sidebar.latest'),
            id: undefined,
            location: 'latest',
        })

        axios
            .get(getters.api + '/browse/latest')
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})
                events.$emit('scrollTop')
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    getShared: ({commit, getters}) => {
        commit('LOADING_STATE', {loading: true, data: []})
        commit('FLUSH_FOLDER_HISTORY')

        let currentFolder = {
            name: i18n.t('sidebar.my_shared'),
            location: 'shared',
            id: undefined,
        }

        commit('STORE_CURRENT_FOLDER', currentFolder)

        axios
            .get(getters.api + '/browse/share' + getters.sorting.URI)
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})
                commit('STORE_PREVIOUS_FOLDER', currentFolder)

                events.$emit('scrollTop')
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    getTrash: ({commit, getters}) => {
        commit('LOADING_STATE', {loading: true, data: []})
        commit('FLUSH_FOLDER_HISTORY')

        let trash = {
            name: i18n.t('locations.trash'),
            id: undefined,
            location: 'trash-root',
        }

        commit('STORE_CURRENT_FOLDER', trash)

        axios
            .get(getters.api + '/browse/trash' + getters.sorting.URI)
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})
                commit('STORE_PREVIOUS_FOLDER', trash)

                events.$emit('scrollTop')
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    getSearchResult: ({commit, getters}, query) => {
        commit('LOADING_STATE', {loading: true, data: []})
        commit('CHANGE_SEARCHING_STATE', true)

        // Get route
        let route = undefined

        if (getters.sharedDetail) {
            let permission = getters.sharedDetail.is_protected
                ? 'private'
                : 'public'

            route = `/api/browse/search/${permission}/${router.currentRoute.params.token}`

        } else {
            route = '/api/browse/search'
        }

        axios
            .get(route, {
                params: {query: query}
            })
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    getFolderTree: ({commit, getters}) => {
        return new Promise((resolve, reject) => {

            // Get route
            let route = undefined

            if (getters.sharedDetail) {
                route = `/api/browse/navigation/${router.currentRoute.params.token}`
            } else {
                route = '/api/browse/navigation'
            }

            axios
                .get(route + getters.sorting.URI)
                .then(response => {
                    resolve(response)

                    commit('UPDATE_FOLDER_TREE', response.data)
                })
                .catch((error) => {
                    reject(error)

                    Vue.prototype.$isSomethingWrong()
                })
        })
    },
}

const mutations = {
    LOADING_STATE(state, payload) {
        state.clipboard = []
        state.entries = payload.data
        state.isLoading = payload.loading
    },
    UPDATE_FOLDER_TREE(state, tree) {
        state.navigation = tree
    },
    FLUSH_FOLDER_HISTORY(state) {
        state.browseHistory = []
    },
    FLUSH_SHARED(state, id) {
        state.entries.find(item => {
            if (item.id === id) item.shared = undefined
        })
    },
    STORE_PREVIOUS_FOLDER(state, folder) {
        state.browseHistory.push(folder)
    },
    REMOVE_BROWSER_HISTORY(state) {
        state.browseHistory.pop()
    },
    CHANGE_ITEM_NAME(state, updatedFile) {

        // Rename filename in clipboard
        if (state.clipboard && state.clipboard.id === updatedFile.id) {
            state.clipboard = updatedFile
        }

        // Rename item name in data view
        state.entries.find(item => {
            if (item.id === updatedFile.id) {
                item.name = updatedFile.name
                item.color = updatedFile.color ? updatedFile.color : null
                item.emoji = updatedFile.emoji ? updatedFile.emoji : null
            }
        })
    },
    CHANGE_SEARCHING_STATE(state, searchState) {
        state.isSearching = searchState
    },
    UPDATE_SHARED_ITEM(state, data) {
        state.entries.find(item => {
            if (item.id === data.item_id) item.shared = data
        })
    },
    ADD_NEW_FOLDER(state, folder) {
        state.entries.unshift(folder)
    },
    ADD_NEW_ITEMS(state, items) {
        state.entries = state.entries.concat(items)
    },
    REMOVE_ITEM(state, id) {
        state.entries = state.entries.filter(el => el.id !== id)
    },
    INCREASE_FOLDER_ITEM(state, id) {
        state.entries.map(el => {
            if (el.id && el.id === id) el.items++
        })
    },
    STORE_CURRENT_FOLDER(state, folder) {
        state.currentFolder = folder
    },
    REMOVE_ITEM_FROM_CLIPBOARD(state, item) {
        state.clipboard = state.clipboard.filter(element => element.id !== item.id)
    },
    ADD_ALL_ITEMS_TO_CLIPBOARD(state) {
        state.clipboard = state.entries
    },
    ADD_ITEM_TO_CLIPBOARD(state, item) {
        let selectedItem = state.entries.find(el => el.id === item.id)

        if (state.clipboard.includes(selectedItem)) return

        state.clipboard.push(selectedItem ? selectedItem : state.currentFolder)
    },
    CLIPBOARD_CLEAR(state) {
        state.clipboard = []
    },
    CLIPBOARD_REPLACE(state, items) {
        state.clipboard = items
    },
}

const getters = {
    clipboard: state => state.clipboard,
    currentFolder: state => state.currentFolder,
    browseHistory: state => state.browseHistory,
    isSearching: state => state.isSearching,
    navigation: state => state.navigation,
    isLoading: state => state.isLoading,
    entries: state => state.entries,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations
}
