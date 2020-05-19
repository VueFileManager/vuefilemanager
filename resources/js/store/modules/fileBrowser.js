import axios from 'axios'
import {events} from '@/bus'
import router from '@/router'
import i18n from '@/i18n/index'

const defaultState = {
    uploadingFilesCount: undefined,
    fileInfoDetail: undefined,
    currentFolder: undefined,
    uploadingFileProgress: 0,
    navigation: undefined,
    isSearching: false,
    browseHistory: [],
    isLoading: true,
    data: [],
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

        if (! payload.back)
            commit('STORE_PREVIOUS_FOLDER', getters.currentFolder)

        let url = payload.folder.location === 'trash'
            ? '/folders/' + payload.folder.unique_id + '?trash=true'
            : '/folders/' + payload.folder.unique_id

        axios
            .get(getters.api + url)
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})
                commit('STORE_CURRENT_FOLDER', payload.folder)

                if (payload.back)
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
            unique_id: undefined,
            location: 'latest',
        })

        axios
            .get(getters.api + '/latest')
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})
                events.$emit('scrollTop')
            })
            .catch(() => isSomethingWrong())
    },
    getShared: ({commit, getters}) => {
        commit('LOADING_STATE', {loading: true, data: []})
        commit('FLUSH_FOLDER_HISTORY')

        let currentFolder = {
            name: i18n.t('sidebar.my_shared'),
            location: 'shared',
            unique_id: undefined,
        }

        commit('STORE_CURRENT_FOLDER', currentFolder)

        axios
            .get(getters.api + '/shared-all')
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})
                commit('STORE_PREVIOUS_FOLDER', currentFolder)

                events.$emit('scrollTop')
            })
            .catch(() => isSomethingWrong())
    },
    getParticipantUploads: ({commit, getters}) => {
        commit('LOADING_STATE', {loading: true, data: []})

        commit('STORE_PREVIOUS_FOLDER', getters.currentFolder)
        commit('STORE_CURRENT_FOLDER', {
            name: i18n.t('sidebar.participant_uploads'),
            unique_id: undefined,
            location: 'participant_uploads',
        })

        axios
            .get(getters.api + '/participant-uploads')
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})

                events.$emit('scrollTop')
            })
            .catch(() => isSomethingWrong())
    },
    getTrash: ({commit, getters}) => {
        commit('LOADING_STATE', {loading: true, data: []})
        commit('FLUSH_FOLDER_HISTORY')

        let trash = {
            name: i18n.t('locations.trash'),
            unique_id: undefined,
            location: 'trash-root',
        }

        commit('STORE_CURRENT_FOLDER', trash)

        axios
            .get(getters.api + '/trash')
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})
                commit('STORE_PREVIOUS_FOLDER', trash)

                events.$emit('scrollTop')
            })
            .catch(() => isSomethingWrong())
    },
    getSearchResult: ({commit, getters}, query) => {
        commit('LOADING_STATE', {loading: true, data: []})
        commit('CHANGE_SEARCHING_STATE', true)

        // Get route
        let route = undefined

        if (getters.sharedDetail && getters.sharedDetail.protected)
            route = '/api/search/private'
        else if (getters.sharedDetail && !getters.sharedDetail.protected)
            route = '/api/search/public/' + router.currentRoute.params.token
        else
            route = '/api/search'

        axios
            .get(route, {
                params: {query: query}
            })
            .then(response => {
                commit('LOADING_STATE', {loading: false, data: response.data})
            })
            .catch(() => isSomethingWrong())
    },
    getFileDetail: ({commit, getters}, file) => {
        axios
            .get(getters.api + '/file-detail/' + file.unique_id)
            .then(response => {
                commit('LOAD_FILEINFO_DETAIL', response.data)
            })
            .catch(() => isSomethingWrong())
    },
    getFolderTree: ({commit, getters}) => {

        return new Promise((resolve, reject) => {

            // Get route
            let route = undefined

            if (getters.sharedDetail && getters.sharedDetail.protected)
                route = '/api/navigation/private'
            else if (getters.sharedDetail && !getters.sharedDetail.protected)
                route = '/api/navigation/public/' + router.currentRoute.params.token
            else
                route = '/api/navigation'

            axios
                .get(route)
                .then(response => {
                    resolve(response)

                    commit('UPDATE_FOLDER_TREE', response.data)
                })
                .catch((error) => {
                    reject(error)

                    isSomethingWrong()
                })
        })
    },
}

const mutations = {
    UPDATE_FOLDER_TREE(state, tree) {
        state.navigation = tree
    },
    LOADING_STATE(state, payload) {
        state.data = payload.data
        state.isLoading = payload.loading
    },
    FLUSH_FOLDER_HISTORY(state) {
        state.browseHistory = []
    },
    FLUSH_SHARED(state, unique_id) {
        state.data.find(item => {
            if (item.unique_id == unique_id) item.shared = undefined
        })
    },
    STORE_PREVIOUS_FOLDER(state, folder) {
        state.browseHistory.push(folder)
    },
    REMOVE_BROWSER_HISTORY(state) {
        state.browseHistory.pop()
    },
    CHANGE_ITEM_NAME(state, updatedFile) {

        // Rename filename in file info detail
        if (state.fileInfoDetail && state.fileInfoDetail.unique_id == updatedFile.unique_id) {
            state.fileInfoDetail = updatedFile
        }

        // Rename item name in data view
        state.data.find(item => {
            if (item.unique_id == updatedFile.unique_id) item.name = updatedFile.name
        })
    },
    CLEAR_FILEINFO_DETAIL(state) {
        state.fileInfoDetail = undefined
    },
    LOAD_FILEINFO_DETAIL(state, item) {
        state.fileInfoDetail = item
    },
    GET_FILEINFO_DETAIL(state, item) {
        let checkData = state.data.find(el => el.unique_id == item.unique_id)

        state.fileInfoDetail = checkData ? checkData : state.currentFolder
    },
    CHANGE_SEARCHING_STATE(state, searchState) {
        state.isSearching = searchState
    },
    UPLOADING_FILE_PROGRESS(state, percentage) {
        state.uploadingFileProgress = percentage
    },
    UPDATE_FILE_COUNT_PROGRESS(state, data) {
        state.uploadingFilesCount = data
    },
    UPDATE_SHARED_ITEM(state, data) {
        state.data.find(item => {
            if (item.unique_id == data.item_id) item.shared = data
        })
    },
    ADD_NEW_FOLDER(state, folder) {
        state.data.unshift(folder)
    },
    ADD_NEW_ITEMS(state, items) {
        state.data = state.data.concat(items)
    },
    REMOVE_ITEM(state, unique_id) {
        state.data = state.data.filter(el => el.unique_id !== unique_id)
    },
    INCREASE_FOLDER_ITEM(state, unique_id) {
        state.data.map(el => {
            if (el.unique_id && el.unique_id == unique_id) el.items++
        })
    },
    STORE_CURRENT_FOLDER(state, folder) {
        state.currentFolder = folder
    },
}

const getters = {
    uploadingFileProgress: state => state.uploadingFileProgress,
    uploadingFilesCount: state => state.uploadingFilesCount,
    fileInfoDetail: state => state.fileInfoDetail,
    currentFolder: state => state.currentFolder,
    browseHistory: state => state.browseHistory,
    isSearching: state => state.isSearching,
    navigation: state => state.navigation,
    isLoading: state => state.isLoading,
    data: state => state.data,
}

// Show error message
function isSomethingWrong() {
    events.$emit('alert:open', {
        title: i18n.t('popup_error.title'),
        message: i18n.t('popup_error.message'),
    })
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations
}
