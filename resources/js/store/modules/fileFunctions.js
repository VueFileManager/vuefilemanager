import i18n from '@/i18n/index'
import router from '@/router'
import {events} from '@/bus'
import {last} from 'lodash'
import axios from 'axios'
import Vue from 'vue'

const defaultState = {
    processingPopup: undefined,
    fileQueue: [],
    filesInQueueTotal: 0,
    filesInQueueUploaded: 0,
    isProcessingFile: false,
    uploadingProgress: 0
}

const actions = {
    downloadFolder: ({commit, getters}, folder) => {

        commit('PROCESSING_POPUP', {
            title: i18n.t('popup_zipping.title'),
            message: i18n.t('popup_zipping.message')
        })

        let route = getters.sharedDetail
            ? `/api/zip/folder/${folder.id}/${router.currentRoute.params.token}`
            : `/api/zip/folder/${folder.id}`

        axios.get(route)
            .then(response => {
                Vue.prototype.$downloadFile(response.data.url, response.data.name)
            })
            .catch(() => {
                Vue.prototype.$isSomethingWrong()
            })
            .finally(() => {
                commit('PROCESSING_POPUP', undefined)
            })
    },
    downloadFiles: ({commit, getters}) => {
        let files = []

        // get ids of selected files
        getters.clipboard.forEach(file => files.push(file.id))

        // Get route
        let route = getters.sharedDetail
            ? `/api/zip/files/${router.currentRoute.params.token}`
            : '/api/zip/files'

        commit('PROCESSING_POPUP', {
            title: i18n.t('popup_zipping.title'),
            message: i18n.t('popup_zipping.message'),
        })

        axios.post(route, {
            items: files
        })
            .then(response => {
                Vue.prototype.$downloadFile(response.data.url, response.data.name)
            })
            .catch(() => {
                Vue.prototype.$isSomethingWrong()
            })
            .finally(() => {
                commit('PROCESSING_POPUP', undefined)
            })
    },
    moveItem: ({commit, getters, dispatch}, {to_item, noSelectedItem}) => {

        let itemsToMove = []
        let items = [noSelectedItem]

        // If coming no selected item dont get items to move from clipboard
        if (!noSelectedItem)
            items = getters.clipboard

        items.forEach(data => itemsToMove.push({
            'id': data.id,
            'type': data.type
        }))

        // Remove file preview
        if (!noSelectedItem)
            commit('CLIPBOARD_CLEAR')

        // Get route
        let route = getters.sharedDetail
            ? `/api/editor/move/${router.currentRoute.params.token}`
            : '/api/move'

        axios
            .post(route, {
                to_id: to_item.id ? to_item.id : null,
                items: itemsToMove
            })
            .then(() => {
                itemsToMove.forEach(item => {
                    commit('REMOVE_ITEM', item.id)
                    commit('INCREASE_FOLDER_ITEM', to_item.id)

                    if (item.type === 'folder')
                        dispatch('getAppData')
                    if (getters.currentFolder.location === 'public')
                        dispatch('getFolderTree')
                })
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    createFolder: ({commit, getters, dispatch}, folder) => {

        // Get route
        let route = getters.sharedDetail
            ? `/api/editor/create-folder/${router.currentRoute.params.token}`
            : '/api/create-folder'

        axios
            .post(route, {
                parent_id: getters.currentFolder.id,
                name: folder.name,
                icon: folder.icon
            })
            .then(response => {
                commit('ADD_NEW_FOLDER', response.data)

                events.$emit('scrollTop')

                // Set focus on new folder name
                setTimeout(() => {
                    events.$emit('newFolder:focus', response.data.id)
                }, 10)

                if (getters.currentFolder.location !== 'public')
                    dispatch('getAppData')
                if (getters.currentFolder.location === 'public')
                    dispatch('getFolderTree')

            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    renameItem: ({commit, getters, dispatch}, data) => {

        // Updated name in favourites panel
        if (getters.permission === 'master' && data.type === 'folder')
            commit('UPDATE_NAME_IN_FAVOURITES', data)

        // Get route
        let route = getters.sharedDetail
            ? `/api/editor/rename/${data.id}/${router.currentRoute.params.token}`
            : `/api/rename/${data.id}`

        axios
            .post(route, {
                ...data,
                _method: 'patch'
            })
            .then(response => {
                commit('CHANGE_ITEM_NAME', response.data)

                if (data.type === 'folder' && getters.currentFolder.location !== 'public')
                    dispatch('getAppData')
                if (data.type === 'folder' && getters.currentFolder.location === 'public')
                    dispatch('getFolderTree')
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    uploadFiles: ({commit, getters}, {form, fileSize, totalUploadedSize}) => {
        return new Promise((resolve, reject) => {

            // Get route
            let route = getters.sharedDetail
                ? `/api/editor/upload/${router.currentRoute.params.token}`
                : '/api/upload'

            // Create cancel token for axios cancellation
            const CancelToken = axios.CancelToken,
                source = CancelToken.source()

            axios
                .post(route, form, {
                    cancelToken: source.token,
                    headers: {
                        'Content-Type': 'application/octet-stream'
                    },
                    onUploadProgress: event => {
                        let percentCompleted = Math.floor(((totalUploadedSize + event.loaded) / fileSize) * 100);

                        commit('UPLOADING_FILE_PROGRESS', percentCompleted >= 100 ? 100 : percentCompleted)

                        // Set processing file
                        if (percentCompleted >= 100)
                            commit('PROCESSING_FILE', true)
                    }
                })
                .then(response => {
                    resolve(response)

                    // Proceed if was returned database record
                    if (response.data.id) {

                        commit('PROCESSING_FILE', false)

                        // Remove first file from file queue
                        commit('SHIFT_FROM_FILE_QUEUE')

                        // Check if user is in uploading folder, if yes, than show new file
                        if (response.data.folder_id == getters.currentFolder.id) {

                            // Add uploaded item into view
                            commit('ADD_NEW_ITEMS', response.data)

                            // Reset file progress
                            commit('UPLOADING_FILE_PROGRESS', 0)

                            // Increase count in files in queue uploaded for 1
                            commit('INCREASE_FILES_IN_QUEUE_UPLOADED')
                        }

                        // Start uploading next file if file queue is not empty
                        if (getters.fileQueue.length) {
                            Vue.prototype.$handleUploading(getters.fileQueue[0])
                        }

                        // Reset upload process
                        if (!getters.fileQueue.length)
                            commit('CLEAR_UPLOAD_PROGRESS')
                    }
                })
                .catch(error => {
                    reject(error)

                    let messages = {
                        '423': {
                            title: i18n.t('popup_exceed_limit.title'),
                            message: i18n.t('popup_exceed_limit.message')
                        },
                        '422': {
                            title: i18n.t('popup_mimetypes_blacklist.title'),
                            message: i18n.t('popup_mimetypes_blacklist.message')
                        },
                        '413': {
                            title: i18n.t('popup_paylod_error.title'),
                            message: i18n.t('popup_paylod_error.message')
                        }
                    }

                    events.$emit('alert:open', {
                        emoji: '😬😬😬',
                        title: messages[error.response.status]['title'],
                        message: messages[error.response.status]['message']
                    })

                    commit('PROCESSING_FILE', false)
                    commit('CLEAR_UPLOAD_PROGRESS')
                })

            // Cancel the upload request
            events.$on('cancel-upload', () => {
                source.cancel()

                // Hide upload progress bar
                commit('PROCESSING_FILE', false)
                commit('CLEAR_UPLOAD_PROGRESS')
            })
        })
    },
    restoreItem: ({commit, getters}, item) => {

        let itemToRestore = []
        let items = [item]
        let restoreToHome = false

        // If coming no selected item dont get items to restore from clipboard
        if (!item)
            items = getters.clipboard

        // Check if file can be restored to home directory
        if (getters.currentFolder.location === 'trash')
            restoreToHome = true

        items.forEach(data => itemToRestore.push({
            type: data.type,
            id: data.id
        }))

        // Remove file preview
        commit('CLIPBOARD_CLEAR')

        axios
            .post(getters.api + '/trash/restore', {
                to_home: restoreToHome,
                items: itemToRestore
            })
            .then(
                // Remove file
                items.forEach(data => commit('REMOVE_ITEM', data.id))
            )
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    deleteItem: ({commit, getters, dispatch}, noSelectedItem) => {

        let itemsToDelete = []
        let items = [noSelectedItem]

        // If coming no selected item dont get items to move from clipboard
        if (!noSelectedItem)
            items = getters.clipboard

            items.forEach(data => {
                itemsToDelete.push({
                    force_delete: data.deleted_at ? true : false,
                    type: data.type,
                    id: data.id
                })

            // Remove file
            commit('REMOVE_ITEM', data.id)

            // Remove item from sidebar
            if (getters.permission === 'master') {

                if (data.type === 'folder')
                    commit('REMOVE_ITEM_FROM_FAVOURITES', data)
            }

            // Remove file
            commit('REMOVE_ITEM', data.id)

            // Remove item from sidebar
            if (getters.permission === 'master') {

                if (data.type === 'folder')
                    commit('REMOVE_ITEM_FROM_FAVOURITES', data)
            }
        })

        // Remove file preview
        if (!noSelectedItem) {
            commit('CLIPBOARD_CLEAR')
        }

        // Get route
        let route = getters.sharedDetail
            ? `/api/editor/remove/${router.currentRoute.params.token}`
            : '/api/remove'

        axios
            .post(route, {
                items: itemsToDelete
            })
            .then(() => {

                itemsToDelete.forEach(data => {

                    // If is folder, update app data
                    if (data.type === 'folder') {

                        if (data.id === getters.currentFolder.id) {

                            if (getters.currentFolder.location === 'public') {
                                dispatch('browseShared', [{folder: last(getters.browseHistory), back: true, init: false}])
                            } else {
                                dispatch('getFolder', [{folder: last(getters.browseHistory), back: true, init: false}])
                            }
                        }
                    }
                })

                if (getters.currentFolder.location !== 'public')
                    dispatch('getAppData')

                if (getters.currentFolder.location === 'public')
                    dispatch('getFolderTree')

            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    emptyTrash: ({commit, getters}) => {

        // Clear file browser
        commit('LOADING_STATE', {loading: true, data: []})

        axios
            .post(getters.api + '/trash/dump', {
                _method: 'delete'
            })
            .then(() => {
                commit('LOADING_STATE', {loading: false, data: []})
                events.$emit('scrollTop')

                commit('CLIPBOARD_CLEAR')
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    }
}

const mutations = {
    PROCESSING_POPUP(state, status) {
        state.processingPopup = status
    },
    ADD_FILES_TO_QUEUE(state, file) {
        state.fileQueue.push(file)
    },
    SHIFT_FROM_FILE_QUEUE(state) {
        state.fileQueue.shift()
    },
    PROCESSING_FILE(state, status) {
        state.isProcessingFile = status
    },
    UPLOADING_FILE_PROGRESS(state, percentage) {
        state.uploadingProgress = percentage
    },
    INCREASE_FILES_IN_QUEUES_TOTAL(state, count) {
        state.filesInQueueTotal += count
    },
    INCREASE_FILES_IN_QUEUE_UPLOADED(state) {
        state.filesInQueueUploaded++
    },
    CLEAR_UPLOAD_PROGRESS(state) {
        state.filesInQueueUploaded = 0
        state.filesInQueueTotal = 0
        state.fileQueue = []
    }
}

const getters = {
    filesInQueueUploaded: state => state.filesInQueueUploaded,
    filesInQueueTotal: state => state.filesInQueueTotal,
    uploadingProgress: state => state.uploadingProgress,
    isProcessingFile: state => state.isProcessingFile,
    processingPopup: state => state.processingPopup,
    fileQueue: state => state.fileQueue
}

export default {
    state: defaultState,
    mutations,
    actions,
    getters
}
