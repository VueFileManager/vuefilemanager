import axios from 'axios'
import {events} from '@/bus'
import i18n from '@/i18n/index'

const actions = {
    moveItem: ({commit, getters}, [item_from, to_item]) => {
        axios
            .post(getters.api + '/move-item', {
                from_unique_id: item_from.unique_id,
                from_type: item_from.type,
                to_unique_id: to_item.unique_id
            })
            .then(() => {
                commit('REMOVE_ITEM', item_from.unique_id)
                commit('INCREASE_FOLDER_ITEM', to_item.unique_id)
            })
            .catch(() => isSomethingWrong())
    },
    createFolder: ({commit, getters}, folderName) => {
        const parent_id = getters.currentFolder ? getters.currentFolder.unique_id : 0

        axios
            .post(getters.api + '/create-folder', {
                parent_id: parent_id,
                name: folderName
            })
            .then(response => {
                commit('ADD_NEW_FOLDER', response.data)
            })
            .catch(() => isSomethingWrong())
    },
    renameItem: ({commit, getters}, data) => {

        // Updated name in favourites panel
        if (getters.permission === 'master' && data.type === 'folder')
            commit('UPDATE_NAME_IN_FAVOURITES', data)

        axios
            .post(getters.api + '/rename-item', data)
            .then(response => {
                commit('CHANGE_ITEM_NAME', response.data)
            })
            .catch(() => isSomethingWrong())
    },
    uploadFiles: ({commit, getters}, files) => {
        return new Promise((resolve, reject) => {
            axios
                .post(getters.api + '/upload-file', files, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: progressEvent => {
                        var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)

                        commit('UPLOADING_FILE_PROGRESS', percentCompleted)
                    }
                })
                .then(response => {

                    // Check if user is in uploading folder, if yes, than show new file
                    if (response.data.folder_id == getters.currentFolder.unique_id)
                        commit('ADD_NEW_ITEMS', response.data)

                    commit('UPDATE_RECENT_UPLOAD', response.data)
                    commit('UPLOADING_FILE_PROGRESS', 0)

                    resolve(response)
                })
                .catch(error => {
                    reject(error)

                    // Reset uploader
                    commit('UPDATE_FILE_COUNT_PROGRESS', undefined)
                })
        })
    },
    restoreItem: ({commit, getters}, item) => {

        let restoreToHome = false

        // Check if file can be restored to home directory
        if (getters.currentFolder.location === 'trash')
            restoreToHome = true

        // Remove file
        commit('REMOVE_ITEM', item.unique_id)

        // Remove file preview
        commit('CLEAR_FILEINFO_DETAIL')

        axios
            .post(getters.api + '/restore-item', {
                type: item.type,
                unique_id: item.unique_id,
                to_home: restoreToHome,
            })
            .catch(() => isSomethingWrong())
    },
    removeItem: ({commit, getters}, data) => {

        // Remove file
        commit('REMOVE_ITEM', data.unique_id)

        // Remove item from sidebar
        if (getters.permission === 'master') {

            if (data.type === 'folder')
                commit('REMOVE_ITEM_FROM_FAVOURITES', data)
            else
                commit('REMOVE_ITEM_FROM_RECENT_UPLOAD', data.unique_id)
        }

        // Remove file preview
        commit('CLEAR_FILEINFO_DETAIL')

        axios
            .post(getters.api + '/remove-item', {
                type: data.type,
                unique_id: data.unique_id,
                force_delete: data.deleted_at ? true : false
            })
            .catch(() => isSomethingWrong())
    },
    emptyTrash: ({commit, getters}) => {

        // Clear file browser
        commit('FLUSH_DATA')
        commit('LOADING_STATE', true)

        axios
            .delete(getters.api + '/empty-trash')
            .then(() => {
                commit('LOADING_STATE', false)
                events.$emit('scrollTop')

                // Remove file preview
                commit('CLEAR_FILEINFO_DETAIL')

                // Show success message
                events.$emit('success:open', {
                    title: i18n.t('popup_trashed.title'),
                    message: i18n.t('popup_trashed.message'),
                })
            })
            .catch(() => isSomethingWrong())
    },
}

// Show error message
function isSomethingWrong() {
    events.$emit('alert:open', {
        title: i18n.t('popup_error.title'),
        message: i18n.t('popup_error.message'),
    })
}

export default {
    actions,
}
