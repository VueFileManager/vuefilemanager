import axios from 'axios'
import {events} from '@/bus'
import router from '@/router'
import { includes } from 'lodash'
import i18n from '@/i18n/index'

const defaultState = {
    uploadingFilesCount: undefined,
    fileInfoDetail: undefined,
    currentFolder: undefined,
    homeDirectory: undefined,
    uploadingFileProgress: 0,
    filesViewWidth: undefined,
    isSearching: false,
    browseHistory: [],
    isLoading: true,
    data: [],
}

const actions = {
    getFolder: (context, [folder, back = false, init = false]) => {
        events.$emit('show:content')

        // Go to files view
        if ( ! includes(['Files', 'SharedContent'], router.currentRoute.name) ) {
            router.push({name: 'Files'})
        }

        context.commit('LOADING_STATE', true)
        context.commit('FLUSH_DATA')

        // Clear search
        if (context.state.isSearching) {
            context.commit('CHANGE_SEARCHING_STATE', false)
            events.$emit('clear-query')
        }

        // Create current folder for history
        let currentFolder = {
            name: folder.name,
            unique_id: folder.unique_id,
            location: folder.deleted_at || folder.location === 'trash' ? 'trash' : 'base'
        }

        let url = currentFolder.location === 'trash' ?'/folder/' + currentFolder.unique_id + '?trash=true' : '/folder/' + currentFolder.unique_id

        axios
            .get(context.getters.api + url)
            .then(response => {
                context.commit('LOADING_STATE', false)
                context.commit('GET_DATA', response.data)
                context.commit('STORE_CURRENT_FOLDER', currentFolder)

                events.$emit('scrollTop')

                if (back) {
                    context.commit('REMOVE_BROWSER_HISTORY')

                } else {
                    if (!init) context.commit('ADD_BROWSER_HISTORY', currentFolder)
                }
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    getShared: (context, back = false) => {
        events.$emit('show:content')

        // Go to files view
        if (router.currentRoute.name !== 'Files') {
            router.push({name: 'Files'})
        }

        if (! back) context.commit('FLUSH_BROWSER_HISTORY')
        context.commit('FLUSH_DATA')
        context.commit('LOADING_STATE', true)

        // Create shared object for history
        let trash = {
            name: 'Shared',
            unique_id: undefined,
            location: 'shared',
        }

        axios
            .get(context.getters.api + '/shared-all')
            .then(response => {
                context.commit('GET_DATA', response.data)
                context.commit('LOADING_STATE', false)
                context.commit('STORE_CURRENT_FOLDER', trash)
                context.commit('ADD_BROWSER_HISTORY', trash)

                events.$emit('scrollTop')
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    getTrash: (context, back = false) => {
        events.$emit('show:content')

        // Go to files view
        if (router.currentRoute.name !== 'Files') {
            router.push({name: 'Files'})
        }

        if (! back) context.commit('FLUSH_BROWSER_HISTORY')
        context.commit('FLUSH_DATA')
        context.commit('LOADING_STATE', true)

        // Create trash object for history
        let trash = {
            name: 'Trash',
            unique_id: undefined,
            location: 'trash-root',
        }

        axios
            .get(context.getters.api + '/trash')
            .then(response => {
                context.commit('GET_DATA', response.data)
                context.commit('LOADING_STATE', false)
                context.commit('STORE_CURRENT_FOLDER', trash)
                context.commit('ADD_BROWSER_HISTORY', trash)

                events.$emit('scrollTop')
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    getSearchResult: (context, query) => {
        context.commit('FLUSH_DATA')
        context.commit('LOADING_STATE', true)
        context.commit('CHANGE_SEARCHING_STATE', true)

        axios
            .get(context.getters.api + '/search', {
                params: {query: query}
            })
            .then(response => {
                context.commit('LOADING_STATE', false)
                context.commit('GET_DATA', response.data)
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    getFileDetail: (context, file) => {
        axios
            .get(context.getters.api + '/file-detail/' + file.unique_id)
            .then(response => {
                context.commit('LOAD_FILEINFO_DETAIL', response.data)
            })
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
    LOADING_STATE(state, val) {
        state.isLoading = val
    },
    SET_START_DIRECTORY(state, directory) {
        state.homeDirectory = directory
    },
    FLUSH_BROWSER_HISTORY(state) {
        state.browseHistory = []
    },
    FLUSH_SHARED(state, unique_id) {
        state.data.find(item => {
            if (item.unique_id == unique_id) item.shared = undefined
        })
    },
    ADD_BROWSER_HISTORY(state, folder) {
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
        state.fileInfoDetail = state.data.find(
            el => el.unique_id == item.unique_id
        )
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
    FLUSH_DATA(state) {
        state.data = []
    },
    GET_DATA(state, loaded_data) {
        state.data = loaded_data
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
    SET_FILES_VIEW_SIZE(state, type) {
        state.filesViewWidth = type
    },
}

const getters = {
    uploadingFileProgress: state => state.uploadingFileProgress,
    uploadingFilesCount: state => state.uploadingFilesCount,
    fileInfoDetail: state => state.fileInfoDetail,
    filesViewWidth: state => state.filesViewWidth,
    homeDirectory: state => state.homeDirectory,
    currentFolder: state => state.currentFolder,
    browseHistory: state => state.browseHistory,
    isSearching: state => state.isSearching,
    isLoading: state => state.isLoading,
    data: state => state.data,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations
}
