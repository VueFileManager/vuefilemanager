import Vue from "vue"
import axios from 'axios'
import {events} from '/resources/js/bus'
import router from '/resources/js/router'
import i18n from '/resources/js/i18n/index'

const defaultState = {
    currentFolder: undefined,
    fastPreview: undefined,
    navigation: undefined,
    isLoading: true,
    clipboard: [],
    entries: [],
}

const actions = {
    getFolder: ({commit, getters}, id) => {
        commit('LOADING_STATE', {loading: true, data: []})

        axios
            .get(`${getters.api}/browse/folders/${id}/${getters.sorting.URI}`)
            .then(response => {
                let folders = response.data.folders.data
                let files = response.data.files.data

                commit('LOADING_STATE', {loading: false, data: folders.concat(files)})
                commit('SET_CURRENT_FOLDER', response.data.root)

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
    getRecentUploads: ({commit, getters}) => {
        commit('LOADING_STATE', {loading: true, data: []})

        axios
            .get(getters.api + '/browse/latest')
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data.files.data})
                commit('SET_CURRENT_FOLDER', undefined)

                events.$emit('scrollTop')
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    getMySharedItems: ({commit, getters}) => {
        commit('LOADING_STATE', {loading: true, data: []})

        axios
            .get(getters.api + '/browse/share' + getters.sorting.URI)
            .then(response => {

                let folders = response.data.folders.data
                let files = response.data.files.data

                commit('LOADING_STATE', {loading: false, data: folders.concat(files)})
                commit('SET_CURRENT_FOLDER', undefined)

                events.$emit('scrollTop')
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    getTrash: ({commit, getters}, id) => {
        commit('LOADING_STATE', {loading: true, data: []})

        axios
            .get(`${getters.api}/browse/trash/${id}/${getters.sorting.URI}`)
            .then(response => {

                let folders = response.data.folders.data
                let files = response.data.files.data

                commit('LOADING_STATE', {loading: false, data: folders.concat(files)})
                commit('SET_CURRENT_FOLDER', response.data.root)

                events.$emit('scrollTop')
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
    SET_CURRENT_FOLDER(state, folder) {
        state.currentFolder = folder
    },
    UPDATE_FOLDER_TREE(state, tree) {
        state.navigation = tree
    },
    FLUSH_SHARED(state, id) {
        state.entries.find(item => {
            if (item.data.id === id) item.data.relationships.shared = undefined
        })
    },
    CHANGE_ITEM_NAME(state, updatedFile) {
        state.entries.find(item => {
            if (item.data.id === updatedFile.data.id) {
                item.data.attributes.name = updatedFile.data.attributes.name
                item.data.attributes.color = updatedFile.data.attributes.color ? updatedFile.data.attributes.color : null
                item.data.attributes.emoji = updatedFile.data.attributes.emoji ? updatedFile.data.attributes.emoji : null
            }
        })
    },
    UPDATE_SHARED_ITEM(state, data) {
        state.entries.find(item => {
            if (item.data.id === data.data.attributes.item_id) {
                item.data.relationships.shared = data
            }
        })
    },
    UPDATE_ITEM(state, data) {
        state.entries.find(item => {
            if (item.data.id === data.data.id) item.data = data.data
        })
    },
    ADD_NEW_FOLDER(state, folder) {
        state.entries.unshift(folder)
    },
    ADD_NEW_ITEMS(state, items) {
        state.entries = state.entries.concat(items)
    },
    REMOVE_ITEM(state, id) {
        state.entries = state.entries.filter(el => el.data.id !== id)
    },
    INCREASE_FOLDER_ITEM(state, id) {
        state.entries.map(el => {
            if (el.data.id && el.data.id === id) el.data.attributes.items++
        })
    },
    REMOVE_ITEM_FROM_CLIPBOARD(state, item) {
        state.clipboard = state.clipboard.filter(element => element.data.id !== item.data.id)
    },
    ADD_ALL_ITEMS_TO_CLIPBOARD(state) {
        state.clipboard = state.entries
    },
    ADD_ITEM_TO_CLIPBOARD(state, item) {
        let selectedItem = state.entries.find(el => el.data.id === item.data.id)

        if (state.clipboard.includes(selectedItem)) return

        state.clipboard.push(selectedItem ? selectedItem : state.currentFolder)
    },
    CLIPBOARD_CLEAR(state) {
        state.clipboard = []
    },
    ADD_TO_FAST_PREVIEW(state, item) {
        state.fastPreview = item
    },
    FAST_PREVIEW_CLEAR(state) {
        state.fastPreview = undefined
    },
}

const getters = {
    currentFolder: state => state.currentFolder,
    fastPreview: state => state.fastPreview,
    navigation: state => state.navigation,
    clipboard: state => state.clipboard,
    isLoading: state => state.isLoading,
    entries: state => state.entries,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations
}
