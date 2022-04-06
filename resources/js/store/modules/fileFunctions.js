import i18n from '../../i18n'
import router from '../../router'
import { events } from '../../bus'
import axios from 'axios'
import Vue from 'vue'

const defaultState = {
    processingPopup: undefined,
    isUploadingFolder: false,
    isProcessingFile: false,
    filesInQueueUploaded: 0,
    filesInQueueTotal: 0,
    uploadingProgress: 0,
    fileQueue: [],
}

const actions = {
    downloadZip: ({ getters }, item = undefined) => {
        let files = []

        // Get if from retrieved item
        if (item) {
            files.push(item.data.id + '|folder')
        }

        // Get ids of selected files
        if (!item) {
            getters.clipboard.forEach((file) => {
                let type = file.data.type === 'folder' ? 'folder' : 'file'

                files.push(file.data.id + '|' + type)
            })
        }

        // Get route
        let route = getters.sharedDetail
            ? `/api/zip/${router.currentRoute.params.token}?items=${files.join(',')}`
            : `/api/zip?items=${files.join(',')}`

        // Download zip
        Vue.prototype.$downloadFile(route, 'files.zip')
    },
    moveItem: ({ commit, getters, dispatch }, { to_item, item }) => {
        let items = item
            ? [item]
            : getters.clipboard

        let itemsToMove = items.map((data) => {
            return {
                id: data.data.id,
                type: data.data.type,
            }
        })

        // Remove file preview
        if (!item) {
            commit('CLIPBOARD_CLEAR')
        }

        // Get route
        let route = {
            RequestUpload: `/api/upload-request/${router.currentRoute.params.token}/move`,
            Public: `/api/editor/move/${router.currentRoute.params.token}`,
        }[router.currentRoute.name] || '/api/move'

        let moveToId = to_item.data ? to_item.data.id : to_item.id

        axios
            .post(route, {
                to_id: moveToId || null,
                items: itemsToMove,
            })
            .then(() => {
                itemsToMove.forEach((item) => {
                    commit('REMOVE_ITEM', item.id)
                    commit('INCREASE_FOLDER_ITEM', moveToId)

                    if (item.type === 'folder')
                        dispatch('getAppData')

                    if (Vue.prototype.$isThisRoute(router.currentRoute, ['Public']))
                        dispatch('getFolderTree')
                })
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    createFolder: ({ commit, getters, dispatch }, folder) => {
        // Get route
        let route = {
            RequestUpload: `/api/upload-request/${router.currentRoute.params.token}/create-folder`,
            Public: `/api/editor/create-folder/${router.currentRoute.params.token}`,
        }[router.currentRoute.name] || '/api/create-folder'

        axios
            .post(route, {
                name: folder.name,
                emoji: folder.emoji,
                parent_id: getters.currentFolder?.data.id,
            })
            .then((response) => {
                commit('ADD_NEW_FOLDER', response.data)

                events.$emit('scrollTop')

                // Set focus on new folder name
                setTimeout(() => {
                    if (!Vue.prototype.$isMobile()) {
                        events.$emit('newFolder:focus', response.data.data.id)
                    }
                }, 10)

                // Refresh folder tree navigation
                dispatch('getFolderTree')
            })
            .catch((error) => {
                events.$emit('alert:open', {
                    title: error.response.data.message,
                    message: i18n.t('popup_error.message'),
                })
            })
    },
    renameItem: ({ commit, getters, dispatch }, data) => {
        // Updated name in favourites panel
        if (data.type === 'folder' && getters.user)
            commit('UPDATE_NAME_IN_FAVOURITES', data)

        // Get route
        let route = {
            RequestUpload: `/api/upload-request/${router.currentRoute.params.token}/rename/${data.id}`,
            Public: `/api/editor/rename/${data.id}/${router.currentRoute.params.token}`,
        }[router.currentRoute.name] || `/api/rename/${data.id}`

        axios
            .post(route, {
                ...data,
                _method: 'patch',
            })
            .then((response) => {
                commit('CHANGE_ITEM_NAME', response.data)

                if (data.type === 'folder' && router.currentRoute.name !== 'Public')
                    dispatch('getAppData')
                if (data.type === 'folder' && router.currentRoute.name === 'Public')
                    dispatch('getFolderTree')
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    uploadFiles: ({ commit, getters, dispatch }, { form, fileSize, totalUploadedSize }) => {
        return new Promise((resolve, reject) => {
            // Get route
            let route = {
                RequestUpload: `/api/upload-request/${router.currentRoute.params.token}/upload`,
                Public: `/api/editor/upload/${router.currentRoute.params.token}`,
            }[router.currentRoute.name] || '/api/upload'

            // Create cancel token for axios cancellation
            const CancelToken = axios.CancelToken,
                source = CancelToken.source()

            axios
                .post(route, form, {
                    cancelToken: source.token,
                    headers: {
                        'Content-Type': 'application/octet-stream',
                    },
                    onUploadProgress: (event) => {
                        let percentCompleted = Math.floor(((totalUploadedSize + event.loaded) / fileSize) * 100)

                        commit('UPLOADING_FILE_PROGRESS', percentCompleted >= 100 ? 100 : percentCompleted)

                        // Set processing file
                        if (percentCompleted >= 100) commit('PROCESSING_FILE', true)
                    },
                })
                .then(async (response) => {
                    resolve(response)

                    // Proceed if was returned database record
                    if (response.data.data.id) {
                        commit('PROCESSING_FILE', false)

                        // Remove first file from file queue
                        commit('SHIFT_FROM_FILE_QUEUE')

                        // Refresh request detail to update currentFolder in Vuex
                        if (router.currentRoute.name === 'RequestUpload' && !getters.currentFolder) {
                            await dispatch('getUploadRequestDetail')
                        }

                        // Check if user is in uploading folder, if yes, than show new file
                        if (
                            (!getters.currentFolder && !response.data.data.attributes.parent_id) ||
                            (getters.currentFolder &&
                                response.data.data.attributes.parent_id === getters.currentFolder.data.id)
                        ) {
                            // Add uploaded item into view
                            commit('ADD_NEW_ITEMS', response.data)
                        }

                        // Reset file progress
                        commit('UPLOADING_FILE_PROGRESS', 0)

                        // Increase count in files in queue uploaded for 1
                        commit('INCREASE_FILES_IN_QUEUE_UPLOADED')

                        // Start uploading next file if file queue is not empty
                        if (getters.fileQueue.length) {
                            Vue.prototype.$handleUploading(getters.fileQueue[0])
                        }

                        // Reset upload process
                        if (!getters.fileQueue.length) {
                            commit('CLEAR_UPLOAD_PROGRESS')

                            // Reload File data after folder uploading is finished
                            if (getters.isUploadingFolder) {
                                // Reload files after upload is done
                                Vue.prototype.$getDataByLocation()

                                // Reload folder tree
                                dispatch('getFolderTree')

                                commit('UPDATE_UPLOADING_FOLDER_STATE', false)
                            }
                        }
                    }
                })
                .catch((error) => {
                    reject(error)

                    let messages = {
                        423: {
                            title: i18n.t('popup_exceed_limit.title'),
                            message: i18n.t('popup_exceed_limit.message'),
                        },
                        422: {
                            title: i18n.t('popup_mimetypes_blacklist.title'),
                            message: i18n.t('popup_mimetypes_blacklist.message'),
                        },
                        413: {
                            title: i18n.t('popup_paylod_error.title'),
                            message: i18n.t('popup_paylod_error.message'),
                        },
                        401: {
                            title: error.response.data.message,
                        },
                    }

                    if (messages[error.response.status]) {
                        events.$emit('alert:open', {
                            emoji: '😬😬😬',
                            title: messages[error.response.status]['title'] || null,
                            message: messages[error.response.status]['message'] || null,
                        })
                    }

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
    restoreItem: ({ commit, getters }, item) => {
        let items = item
            ? [item]
            : getters.clipboard

        let restoreToHome = Vue.prototype.$isThisRoute(router.currentRoute, ['Trash'])

        let itemToRestore = items.map((data) => {
            return {
                type: data.data.type,
                id: data.data.id,
            }
        })

        // Remove file preview
        commit('CLIPBOARD_CLEAR')

        axios
            .post(getters.api + '/trash/restore', {
                to_home: restoreToHome,
                items: itemToRestore,
            })
            .then(() => items.forEach((item) => commit('REMOVE_ITEM', item.data.id)))
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    deleteItem: ({ commit, getters, dispatch }, item) => {
        let items = item
            ? [item]
            : getters.clipboard

        let deletedItems = items.map((data) => {
            // Remove file from view
            commit('REMOVE_ITEM', data.data.id)
            commit('REMOVE_ITEM_FROM_CLIPBOARD', data.data.id)
            events.$emit('file:deleted', data.data.id)

            // Remove item from sidebar
            if (! ['Public', 'RequestUpload'].includes(router.currentRoute.name) && data.data.type === 'folder')
                commit('REMOVE_ITEM_FROM_FAVOURITES', data)

            return {
                force_delete: !!data.data.attributes.deleted_at,
                type: data.data.type,
                id: data.data.id,
            }
        })

        // Get route
        let route = {
            RequestUpload: `/api/upload-request/${router.currentRoute.params.token}/remove`,
            Public: `/api/editor/remove/${router.currentRoute.params.token}`,
        }[router.currentRoute.name] || '/api/remove'

        axios
            .post(route, {
                items: deletedItems,
            })
            .then(() => {
                deletedItems.forEach((data) => {
                    // If is folder, update app data
                    if (data.type === 'folder' && getters.currentFolder && data.id === getters.currentFolder.data.id) {
                        router.back()
                    }
                })

                // Refresh folder tree navigation
                dispatch('getFolderTree')
            })
        //.catch(() => Vue.prototype.$isSomethingWrong())
    },
    emptyTrash: ({ commit, getters }) => {
        // Clear file browser
        commit('LOADING_STATE', { loading: true, data: [] })

        axios
            .post(getters.api + '/trash/dump', {
                _method: 'delete',
            })
            .then(() => {
                commit('LOADING_STATE', { loading: false, data: [] })
                events.$emit('scrollTop')

                commit('CLIPBOARD_CLEAR')
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
    emptyTrashQuietly: ({ commit, getters }) => {
        axios
            .post(getters.api + '/trash/dump', {
                _method: 'delete',
            })
            .then(() => {
                if (router.currentRoute.name === 'Trash') {
                    commit('LOADING_STATE', { loading: false, data: [] })
                }

                events.$emit('toaster', {
                    type: 'success',
                    message: i18n.t('Your trash was successfully cleared.'),
                })
            })
            .catch(() => Vue.prototype.$isSomethingWrong())
    },
}

const mutations = {
    UPDATE_UPLOADING_FOLDER_STATE(state, status) {
        state.isUploadingFolder = status
    },
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
    },
}

const getters = {
    filesInQueueUploaded: (state) => state.filesInQueueUploaded,
    filesInQueueTotal: (state) => state.filesInQueueTotal,
    uploadingProgress: (state) => state.uploadingProgress,
    isUploadingFolder: (state) => state.isUploadingFolder,
    isProcessingFile: (state) => state.isProcessingFile,
    processingPopup: (state) => state.processingPopup,
    fileQueue: (state) => state.fileQueue,
}

export default {
    state: defaultState,
    mutations,
    actions,
    getters,
}
