import axios from 'axios'
import {events} from '@/bus'
import router from '@/router'
import { includes } from 'lodash'
import i18n from '@/i18n/index.js'

const defaultState = {
    fileInfoPanelVisible: localStorage.getItem('file_info_visibility') == 'true' || false,
    preview_type: localStorage.getItem('preview_type') || 'list',
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
    goToFolder: (context, [folder, back = false, init = false]) => {
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
            .get(context.getters.api + '/shared')
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
    emptyTrash: (context) => {
        context.commit('FLUSH_DATA')
        context.commit('LOADING_STATE', true)

        axios
            .delete(context.getters.api + '/empty-trash')
            .then(() => {
                context.commit('LOADING_STATE', false)
                events.$emit('scrollTop')

                // Remove file preview
                context.commit('CLEAR_FILEINFO_DETAIL')

                // Show error message
                events.$emit('success:open', {
                    title: i18n.t('popup_trashed.title'),
                    message: i18n.t('popup_trashed.message'),
                })
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    createFolder: (context, folderName) => {
        const parent_id = context.state.currentFolder
            ? context.state.currentFolder.unique_id
            : 0

        axios
            .post(context.getters.api + '/create-folder', {
                parent_id: parent_id,
                name: folderName
            })
            .then(response => {
                context.commit('ADD_NEW_FOLDER', response.data)
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    removeItems: (context, [ids, items]) => {
        context.commit('REMOVE_ITEMS', ids)

        // Remove file preview
        context.commit('CLEAR_FILEINFO_DETAIL')

        axios
            .post(context.getters.api + '/remove-items', {
                items: items
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    removeItem: ({commit, state, getters}, data) => {

        // Remove file
        commit('REMOVE_ITEM', data.unique_id)

        if (data.type === 'file' || data.type === 'image')
            commit('REMOVE_ITEM_FROM_RECENT_UPLOAD', data.unique_id)
        if (data.type === 'folder')
            commit('REMOVE_ITEM_FROM_FAVOURITES', data)

        // Remove file preview
        commit('CLEAR_FILEINFO_DETAIL')

        axios
            .post(getters.api + '/remove-item', {
                type: data.type,
                unique_id: data.unique_id,
                force_delete: data.deleted_at ? true : false
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    restoreItem: (context, item) => {

        let restoreToHome = false

        // Check if file can be restored to home directory
        if (context.state.currentFolder.location === 'trash') restoreToHome = true

        // Remove file
        context.commit('REMOVE_ITEM', item.unique_id)

        // Remove file preview
        context.commit('CLEAR_FILEINFO_DETAIL')

        axios
            .post(context.getters.api + '/restore-item', {
                type: item.type,
                unique_id: item.unique_id,
                to_home: restoreToHome,
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    uploadFiles: (context, files) => {
        return new Promise((resolve, reject) => {
            axios
                .post(context.getters.api + '/upload-file', files, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: progressEvent => {
                        var percentCompleted = Math.round(
                            (progressEvent.loaded * 100) /
                            progressEvent.total
                        )

                        context.commit(
                            'UPLOADING_FILE_PROGRESS',
                            percentCompleted
                        )
                    }
                })
                .then(response => {

                    // Check if user is in uploading folder, if yes, than show new file
                    if (response.data.folder_id == context.state.currentFolder.unique_id)
                        context.commit('ADD_NEW_ITEMS', response.data)

                    context.commit('UPDATE_RECENT_UPLOAD', response.data)
                    context.commit(
                        'UPLOADING_FILE_PROGRESS',
                        0
                    )
                    resolve(response)
                })
                .catch(error => {
                    reject(error)

                    context.commit('UPDATE_FILE_COUNT_PROGRESS', undefined)
                })
        })
    },
    changeItemName: (context, data) => {


        if (data.type === 'folder') context.commit('UPDATE_NAME_IN_FAVOURITES', data)

        axios
            .post(context.getters.api + '/rename-item', data)
            .then(response => {
                context.commit('CHANGE_ITEM_NAME', response.data)
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
    changePreviewType: ({commit, dispatch, state}) => {
        // Get preview type
        let previewType = localStorage.getItem('preview_type') == 'grid' ? 'list' : 'grid'

        // Store preview type to localStorage
        localStorage.setItem('preview_type', previewType)

        // Change preview
        commit('CHANGE_PREVIEW', previewType)

        if (state.currentFolder.location === 'trash-root') {
            dispatch('getTrash')

        } else {
            dispatch('goToFolder', [state.currentFolder, false, true])
        }
    },
    fileInfoToggle: (context, visibility = undefined) => {
        if (!visibility) {
            if (context.state.fileInfoPanelVisible) {
                context.commit('FILE_INFO_TOGGLE', false)
            } else {
                context.commit('FILE_INFO_TOGGLE', true)
            }
        } else {
            context.commit('FILE_INFO_TOGGLE', visibility)
        }
    },
    getLatestUploadDetail: (context, file) => {
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
    loadFileInfoDetail: (context, file) => {

        context.commit('GET_FILEINFO_DETAIL', file)
    },
    moveItem: (context, [item_from, to_item]) => {
        axios
            .post(context.getters.api + '/move-item', {
                from_unique_id: item_from.unique_id,
                from_type: item_from.type,
                to_unique_id: to_item.unique_id
            })
            .then(() => {
                context.commit('REMOVE_ITEM', item_from.unique_id)
                context.commit('INCREASE_FOLDER_ITEM', to_item.unique_id)
            })
            .catch(() => {
                // Show error message
                events.$emit('alert:open', {
                    title: i18n.t('popup_error.title'),
                    message: i18n.t('popup_error.message'),
                })
            })
    }
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
    FILE_INFO_TOGGLE(state, isVisible) {
        state.fileInfoPanelVisible = isVisible

        localStorage.setItem('file_info_visibility', isVisible)
    },
    CHANGE_PREVIEW(state, type) {
        state.preview_type = type
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
    REMOVE_ITEMS(state, ids) {
        state.data = state.data.filter(
            el => -1 == ids.indexOf(el.unique_id)
        )
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
    fileInfoVisible: state => state.fileInfoPanelVisible,
    fileInfoDetail: state => state.fileInfoDetail,
    filesViewWidth: state => state.filesViewWidth,
    homeDirectory: state => state.homeDirectory,
    currentFolder: state => state.currentFolder,
    browseHistory: state => state.browseHistory,
    preview_type: state => state.preview_type,
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
