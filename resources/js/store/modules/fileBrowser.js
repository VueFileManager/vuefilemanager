import Vue from 'vue'
import axios from 'axios'
import { events } from '../../bus'
import router from '../../router'
import i18n from '../../i18n'

const defaultState = {
    currentFolder: undefined,
    isMultiSelectMode: false,
    fastPreview: undefined,
    navigation: undefined,
    paginate: undefined,
    isLoading: true,
    clipboard: [],
    entries: [],
}

const actions = {
    getFolder: ({ commit, getters },{page, id}) => {
        return new Promise ((resolve, reject) => {
            if(page === 1)
                commit('START_LOADING_VIEW')

            axios
                .get(`${getters.api}/browse/folders/${id || 'all'}${getters.sorting.URI}&page=${page}`)
                .then((response) => {
                    commit('SET_CURRENT_FOLDER', response.data.meta.root)
                    commit('SET_PAGINATOR', response.data.meta.paginate)
                    commit('STOP_LOADING_VIEW')
                    commit('ADD_NEW_ITEMS', response.data.data)

                    events.$emit('scrollTop')

                    resolve(response);
                })
                .catch((error) => {
                    // Redirect if unauthenticated
                    if ([401, 403].includes(error.response.status)) {
                        commit('SET_AUTHORIZED', false)
                        router.push({ name: 'SignIn' })
                    } else {
                        // Show error message
                        events.$emit('alert:open', {
                            title: i18n.t('popup_error.title'),
                            message: i18n.t('popup_error.message'),
                        })
                    }

                    reject(error);
                })
        })
    },
    getRecentUploads: ({commit, getters}, page) => {
        return new Promise((resolve, reject) => {
            if (page === 1)
                commit('START_LOADING_VIEW')

            axios
                .get(`${getters.api}/browse/latest?page=${page}`)
                .then((response) => {
                    commit('SET_PAGINATOR', response.data.meta.paginate)
                    commit('SET_CURRENT_FOLDER', undefined)
                    commit('STOP_LOADING_VIEW')
                    commit('ADD_NEW_ITEMS', response.data.data)

                    events.$emit('scrollTop')

                    resolve(response)
                })
                .catch(() => Vue.prototype.$isSomethingWrong())
        })
    },
    getMySharedItems: ({ commit, getters }, page) => {
        return new Promise((resolve, reject) => {
            if (page === 1)
                commit('START_LOADING_VIEW')

            axios
                .get(`${getters.api}/browse/share${getters.sorting.URI}&page=${page}`)
                .then((response) => {
                    commit('SET_PAGINATOR', response.data.meta.paginate)
                    commit('SET_CURRENT_FOLDER', undefined)
                    commit('STOP_LOADING_VIEW')
                    commit('ADD_NEW_ITEMS', response.data.data)

                    events.$emit('scrollTop')

                    resolve(response)
                })
                .catch(() => Vue.prototype.$isSomethingWrong())
        })
    },
    getTrash: ({ commit, getters }, {page, id}) => {
        return new Promise((resolve, reject) => {
            if (page === 1)
                commit('START_LOADING_VIEW')

            axios
                .get(`${getters.api}/browse/trash/${id || 'all'}${getters.sorting.URI}&page=${page}`)
                .then((response) => {
                    commit('SET_PAGINATOR', response.data.meta.paginate)
                    commit('SET_CURRENT_FOLDER', response.data.meta.root)
                    commit('STOP_LOADING_VIEW')
                    commit('ADD_NEW_ITEMS', response.data.data)

                    events.$emit('scrollTop')

                    resolve(response)
                })
                .catch((error) => {
                    Vue.prototype.$isSomethingWrong()

                    reject(error);
                })
        })
    },
    getFolderTree: ({ commit, getters }) => {
        return new Promise((resolve, reject) => {
            // Get route
            let route = {
                RequestUpload: `/api/file-request/${router.currentRoute.params.token}/navigation`,
                Public: `/api/sharing/navigation/${router.currentRoute.params.token}`,
            }[router.currentRoute.name] || '/api/browse/navigation'

            axios
                .get(route + getters.sorting.URI)
                .then((response) => {
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
    SET_PAGINATOR(state, payload) {
        state.paginate = payload
    },
    START_LOADING_VIEW(state) {
        state.entries = []
        state.isLoading = true
    },
    STOP_LOADING_VIEW(state) {
        state.isLoading = false
    },
    SET_CURRENT_FOLDER(state, folder) {
        state.currentFolder = folder
    },
    UPDATE_FOLDER_TREE(state, tree) {
        state.navigation = tree
    },
    FLUSH_SHARED(state, id) {
        state.entries.find((item) => {
            if (item.data.id === id) item.data.relationships.shared = undefined
        })
    },
    CHANGE_ITEM_NAME(state, file) {
        state.entries.find((item) => {
            if (item.data.id === file.data.id) {
                item.data.attributes.name = file.data.attributes.name
                item.data.attributes.color = file.data.attributes.color ? file.data.attributes.color : null
                item.data.attributes.emoji = file.data.attributes.emoji ? file.data.attributes.emoji : null
            }
        })
    },
    UPDATE_SHARED_ITEM(state, data) {
        state.entries.find((item) => {
            if (item.data.id === data.data.attributes.item_id) {
                item.data.relationships = {
                    ...item.data.relationships,
                    ...{shared: data}
                }
            }
        })
    },
    UPDATE_ITEM(state, data) {
        state.entries.find((item) => {
            if (item.data.id === data.data.id) item.data = data.data
        })
    },
    ADD_NEW_ITEM(state, folder) {
        state.entries.unshift(folder)
    },
    ADD_NEW_ITEMS(state, items) {
        state.entries = state.entries.concat(items)
    },
    REMOVE_ITEM(state, id) {
        state.entries = state.entries.filter((el) => el.data.id !== id)
    },
    INCREASE_FOLDER_ITEM(state, id) {
        state.entries.map((el) => {
            if (el.data.id && el.data.id === id) el.data.attributes.items++
        })
    },
    REMOVE_ITEM_FROM_CLIPBOARD(state, id) {
        state.clipboard = state.clipboard.filter((element) => element.data.id !== id)
    },
    ADD_ALL_ITEMS_TO_CLIPBOARD(state) {
        state.clipboard = state.entries
    },
    CLIPBOARD_REPLACE(state, item) {
        state.clipboard = [item]
    },
    ADD_ITEM_TO_CLIPBOARD(state, item) {
        let selectedItem = state.entries.find((el) => el.data.id === item.data.id)

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
    TOGGLE_MULTISELECT_MODE(state) {
        state.clipboard = []
        state.isMultiSelectMode = !state.isMultiSelectMode
    },
    DISABLE_MULTISELECT_MODE(state) {
        state.clipboard = []
        state.isMultiSelectMode = false
    },
}

const getters = {
    isMultiSelectMode: (state) => state.isMultiSelectMode,
    currentFolder: (state) => state.currentFolder,
    fastPreview: (state) => state.fastPreview,
    navigation: (state) => state.navigation,
    clipboard: (state) => state.clipboard,
    isLoading: (state) => state.isLoading,
    paginate: (state) => state.paginate,
    entries: (state) => state.entries,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations,
}
